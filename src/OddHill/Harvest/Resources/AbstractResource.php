<?php namespace OddHill\Harvest\Resources;

use OddHill\Harvest\Client;

abstract class AbstractResource {

    /**
     * @var null|string
     */
    private $accessToken;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Perform a get request.
     *
     * @param  string $url
     * @param  array $options
     * @return array
     */
    public function get($url, array $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * Perform a post request.
     *
     * @param  string $url
     * @param  array $options
     * @return array
     */
    public function post($url, array $options = [])
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * Builds and performs a request.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $options
     * @return array
     *
     * TODO: Should allow the user to recieve XML data also if they wish to.
     */
    public function request($method, $url, array $options = [])
    {
        $client = $this->client->getHttpClient();

        // If an access token has been set add it to the request.
        if ($this->hasAccessToken())
        {
            $options['query']['access_token'] = $this->accessToken;
        }

        // Set headers to accept only json data.
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';

        $request = $client->createRequest($method, $url, $options);

        $response = $client->send($request);

        return $response->json();
    }

    /**
     * Set the access token.
     *
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Checks if an access token exists.
     *
     * @return bool
     */
    private function hasAccessToken()
    {
        if (is_null($this->accessToken))
        {
            return false;
        }

        return true;
    }

}
