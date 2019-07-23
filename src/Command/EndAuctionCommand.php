<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuctionBid;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EndAuctionCommand extends ContainerAwareCommand
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		parent::__construct();
		$this->em = $em;
	}

	protected function configure()
	{
		$this
			->setName('auction:end')
			->setDescription('Ending Auction')
			->addArgument(
				'auction_id',
				InputArgument::REQUIRED,
				'What auction you want to end ?'
			)
			;
	}

	private function getWinner($offer)
	{
		$bids = $offer->getBids();
		foreach ($bids as $bid) {
		}
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$auction_id = $input->getArgument('auction_id');
		if (is_numeric($auction_id)) {
			$offer = $this->em->getRepository(AuctionBid::class)->find($auction_id);
			if (null === $offer)
				die("Auction not found.\n");
			$date = new \DateTime(date("Y-m-d H:i:s"));
			if ($offer->getEndDate() > $date)
				die("It's early to close it, sorry!\n");
			if (false === $offer->getIsActive())
				die("Auction already inactive.\n");
			$output->writeln($offer->getBids()[0]->getBidder()->__toString());		
			$output->writeln(date_format($offer->getStartDate(), 'Y-m-d H:i:s'));
		}

		die("Auction not found.\n");
	}
}
