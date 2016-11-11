<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist'] =
    str_replace("cal_noSpan", "cal_home_only,cal_noSpan", $GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_noSpan']['eval']['tl_class'] .= 'w50';

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_home_only'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_home_only'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class'=>'w50'],
    'sql'       => "char(1) NOT NULL default ''"
];
