<?php


namespace Hanson\WorkException;


use Illuminate\Support\ServiceProvider;

class WorkExceptionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->publishes([
            __DIR__.'/config/work_exception.php' => config_path('work_exception.php')
        ], 'work-exception');

        $this->commands([
            ChatCommand::class,
        ]);
    }

}