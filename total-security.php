<?php
/*
 * Plugin Name: Total Security
 * Plugin URI: http://fabrix.net/total-security/
 * Description: Checks your WordPress installation and provides detailed reporting on discovered vulnerabilities, anything suspicious and how to fix them.
 * Version: 2.7
 * Author: Fabrix DoRoMo
 * Author URI: http://fabrix.net
 * License: GPL2+
 * Text Domain: total-security
 * Domain Path: /lang
 * Copyright 2013 fabrix.net (email: fabrix@fabrix.net)
 */

class Total_Security {
        public $min_wp_ver 	        = '3.5.1'; // hashes: 3.5.1
  		public $pluginversion 	    = '2.7';
        public $pluginname			= 'Total Security';
        public $hook 				= 'total-security';
        public $_p2 	            = 'vulnerability_scan';
        public $_p3 	            = 'unsafe_files_search';
        public $_p4 	            = '404_logs';
        public $_p5 	            = 'core_exploit_scanner'; // $this->hook . '-'.$this->_p5
        public $accesslvl			= 'manage_options';
        //p2
        public $p2_options_key   	= 'cookie269765'; // $this->p2_options_key
        //p5
        public $p5_options_key   	= 'cookie359759';
        public $p5_salt         	= 'hash45757';
        public $p5_snippet         	= 'ide-msvcpp'; // http://steamdev.com/snippet/
        public $fdx_defaults        = array(
                 'p2_op1'             => '200', //OK
                 'p2_check_1'         => 1, //OK
                 'p3_op1'             => '500',
                 'p4_check_1'         => 1,
       );

	function __construct() {
        load_plugin_textdomain( $this->hook, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); // Load plugin text domain
    	add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );  // Register admin styles
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );// Register admin scripts
        add_action( 'admin_menu', array( $this, 'action_menu_pages' ) ); // Registers all WordPress admin menu items

      //-------------P2
        register_deactivation_hook( __FILE__, array( $this, 'fdx_deactivate' ) );
        add_action('wp_ajax_sn_run_tests', array($this, 'run_tests'));

      //-------------P3
        add_action( 'wp_ajax_fdx-scanner_file_scan', array( $this, 'fdx_ajax_file_scan' ));
        add_action( 'wp_ajax_fdx-run_end', array( $this, 'fdx_run_end' ));

      //-------------P5
        add_action('wp_ajax_sn_core_get_file_source', array($this, 'get_file_source'));
        add_action('wp_ajax_sn_core_restore_file', array($this, 'restore_file_dialog'));
        add_action('wp_ajax_sn_core_restore_file_do', array($this, 'restore_file'));
        add_action('wp_ajax_sn_core_run_scan', array($this, 'scan_files'));

      //--------------ALL
        require_once( 'modules/class-process.php' );
        new FDX_Process();

     //-------------P2
        require_once( 'modules/class-p2.php' );
        new FDX_CLASS_P2();

     //-------------P3
        require_once( 'modules/class-p3.php' );
        new FDX_CLASS_P3();

     //-------------P4
        global $fdx_lg;
        require_once( 'modules/class-p4.php' );
        $fdx_lg = new FDX_CLASS_P4();

}//end construct

/*
 * Registers and enqueues admin-specific styles.
 */
public function register_admin_styles() {
             if ( isset( $_GET['page'] ) && strpos( $_GET['page'], $this->hook ) !== false ) {
             wp_enqueue_style('fdx-core', plugins_url( 'css/admin.css',__FILE__ ), array(), $this->pluginversion );
             //-------------P5
             wp_enqueue_style('wp-jquery-ui-dialog');
             wp_enqueue_style('fdx-snippet', plugins_url( 'css/snippet.min.css',__FILE__ ), array(), $this->pluginversion );
//---------------------------------------------
    }
}

/*
 * Registers and enqueues admin-specific JavaScript.
 */
