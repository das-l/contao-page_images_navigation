<?php

namespace Ruudt\PageImagesNavigationBundle\FrontendModule;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\ModuleSitemap;
use Contao\PageModel;
use Contao\StringUtil;
use Ruudt\PageImagesNavigationBundle\PageImage;
use Srhinow\ContaoPageImagesBundle\PageImages\PageImages;

/**
 * Class ModulePageImagesNavigation
 *
 * @copyright  Leo Feyer 2005-2013
 * @copyright  Ruud Walraven 2013
 * @author     Leo Feyer <http://www.contao.org>
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @author     Alex Wuttke <alex@das-l.de>
 */
class ModulePageImagesNavigation extends PageImages
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_navigation';


	/**
	 * Do not display the module if there are no menu items
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['navigation_pageimages'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$strBuffer = parent::generate();
		return ($this->Template->items != '') ? $strBuffer : '';
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		// Set the trail and level
		if ($this->defineRoot && $this->rootPage > 0)
		{
			$trail = array($this->rootPage);
			$level = 0;
		}
		else
		{
			$trail = $objPage->trail;
			$level = ($this->levelOffset > 0) ? $this->levelOffset : 0;
		}

		$lang = null;
		$host = null;

		// Overwrite the domain and language if the reference page belongs to a differnt root page (see #3765)
		if ($this->defineRoot && $this->rootPage > 0)
		{
			$objRootPage = PageModel::findWithDetails($this->rootPage);

			// Set the language
			if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && $objRootPage->rootLanguage != $objPage->rootLanguage)
			{
				$lang = $objRootPage->rootLanguage;
			}

			// Set the domain
			if ($objRootPage->rootId != $objPage->rootId && $objRootPage->domain != '' && $objRootPage->domain != $objPage->domain)
			{
				$host = $objRootPage->domain;
			}
		}

		$this->Template->request = ampersand(Environment::get('indexFreeRequest'));
		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->items = $this->renderNavigation($trail[$level], 1, $host, $lang);
	}


	/**
	 * Recursively compile the navigation menu and return it as HTML string
	 * @param integer
	 * @param integer
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function renderNavigation($pid, $level=1, $host=null, $language=null)
	{
		// Get all active subpages
		$objSubpages = PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden, $this instanceof ModuleSitemap);

		if ($objSubpages === null)
		{
			return '';
		}

		$items = array();
		$groups = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Layout template fallback
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->cssID = $this->cssID; // see #4897
		$objTemplate->level = 'level_' . $level++;

		// Get page object
		global $objPage;

		// Browse subpages
		while($objSubpages->next())
		{
			// Skip hidden sitemap pages
			if ($this instanceof ModuleSitemap && $objSubpages->sitemap == 'map_never')
			{
				continue;
			}

			$subitems = '';
			$_groups = deserialize($objSubpages->groups);

			// Override the domain (see #3765)
			if ($host !== null)
			{
				$objSubpages->domain = $host;
			}

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected || ($this instanceof ModuleSitemap && $objSubpages->sitemap == 'map_always'))
			{
				// Check whether there will be subpages
				if ($objSubpages->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->Database->getChildRecords($objSubpages->id, 'tl_page'))))))
				{
					$subitems = $this->renderNavigation($objSubpages->id, $level, $host, $language);
				}

				// Get href
				switch ($objSubpages->type)
				{
					case 'redirect':
						$href = $objSubpages->url;

						if (strncasecmp($href, 'mailto:', 7) === 0)
						{
							$href = StringUtil::encodeEmail($href);
						}
						break;

					case 'forward':
						if ($objSubpages->jumpTo)
						{
							$objNext = $objSubpages->getRelated('jumpTo');
						}
						else
						{
							$objNext = PageModel::findFirstPublishedRegularByPid($objSubpages->id);
						}

						if ($objNext !== null)
						{
							$strForceLang = null;
							$objNext->loadDetails();

							// Check the target page language (see #4706)
							if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
							{
								$strForceLang = $objNext->language;
						}

							$href = $this->generateFrontendUrl($objNext->row(), null, $strForceLang);

							// Add the domain if it differs from the current one (see #3765)
							if ($objNext->domain != '' && $objNext->domain != Environment::get('host'))
						{
								$href = (Environment::get('ssl') ? 'https://' : 'http://') . $objNext->domain . TL_PATH . '/' . $href;
							}
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($objSubpages->row(), null, $language);

						// Add the domain if it differs from the current one (see #3765)
						if ($objSubpages->domain != '' && $objSubpages->domain != Environment::get('host'))
						{
							$href = (Environment::get('ssl') ? 'https://' : 'http://') . $objSubpages->domain . TL_PATH . '/' . $href;
						}
						break;
				}
				
				// Active page
				if (($objPage->id == $objSubpages->id || $objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) && !$this instanceof ModuleSitemap && !Input::get('articles'))
				{
					// Mark active forward pages (see #4822)
					$strClass = (($objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) ? 'forward' . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : '') : 'active') . (($subitems != '') ? ' submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '');
					$row = $objSubpages->row();

					// Add the pageimage
					$pageImage = $this->getPageImages($objSubpages->id, '1');
					if (count($pageImage))
					{
						if ($this->pageimages_override_size && $this->pageimages_size)
						{
							$pageImage['size'] = $this->pageimages_size;
						}

						$objPageImage = new PageImage;
						$this->addImageToTemplate($objPageImage, $pageImage);
						$row['pageImage'] = $objPageImage;
					}
					else
					{
						$row['pageImage'] = false;
					}

					$row['isActive'] = true;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);

					// Override the link target
					if ($objSubpages->type == 'redirect' && $objSubpages->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = (($subitems != '') ? 'submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '');

					// Mark pages on the same level (see #2419)
					if ($objSubpages->pid == $objPage->pid)
					{
						$strClass .= ' sibling';
					}

					$row = $objSubpages->row();

					// Add the pageimage
					$pageImage = $this->getPageImages($objSubpages->id, '1');
					if (count($pageImage))
					{
						if ($this->pageimages_override_size && $this->pageimages_size)
						{
							$pageImage['size'] = $this->pageimages_size;
						}

						$objPageImage = new PageImage;
						$this->addImageToTemplate($objPageImage, $pageImage);
						$row['pageImage'] = $objPageImage;
					}
					else
					{
						$row['pageImage'] = false;
					}

					$row['isActive'] = false;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);

					// Override the link target
					if ($objSubpages->type == 'redirect' && $objSubpages->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		if (!empty($items))
		{
			$last = count($items) - 1;

			$items[0]['class'] = trim($items[0]['class'] . ' first');
			$items[$last]['class'] = trim($items[$last]['class'] . ' last');
		}

		$objTemplate->href_image = $this->pageimages_href_image;
		$objTemplate->items = $items;
		return !empty($items) ? $objTemplate->parse() : '';
	}
}
