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
	->removeField('location')
	->removeField('address')
//	->removeField('teaser')
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
	->applyToPalette('default', 'tl_calendar_events')
;

// TODO: Palette umsortieren: Datum vor Zeit

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
