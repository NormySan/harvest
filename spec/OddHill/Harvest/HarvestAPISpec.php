<?php namespace spec\OddHill\Harvest;

use GuzzleHttp\Client;
use OddHill\Harvest\HarvestFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HarvestAPISpec extends ObjectBehavior
{
    function let(Client $client, HarvestFactory $factory)
    {
        $config = [];

        $this->beConstructedWith($config, $client, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('OddHill\Harvest\HarvestAPI');
    }

}