public function register_admin_scripts() {
              if ( isset( $_GET['page'] ) && strpos( $_GET['page'], $this->hook ) !== false ) {
              wp_enqueue_script('dashboard');
              wp_enqueue_script('postbox');
         //   wp_enqueue_script( 'media-upload' );
              wp_enqueue_script( 'admin-core', plugins_url( 'js/admin.js',__FILE__), array(), $this->pluginversion);

           //-------------P2-p5
              wp_enqueue_script( 'fdx-cookie', plugins_url( 'js/jquery.cookie.js',__FILE__), array(), $this->pluginversion);
              wp_enqueue_script( 'fdx-blockUI', plugins_url( 'js/jquery.blockUI.js',__FILE__), array(), $this->pluginversion);
          //-------------p5
              wp_enqueue_script('jquery-ui-dialog');
              wp_enqueue_script( 'fdx-snippet', plugins_url( 'js/snippet.min.js',__FILE__), array(), $this->pluginversion);

//---------------------------------------------
    }
}

/*
 * Registers all WordPress admin menu items.
 */
function action_menu_pages() {
 			add_menu_page(
				__( $this->pluginname, $this->hook ) . ' - ' . __( 'Basic Settings and Connect', $this->hook ),
				__( $this->pluginname, $this->hook ),
				$this->accesslvl,
				$this->hook,
				array( $this, 'fdx_options_subpanel_p1' ),
				plugins_url( 'images/_16x16.png', __FILE__)
			);

               add_submenu_page(
					$this->hook,
					__( $this->pluginname, $this->hook ) . ' - ' . __( 'Error 404 Log', $this->hook ),
					__( 'Error 404 Log', $this->hook ),
					$this->accesslvl,
                      $this->hook . '-'.$this->_p4,
					array( $this, 'fdx_options_subpanel_p4' )
				);


				add_submenu_page(
					$this->hook,
					__( $this->pluginname, $this->hook ) . ' - ' . __( 'Vulnerability Scan', $this->hook ),
					__( 'Vulnerability Scan', $this->hook ),
					$this->accesslvl,
                      $this->hook . '-'.$this->_p2,
					array( $this, 'fdx_options_subpanel_p2' )
				);

                add_submenu_page(
					$this->hook,
					__( $this->pluginname, $this->hook ) . ' - ' . __( 'Unsafe Files Search', $this->hook ),
					__( 'Unsafe Files Search', $this->hook ),
					$this->accesslvl,
                      $this->hook . '-'.$this->_p3,
					array( $this, 'fdx_options_subpanel_p3' )
				);

                add_submenu_page(
					$this->hook,
					__( $this->pluginname, $this->hook ) . ' - ' . __( 'Core Exploit Scanner', $this->hook ),
					__( 'Core Exploit Scanner', $this->hook ),
					$this->accesslvl,
                    $this->hook . '-'.$this->_p5,
					array( $this, 'fdx_options_subpanel_p5' )
				);

				//Make the dashboard the first submenu item and the item to appear when clicking the parent.
				global $submenu;
				if ( isset( $submenu[$this->hook] ) ) {
					$submenu[$this->hook][0][0] = __( 'Dashboard', $this->hook );
				}

}

/*********************************** P1 *****************************************
************* CONNECT TO TWITTER
********************************************************************************/

function fdx_options_subpanel_p1() {
require_once ('modules/inc-p1.php');
}

/*
 * abc
 */



/*********************************** P2 *****************************************
************* Vulnerability Scan
********************************************************************************/

function fdx_options_subpanel_p2() {
require_once ('modules/inc-p2.php');
}

