<?php namespace OddHill\Harvest; 

use GuzzleHttp\Exception\ClientException;
use OddHill\Harvest\Contracts\HttpClient as HttpClientInterface;
use GuzzleHttp\Client;

// implements HttpClientInterface

class HttpClient {

    /**
     * Headers to use for the requests.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Query parameters to use with the request.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Body parameters.
     *
     * @var array
     */
    protected $body = [];

    /**
     * The guzzle http client.
     *
     * @var Client
     */
    private $client;

    /**
     * Construct.
     */
    function __construct($baseUrl)
    {
        $this->client = new Client(['base_url' => $baseUrl]);
    }

    /**
     * Perform a get request.
     *
     * @param  string $url
     * @param  array  $options
     * @return mixed
     */
    public function get($url, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * Perform a post request.
     *
     * @param  string $url
     * @param  array  $options
     * @return mixed
     */
    public function post($url, $options = [])
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * Perform a put request.
     *
     * @param  string $url
     * @param  array  $options
     * @return mixed
     */
    public function put($url, $options = [])
    {
        return $this->request('PUT', $url, $options);
    }

    /**
     * Perform a delete request.
     *
     * @param  string $url
     * @param  array  $options
     * @return mixed
     */
    public function delete($url, $options = [])
    {
        return $this->request('DELETE', $url, $options);
    }

    /**
     * Build the request and send it.
     *
     * @param string $method
     * @param null   $url
     * @param array  $options
     * @param array  $options
     */
    private function request($method = 'GET', $url, $options = [])
    {
        // Merge in external options set in the class.
        $options = array_merge($options, ['headers' => $this->headers]);
        $options = array_merge($options, ['body' => $this->body]);

        $request = $this->client->createRequest($method, $url, $options);

        $response = $this->client->send($request);

        return $response->json();
    }

    /**
     * Set many headers at once.
     *
     * @param  array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        foreach ($headers as $header => $value)
        {
            $this->setHeader($header, $value);
        }

        return $this;
    }

    /**
     * Set one header.
     *
     * @param  string $header
     * @param  string $value
     * @return $this
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Set a parameter to use with the request.
     *
     * @param  string $param
     * @param  string $value
     * @return $this
     */
    public function setParameter($param, $value)
    {
        $this->parameters[$param] = $value;

        return $this;
    }

    /**
     * Set body values.
     *
     * @param  array $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = array_merge($this->body, $body);

        return $this;
    }

}