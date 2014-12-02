<?php namespace OddHill\Harvest\Laravel\Four; 

use Illuminate\Support\ServiceProvider;
use OddHill\Harvest\Client;

class HarvestServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $options = $this->app['config']['services.harvest'];

        $this->app->bindShared('OddHill\Harvest\Client', function() use ($options)
        {
            return new Client($options);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['OddHill\Harvest\Client'];
    }

}