/*
 * run all tests; via AJAX
 */
  function run_tests() {
    $settings = FDX_Process::fdx_get_settings();
    @set_time_limit($settings['p2_op1']);  //seconds
    $test_count = 0;
    $test_description = array('last_run' => current_time('timestamp'));
    foreach(FDX_CLASS_P2::$security_tests as $test_name => $test){
      if ($test_name[0] == '_') {
        continue;
      }
      $response = FDX_CLASS_P2::$test_name();
      $test_description['test'][$test_name]['status'] = $response['status'];
      if (!isset($response['msg'])) {
        $response['msg'] = '';
      }
      if ($response['status'] == 10) {
        $test_description['test'][$test_name]['msg'] = sprintf($test['msg_ok'], $response['msg']);
      } elseif ($response['status'] == 0) {
        $test_description['test'][$test_name]['msg'] = sprintf($test['msg_bad'], $response['msg']);
      } else {
        $test_description['test'][$test_name]['msg'] = sprintf($test['msg_warning'], $response['msg']);
      }
      $test_count++;
    } // foreach
    update_option($this->p2_options_key, $test_description);
    die('1');
  }

/*
 * convert status integer to button
 */
  function status($int) {
    if ($int == 0) {
      $string = '<img src="'.plugins_url( 'images/critical.png',__FILE__).'" width="32" height="32" border="0" alt="*" />';
    } elseif ($int == 10) {
      $string = '<img src="'.plugins_url( 'images/clean.png',__FILE__).'" height="32" border="0" alt="*" />';
    } else {
      $string = '<img src="'.plugins_url( 'images/wan.png',__FILE__).'" width="32" height="32" border="0" alt="*" />';
    }
    return $string;
  }

/*********************************** P3 *****************************************
************* Unsafe Files Search
********************************************************************************/

function fdx_options_subpanel_p3() {
require_once ('modules/inc-p3.php');
}

/**
 * AJAX callback to initiate a file scan.
 */
function fdx_ajax_file_scan() {
    $settings = FDX_Process::fdx_get_settings();
	check_ajax_referer( 'fdx-scanner_scan' );

	if ( ! isset($_POST['start']) )
		die( json_encode( array( 'status' => 'error', 'data' => __('Error: start not set.', $this->hook) ) ) );
	else
		$start = (int) $_POST['start'];

    $max = $settings['p3_op1'];

	$args = compact( 'start', 'max' );

	$scanner = new File_FDX_Scanner( ABSPATH, $args );
	$result = $scanner->run();
    if ( is_wp_error($result) ) {
		$message = $result->get_error_message();
		$data = $result->get_error_data();
		echo json_encode( array( 'status' => 'error', 'message' => $message, 'data' => $data ) );
	} else if ( $result ) {
		echo json_encode( array( 'status' => 'complete' ) );
	} else {
		echo json_encode( array( 'status' => 'running', 'data' => __('Scanner filesystem', $this->hook). ': ' . ($start+$max) . '...' ) );
	}

	exit;
}

/**
 * AJAX run_end
 */
function fdx_run_end() {
	check_ajax_referer( 'fdx-scanner_scan' );
	$scanner = new RunEnd();
	$scanner->RunEnd();
	exit;
}

/**
 * Display table of results.
 */
public function fdx_show_results( $results ) {
	$result = '<div class="last_scan"><code>'.__('Last run on', $this->hook).': 123</code></div>';

   //  severe=03 warning=02 note=01
	foreach ( array('03','02','01', '00') as $l ) {
		if ( ! empty($results[$l]) ) {
            $result .= '<table class="widefat"><thead><tr>';
            if ( $l == '00' ) $result .= '<th><img src="'. plugins_url( 'images/wan.png',__FILE__) . '" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Log, Binary, Data and Temporary files', $this->hook).'. (<strong style="color:#EFA800;">'. count($results[$l]) .' '. __('matches', $this->hook).'</strong>)</th>';
            if ( $l == '01' ) $result .= '<th><img src="'. plugins_url( 'images/wan.png',__FILE__) . '" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Compressed files', $this->hook).'. (<strong style="color:#EFA800;">'. count($results[$l]) .' '. __('matches', $this->hook).'</strong>)</th>';
            if ( $l == '02' ) $result .= '<th><img src="'. plugins_url( 'images/critical.png',__FILE__) . '" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Dangerous and malicious files', $this->hook).'. (<strong style="color:red;">'. count($results[$l]) .' '. __('matches', $this->hook).' </strong>)</th>';
            if ( $l == '03' ) $result .= '<th><img src="'. plugins_url( 'images/critical.png',__FILE__) . '" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Unknown file found in WP core', $this->hook).'. (<strong style="color:red;">'. count($results[$l]) .' '. __('matches', $this->hook).' </strong>)</th>';
         	$result .= '<th>&nbsp;</th></tr></thead><tbody>';
        		foreach ( $results[$l] as $r )
				$result .= self::fdx_draw_row( $r );

			$result .= '</tbody></table><br />';

		}
	}

	echo $result;
}

