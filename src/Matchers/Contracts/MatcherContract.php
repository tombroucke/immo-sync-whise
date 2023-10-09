<?php

namespace ADB\ImmoSyncWhise\Matchers\Contracts;

interface MatcherContract
{
    public function getTitle(string $key);

    public function getDescription(string $key);

    public function getField($key, $field);

    public function getFieldValueFromLookup($key, $field);
}
