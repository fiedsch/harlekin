<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->removeField('featured')
	->removeField('author')
	->removeField('endDate')
	->removeField('alias')
	->removeField('source')
	->removeField('linkText')
	->removeField('canonicalLink')
	->removeField('pageTitle')
	->removeField('robots')
	->removeField('description')
	->removeField('serpPreview')
	->removeField('address')
//	->removeField('teaser') // [*] siehe App\EventListener\DataContainer\EventCallbacks, wo dies abhängig von den Einstellungen des Kalenders gemacht wird
	->removeField('addEnclosure')
	->removeField('enclosure')
	// ->removeField('cssClass')
	->removeField('searchIndexer')
	//->removeField('noComments') // falls das comments bundle installiert ist: siehe den AddCommentsFieldsListener dort
	->removeField('published')
	->removeField('start')
	->removeField('stop')
	->removeField('startDate') // [*] fügen wir später an anderer Position wieder ein
	->removeField('endDate') // [*]
	// ->addField('startDate', 'addTime', PaletteManipulator::POSITION_BEFORE)
    //->addField('spielort', 'startDate', PaletteManipulator::POSITION_AFTER) // [*]
	->applyToPalette('default', 'tl_calendar_events')
;

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['spielort'] = [
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['home','away'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['spielort_values'],
    'exclude'                 => true,
    'filter'                  => true,
    'eval'                    => array('tl_class'=>'w50', 'mandatory'=>true, 'includeBlankOption'=>false, 'default' => 'home'),
    'sql'                     => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'] = [
    ['App\EventListener\DataContainer\EventCallbacks', 'handleFieldsDisplay'],
	['App\EventListener\DataContainer\EventCallbacks', 'handleFieldsLabels'],
	['App\EventListener\DataContainer\EventCallbacks', 'handleCalendarOperations'],
];