/**
 * Draw a single result row.
 */
public function fdx_draw_row( $r ) {
	$html = '<tr><td>' . esc_html( $r['loc'] );
	$html .= '</td><td>';

	return $html . '</td></tr>';
}


/**
 * .
 */
public function fdx_results_page() {
	global $wp_version;
	delete_transient( 'fdx_results_trans' );
	delete_transient( 'fdx_files' );
	$results = get_option( 'fdx_results' );

  ?>





<?php if ( ! $results ) { ?>
      <?php
      echo '<table class="widefat">';
      echo '<thead><tr>';
      echo '<th>&nbsp;</th>';
      echo '<th class="fdx-status" id="red">'.__('Unexecuted!', $this->hook).'</th>';
      echo '<th>&nbsp;</th>';
      echo '</tr></thead>';
      echo '<tbody>';
      echo '</table>';
      ?>


<!--<table class="widefat">
<thead>
<tr><th><img src="<?php echo plugins_url( 'images/clean.png',__FILE__); ?>" width="32" height="32" border="0" alt="*" style="vertical-align: middle" /> <strong style="color:green;"><?php _e('Nothing Found!', $this->hook); ?></strong> </th>
<th>&nbsp;</th></tr></thead>
</table>-->


<?php } else {
self::fdx_show_results( $results );
} ?>



<?php }

/*********************************** P4 *****************************************
************* 404 logs
********************************************************************************/

function fdx_options_subpanel_p4() {
require_once ('modules/inc-p4.php');
}

/*
 * abc
 */





/********************************** P5 ******************************************
************* Core Exploit Scanner
********************************************************************************/

function fdx_options_subpanel_p5() {

 if ( isset($_GET['view']) && 'diff' == $_GET['view'] ) {
self::fdx_diff_page();
die;
   	} else {
require_once ('modules/inc-p5.php');
   }
}

/* ajax for viewing file source
*------------------------------------------------------------*/
 function get_file_source() {
    $out = array();
    if (!current_user_can('administrator') || md5($this->p5_salt . stripslashes(@$_POST['filename'])) != $_POST['hash']) {
      $out['err'] = 'Cheating are you?';
      die(json_encode($out));
    }
    $out['ext'] = pathinfo(@$_POST['filename'], PATHINFO_EXTENSION);
    $out['source'] = '';
     if (is_readable($_POST['filename'])) {
      $content = file_get_contents($_POST['filename']);
      if ($content !== FALSE) {
        $out['err'] = 0;
        $out['source'] = utf8_encode($content);
      } else {
        $out['err'] = 'File is empty.';
      }
    } else {
      $out['err'] = 'File does not exist or is not readable.';
    }
    die(json_encode($out));
  }

