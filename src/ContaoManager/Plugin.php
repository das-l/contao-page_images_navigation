<?php

/**
 * @author  Alex Wuttke <alex@das-l.de>
 * @license LGPL-3.0+
 */

namespace DasL\PageImagesNavigationBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            (new BundleConfig('DasL\PageImagesNavigationBundle\DasLPageImagesNavigationBundle'))
                ->setLoadAfter(['Srhinow\ContaoPageImagesBundle\SrhinowContaoPageImagesBundle'])
        ];
    }
}
