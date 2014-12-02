<?php namespace OddHill\Harvest\Laravel\Four;

use Illuminate\Support\Facades\Facade;

class HarvestFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'OddHill\Harvest\Client';
    }

}