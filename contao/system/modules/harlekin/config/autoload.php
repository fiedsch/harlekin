<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Elements
	'ContentTeamMemberList' => 'system/modules/harlekin/elements/ContentTeamMemberList.php',

    // Models
    'TeamMemberModel'       => 'system/modules/harlekin/models/TeamMemberModel.php',
    'TeamModel'             => 'system/modules/harlekin/models/TeamModel.php',

));

TemplateLoader::addFiles(array
(
    'ce_teammemberlist' => 'system/modules/harlekin/templates',
));
