<?php namespace Visiosoft\SystemModule\Telescope\Table;

/**
 * Class RedisTableBuilder
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RedisTableBuilder extends TelescopeTableBuilder
{

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [
        'entry.content.id' => [
            'heading' => 'ID',
        ],
    ];
}
