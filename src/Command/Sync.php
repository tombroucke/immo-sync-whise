<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Command;
use ADB\ImmoSyncWhise\Enum\PostType;
use Illuminate\Support\Collection;

class Sync extends Command
{
    public const COMMAND_NAME = 'iws sync';

    /**
     *
     * wp iws sync today
     */
    public function today($args)
    {
        $yesterday = date("Y-m-d H:i:s", strtotime("yesterday"));
        \WP_CLI::log("Syncing all estates which have been added or edited since: {$yesterday}");
        $this->operationsLogger->info("Syncing all estates which have been added or edited since: {$yesterday}");

        $estates = $this->estateAdapter->list([
            'LanguageId' => $_ENV['LANG'],
            'UpdateDateTimeRange' => [
                'Min' => $yesterday
            ]
        ]);

        foreach ($estates as $estate) {
            $this->estate->exists($estate->getData()['id']) ? $this->handleUpdate($estate) : $this->handleSave($estate);
            // $this->logger->warning(json_encode($estate->getData()['name']));
            // $this->logger->warning(json_encode($estate->getData()['updateDateTime']));
        }

        \WP_CLI::success('Syncing successful');
        $this->operationsLogger->info("Syncing successful");
    }

    /**
     *
     * wp iws sync deleted
     */
    public function deleted($args)
    {
        \WP_CLI::success("Performing daily deletion check");
        $this->operationsLogger->info("Performing daily deletion check");

        $whiseEstateIds = Collection::make($this->estateAdapter->list(['LanguageId' => $_ENV['LANG']]))->pluck('id')->toArray();

        $wpEstatesIds = get_posts([
            'post_type' => PostType::Name,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);

        $deletedIds = [];

        foreach ($wpEstatesIds as $wpEstateId) {
            $iwsId = intval(get_post_meta($wpEstateId, "_iws_id", true));
            if (!in_array($iwsId, $whiseEstateIds)) {
                wp_delete_post($wpEstateId); // Delete the post in WordPress
                array_push($deletedIds, [
                    'post_id' => $wpEstateId,
                    'whise_id' => $iwsId
                ]);
            }
        }

        $deleted = json_encode($deletedIds);

        \WP_CLI::success("Succesfully deleted estates {$deleted}");
        $this->operationsLogger->info("Succesfully deleted estates {$deleted}");
    }

    private function handleSave($estate)
    {
        // Save the Post
        $postId = $this->estate->save($estate);

        // Configure the parser
        $this->estateParser->setMethod('add_post_meta');
        $this->estateParser->setPostId($postId);
        $this->estateParser->setObject($estate);

        // Parse the response object
        $this->estateParser->parseProperties();
        $this->estateParser->parseDetails();
        $this->estateParser->parsePictures();

        \WP_CLI::success("Imported estate, created post {$postId}");
        $this->operationsLogger->info("Imported estate, created post {$postId}");
    }

    private function handleUpdate($estate)
    {
        // Update the Post
        $postId = $this->estate->update($estate);

        // Configure the parser
        $this->estateParser->setMethod('update_post_meta');
        $this->estateParser->setPostId($postId);
        $this->estateParser->setObject($estate);

        // Parse the response object
        $this->estateParser->parseProperties();
        $this->estateParser->removeDetails();
        $this->estateParser->parseDetails();
        $this->estateParser->removePictures();
        $this->estateParser->parsePictures();

        \WP_CLI::success("Synced estate, updated post {$postId}");
        $this->operationsLogger->info("Synced estate, updated post {$postId}");
    }
}
