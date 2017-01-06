<?php

$GLOBALS['TL_CTE']['texts']['teammemberlist'] = 'ContentTeamMemberList';

$GLOBALS['BE_MOD']['accounts']['team']
    = [
        'tables' => ['tl_team','tl_team_member'],
        'icon'   => 'system/modules/harlekin/assets/images/mgroup.gif',
    ];

$GLOBALS['TL_HOOKS']['getAllEvents'][] = array('Harlekin\\EventHelper', 'reduceToHomeEvents');
