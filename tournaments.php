<?php
/*
Plugin Name: Croleague Tournaments
Plugin URI: http://croleague.com
Description: Omogućuje lak rad s turnirima.
Version: 1.0
License: MIT
License URI: http://opensource.org/licenses/MIT
Author: T.B.
Author URI: http://infy.cybershade.org/
*/

if(!defined('WPINC')) die;

define('TOURNAMENTS_PATH', plugin_dir_path(__FILE__));

require_once TOURNAMENTS_PATH . 'class.tournaments.php';
new Tournaments(); // run
