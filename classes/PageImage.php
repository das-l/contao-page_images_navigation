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
 * Run in a custom namespace, so the class can be replaced
 */
namespace PageImages;


/**
 * Class PageImage
 *
 * @copyright  Ruud Walraven 2013
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    PageImagesNavigation
 */
class PageImage
{
	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}
}