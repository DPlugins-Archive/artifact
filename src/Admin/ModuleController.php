<?php

namespace AncientWorks\Artifact\Admin;

use AncientWorks\Artifact\Utils\Template;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class ModuleController
{
    public function __invoke()
    {
        echo Template::template()->render('pages/admin/modules');
    }
}