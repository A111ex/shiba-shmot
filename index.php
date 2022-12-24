<?php
// Version
define('VERSION', '2.3.0.2.3');

header('Cache-Control: no-cache, max-age=0, must-revalidate, no-store');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');