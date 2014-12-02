<?php namespace Jarvis\Harvest\Resources; 

use Jarvis\Harvest\HarvestAPI;

class Clients extends HarvestAPI {

    /**
     * Endpoint URL.
     *
     * @var string
     */
    protected $endpoint = 'clients';

    /**
     * Get a list of all clients.
     *
     * @param DateTime $updatedSince
     */
    public function all($updatedSince = null)
    {
        if ( ! is_null($updatedSince))
        {

        }

        return $this->callEndpoint();
    }
} 