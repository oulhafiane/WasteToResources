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

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$auction_id = $input->getArgument('auction_id');
		if (is_numeric($auction_id)) {
			$offer = $this->em->getRepository(AuctionBid::class)->find($auction_id);
			$text = $offer->getTitle();
		}

		$output->writeln($text);
	}
}
