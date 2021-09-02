<?php

use AncientWorks\Artifact\Admin\DashboardController;

defined('ABSPATH') || exit; ?>

<?php $this->layout('layouts/admin') ?>


<style>
  .glance__grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
  }

  .glance__grid>.jp-at-a-glance {
    height: 100%;
    margin-bottom: initial;
  }
</style>

<div class="jp-lower" style="padding-top: initial;">
  <div aria-live="assertive" class="">
    <div id="jp-navigation" class="dops-navigation">
      <div class="dops-section-nav">
        <div class="dops-section-nav__mobile-header" role="button" tabindex="0">
          <span class="dops-section-nav__mobile-header-text">General</span>
        </div>
        <div class="dops-section-nav__panel">
          <div class="dops-section-nav-group">
            <div class="dops-section-nav-tabs">
              <ul class="dops-section-nav-tabs__list" role="menu">
                <li class="is-selected dops-section-nav-tab">
                  <a class="dops-section-nav-tab__link" tabindex="0" role="menuitem">
                    <span class="dops-section-nav-tab__text">General</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="jp-at-a-glance">
      <div class="jp-dash-section-header">
        <div class="jp-dash-section-header__label">
          <h2 class="jp-dash-section-header__name">Workflow</h2>
        </div>
      </div>

      <div class="jp-at-a-glance__item-grid glance__grid">
        <?php foreach (DashboardController::$panel as $key => $panel) : ?>
          <?php if ($panel['size'] !== 'grid') continue; ?>
          <?php $this->insert($panel['panel_template']) ?>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="jp-at-a-glance">
      <?php foreach (DashboardController::$panel as $key => $panel) : ?>
        <?php if ($panel['size'] !== 'full') continue; ?>
        <?php $this->insert($panel['panel_template']) ?>
      <?php endforeach; ?>
    </div>
  </div>
</div>