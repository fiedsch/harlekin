<?php
/**
 * hide some (almost all) buttons if user is not admin as
 * regular Users are only supposed to edit calendar_events.
 */
if (!\BackendUser::getInstance()->isAdmin) {
    foreach (['editheader', 'copy','delete','show'] as $operation) {
        unset($GLOBALS['TL_DCA']['tl_calendar']['list']['operations'][$operation]);
    }
    // remove the all-operation which does not work anymore after our unset(...) above
    unset($GLOBALS['TL_DCA']['tl_calendar']['list']['global_operations']['all']);
}

