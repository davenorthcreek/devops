<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = Auth::user();
            if ($user->dashboard) {
                $event->menu->add([
                    'key'         => 'dashboard',
                    'text'        => 'my_dashboard',
                    'url'         => 'dashboard',
                    'icon'        => 'fas fa-wrench',
                    'label'       => '✔',
                    'label_color' => 'success',
                ]);
            } else {
                $event->menu->add([
                    'key'         => 'dashboard',
                    'text'        => 'Create Dashboard',
                    'url'         => 'dashboard',
                    'icon'        => 'fas fa-magic',
                    'label'       => '...',
                    'label_color' => 'warning',
                ]);
            }
       });
    }
}
