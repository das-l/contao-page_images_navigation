<?php

namespace Ruudt\PageImagesNavigationBundle;

/**
 * Class PageImage
 *
 * @copyright  Ruud Walraven 2013
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 */
class PageImage
{
	/**
	 * Data array
	 */
	protected $arrData = array();


	/**
	 * Set an object property
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}
}
