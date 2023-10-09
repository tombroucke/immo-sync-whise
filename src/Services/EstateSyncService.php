<?php

namespace ADB\ImmoSyncWhise\Services;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
use Psr\Log\LoggerInterface;

class EstateSyncService
{
    public function __construct(
        private Estate $estate,
        private EstateAdapter $estateAdapter,
        private EstateParser $estateParser,
        public LoggerInterface $logger,
    ) {
    }

    public function syncAll(): void
    {
        \WP_CLI::log("Fetching all estates from Whise API");
        $this->logger->info("Fetching all estates from Whise API");

        $estates = $this->estateAdapter->list(['LanguageId' => $_ENV['LANG']]);

        foreach ($estates as $estate) {
            // Save the Post
            $postId = $this->estate->save($estate);

            // Configure the parser
            $this->estateParser->setMethod('add_post_meta')->setPostId($postId)->setObject($estate);

            // Parse the response object
            $this->estateParser->parseProperties();
            $this->estateParser->parseDetails();
            $this->estateParser->parsePictures();

            \WP_CLI::success("Fetched estate, created post with ID #{$postId}");
            $this->logger->info("Fetched estate, created post with ID #{$postId}");
        }

        \WP_CLI::success('Fetching successful');
        $this->logger->info("Fetching successful");
    }
}
