<?php

declare(strict_types=1);

namespace App\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Module;
#[AsHook('getAllEvents')]
class GetAllEventsListener
{
    public function __invoke(array $events, array $calendars, int $timeStart, int $timeEnd, Module $module): array
    {
        if (!$module->cal_home_only) {
            return $events;
        }
        $result = [];
        foreach ($events as $day => $daydata) {
            foreach ($daydata as $tstamp => $tsampdata) {
                $tmp = array_filter($tsampdata, function($event) {
                    return $event['spielort'] === 'home';
                });
                if (!empty($tmp)) {
                    $result[$day][$tstamp] = $tmp;
                }
            }
        }

        return $result;
    }

}
