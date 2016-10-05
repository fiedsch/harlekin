<?php

// System::loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_team_member'] = [

    'config' => [
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_team',
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
                'pid,member_id' => 'unique',
            ]
        ]
    ],

    'list' => [
        'sorting' => [
            'mode'                    => 4, // Displays the child records of a parent record
            'headerFields'            => array('name'),
            //'flag'                    => 1,
            'fields'                  => ['member_id ASC'],
            'panelLayout'             => '',
            'child_record_callback'   => ['tl_team_member', 'listMember'],
            'child_record_class'      => 'no_padding',
            'disableGrouping'         => true,
        ],
        'global_operations' => [
            'all' => [
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations' => [

            'edit' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_team_member']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ],
            'copy' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_team_member']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.gif'
            ],
            'cut' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_team_member']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif'
            ],
            'delete' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_team_member']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_team_member']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ]
        ]
    ],

    'palettes' => [
        'default'                     => '{member_legend},member_id'
    ],

    /*
     * TODO bei fields:
     *  Teamcaptain (erster, zweiter)
     *
     */
    'fields' => [
        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'foreignKey'              => 'tl_team.name',
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => ['type'=>'belongsTo', 'load'=>'eager']
        ],
        'tstamp' => [
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'member_id' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_team_member']['member_id'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'select',
            'eval'                    => [
                    'chosen' => true,
                    'wizard' => true
		    ],
            'wizard' => [ [ 'tl_team_member', 'editMember' ] ],
            'foreignKey'              => 'tl_member.CONCAT(lastname, ", ", firstname)',
            'relation'                => ['type'=>'hasOne', 'table'=>'tl_member', 'load'=>'eager'],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
    ]
];


// TODO: in eigene Klasse auslagern
class tl_team_member extends Backend
{
    /**
     *
     */
    public function editMember(DataContainer $dc)
    {
        if ($dc->value < 1) { return ''; }
        return '<a href="contao/main.php?do=member&amp;&amp;act=edit&amp;id='.$dc->value
            . '&amp;popup=1&amp;&amp;rt='.REQUEST_TOKEN
            . '" title="'. specialchars($GLOBALS['TL_LANG']['tl_team_member']['edit'][1]).'"'
            . ' style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''
            . specialchars(str_replace("'", "\\'", specialchars($GLOBALS['TL_LANG']['tl_team_member']['edit'][1])))
            . '\',\'url\':this.href});return false">'
            . Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_team_member']['edit'][1], 'style="vertical-align:top"')
            . '</a>';
    }

    /**
     * Return HTML Code to display one team member
     *
     * @param $arrRow
     * @return string
     */
    public function listMember($arrRow)
    {
        $member = \MemberModel::findById($arrRow['member_id']);

        // $team = \TeamModel::findById($arrRow['pid']);

        $team_member = \TeamMemberModel::findById($arrRow['id']);

        //return sprintf('<div class="tl_content_left">%s, %s: %s (%s, <pre>%s</pre>, <pre>%s</pre>)</div>',
        return sprintf('<div class="tl_content_left">%s, %s: %s</div>',
            $member->lastname,
            $member->firstname,
            $team_member->getRelated('member_id')->email
            /*
            ,
            json_encode($arrRow, true),
            print_r($team, true),
            print_r($team_member, true)
            */
        );
    }
}