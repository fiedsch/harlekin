<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['teammemberlist']
  = '{type_legend},type,headline;{team_legend},team;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['team'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['team'],
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    //'inputType'               => 'select',
    'foreignKey'              => 'tl_member.CONCAT(lastname,", ",firstname)',
    'eval'                    => array('tl_class'=>'long','multiple'=>true),
    //'eval'                    => array('tl_class'=>'long','chosen'=>true, 'multiple'=>true),
    'sql'                     => "blob NULL",
);


