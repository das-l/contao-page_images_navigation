<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * @package     PageImagesNavigation
 * @author      Ruud Walraven <ruud.walraven@gmail.com>
 * @copyright   Ruud Walraven 2011 - 2013
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['navigationMenu'], 1, array
(
	'navigation_pageimages'	=> 'ModulePageImagesNavigation'
));

array_insert($GLOBALS['FE_MOD']['navigationMenu'], 3, array
(
	'customnav_pageimages'	=> 'ModulePageImagesCustomnav'
));