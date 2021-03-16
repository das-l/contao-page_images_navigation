<?php

/**
 * @author		Ruud Walraven <ruud.walraven@gmail.com>
 * @copyright	Ruud Walraven 2013
 */

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['navigationMenu'], 1, array
(
	'navigation_pageimages'	=> 'Ruudt\\PageImagesNavigationBundle\\FrontendModule\\ModulePageImagesNavigation'
));

array_insert($GLOBALS['FE_MOD']['navigationMenu'], 3, array
(
	'customnav_pageimages'	=> 'Ruudt\\PageImagesNavigationBundle\\FrontendModule\\ModulePageImagesCustomnav'
));
