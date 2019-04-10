<?php namespace Anomaly\SystemModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Laravel\Telescope\Console\ClearCommand;
use Laravel\Telescope\Console\PruneCommand;
use Laravel\Telescope\Contracts\ClearableRepository;
use Laravel\Telescope\Contracts\EntriesRepository;
use Laravel\Telescope\Contracts\PrunableRepository;
use Laravel\Telescope\Storage\DatabaseEntriesRepository;
use Laravel\Telescope\Telescope;

/**
 * Class SystemModuleServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class SystemModuleServiceProvider extends AddonServiceProvider
{

    /**
     * The addon commands.
     *
     * @var array
     */
    protected $commands = [
        ClearCommand::class,
        PruneCommand::class,
    ];

    /**
     * The scheduled commands.
     *
     * @var array
     */
    protected $schedules = [
        'daily' => [
            PruneCommand::class,
        ],
    ];

    /**
     * The addon routes.
     *
     * @type array|null
     */
    protected $routes = [
        'admin/system/{type?}'          => 'Anomaly\SystemModule\Http\Controller\Admin\TelescopeController@index',
        'admin/system/{type}/toggle'    => 'Anomaly\SystemModule\Http\Controller\Admin\TelescopeController@toggle',
        'admin/system/{type}/view/{id}' => 'Anomaly\SystemModule\Http\Controller\Admin\TelescopeController@view',
    ];

    /**
     * Register the addon.
     */
    public function register()
    {
        config(['telescope' => require base_path('vendor/laravel/telescope/config/telescope.php')]);

        $this->app->singleton(
            EntriesRepository::class,
            DatabaseEntriesRepository::class
        );

        $this->app->singleton(
            ClearableRepository::class,
            DatabaseEntriesRepository::class
        );

        $this->app->singleton(
            PrunableRepository::class,
            DatabaseEntriesRepository::class
        );

        $this->app->when(DatabaseEntriesRepository::class)
            ->needs('$connection')
            ->give(config('telescope.storage.database.connection'));
    }

    /**
     * Boot the addon.
     */
    public function boot()
    {
        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\LogWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.logs.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\JobWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.jobs.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\MailWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.mail.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\CacheWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.cache.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\RedisWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.redis.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\DumpWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.dumps.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\QueryWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.queries.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\ModelWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.models.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\EventWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.events.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\RequestWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.requests.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\CommandWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.commands.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\ScheduleWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.schedule.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\ExceptionWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.exceptions.enabled',
                    true
                ),
            ]
        );

        config(
            [
                'telescope.watchers.Laravel\Telescope\Watchers\NotificationWatcher.enabled' => config(
                    'anomaly.module.system::telescope.watchers.notifications.enabled',
                    true
                ),
            ]
        );

        config(['telescope.watchers.Laravel\Telescope\Watchers\GateWatcher.enabled' => false]);

        if (request()->is('admin/system*')) {
            return;
        }

        if (!config('anomaly.module.system::telescope.enabled', false)) {
            return;
        }

        if (!config('anomaly.module.system::telescope.admin_enabled', false) && request()->is('admin*')) {
            return;
        }

        Telescope::start($this->app);

        Telescope::listenForStorageOpportunities($this->app);
    }

}
