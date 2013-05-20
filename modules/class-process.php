<?php
class FDX_Process extends Total_Security {

function __construct() {
              if (isset( $_POST['fdx_page']) ) {
			  add_filter('init', array( $this, 'fdx_update_post_settings') );
              }

$this->fdx_gen_table(); //exe
}


/*
 * Set up log table
 */
function fdx_gen_table() {
global $wpdb;
//
$tables = "CREATE TABLE " . $wpdb->base_prefix . "total_security_log (
id int(11) NOT NULL AUTO_INCREMENT ,
timestamp int(10) NOT NULL ,
host varchar(20) ,
url varchar(255) ,
referrer varchar(255) ,
data MEDIUMTEXT ,
PRIMARY KEY  (id)
);";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $tables );
}

/*
 * Get settings defaults
 */
function fdx_get_settings() {
	$settings = $this->fdx_defaults;
	$wordpress_settings = get_option( 'fdx_settings' );
	if ( $wordpress_settings ) {
		foreach( $wordpress_settings as $key => $value ) {
			$settings[ $key ] = $value;
		}
	}
	return $settings;
}

/*
 * Executes appropriate process function based on post variable
 */
function fdx_update_post_settings() {
		   switch ( $_POST['fdx_page'] ) {
                    case 'fdx_form_all':
					$this->fdx_process_all();
					break;
                    case 'fdx_reset':
				    update_option( 'fdx_settings', false );
					break;
                    case 'fdx_clean':
				    $this->fdx_process_clean();
					break;
    }
}

/*
 * Process All
 */
function fdx_process_all(){
            if ( isset( $_POST['p2_check_1'] ) ) {
				$settings['p2_check_1'] = true;
			} else {
				$settings['p2_check_1'] = false;
			}

            if ( isset( $_POST['p2_select_1'] ) ) {
        	$settings['p2_op1'] = $_POST['p2_select_1'];
            }

            if ( isset( $_POST['p3_select_1'] ) ) {
        	$settings['p3_op1'] = $_POST['p3_select_1'];
            }

            if ( isset( $_POST['p4_check_1'] ) ) {
				$settings['p4_check_1'] = true;
			} else {
				$settings['p4_check_1'] = false;
			}

             if ( isset( $_POST['p6_check_1'] ) ) {
				$settings['p6_check_1'] = true;
			} else {
				$settings['p6_check_1'] = false;
			}

            if ( isset($_POST['p6_key']) ) {
				$p6_key = $_POST['p6_key'];
			} else {
				$p6_key = '';
			}

            if ( isset($_POST['p6_url']) ) {
				$p6_url = $_POST['p6_url'];
			} else {
				$p6_url = '';
			}

            $settings['p6_key'] = stripslashes( $p6_key );
            $settings['p6_url'] = stripslashes( $p6_url );

    		update_option( 'fdx_settings', $settings );
}


/*
 * P4 - Clean Database
 */
function fdx_process_clean(){
global $wpdb;
    $wpdb->query( "DELETE FROM " . $wpdb->base_prefix . "total_security_log" );
}


}// end class

