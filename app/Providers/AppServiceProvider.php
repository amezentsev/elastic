<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

use Manticoresearch\Client as ManticoreClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $elasticUrl = config('services.elasticsearch.protocol')."://".config('services.elasticsearch.host').":".config('services.elasticsearch.port');

            return ClientBuilder::create()->setHosts([$elasticUrl])->build();
        });

        $this->app->singleton(ManticoreClient::class, function ($app) {
            $config = ['host'=> config('services.manticore.host'),'port'=> config('services.manticore.port')];

            return new \Manticoresearch\Client($config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
