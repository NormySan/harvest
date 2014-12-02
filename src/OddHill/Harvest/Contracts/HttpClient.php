<?php namespace OddHill\Harvest\Contracts; 

interface HttpClient {

    /**
     * Perform a get request.
     *
     * @param  string $url
     * @param  array  $parameters
     * @return mixed
     */
    public function get($url, $parameters = []);

    /**
     * Perform a post request.
     *
     * @param  string $url
     * @param  array  $body
     * @return mixed
     */
    public function post($url, $body = []);

    /**
     * Perform a put request.
     *
     * @param  string $url
     * @param  array  $body
     * @return mixed
     */
    public function put($url, $body = []);

    /**
     * Perform a delete request.
     *
     * @param  string $url
     * @param  array  $body
     * @return mixed
     */
    public function delete($url, $body = []);

    /**
     * Set headers to use with the request.
     *
     * @param  array $headers
     */
    public function setHeaders($headers);
}