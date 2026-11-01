<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_calendar']['fields']['spielplan'] = [
    'inputType'               => 'checkbox',
    //'eval'                    => ['tl_class'=>'w50' ],
    'sql'                     => ['type' => 'boolean', 'default' => false]
];

PaletteManipulator::create()
	->addField('spielplan', 'jumpTo')
	->applyToPalette('default', 'tl_calendar')
;


