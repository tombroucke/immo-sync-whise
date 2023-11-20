<?php

namespace ADB\ImmoSyncWhise\Services;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Enum\PostType;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
use Illuminate\Support\Collection;
use ADB\ImmoSyncWhise\Vendor\Psr\Log\LoggerInterface;

class EstateSyncDeletedService
{
    public function __construct(
        private Estate $estate,
        private EstateAdapter $estateAdapter,
        private EstateParser $estateParser,
        public LoggerInterface $logger,
    ) {
    }

    public function sync(): void
    {
        \WP_CLI::success("Performing daily deletion check");
        $this->logger->info("Performing daily deletion check");

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
        $this->logger->info("Succesfully deleted estates {$deleted}");
    }
}
