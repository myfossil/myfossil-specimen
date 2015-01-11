<?php
namespace myFOSSIL\Plugin\Specimen;

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://atmoapps.com
 * @since      0.0.1
 *
 * @package    myFOSSIL
 * @subpackage myFOSSIL/public/partials
 */

require_once( 'single.php' );
require_once( 'single/member.php' );
require_once( 'single/header.php' );
require_once( 'single/settings.php' );

/* main view */
require_once( 'single/main.php' );

/* history view */
require_once( 'single/history.php' );

/* discussion view */
require_once( 'single/discussion.php' );

/* list view */
require_once( 'list/create-button.php' );
require_once( 'list/table.php' );
