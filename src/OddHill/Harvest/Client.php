<?php namespace OddHill\Harvest;

use GuzzleHttp\Client as GuzzleClient;
use Exception, InvalidArgumentException;
use GuzzleHttp\Exception\ClientException;

class Client {

    const HARVEST_API_URI       = 'https://api.harvestapp.com/';
    const HARVEST_AUTHORIZE_URI = 'oauth2/authorize';
    const HARVEST_TOKEN_URI     = 'oauth2/token';

    /**
     * Harvest URLs.
     *
     * @var array
     */
    private $urls = [
        'base_url'      => 'https://api.harvestapp.com/',
        'authorize'     => 'oauth2/authorize',
        'token'         => 'oauth2/token',
    ];

    /**
     * Harvest options.
     *
     * @var array
     */
    protected $options = [
        'account'       => 'api',
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => '',
        'response_type' => 'json',
    ];

    /**
     * The HTTP client to use for the requests.
     *
     * @var GuzzleClient
     */
    private $httpClient;

    /**
     * @param array $options
     * @param string $accessToken
     */
    function __construct($options = [], $accessToken = null)
    {
        $this->setOptions($options);
    }

    /**
     * Set the http client.
     *
     * @param GuzzleClient $client
     */
    public function setHttpClient(GuzzleClient $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Get a fresh instance of the http client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient))
        {
            $this->httpClient = new GuzzleClient([
                'base_url' => $this->getBaseUrl(),
            ]);
        }

        return clone $this->httpClient;
    }

    /**
     * Returns a resource class if it exists otherwise throws an exception.
     *
     * @param  string $resource
     * @param  null   $token
     * @return \OddHill\Harvest\Resources\AbstractResource
     */
    public function resource($resource, $token = null)
    {
        $resource = "OddHill\\Harvest\\Resources\\" . $this->toCamelCase($resource);

        if ( ! class_exists($resource))
        {
            throw new InvalidArgumentException("The entered resource [$resource] is not a valid resource name.");
        }

        $object = new $resource($this);

        // If an access token was set add it to the request object.
        if ( ! is_null($token))
        {
            $object->setAccessToken($token);
        }

        return $object;
    }

    /**
     * Redirect the user to the harvest authorization page.
     *
     * @param null $csrf
     */
    public function authorize($csrf = null)
    {
        $parameters = [
            'client_id'     => $this->options['client_id'],
            'redirect_uri'  => $this->options['redirect_uri'],
            'response_type' => 'code',
        ];

        if ( ! is_null($csrf)) {
            $parameters['state'] = $csrf;
        }

        header('Location: ' . $this->urls['base_url'] . '/' . $this->urls['authorize'] . $this->httpBuildQuery($parameters));
    }

    /**
     * Request an access token.
     *
     * @param  string $code
     * @return mixed
     */
    public function requestAccessToken($code)
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => [
                'code'          => $code,
                'client_id'     => $this->options['client_id'],
                'client_secret' => $this->options['client_secret'],
                'redirect_uri'  => $this->options['redirect_uri'],
                'grant_type'    => 'authorization_code',
            ],
        ];

        $client = $this->getHttpClient();

        return $client->post($this->urls['token'], $options)->json();
    }

    /**
     * Refresh the access token.
     *
     * @param  string $refreshToken
     * @return mixed
     */
    public function refreshAccessToken($refreshToken)
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => [
                'refresh_token' => $refreshToken,
                'client_id'     => $this->options['client_id'],
                'client_secret' => $this->options['client_secret'],
                'grant_type'    => 'refresh_token'
            ],
        ];

        $client = $this->getHttpClient();

        return $client->post(self::HARVEST_TOKEN_URI, $options)->json();
    }

    /**
     * Set the options.
     *
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Get a single option value.
     *
     * @param  string $option
     * @throws Exception
     * @return string
     */
    public function getOption($option)
    {
        if ( ! array_key_exists($option, $this->options))
        {
            throw new Exception("The requested option [$option] has not been set or is not a valid option key.");
        }

        return $this->options[$option];
    }

    /**
     * Get the translated base url.
     *
     * @return string
     */
    private function getBaseUrl()
    {
        $baseUrl = $this->urls['base_url'];

        if ($this->options['account'] !== 'api')
        {
            $baseUrl = str_replace('api', $this->options['account'], $baseUrl);
        }

        return $baseUrl;
    }

    /**
     * Generates a formatted http query string.
     *
     * @param  array $params
     * @return string
     */
    private function httpBuildQuery($params)
    {
        return '?' . http_build_query($params);
    }

    /**
     * Converts a string to camel case.
     *
     * @param  string $string
     * @return string
     */
    private function toCamelCase($string)
    {
        $string = ucfirst(str_replace(['-', '_'], ' ', $string));

        return ucfirst(str_replace(' ', '', $string));
    }

    /**
     * Do not want to call the resource method? Then just make a method call
     * in camel case and watch the magic unfold!
     *
     * @param  string $name
     * @param  array  $arguments
     * @return object
     */
    public function __call($name, $arguments)
    {
        $token = null;

        // If the first arguments is set and it is a string it should be the
        // access token.
        if (is_string($arguments[0]))
        {
            $token = $arguments[0];
        }

        return $this->resource($name, $token);
    }

}
