<?php

/* we don't want email to be mandatory */
$GLOBALS['TL_DCA']['tl_member']['fields']['email']['eval']['mandatory'] = false;

$GLOBALS['TL_DCA']['tl_member']['fields']['gender']['filter'] = true;

/* new fields */

$GLOBALS['TL_DCA']['tl_member']['fields']['nickname'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_member']['nickname'],
    'inputType' => 'text',
    'exclude'   => true,
    'search'    => true,
    'filter'    => false,
    'sorting'   => true,
    'eval'      => ['maxlength' => 255],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['teams'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_member']['teams'],
    'inputType'  => 'checkboxWizard',
    'exclude'    => true,
    'search'     => true,
    'filter'     => true,
    'sorting'    => true,
    'foreignKey' => 'tl_team.name',
    'eval'       => ['multiple' => true],
    'sql'        => "blob NULL",
];


/* modify palette (1) remove unused fields */
/*
 * {personal_legend},firstname,lastname,dateOfBirth,gender;
 * {address_legend:hide},street,postal,city,country;
 * {contact_legend},phone,mobile,fax,email,website,language;
 * {groups_legend},groups;
 * {login_legend},login;
 * {homedir_legend:hide},assignDir;
 * {account_legend},disable,start,stop
 */

foreach (['company', 'state', 'country', 'fax', 'website', 'language', 'assignDir'] as $field) {
    $GLOBALS['TL_DCA']['tl_member']['palettes']['default']
        = str_replace(",$field", '', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
    // remove these fields from the search,sort,filter, panel also!
    foreach (['search', 'sorting', 'filter'] as $paletteOption) {
        $GLOBALS['TL_DCA']['tl_member']['fields'][$field][$paletteOption] = false;
    }

}

$GLOBALS['TL_DCA']['tl_member']['palettes']['default']
    = str_replace("lastname", 'lastname,nickname', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_member']['palettes']['default']
    = str_replace("{contact_legend}", '{harlekin_legend},teams;{contact_legend}', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
/* add css attribues so the fields align nicer */

$GLOBALS['TL_DCA']['tl_member']['fields']['postal']['eval']['tl_class'] .= ' clr';

/*
str_replace('{date_legend}', '{harlekin_legend},;{date_legend}',
    $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
*/
/* modify palette (2) add new fields */