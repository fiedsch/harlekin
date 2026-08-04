<?php

declare(strict_types = 1);

namespace App\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;


class EventCallbacks
{
    public function __construct(private Connection $connection)
    {
    }

    #[AsCallback(table: 'tl_calendar_events', target: 'config.onbeforesubmit')]
    public function onBeforeSubmit(
        array $record,
        DataContainer $dc
    )
    {
        $record['published'] = true;

        return $record;
    }

    #[AsCallback(table: 'tl_calendar_events', target: 'config.oncreate')]
    public function onCreate(
        string $table,
        int $insertId,
        array $record,
        DataContainer $dc
    )
    {
        $query = <<<SQL
UPDATE tl_calendar_events
    SET addTime = ?
    WHERE id = ?
SQL;

       $this->connection->executeQuery($query, [true,  $insertId]);
    }

//    #[AsCallback(table: 'tl_calendar_events', target: 'config.onload')]
//    public function onLoad(DataContainer $dc)
//    {
//        /* TODO: if time is not set, set it to a default time (e.g. 20:00) */
//    }

}
