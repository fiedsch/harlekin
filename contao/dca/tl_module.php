<?php
use \Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
	->addField('cal_home_only', 'cssID', PaletteManipulator::POSITION_AFTER)
	->applyToPalette('eventlist', 'tl_module');

PaletteManipulator::create()
	->addField('cal_home_only', 'cssID', PaletteManipulator::POSITION_AFTER)
	->applyToPalette('calendar', 'tl_module');

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_home_only'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class'=>'w50'],
    'sql'       => "char(1) NOT NULL default ''"
];
