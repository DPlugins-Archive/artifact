<?php

use AncientWorks\Artifact\Artifact;

defined('ABSPATH') || exit; ?>

<div class="jp-footer" id="jp-footer">
    <div class="jp-footer__a8c-attr-container">
        <a href="">
            <span style="color: gray;">An ancient object for modern WordPress</span>
        </a>
    </div>
    <ul class="jp-footer__links">
        <li class="jp-footer__link-item">
            <a href="https://ancient.works/redirect/artifact" target="_blank" class="jp-footer__link" title="Artifact version">Artifact version <?= Artifact::$version ?></a>
        </li>
        <li class="jp-footer__link-item">
            <a href="<?= add_query_arg(['page' => Artifact::$slug, 'route' => 'about'], admin_url('admin.php')) ?>" class="jp-footer__link" title="About Artifact">About</a>
        </li>
        <li class="jp-footer__link-item">
            <a href="<?= add_query_arg(['page' => Artifact::$slug, 'route' => 'modules'], admin_url('admin.php')) ?>" title="Access the full list of Artifact's modules available on your site." class="jp-footer__link">Modules</a>
        </li>
    </ul>
</div>