/* do the actual scanning
*------------------------------------------------------------*/
  function scan_files() {
    global $wp_version;
    $results['missing_ok'] =  $results['missing_bad'] = array();
    $results['changed_ok'] = $results['changed_bad'] = array();
    $results['ok'] = array();
    $results['last_run'] = current_time('timestamp');
    $results['total'] = 0;
    $i = 0;
    $missing_ok = array('readme.html', 'license.txt', 'wp-config-sample.php',
                        'wp-admin/install.php', 'wp-admin/upgrade.php');

    $all_exclude = array('readme.html', 'license.txt', 'wp-config-sample.php',
                        'wp-admin/install.php', 'wp-admin/upgrade.php');
      // hashes files
      require 'libs/hashes-'. $wp_version.'.php';
      $results['total'] = sizeof($filehashes);
      foreach ($filehashes as $file => $hash) {
        clearstatcache();
        if (file_exists(ABSPATH . $file)) {
          if ($hash == md5_file(ABSPATH . $file)) {
            $results['ok'][] = $file;
        } else {
            $results['changed_bad'][] = $file;
          }
        } else {
          if (in_array($file, $missing_ok)) {
            $results['missing_ok'][] = $file;
        } else {
            $results['missing_bad'][] = $file;

          }
        }
      } // foreach file
      update_option($this->p5_options_key, $results);
      return;

  }

/* check if files can be restored
*------------------------------------------------------------*/
  function check_file_write() {
    $url = wp_nonce_url('options.php?page='. $this->hook . '-'.$this->_p5 , 'fdx-file-rest');
    ob_start();
    $creds = request_filesystem_credentials($url, '', false, false, null);
    ob_end_clean();
    return (bool) $creds;
  }

/* restore the selected file
*------------------------------------------------------------*/
  function restore_file() {
    $file = str_replace(ABSPATH, '', stripslashes($_POST['filename']));
    $url = wp_nonce_url('options.php?page='. $this->hook . '-'.$this->_p5 , 'fdx-file-rest');
    $creds = request_filesystem_credentials($url, '', false, false, null);
    if (!WP_Filesystem($creds)) {
      die('can\'t write to file.');
    }
    $org_file = wp_remote_get('http://core.trac.wordpress.org/browser/tags/' . get_bloginfo('version') . '/' . $file . '?format=txt');
    if (!$org_file['body']) {
      die('can\'t download remote file source.');
    }
    global $wp_filesystem;
    if (!$wp_filesystem->put_contents(trailingslashit(ABSPATH) . $file, $org_file['body'], FS_CHMOD_FILE)) {
      die('unknown error while writing file.');
    }
    self::scan_files();
    die('1');
  }

/* render restore file dialog
*------------------------------------------------------------*/
  function restore_file_dialog() {
    $out = array();
    if (!current_user_can('administrator') || md5($this->p5_salt . stripslashes(@$_POST['filename'])) != $_POST['hash']) {
      $out['err'] = 'Cheating are you?';
      die(json_encode($out));
    }
    if (self::check_file_write()) {
      $out['out'] = '<p>'.__('By clicking the "restore file" button a copy of the original file will be downloaded from wordpress.org and the modified file will be overwritten. Please note that there is no undo!', $this->hook).'<br /><br /><br />
      <input type="button" value="'.__('Restore file', $this->hook).'" data-filename="' . stripslashes(@$_POST['filename']) . '" id="fdx-restore-file" class="button-primary" /></p>';
    } else {
      $out['out'] = '<p>'.__('Your WordPress core files are not writable from PHP. This is not a bad thing as it increases your security but you will have to restore the file manually by logging on to your FTP account and overwriting the file. You can', $this->hook).'
       <a target="_blank" href="http://core.trac.wordpress.org/browser/tags/' . get_bloginfo('version') . '/' . str_replace(ABSPATH, '', stripslashes($_POST['filename'])) . '?format=txt' . '">'.__('download the file directly from worpress.org', $this->hook).  '</a>.</p>';
    }
    die(json_encode($out));
  }

