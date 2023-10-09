<?php

namespace ADB\ImmoSyncWhise\Parser;

use ADB\ImmoSyncWhise\Database\Database;
use Psr\Log\LoggerInterface;
use Throwable;

class EstateParser
{
    public $method;

    public $postId;

    public $estateResponse;

    public function __construct(public LoggerInterface $logger)
    {
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    public function setObject($response): void
    {
        $this->estateResponse = $response;
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
            foreach ($this->estateResponse->pictures as $key => $pictureInfo) {
                $image = media_sideload_image($pictureInfo->urlXXL, $this->postId, 'Orientation: ' . $pictureInfo->orientation, 'id');

                if ($key === 0) {
                    set_post_thumbnail($this->postId, $image);
                }
            }
        } catch (Throwable $e) {
            $error = json_encode($e->getMessage());

            $this->logger->error("There was an error when saving estate pictures {$error} for {$this->postId}");
        }
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
