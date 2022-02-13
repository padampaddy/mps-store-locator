<?php

/*
Plugin Name: MPS Store Locator
Description: MPS Store Locator Plugin 
Author: MPS
Version: 1.0.0
*/

define("MPS_STORE_LOCATOR_PLUGIN__DIR__", __DIR__);
define("MPS_STORE_LOCATOR_PLUGIN__URL__", plugin_dir_url(__FILE__));
include 'store/store.php';
include 'settings.php';
include 'shortcode/shortcode.php';
