<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['teammemberlist']
  = '{type_legend},type,headline;{team_legend},team;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['team'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['team'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_team.name',
    'eval'                    => array('tl_class'=>'long','chosen'=>true, 'multiple'=>false),
    'sql'                     => "int(10) NOT NULL default '0'",
);


