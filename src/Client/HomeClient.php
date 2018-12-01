<?php
namespace StefanFroemken\Home\Client;

/*
 * This file is part of the home project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GuzzleHttp\Client;
use StefanFroemken\Home\Exception\HttpRequestException;
use StefanFroemken\Home\PostProcessor\PostProcessorInterface;
use StefanFroemken\Home\Request\RequestInterface;

/**
 * Home Client which starts the requests to various devices
 */
class HomeClient
{
    /**
     * Guzzle Client
     *
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Process request
     *
     * @param RequestInterface $request
     * @return array
     * @throws \Exception if request is not valid or could not be decoded!
     */
    public function processRequest(RequestInterface $request): array
    {
        $body = null;
        if (!$request->isValidRequest()) {
            throw new HttpRequestException('Request not valid', 1513940893);
        }
        $response = $this->client->request(
            $request->getMethod(),
            $request->getUri(),
            [
                'body' => $request->getBody(),
                'headers' => $this->getHeaders($request)
            ]
        );
        if ($response->getStatusCode() === 200) {
            $body = (string)$response->getBody();
            foreach ($request->getPostProcessors() as $postProcessor) {
                if ($postProcessor instanceof PostProcessorInterface) {
                    $body = $postProcessor->process($body);
                }
            }
            $this->cacheInstance->set($cacheIdentifier, \json_encode($body), $request->getCacheTags());
        }
        return $body;
    }

    /**
     * Get headers for request
     *
     * @param RequestInterface $request
     * @return array
     */
    protected function getHeaders(RequestInterface $request): array
    {
        $headers = [];
        $headers['X-SP-Mandant'] = $request->getMandant();
        if ($request->getAccept()) {
            $headers['Accept'] = $request->getAccept();
            if ($request->getAccept() === 'text/plain') {
                $headers['Content-Type'] = 'text/plain';
            }
        }
        $token = $this->registry->get('ServiceBw', 'token');
        if (!empty($token)) {
            $headers['Authorization'] = $token;
        }

        return $headers;
    }
}