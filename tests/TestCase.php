<?php

namespace Nagy\LaravelStatus\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Nagy\LaravelStatus\LaravelStatusServiceProvider;

abstract class TestCase extends Orchestra
{

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.key', env('APP_KEY'));
    }

    public function setUpDatabase($app)
    {
        include_once __DIR__.'/database/migrations/create_users_table.php';
        with(new \CreateUsersTable())->up();

        $this->withFactories(__DIR__.'/database/factories');
    }
    
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Nagy\LaravelStatus\LaravelStatusServiceProvider::class,
        ];
    }

    public function seedUser($status)
    {
        return factory(\Nagy\LaravelStatus\Tests\Models\User::class)->create([
            'status' => $status,
        ]);
    }

    public function seedUsers($count, $status)
    {
        return factory(\Nagy\LaravelStatus\Tests\Models\User::class, $count)->create([
            'status' => $status,
        ]);
    }
}