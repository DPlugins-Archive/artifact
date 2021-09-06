<?php

use AncientWorks\Artifact\Artifact;

defined('ABSPATH') || exit;

$_license_key = get_option(Artifact::$slug . "_license_key");
$_beta        = get_option(Artifact::$slug . "_beta");

?>

<?php $this->layout('layouts/admin') ?>

<div class="jp-lower">
  <div id="jp-admin-notices" aria-live="polite"></div>
  <div aria-live="polite">
    <div></div>
    <div></div>
  </div>
  <div aria-live="assertive" class="">
    <div id="jp-navigation" class="dops-navigation">
      <div class="dops-section-nav has-pinned-items">
        <div class="dops-section-nav__mobile-header" role="button" tabindex="0">
          <span class="dops-section-nav__mobile-header-text"> General </span>
        </div>
        <div class="dops-section-nav__panel">
          <div class="dops-section-nav-group">
            <div class="dops-section-nav-tabs">
              <ul class="dops-section-nav-tabs__list" role="menu">
                <li class="is-selected dops-section-nav-tab">
                  <a href="" class="dops-section-nav-tab__link" tabindex="0" role="menuitem">
                    <span class="dops-section-nav-tab__text"> General </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="jp-settings-container">
      <div class="jp-no-results">Enter a search term to find settings or close search.</div>
      <div>
        <div title="Configure your Artifact installation." class="dops-card jp-settings-description">
          <h2 class="dops-card-title">Configure your Artifact installation.</h2>
        </div>

        <form method="POST" id="artifact-settings-license" class="jp-form-settings-card" action="<?= add_query_arg([
                                                                                                    'page' => Artifact::$slug,
                                                                                                    'route' => 'settings',
                                                                                                    'action' => 'save_license'
                                                                                                  ], admin_url('admin.php')) ?>">
          <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce(Artifact::$slug) ?>">
          <div class="dops-card dops-section-header is-compact">
            <div class="dops-section-header__label">
              <span class="dops-section-header__label-text">License</span>
            </div>
            <div class="dops-section-header__actions">
              <button type="submit" class="dops-button is-compact is-primary">Save settings</button>
            </div>
          </div>

          <div class="jp-form-settings-group">
            <div class="dops-card jp-form-has-child">
              <table class="form-table" role="presentation">
                <tbody>
                  <tr>
                    <th scope="row"><label>License Key</label></th>
                    <td>
                      <input name="license_key" type="password" value="<?= $_license_key ?>">
                    </td>
                  </tr>
                  <tr>
                    <th scope="row"><label>Enable Pre-release version</label></th>
                    <td>
                      <input name="beta" type="checkbox" value="1" <?= $_beta ? 'checked' : '' ?>>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="jp-support-info">
                <a class="dops-info-popover dops-info-popover-button">
                  <svg class="gridicon gridicons-info-outline needs-offset" height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g>
                      <path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path>
                    </g>
                  </svg>
                  <span class="screen-reader-text">Learn more</span>
                </a>
              </div>

            </div>
          </div>

          <div class="dops-card jp-connect-user-bar__card is-compact">
            <div class="jp-connect-user-bar__text">Get automatic plugin update and Premium Artifact's module.</div>
            <div class="jp-connect-user-bar__button">
              <div>
                <a href="https://ancient.works/redirect/artifact" type="button" class="is-primary jp-jetpack-connect__button dops-button" target="_blank">Get your Artifact license</a>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>