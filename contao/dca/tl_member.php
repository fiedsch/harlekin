<?php

/* 
 * modify palette remove unused fields
 */
use Contao\CoreBundle\DataContainer\PaletteManipulator;


$paletteManipulator = PaletteManipulator::create();
foreach (['company', 'state', 'country', 'fax', 'website', 'language', 'assignDir'] as $field) {

    $paletteManipulator->removeField($field);
    
    // remove these fields from the search,sort,filter, panel also!
    foreach (['search', 'sorting', 'filter'] as $paletteOption) {
        $GLOBALS['TL_DCA']['tl_member']['fields'][$field][$paletteOption] = false;
    }

}
$paletteManipulator->applyToPalette('default', 'tl_member');

/*
 * Do not remove from palette, but disable search, sort, filter 
 */
foreach (['search', 'sorting', 'filter'] as $paletteOption) {
  $GLOBALS['TL_DCA']['tl_member']['fields']['login'][$paletteOption] = false;
}