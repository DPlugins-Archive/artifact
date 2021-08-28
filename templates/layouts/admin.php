<?php

defined('ABSPATH') || exit; ?>

<div id="jp-plugin-container">
    <?php $this->insert('partials/_header') ?>

    <?= $this->section('content') ?>

    <?php $this->insert('partials/_footer') ?>
</div>