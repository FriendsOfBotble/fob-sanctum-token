<?php

namespace FriendsOfBotble\SanctumToken\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use FriendsOfBotble\SanctumToken\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class SanctumTokenServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this
            ->setNamespace('plugins/sanctum-token')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes();
    }

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-sanctum-token',
                    'name' => trans('plugins/sanctum-token::sanctum-token.name'),
                    'icon' => 'ti ti-key',
                    'url' => route('sanctum-token.index'),
                    'permissions' => ['sanctum-token.index'],
                ]);
        });
    }
}
