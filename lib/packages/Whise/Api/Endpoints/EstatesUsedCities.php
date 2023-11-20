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

final class EstatesUsedCities extends Endpoint
{
    /**
     * Request a list of cities in use.
     *
     * @link http://api.whise.eu/WebsiteDesigner.html#operation/Estates_GetUsedCities
     * Official documentation
     *
     * @param array $estate_filter Associative array containing estate filter
     * parameters, or an associative array containing raw request parameters
     *
     * @throws Exception\InvalidRequestException if the API rejects the request
     * due to invalid input
     * @throws Exception\AuthException if access is denied
     * @throws Exception\AuthException if access token is missing or invalid
     * @throws Exception\ApiException if a server-side error occurred
     *
     * @return CollectionResponse Traversable collection of items
     */
    public function list(?array $estate_filter = null): CollectionResponse
    {
        $parameters = $this->getFilterParameters([
            'EstateFilter' => $estate_filter,
        ]);

        $request = new CollectionRequest('POST', 'v1/estates/usedcities/list', $parameters);
        $request->setResponseKey('cities')->requireAuthentication(true)->allowGreedyCache(true);
        return new CollectionResponse($this->getApiAdapter()->request($request));
    }
}
