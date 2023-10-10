<?php

namespace ADB\ImmoSyncWhise\Parser;

use ADB\ImmoSyncWhise\Database\Database;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Psr\Log\LoggerInterface;
use Throwable;

class EstateParser
{
    public $method;

    public $postId;

    public $estateResponse;

    private $client;

    public function __construct(public LoggerInterface $logger)
    {
        $this->client = new Client();
    }

    public function setMethod(string $method): EstateParser
    {
        $this->method = $method;

        return $this;
    }

    public function setPostId(int $postId): EstateParser
    {
        $this->postId = $postId;

        return $this;
    }

    public function setObject($response): EstateParser
    {
        $this->estateResponse = $response;

        return $this;
    }

    public function parseProperties()
    {
        foreach ($this->estateResponse->getData() as $key => $value) {
            if ($key === 'details' || $key === 'pictures') return;

            if (gettype($value) == 'object') {
                if (isset($value->date)) $this->parse($key, $value, 'date');
                if (isset($value->id)) $this->parse($key, $value, 'id');
            } else {
                $this->parseLine($key, $value);
            }

            if (is_array($value)) $this->parseArray($key, $value[0], 'content');
        }
    }

    public function parsePictures()
    {
        try {
            // $start_time = microtime(true);

            $promises = [];

            foreach ($this->estateResponse->pictures as $pictureInfo) {
                $promises[] = $this->client->getAsync($pictureInfo->urlLarge);
            }

            $responses = Promise\Utils::unwrap($promises);

            foreach ($responses as $key => $response) {
                if ($response instanceof \WP_Error) {
                    continue;
                }

                $attachmentId = $this->saveImageToPost($response, $this->postId);

                if ($key === 0) {
                    set_post_thumbnail($this->postId, $attachmentId);
                }
            }

            // $end_time = microtime(true);

            // $execution_time = $end_time - $start_time;

            // echo "Pictures parse Execution Time: {$execution_time} seconds\n";
        } catch (Throwable $e) {
            $error = json_encode($e->getMessage());

            $this->logger->error("There was an error when saving estate pictures {$error} for {$this->postId}");
        }
    }

    private function saveImageToPost($response, int $postId): int
    {
        $filename = basename(uniqid() . '.jpg');
        $uploadDir = wp_upload_dir();
        $filePath = $uploadDir['path'] . '/' . $filename;

        // Save the image file
        file_put_contents($filePath, $response->getBody());

        // Create the attachment data
        $attachment = [
            'guid'           => $uploadDir['url'] . '/' . $filename,
            'post_mime_type' => $response->getHeaderLine('Content-Type'),
            'post_title'     => $filename,
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        // Insert the attachment
        $attachmentId = wp_insert_attachment($attachment, $filePath, $postId);

        // Generate the attachment metadata
        $attachmentData = wp_generate_attachment_metadata($attachmentId, $filePath);
        wp_update_attachment_metadata($attachmentId, $attachmentData);

        return $attachmentId;
    }

    public function removePictures()
    {
        /** @var WP_Post[] $images */
        $images = get_attached_media('image', $this->postId);

        foreach ($images as $image) {
            wp_delete_attachment($image->ID, true);
        }
    }

    public function parseDetails()
    {
        $start_time = microtime(true);

        $db = new Database();

        try {
            foreach ($this->estateResponse->details as $detailGroup) {
                $db->save($this->postId, $detailGroup->getData());
            }
        } catch (Throwable $e) {
            $error = json_encode($e->getMessage());

            $this->logger->error("There was an error when saving estate details {$error} for {$this->postId}");
        }
    }

    public function removeDetails()
    {
        (new Database())->delete($this->postId);
    }

    private function parse($key, $value, $property)
    {
        call_user_func_array($this->method, [
            $this->postId, '_iws_' . $key,
            $value->{$property},
            $this->method == 'add_post_meta' ? true : ''
        ]);
    }

    private function parseArray($key, $value, $property)
    {
        call_user_func_array($this->method, [
            $this->postId, '_iws_' . $key,
            $value->getData()[$property],
            $this->method == 'add_post_meta' ? true : ''
        ]);
    }

    private function parseLine($key, $value): void
    {
        call_user_func_array($this->method, [
            $this->postId,
            '_iws_' . $key,
            $value,
            $this->method == 'add_post_meta' ? true : ''
        ]);
    }
}
