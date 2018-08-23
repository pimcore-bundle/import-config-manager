<?php

namespace ImportConfigManagerBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class ImportConfigManagerBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/importconfigmanager/js/pimcore/startup.js'
        ];
    }
}
