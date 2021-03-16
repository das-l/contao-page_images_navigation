<?php

/**
 * @author  Alex Wuttke <alex@das-l.de>
 * @license LGPL-3.0+
 */

namespace Ruudt\PageImagesNavigationBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            (new BundleConfig('Ruudt\PageImagesNavigationBundle\RuudtPageImagesNavigationBundle'))
                ->setLoadAfter(['Srhinow\ContaoPageImagesBundle\SrhinowContaoPageImagesBundle'])
        ];
    }
}
