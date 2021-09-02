<?php

use AncientWorks\Artifact\Artifact;
use AncientWorks\Artifact\Utils\Notice;

defined('ABSPATH') || exit; ?>

<div class="jp-masthead">
    <div class="jp-masthead__inside-container">
        <div class="jp-masthead__logo-container">
            <a class="jp-masthead__logo-link" href="<?= add_query_arg(['page' => Artifact::$slug, 'route' => 'dashboard'], admin_url('admin.php')) ?>">
                <img src="<?= plugins_url('/dist/img/artifact-logo-header.svg', ARTIFACT_FILE) ?>" style="max-height: 2rem;">
            </a>
            <code>
                <?= Artifact::$version ?>
            </code>
        </div>
        <div class="jp-masthead__nav">
            <span class="dops-button-group">
                <a href="<?= add_query_arg(['page' => Artifact::$slug, 'route' => 'dashboard'], admin_url('admin.php')) ?>" type="button" class="dops-button is-compact <?= !isset($_REQUEST['route']) || $_REQUEST['route'] === 'dashboard' ? 'is-primary' : '' ?>">Dashboard</a>
                <a href="<?= add_query_arg(['page' => Artifact::$slug, 'route' => 'settings'], admin_url('admin.php')) ?>" type="button" class="dops-button is-compact <?= isset($_REQUEST['route']) && $_REQUEST['route'] === 'settings' ? 'is-primary' : '' ?>">Settings</a>
            </span>
        </div>
    </div>
</div>

<div class="jp-lower" style="padding-bottom: initial;">
    <div id="jp-admin-notices" aria-live="polite"></div>
    <div aria-live="polite">
        <?php foreach (Notice::lists() as $notice) : ?>
            <div class="dops-notice is-<?= $notice['status'] ?>">
                <span class="dops-notice__icon-wrapper">
                    <?php
                    switch ($notice['status']):
                        case 'info': ?>
                            <svg class="gridicon gridicons-info dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g>
                                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
                                </g>
                            </svg>
                        <?php break;
                        case 'warning': ?>
                            <svg class="gridicon gridicons-warning dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g>
                                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2l-.5-6h3l-.5 6z" />
                                </g>
                            </svg>
                        <?php break;
                        case 'success': ?>
                            <svg class="gridicon gridicons-success dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g>
                                    <path d="M11 17.768l-4.884-4.884 1.768-1.768L11 14.232l8.658-8.658C17.823 3.39 15.075 2 12 2 6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10c0-1.528-.353-2.97-.966-4.266L11 17.768z" />
                                </g>
                            </svg>
                        <?php break;
                        case 'error': ?>
                            <svg class="gridicon gridicons-error dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g>
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
                                </g>
                            </svg>
                        <?php break;
                        default:
                            break; ?>
                    <?php
                    endswitch; ?>
                </span>
                <span class="dops-notice__content">
                    <span class="dops-notice__text">
                        <?= $notice['message'] ?>
                    </span>
                </span>
                <?php if (!empty($notice['learn_more'])) : ?>
                    <a class="dops-notice__action" href="<?= $notice['learn_more'] ?>" target="_blank">
                        <span>Learn More</span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <div id="overlay-notices" class="global-notices"></div>

    </div>
</div>