/* helper function for listing files
*------------------------------------------------------------*/
  function list_files($files, $view = false, $restore = false) {
    $out = '';
    $out .= '<ul class="fdx-list">';
    foreach ($files as $file) {
      $out .= '<li>';
      $out .= '<code>' . ABSPATH . $file . '</code>';
      if ($view) {
        $out .= ' <a data-hash="' . md5($this->p5_salt . ABSPATH . $file) . '" data-file="' . ABSPATH . $file . '" href="#source-dialog" class="fdx-show-source" title="'.__('View file source', $this->hook).'"><img src="'.plugins_url( 'images/ico2.png',__FILE__).'" width="16" height="16" border="0" alt="'.__('View file source', $this->hook).'" /></a>';
       }
      if ($view && $restore ) {
        $url = add_query_arg( array( 'view' => 'diff', 'file' => $file ), menu_page_url( $this->hook . '-'.$this->_p5, false ) );
		$url = wp_nonce_url( $url );
		$out .= ' <a href="#" onclick="PopupCenter(\''.$url.'\', \''.esc_attr($file).'\',700,500,\'yes\');" title="'.__('See what has been modified', $this->hook).'"><img src="'.plugins_url( 'images/ico3.png',__FILE__).'" width="16" height="16" border="0" alt="'.__('See what has been modified', $this->hook).'" /></a>';
       }
      if ($restore) {
        $out .= ' <a data-hash="' . md5($this->p5_salt . ABSPATH . $file) . '" data-file="' . ABSPATH . $file . '" href="#restore-dialog" class="fdx-restore-source" title="'.__('Restore file', $this->hook).'"><img src="'.plugins_url( 'images/ico1.png',__FILE__).'" width="16" height="16" border="0" alt="'.__('Restore file', $this->hook).'" /></a>';
      }
      $out .= '</li>';
    }
    $out .= '</ul>';
    return $out;
  }

/* +++++ Diff Page
*------------------------------------------------------------*/
function fdx_diff_page() {
	$file = $_GET['file'];
    echo '<style> #adminmenuwrap,#adminmenuwrap, #adminmenuback, #wpadminbar, #message, #footer { display: none !important }</style>';
	echo '<h2>'.__('Changes made to file', $this->hook). ': <code>' . esc_html($file) . '</code></h2>';
	echo self::fdx_display_file_diff( $file );
}

/* +++++ Generate the diff of a modified core file.
*------------------------------------------------------------*/
function fdx_display_file_diff( $file ) {
require_once( 'modules/class-p5.php' );
	global $wp_version;
	// core file names have a limited character set
	$file = preg_replace( '#[^a-zA-Z0-9/_.-]#', '', $file );
	if ( empty( $file ) || ! is_file( ABSPATH . $file ) )
		return '<p>Sorry, an error occured. This file might not exist!</p>';
	$key = $wp_version . '-' . $file;
	$cache = get_option( 'exploitscanner_diff_cache' );
	if ( ! $cache || ! is_array($cache) || ! isset($cache[$key]) ) {
		$url = "http://core.svn.wordpress.org/tags/$wp_version/$file";
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) || 200 != $response['response']['code'] )
			return '<p>Sorry, an error occured. Please try again later.</p>';
		$clean = $response['body'];
		if ( is_array($cache) ) {
			if ( count($cache) > 4 ) array_shift( $cache );
			$cache[$key] = $clean;
		} else {
			$cache = array( $key => $clean );
		}
		update_option( 'exploitscanner_diff_cache', $cache );
	} else {
		$clean = $cache[$key];
	}
	$modified = file_get_contents( ABSPATH . $file );
	$text_diff = new Text_Diff( explode( "\n", $clean ), explode( "\n", $modified ) );
	$renderer = new FDX_Text_Diff_Renderer();
	$diff = $renderer->render( $text_diff );
	$r  = "<table class='diff'>\n<col style='width:5px' /><col />\n";
	$r .= "<tbody>\n$diff\n</tbody>\n";
	$r .= "</table>";
	return $r;
}



/*
 * @ ALL
 *
 * @ clean-up when deactivated
 */
 function fdx_deactivate() {
    delete_option($this->p2_options_key);
    delete_option($this->p5_options_key);
    delete_option( 'fdx_results' ); //p3

}


}//++++++++++++++++++++++++++++end ALL
$total_security = new Total_Security();