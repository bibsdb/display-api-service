<?php

namespace App\Command;

use App\Repository\FeedRepository;
use App\Repository\FeedSourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:feed:remove-feed-source',
    description: 'Remove a feed source',
)]
class RemoveFeedSourceCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager, private FeedSourceRepository $feedSourceRepository, private FeedRepository $feedRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('ulid', InputArgument::OPTIONAL, 'Ulid of existing feed source to override');
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ulid = $input->getArgument('ulid');

        if (!$ulid) {
            $feedSourcesMessage = "No ulid supplied. Installed feed sources:\n\n";

            $feedSources = $this->feedSourceRepository->findAll();

            foreach ($feedSources as $feedSource) {
                $feedSourceId = $feedSource->getId();
                $feedSourceTitle = $feedSource->getTitle();
                $feedSourceTenant = $feedSource->getTenant()->getTitle();
                $feedSourcesMessage .= "$feedSourceId - $feedSourceTitle (Tenant: $feedSourceTenant)\n";
            }

            $io->info($feedSourcesMessage);

            return Command::INVALID;
        }

        $io->writeln("Removing FeedSource with ULID: $ulid");

        $feedSource = $this->feedSourceRepository->find($ulid);

        if (!$feedSource) {
            $io->error('Feed source could not be found. Aborting.');

            return Command::INVALID;
        }

        // Remove all feeds associated with the Feed Source.
        $feeds = $this->feedRepository->findBy(['feedSource' => $feedSource]);
        $feedsCount = count($feeds);

        $io->confirm("Are you sure you want to remove the feed source. $feedsCount feeds will be removed as well.");

        foreach ($feeds as $feed) {
            $feed->getSlide()->setFeed(null);
        }

        $this->entityManager->remove($feedSource);
        $this->entityManager->flush();

        $io->success("Feed source with id: $ulid removed.");

        return Command::SUCCESS;
    }
}
