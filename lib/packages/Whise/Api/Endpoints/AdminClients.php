<?php

/*
* This file is part of the fw4/whise-api library
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ADB\ImmoSyncWhise\Vendor\Whise\Api\Endpoints;

use ADB\ImmoSyncWhise\Vendor\Whise\Api\Endpoint;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Request\Request;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Request\CollectionRequest;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Response\Response;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Response\CollectionResponse;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Response\CollectionResponsePaginated;

final class AdminClients extends Endpoint
{
    /**
     * Request a list of activated clients.
     *
     * @link http://api.whise.eu/WebsiteDesigner.html#operation/Admin_GetClients
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
        $request = new CollectionRequest('POST', 'v1/admin/clients/list', $parameters);
        $request->setResponseKey('clients')->requireAuthentication(true)->allowGreedyCache(true);
        return new CollectionResponsePaginated($request, $this->getApiAdapter());
    }

    /**
     * Request the settings defined by the client.
     *
     * @link http://api.whise.eu/WebsiteDesigner.html#operation/Admin_GetClientSetting
     * Official documentation
     *
     * @param int|array $parameters Associative array containing request
     * parameters, or the client ID
     *
     * @throws Exception\InvalidRequestException if the API rejects the request
     * due to invalid input
     * @throws Exception\AuthException if access is denied
     * @throws Exception\AuthException if access token is missing or invalid
     * @throws Exception\ApiException if a server-side error occurred
     *
     * @return Response
     */
    public function settings($parameters): Response
    {
        if (!is_array($parameters)) {
            $parameters = [
                'ClientId' => intval($parameters),
            ];
        }
        $request = new Request('POST', 'v1/admin/clients/settings', $parameters);
        $request->setResponseKey('settings')->requireAuthentication(true)->allowGreedyCache(true);
        return new Response($this->getApiAdapter()->request($request));
    }

    /**
     * Request a JWT token for a client. The OfficeId field is required to
     * select an activated office of that client.
     *
     * @link http://api.whise.eu/WebsiteDesigner.html#operation/Admin_GetClientToken
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
     * @return Response
     */
    public function token(array $parameters): Response
    {
        $request = new Request('POST', 'v1/admin/clients/token', $parameters);
        $request->requireAuthentication(true);
        return new Response($this->getApiAdapter()->request($request));
    }
}
