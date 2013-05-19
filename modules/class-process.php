<?php
class FDX_Process extends Total_Security {

function __construct() {
              if (isset( $_POST['fdx_page']) ) {
			  add_filter('init', array( $this, 'fdx_update_post_settings') );
              }

$this->fdx_gen_table(); //executa  funçoes
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

                    case 'fdx_form_p2':
					$this->fdx_process_p2();
					break;
                    case 'fdx_form_p3':
					$this->fdx_process_p3();
					break;
                    case 'fdx_form_p4':
					$this->fdx_process_p4();
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
 * Process P2
 */
function fdx_process_p2(){
            if ( isset( $_POST['p2_check_1'] ) ) {
				$settings['p2_check_1'] = true;
			} else {
				$settings['p2_check_1'] = false;
			}

            if ( isset( $_POST['p2_select_1'] ) ) {
        	$settings['p2_op1'] = $_POST['p2_select_1'];

       }
    		update_option( 'fdx_settings', $settings );
}


/*
 * Process P3
 */
function fdx_process_p3(){
            if ( isset( $_POST['p3_select_1'] ) ) {
        	$settings['p3_op1'] = $_POST['p3_select_1'];
            }


    		update_option( 'fdx_settings', $settings );
}





/*
 * Process P4
 */
function fdx_process_p4(){
            if ( isset( $_POST['p4_check_1'] ) ) {
				$settings['p4_check_1'] = true;
			} else {
				$settings['p4_check_1'] = false;
			}
    		update_option( 'fdx_settings', $settings );
}







/*
 * Process P4 - Clean Database
 */
function fdx_process_clean(){
global $wpdb;
    $wpdb->query( "DELETE FROM " . $wpdb->base_prefix . "total_security_log" );

//    echo '<div id="message" class="updated"><p>fabrizio</p></div>';
}













}// end class

