<?php
/**
 * Helper functions for Contao Events/Calendars etc.
 *
 * fiedsch@ja-eh.at
 */

namespace Harlekin;

class EventHelper
{

    /**
     * Reduce events provided to those that are 'home' events (see dca/tl_calendar_events.php `im_harlekin`)
     *
     * @param array $arrEvents
     * @param array $arrCalendars
     * @param int $intStart
     * @param int $intEnd
     * @param object $objModule
     * @return array
     */
    public function reduceToHomeEvents($arrEvents, $arrCalendars, $intStart, $intEnd, $objModule)
    {
        if (!$objModule->cal_home_only) {
            return $arrEvents;
        }
        $result = [];
        foreach ($arrEvents as $day => $daydata) {
            foreach ($daydata as $tstamp => $tsampdata) {
                $tmp = array_filter($tsampdata, function($event) {
                    return $event['im_harlekin'] === 'home';
                });
                if (!empty($tmp)) {
                    $result[$day][$tstamp] = $tmp;
                }
            }
        }
        return $result;
    }

}