<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Command;

class Fetch extends Command
{
    public const COMMAND_NAME = 'iws fetch';

    /**
     *
     * wp iws fetch get
     */
    public function get($args)
    {
        \WP_CLI::log("Fetching estate with ID {$args[0]} from Whise API");
        $this->operationsLogger->info("Fetching estate with ID {$args[0]} from Whise API");

        $estate = $this->estateAdapter->get($args[0], ['LanguageId' => $_ENV['LANG']]);
        $this->handle($estate);

        \WP_CLI::success("Fetching successful");
        $this->operationsLogger->info("Fetching successful");
    }

    /**
     *
     * wp iws fetch all
     */
    public function all()
    {
        \WP_CLI::log("Fetching all estates from Whise API");
        $this->operationsLogger->info("Fetching all estates from Whise API");

        $estates = $this->estateAdapter->list([
            'LanguageId' => $_ENV['LANG'],
        ]);

        foreach ($estates as $estate) {
            $this->handle($estate);
        }

        \WP_CLI::success('Fetching successful');
        $this->operationsLogger->info("Fetching successful");
    }

    private function handle($estate)
    {
        // Save the Post
        $postId = $this->estate->save_estate($estate);

        // Configure the parsers
        $this->estateParser->setMethod('add_post_meta');
        $this->estateParser->setPostId($postId);
        $this->estateParser->setEstateObject($estate);

        // Parse the response object
        $this->estateParser->parseProperties();
        $this->estateParser->parseDetails();
        $this->estateParser->parsePictures();

        \WP_CLI::success("Fetched estate, created post {$postId}");
        $this->operationsLogger->info("Fetched estate, created post {$postId}");
    }
}
