<?php

namespace Datlechin\SanctumToken\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Datlechin\SanctumToken\Models\PersonalAccessToken;
use Datlechin\SanctumToken\Repositories\Caches\SanctumTokenCacheDecorator;
use Datlechin\SanctumToken\Repositories\Eloquent\SanctumTokenRepository;
use Datlechin\SanctumToken\Repositories\Interfaces\SanctumTokenInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class SanctumTokenServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this
            ->setNamespace('plugins/sanctum-token')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes();

        $this->app->bind(SanctumTokenInterface::class, function () {
            return new SanctumTokenCacheDecorator(new SanctumTokenRepository(new PersonalAccessToken()));
        });
    }

    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-sanctum-token',
                'name' => trans('plugins/sanctum-token::sanctum-token.name'),
                'icon' => 'fas fa-fingerprint',
                'url' => route('sanctum-token.index'),
            ]);
        });
    }
}
