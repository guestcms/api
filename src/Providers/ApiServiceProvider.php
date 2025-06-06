<?php

namespace GuestCMS\Api\Providers;

use GuestCMS\Api\Commands\GenerateDocumentationCommand;
use GuestCMS\Api\Facades\ApiHelper;
use GuestCMS\Api\Http\Middleware\ForceJsonResponseMiddleware;
use GuestCMS\Api\Models\PersonalAccessToken;
use GuestCMS\Base\Facades\PanelSectionManager;
use GuestCMS\Base\PanelSections\PanelSectionItem;
use GuestCMS\Base\Supports\ServiceProvider;
use GuestCMS\Base\Traits\LoadAndPublishDataTrait;
use GuestCMS\Setting\PanelSections\SettingCommonPanelSection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Laravel\Sanctum\Sanctum;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app['config']->set([
            'scribe.routes.0.match.prefixes' => ['api/*'],
            'scribe.routes.0.apply.headers' => [
                'Authorization' => 'Bearer {token}',
                'Api-Version' => 'v1',
            ],
        ]);

        if (class_exists('ApiHelper')) {
            AliasLoader::getInstance()->alias('ApiHelper', ApiHelper::class);
        }
    }

    public function boot(): void
    {
        if (version_compare('7.2.0', get_core_version(), '>')) {
            return;
        }

        $this
            ->setNamespace('packages/api')
            ->loadRoutes()
            ->loadAndPublishConfigurations(['api', 'permissions'])
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->loadAndPublishViews();

        if (ApiHelper::enabled()) {
            $this->loadRoutes(['api']);
        }

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            if (ApiHelper::enabled()) {
                $this->app['router']->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
            }
        });

        PanelSectionManager::beforeRendering(function () {
            PanelSectionManager::default()
                ->registerItem(
                    SettingCommonPanelSection::class,
                    fn () => PanelSectionItem::make('settings.common.api')
                        ->setTitle(trans('packages/api::api.settings'))
                        ->withDescription(trans('packages/api::api.settings_description'))
                        ->withIcon('ti ti-api')
                        ->withPriority(110)
                        ->withRoute('api.settings')
                );
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateDocumentationCommand::class,
            ]);
        }

        $this->app->booted(function () {
            add_filter('core_acl_role_permissions', function (array $permissions) {
                $apiPermissions = $this->app['config']->get('packages.api.permissions', []);

                if (! $apiPermissions) {
                    return $permissions;
                }

                foreach ($apiPermissions as $permission) {
                    $permissions[$permission['flag']] = $permission;
                }

                return $permissions;
            }, 120);
        });
    }

    protected function getPath(?string $path = null): string
    {
        return __DIR__ . '/../..' . ($path ? '/' . ltrim($path, '/') : '');
    }
}
