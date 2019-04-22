<?php

namespace Thinkstudeo\Textlocal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;

class Request
{
    /**
     * Guzzlehttp client
     *
     * @var Guzzlehttp\Client
     */
    protected $http;

    /**
     * Create a new instance.
     *
     * @param \Thinkstudeo\Textlocal\Response $response
     */
    public function __construct(Response $response)
    {
        $client         = new Client(['verify' => false]);
        $this->http     = $client;
        $this->response = $response;
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $options
     * @return Response|ApiRequestFailure
     */
    public function get($url, array $queryParams = [], array $options = [])
    {
        $params = ['query' => array_merge($queryParams, $options)];

        return $this->request('GET', $url, $params);
    }

    /**
     * @param string $url
     * @param array $formParams
     * @param array $options
     * @return Response|ApiRequestFailure
     */
    public function post($url, array $formParams = [], array $options = [])
    {
        $params = ['form_params' => array_merge($formParams, $options)];

        return $this->request('POST', $url, $params);
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @return Response|ApiRequestFailure
     * @throws GuzzleException
     */
    public function request($method, $url, $params)
    {
        var_dump('Request request url');
        var_dump($url);
        // var_dump('Request request params');
        // var_dump($params);
        try
        {
            $response = $this->http->request($method, $url, $params);
            $data     = json_decode($response->getBody()->getContents());
            var_dump('Request request response');
            var_dump(json_encode($data));
        }
        catch (RequestException | SeekException | TransferException $e)
        {
            throw $e;
        }

        return $this->handleResponse($data);
    }

    /**
     * Handle the response from textlocal api call.
     * @param $data
     * @param $url
     * @return mixed
     */
    protected function handleResponse($data)
    {
//        var_dump("Request handleResponse data:");
//        var_dump($data);
        // $responseClass = $this->responseClass($url);

        // return $responseClass::handle($data);

        return $this->response->handle($data);
    }
}