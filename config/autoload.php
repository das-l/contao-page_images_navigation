<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Pageimages_navigation
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'PageImages',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'PageImages\PageImage'                  => 'system/modules/pageimages_navigation/classes/PageImage.php',

	// Modules
	'PageImages\ModulePageImagesCustomnav'  => 'system/modules/pageimages_navigation/modules/ModulePageImagesCustomnav.php',
	'PageImages\ModulePageImagesNavigation' => 'system/modules/pageimages_navigation/modules/ModulePageImagesNavigation.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nav_pageimages' => 'system/modules/pageimages_navigation/templates',
));
