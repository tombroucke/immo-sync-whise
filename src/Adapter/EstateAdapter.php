<?php

namespace Bruno\ImmoSyncWhise\Adapter;

use Bruno\ImmoSyncWhise\Api;

class EstateAdapter
{
    public $api;

    /**
     * EstateAdapter constructor.
     *
     * @param Api $api The API instance to be used.
     */
    public function __construct(Api $api)
    {
        $this->api = $api->connection;
    }

    /**
     * Get a list of estates based on the provided filters, sorting, and fields.
     *
     * @param array $filter An array of filters to apply to the query.
     * @param array $sorting An array specifying the sorting criteria.
     * @param array $fields An array specifying the fields to include in the result.
     * @return array The list of estates matching the criteria.
     */
    public function list($filter = [], $sorting = [], $fields = [])
    {
        return $this->api->estates()->list($filter, $sorting, $fields);
    }

    /**
     * Get the details of an estate by its ID.
     *
     * @param int $id The ID of the estate to retrieve.
     * @param array $filter An array of filters to apply to the query.
     * @param array $fields An array specifying the fields to include in the result.
     * @return array|null The details of the estate or null if not found.
     */
    public function get($id, $filter = [], $fields = [])
    {
        return $this->api->estates()->get($id, $filter, $fields);
    }
}
