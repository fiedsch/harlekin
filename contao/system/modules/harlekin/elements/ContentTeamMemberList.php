<?php

/**
 * Team Member List Content Element for Contao CMS (Copyright (c) 2005-2015 Leo Feyer)
 *
 * Copyright (c) 2016 Andreas Fieger
 *
 * @license LGPL-3.0+
 */


/**
 * Content element "teammemberlist".
 *
 * @author Andreas Fieger <https://github.com/fiedsch>
 */
class ContentTeamMemberList extends \ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_teammemberlist';


    /**
     * Return if the data file does not exist
     * @return string
     */
    public function generate()
    {
        return parent::generate();
    }


    /**
     * Generate the content element
     */
    protected function compile()
    {

        try {

            $memberids = deserialize($this->team, true);

            $team = [];

            foreach ($memberids as $memberid) {
                $team[] = \MemberModel::findById($memberid);
            }

            $this->Template->team = $team;

        } catch (\Exception $e) {

            // Eintrag in Contao Log ?

        }

    }
}
