<?php

/**
 * @author		Ruud Walraven <ruud.walraven@gmail.com>
 * @copyright	Ruud Walraven 2013
 */


/**
 * Add a palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]				= 'pageimages_override_size';
$GLOBALS['TL_DCA']['tl_module']['palettes']['navigation_pageimages']		= str_replace('{template_legend', '{pageimages_legend},pageimages,pageimages_href_image,pageimages_override_size;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['navigation']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['customnav_pageimages']			= str_replace('{template_legend', '{pageimages_legend},pageimages,pageimages_href_image,pageimages_override_size;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['customnav']);

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['pageimages_override_size']	= 'pageimages_size';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['pageimages_href_image'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['pageimages_href_image'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['pageimages_override_size'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['pageimages_override_size'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['pageimages_size'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['pageimages_size'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => $GLOBALS['TL_CROP'],
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
