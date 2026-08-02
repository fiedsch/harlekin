<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
	->removeField('author')
	->removeField('featured')
	->removeField('endDate')
	->removeField('alias')
	->removeField('location')
	->removeField('address')
	->removeField('teaser')
	->removeField('source')
	->removeField('linkText')
	->removeField('canonicalLink')
	->removeField('pageTitle')
	->removeField('robots')
	->removeField('description')
	->removeField('serpPreview')
	->removeField('addImage')
	->removeField('recurring')
	->removeField('addEnclosure')
	->removeField('enclosure')
	->removeField('cssClass')
	->removeField('searchIndexer')
	//->removeField('noComments') // siehe AddCommentsFieldsListener
	->removeField('published')
	->removeField('start')
	->removeField('stop')
	->applyToPalette('default', 'tl_calendar_events')
;

PaletteManipulator::create()
	->removeField('endTime')
	->applyToSubpalette('addTime', 'tl_calendar_events')
;

// TODO: im submit callback
//     https://docs.contao.org/5.x/dev/reference/dca/callbacks/#config-onsubmit
//     "die benötigten Felder geeignet setzen".
//     Bsp.: published = 1
