<?php

use AncientWorks\Artifact\Artifact;
use AncientWorks\Artifact\ModuleProvider;

defined('ABSPATH') || exit; ?>

<?php $this->layout('layouts/admin') ?>

<style>
  #jp-plugin-container .wrap {
    margin: 0 auto;
    max-width: 45rem;
    padding: 0 1.5rem;
  }

  #jp-plugin-container.is-wide .wrap {
    max-width: 1040px;
  }

  #jp-plugin-container .wrap .jetpack-wrap-container {
    margin-top: 1em;
  }
</style>

<div class="wrap">
  <div id="jp-admin-notices" aria-live="polite"></div>
</div>
<div class="page-content configure">
  <div class="frame top hide-if-no-js">
    <div class="wrap">
      <div class="manage-left">
        <table class="table table-bordered fixed-top">
          <thead>
            <tr>
              <th class="check-column">
                <input disabled type="checkbox" class="checkall">
              </th>
              <th colspan="2">
                <input type="hidden" id="_wpnonce" name="_wpnonce" value="">
                <input type="hidden" name="_wp_http_referer" value="">
                <div class="tablenav top">
                  <div class="alignleft actions bulkactions">
                    <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                    <select disabled name="action" id="bulk-action-selector-top">
                      <option value="-1">Bulk actions</option>
                      <option value="bulk-activate">Activate</option>
                      <option value="bulk-deactivate">Deactivate</option>
                    </select>
                    <input disabled type="submit" id="doaction" class="button action" value="Apply">
                  </div>
                  <br class="clear">
                </div>
                <span class="filter-search">
                  <button disabled type="button" class="button">Filter</button>
                </span>
              </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <!-- /.wrap -->
  </div>
  <!-- /.frame -->


  <div class="frame bottom">
    <div class="wrap">
      <div class="manage-right">
        <div class="bumper">
          <form class="navbar-form" role="search">
            <input type="hidden" name="page" value="jetpack_modules">
            <p class="search-box">
              <label class="screen-reader-text" for="srch-term-search-input">Search:</label>
              <input disabled type="search" id="srch-term-search-input" name="s" value="" placeholder="Search Modules…">
              <input disabled type="submit" id="search-submit" class="button" value="Search">
            </p>
            <p>View:</p>
            <div class="button-group filter-active">
              <button disabled type="button" class="button active">All</button>
              <button disabled type="button" class="button " data-filter-by="activated" data-filter-value="true">Active</button>
              <button disabled type="button" class="button " data-filter-by="activated" data-filter-value="false">Inactive</button>
            </div>
            <p>Sort by:</p>
            <div class="button-group sort">
              <button disabled type="button" class="button active" data-sort-by="name">Alphabetical</button>
              <button disabled type="button" class="button " data-sort-by="introduced" data-sort-order="reverse">Newest</button>
              <button disabled type="button" class="button " data-sort-by="sort">Popular</button>
            </div>
            <p>Show:</p>
            <ul class="subsubsub">
              <li class="all">
                <a class="current all" data-title="All">All <span class="count"> (<?= count(ModuleProvider::$available) ?>) </span>
                </a>
              </li>
            </ul>
          </form>
        </div>
      </div>
      <div class="manage-left">
        <form class="jetpack-modules-list-table-form" onsubmit="return false;">
          <table class="table table-bordered wp-list-table widefat fixed jetpack-modules with-transparency">
            <tbody id="the-list">
              <?php if (Artifact::$updater->isActivated() === false) : ?>
                <tr class="jetpack-module  alternate unavailable">
                  <th scope="row" class="check-column">
                    <input type="checkbox">
                  </th>
                  <td class="name column-name">
                    <span class="info"><a href="https://ancient.works/redirect/artifact" target="blank">Get more modules ... <code> Pro version </code></a> </span>
                    <div class="row-actions">
                      <span class="unavailable_reason">module not installed</span>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
              <?php foreach (ModuleProvider::$available as $module) : ?>
                <tr class="jetpack-module <?= $module['enabled'] ? 'active' : '' ?>" id="<?= $module['id'] ?>">
                  <th scope="row" class="check-column">
                    <input disabled type="checkbox" name="modules[]" value="<?= $module['id'] ?>">
                  </th>
                  <td class="name column-name">
                    <span class="info">
                      <a href="<?= add_query_arg(['page' => Artifact::$slug, 'route' => 'dashboard#' . $module['id']], admin_url('admin.php')) ?>" target="blank"><?= $module['name'] ?: $module['id'] ?> </a> <code> <?= $module['version'] ?> </code>
                    </span>
                    <div class="row-actions">
                      <span class="<?= $module['enabled'] ? 'delete' : 'activate' ?>">
                        <a href="<?= add_query_arg([
                                    'page' => Artifact::$slug,
                                    'route' => 'modules',
                                    'module_id' => $module['id'],
                                    'action' => $module['enabled'] ? 'deactivate' : 'activate',
                                    '_wpnonce' => wp_create_nonce('artifact')
                                  ], admin_url('admin.php')) ?>"><?= $module['enabled'] ? 'Deactivate' : 'Activate' ?></a>
                      </span>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </form>
      </div>
    </div>
    <!-- /.wrap -->
  </div>
  <!-- /.frame -->
</div>
<div id="jp-stats-report-bottom">
  <div class="wrap"></div>
</div>