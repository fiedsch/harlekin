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
	//->removeField('location')
	->removeField('address')
//	->removeField('teaser') // [*] siehe App\EventListener\DataContainer\EventCallbacks, wo Kalender IDs hart kodiert sind :-(
	->removeField('addEnclosure')
	->removeField('enclosure')
//	->removeField('addImage')
//	->removeField('recurring')

	->removeField('cssClass')
	->removeField('searchIndexer')
//	//->removeField('noComments') // siehe AddCommentsFieldsListener
	->removeField('published')
	->removeField('start')
	->removeField('stop')
	->removeField('startDate') // will be added again later at another position
	->addField('startDate', 'addTime', PaletteManipulator::POSITION_BEFORE)
    //->addField('spielort', 'startDate', PaletteManipulator::POSITION_AFTER) // [*]
	->applyToPalette('default', 'tl_calendar_events')
;

PaletteManipulator::create()
	->addField('startDate', 'addTime', PaletteManipulator::POSITION_BEFORE)
	->applyToPalette('default', 'tl_calendar_events')
;


// PaletteManipulator::create()
// 	->removeField('endTime')
// 	->applyToSubpalette('addTime', 'tl_calendar_events')
// ;

// TODO: im submit callback
//     https://docs.contao.org/5.x/dev/reference/dca/callbacks/#config-onsubmit
//     "die benötigten Felder geeignet setzen".
//     Bsp.: published = 1

// TODO: im oncreate callback (?) z.B. addTime ankreuzen
//       und (oben) dieses Feld verstecken -- wir wollen immer eine Zeit hinzufügen
//       https://docs.contao.org/5.x/dev/reference/dca/callbacks/#config-oncreate

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
	['App\EventListener\DataContainer\EventCallbacks', 'handleFieldsLabels']
];
