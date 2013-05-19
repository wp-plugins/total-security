<?php
/**
 * Code used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

//get_option('XXX_YYY_options');

global $wpdb;
//drop database tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "total_security_log" );

//Settings
delete_option('fdx_settings');