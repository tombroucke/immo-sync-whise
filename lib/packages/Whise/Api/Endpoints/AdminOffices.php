<?php

/*
* This file is part of the fw4/whise-api library
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ADB\ImmoSyncWhise\Vendor\Whise\Api\Endpoints;

use ADB\ImmoSyncWhise\Vendor\Whise\Api\Endpoint;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Request\CollectionRequest;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Response\CollectionResponse;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Response\CollectionResponsePaginated;

final class AdminOffices extends Endpoint
{
    /**
     * Request a list of activated offices.
     *
     * @link http://api.whise.eu/WebsiteDesigner.html#operation/Admin_GetOffices
     * Official documentation
     *
     * @param array $parameters Associative array containing request parameters
     *
     * @throws Exception\InvalidRequestException if the API rejects the request
     * due to invalid input
     * @throws Exception\AuthException if access is denied
     * @throws Exception\AuthException if access token is missing or invalid
     * @throws Exception\ApiException if a server-side error occurred
     *
     * @return CollectionResponse Traversable collection of items
     */
    public function list(?array $parameters = null): CollectionResponse
    {
        $request = new CollectionRequest('POST', 'v1/admin/offices/list', $parameters);
        $request->setResponseKey('offices')->requireAuthentication(true)->allowGreedyCache(true);

        return new CollectionResponsePaginated($request, $this->getApiAdapter());
    }
}
