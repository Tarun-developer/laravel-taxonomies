<?php

namespace Botdigit\Taxonomies;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class TaxonomiesServiceProvider extends ServiceProvider {

    protected $migrations = [
        'CreateTaxonomiesTable' => 'create_taxonomies_table'
    ];

    /**
     * @inheritdoc
     */
    public function boot() {
        $this->handleConfig(); 
        $this->handleMigrations();
    }

    /**
     * @inheritdoc
     */
    public function register() {
        //
    }

    /**
     * @inheritdoc
     */
    public function provides() {
        return [];
    }

    /**
     * Publish and merge the config file.
     *
     * @return void
     */
    private function handleConfig() {
        $source = dirname(__DIR__) . '/config/taxonomies.php';
        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('taxonomies.php')], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('taxonomies');
        }

        $this->mergeConfigFrom($source, 'taxonomies');
    }

    /**
     * Publish migrations.
     *
     * @return void
     */
    private function handleMigrations() {
        foreach ($this->migrations as $class => $file) {
            if (!class_exists($class)) {
                $timestamp = date('Y_m_d_His', time());

                $this->publishes([
                    __DIR__ . '/../database/migrations/' . $file . '.php.stub' =>
                    database_path('migrations/' . $timestamp . '_' . $file . '.php')
                        ], 'migrations');
            }
        }
    }

}
