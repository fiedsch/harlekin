<?php

/*
 * Palette ändern: Felder entfernen, die wir bei unserem speziellen Anwendungsfall nicht benötigen
 */

// Teasertext entfernen. Sollen Detailinforationen hinzugefügt werden, kann das auch
// über hinzufühgen von Content Elementen zum Event gemacht werden.

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
    str_replace('teaser', '', $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);

// Veranstaltungsort entfernen. Wird später in der "harlekin_legend" eingefügt

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
    str_replace('location', '', $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location']['eval']['tl_class'] = 'w50';

// Bilder (wie teaser) deaktivieren

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
    str_replace('{image_legend},addImage;', '', $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);

// Anhänge deaktivieren
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
    preg_replace('/{enclosure_legend.*,addEnclosure;/', '', $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);
/*
 * Palette ändern: Felder hinzufügen, die wir bei unserem speziellen Anwendungsfall benötigen
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
    str_replace('{date_legend}', '{harlekin_legend},im_harlekin,location;{date_legend}', $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['im_harlekin'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['im_harlekin'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['home','away'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['MSC'],
    'exclude'                 => true,
    'filter'                  => true,
    'eval'                    => array('tl_class'=>'w50', 'mandatory'=>true, 'includeBlankOption'=>true),
    'sql'                     => "varchar(32) NOT NULL default ''",
);


/*
 * Search und Filter Standards verändern
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['recurring']['filter'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['source']['filter'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['noComments']['filter'] = false;

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['alias']['search'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['teaser']['search'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['alt']['search'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['imageUrl']['search'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['caption']['search'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['url']['search'] = false;

/*
 * Defaultwerte ändern
 */

// Ein Event soll standardmäßig veröffentlicht sein
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['published']['sql']
    = str_replace("default ''", "default '1'", $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['published']['sql']);