<?php
$GLOBALS['TL_DCA']['tl_team'] = [
  'config' => [
      'dataContainer'               => 'Table',
      'enableVersioning'            => true,
      'sql' => [
          'keys' => [
              'id' => 'primary'
          ]
      ]
  ],

  'list' => [
      'sorting' => [
          'mode'                    => 1,
          'fields'                  => ['name'],
          'flag'                    => 1,
          'panelLayout'             => 'sort,filter;search,limit'
      ],
      'label' => [
          'fields'                  => ['name'],
          'format'                  => '%s'
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
              'label'               => &$GLOBALS['TL_LANG']['tl_team']['edit'],
              'href'                => 'act=edit',
              'icon'                => 'edit.gif'
          ],

          'copy' => [
              'label'               => &$GLOBALS['TL_LANG']['tl_team']['copy'],
              'href'                => 'act=copy',
              'icon'                => 'copy.gif',
          ],
          'delete' => [
              'label'               => &$GLOBALS['TL_LANG']['tl_team']['delete'],
              'href'                => 'act=delete',
              'icon'                => 'delete.gif',
              'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
          ],
          'show' => [
              'label'               => &$GLOBALS['TL_LANG']['tl_team']['show'],
              'href'                => 'act=show',
              'icon'                => 'show.gif'
          ]
    ]
  ],

  'palettes' => [
      '__selector__'                => ['protected', 'allowComments'],
      'default'                     => '{name_legend},name,jumpTo;{liga_legend},liga'
  ],


    /*
     * TODO bei fields:
     *  Mannschaftslogo (filepicker)
     *
     */

    'fields' => [
        'id' => [
            'sql'       => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ],
        'name' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_team']['name'],
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => false,
            'sorting'   => true,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'jumpTo' => [
            'label'      => &$GLOBALS['TL_LANG']['tl_team']['jumpTo'],
            'exclude'    => true,
            'inputType'  => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval'       => ['mandatory'=>false, 'fieldType'=>'radio'],
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type'=>'hasOne', 'load'=>'eager']
        ],
        'liga' => [
            'label'       => &$GLOBALS['TL_LANG']['tl_team']['liga'],
            'exclude'     => true,
            'inputType'   => 'radio',
            'options'     => [1=>'DEDSV', 2=>'DSAB', 3=>'OBDV', 4=>'SDM'],
            'eval'        => ['mandatory'=>true],
            'sql'         => "blob NULL"
        ]
    ]
];

