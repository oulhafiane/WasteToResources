<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
use App\Entity\Gain;
use App\Entity\Notification;
use App\Entity\Bid;
use App\Entity\Transaction;
use App\Service\Mercure;
use App\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EndOffersCommand extends ContainerAwareCommand
{
	private $em;
	private $mercure;
	private $helper;

	public function __construct(EntityManagerInterface $em, Mercure $mercure, Helper $helper)
	{
		parent::__construct();
		$this->em = $em;
		$this->mercure = $mercure;
		$this->helper = $helper;
	}

	protected function configure()
	{
		$this
			->setName('offers:end')
			->setDescription('Ending Offers')
			;
	}

	private function openTransaction($winnerBid, $offer)
	{
		$user = $winnerBid->getBidder();
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = $this->helper->getFees($total, 'feesTransactionStatic', 'feesTransactionDynamic');
		$paid = true;
		if ($user->getBalance() < ($total + $fees))
			$paid = false;
		if (true === $paid) {
			$user->setBalance($user->getBalance() - ($total + $fees));
			$gain = new Gain();
			$gain->setOffer($offer);
			$gain->setUser($user);
			$gain->setFees($fees);
			$gain->setType(Gain::NOTCREATOR);
			$gain->setPaid();
		}

		$transaction = new Transaction();
		$transaction->setBuyer($user);
		$transaction->setSeller($offer->getOwner());
		$transaction->setTotal($winnerBid->getPrice());
		$transaction->setOffer($offer);
		$transaction->setGain($gain);
		if (true === $paid) {
			$transaction->setPaid();
			$messageBuyer = "You win the auction (".$offer->getTitle().") and you paid it successfully.";
			$messageSeller = "Your auction (".$offer->getTitle().") ended and paid successfully.";
		} else {
			$messageBuyer = "You win the auction (".$offer->getTitle().") and you need to pay the transaction.";
			$messageSeller = "Your auction (".$offer->getTitle().") ended and it pending payment.";
		}
		
		$offer->setInactive();

		$notificationBuyer = new Notification();
		$notificationBuyer->setMessage($messageBuyer);
		$notificationBuyer->setType(Notification::TRANSACTION);
		$notificationBuyer->setUser($user);
		$notificationBuyer->setReference($transaction->getId());

		$notificationSeller = new Notification();
		$notificationSeller->setMessage($messageSeller);
		$notificationSeller->setType(Notification::TRANSACTION);
		$notificationSeller->setUser($offer->getOwner());
		$notificationSeller->setReference($transaction->getId());

		try {
			$this->em->persist($transaction);
			$this->em->persist($offer);
			$this->em->persist($notificationBuyer);
			$this->em->persist($notificationSeller);
			if (true === $paid) {
				$this->em->persist($gain);
				$this->em->persist($user);
			}
			$this->em->flush();
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->mercure->publishNotification($notificationBuyer);
		$this->mercure->publishNotification($notificationSeller);
	}

	private function endAuction($auction, $output)
	{
		$winnerBid = $this->em->getRepository(Bid::class)->findOneBy(['offer' => $auction, 'isActive' => true], ['price' => 'DESC']);
		if (null === $winnerBid) {
			$this->refundOwner($auction);
		} else {
			$output->writeln("The winner is : ".$winnerBid->getBidder()->getEmail()." with price : ".$winnerBid->getPrice());
			$this->openTransaction($winnerBid, $auction);
		}
	}

	private function refundOwner($offer)
	{
		$gains = $offer->getGains();
		$notification = null;
		foreach ($gains as $gain) {
			if (Gain::CREATOR === $gain->getType()) {
				$fees = $gain->getFees();
				$user = $gain->getUser();
				$user->setBalance($user->getBalance() + $fees);
				$gain->setRefunded();
				$notification = new Notification();
				$notification->setMessage("You got your money back (".$fees." MAD) because no one accepted you offer : ".$offer->getTitle().".");
				$notification->setType(Notification::OFFERREFUND);
				$notification->setUser($user);
				$notification->setReference($offer->getId());
				try {
					$this->em->persist($gain);
					$this->em->persist($user);
					$this->em->persist($notification);
				} catch (\Exception $ex) {
					throw new HttpException(500, $ex->getMessage());
				}

				break ;
			}
		}

		$offer->setInactive();
		try {
			$this->em->persist($offer);
			$this->em->flush();
		} catch (\Exception $ex) {
			throw new HttpException(500, $ex->getMessage());
		}

		if (null !== $notification)
			$this->mercure->publishNotification($notification);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$offers = $this->em->getRepository(Offer::class)->getEndedOffers();
		foreach ($offers as $offer) {
			$output->writeln($offer->getId()." => ".$offer->getTitle());
			if ($offer instanceof AuctionBid) {
				$this->endAuction($offer, $output);	
			} else {
				$this->refundOwner($offer);
			}
			$output->writeln("=========");
		}
	}
}
