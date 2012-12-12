<?php
/*
Plugin Name: Total Security
Plugin URI: http://fabrix.net/total-security/
Description: Checks your WordPress installation and provides detailed reporting on discovered vulnerabilities, anything suspicious and how to fix them.
Author: Fabrix DoRoMo
Version: 2.0.350
Author URI: http://fabrix.net/
*/
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*********************************************************************************/
define('FDX2_PLUGIN_NAME', 'Total Security' );
define('FDX2_PLUGIN_VERSION', '2.0.350' );
define('FDX2_PLUGIN_URL', plugin_dir_url(__FILE__));

define('FDX2_WPPAGE', 'http://wordpress.org/extend/plugins/total-security/');
define('FDX2_PLUGINPAGE', 'http://fabrix.net/total-security/');
define('FDX2_GLOTPRESS', 'http://translate.fabrix.net/projects/total-security/');
define('FDX2_SUPFORUM', 'http://wmais.in/?forum=total-security/');
define('FDX2_DONATELINK', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DHY4NXW35T4Y');

define('FDX2_LAST_WP_VER', '3.5'); //Last version of wordpress

define('FDX2_PLUGIN_P1', 'total-security' ); //link1, plugin prefix (.mo)
define('FDX2_PLUGIN_P2', 'total-security-core' ); //link2
define('FDX2_PLUGIN_P3', 'total-security-uns' ); //link3
define('FDX2_PLUGIN_P4', 'total-security-sys' ); //link4


/* SPECIFIC */
define('FDX_OPTIONS_KEY', 'fdx_key1_237'); // cookies
define('FDX_CS_OPTIONS_KEY', 'fdx_key2_247'); // cookies
define('FDX_CS_SALT', 'fdx_hash_456'); // MD5
define('FDX_DIC', plugin_dir_path(__FILE__) . 'libs/brute-force-dictionary.txt');

/*
*------------------------------------------------------------*/
include 'config.php'; //if there is possibility to change parameters
require_once 'admin/vulnerability_scan_inc.php';

/* I18n - http://codex.wordpress.org/I18n_for_WordPress_Developers
*------------------------------------------------------------*/
$currentLocale = get_locale();
			if(!empty($currentLocale)) {
				$moFile = dirname(__FILE__) . "/languages/".FDX2_PLUGIN_P1."-" . $currentLocale . ".mo";
				if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('fdx-lang', $moFile);
}

/* main Class
*------------------------------------------------------------*/
class fdx_class {

  function fdx_init() {
    if (is_admin() && current_user_can('administrator')) {
      add_action('admin_menu', array(__CLASS__, 'fdx_admin_menu'));
      add_action('wp_ajax_sn_run_tests', array(__CLASS__, 'run_tests'));

      add_action( 'fdx_core_get_file_source', 'fdx_diff_page' );
       //------------------------------
       if ( isset( $_GET['page'] ) && $_GET['page'] == FDX2_PLUGIN_P1 || isset( $_GET['page'] ) && $_GET['page'] == FDX2_PLUGIN_P2 || isset( $_GET['page'] ) && $_GET['page'] == FDX2_PLUGIN_P3 || isset( $_GET['page'] ) && $_GET['page'] == FDX2_PLUGIN_P4)  {
         add_action('admin_enqueue_scripts', array(__CLASS__, 'fdx_enqueue_scripts'));
        }
        add_action('wp_ajax_sn_core_get_file_source', array(__CLASS__, 'get_file_source'));
        add_action('wp_ajax_sn_core_restore_file', array(__CLASS__, 'restore_file_dialog'));
        add_action('wp_ajax_sn_core_restore_file_do', array(__CLASS__, 'restore_file'));
        add_action('wp_ajax_sn_core_run_scan', array(__CLASS__, 'scan_files'));
        add_action('admin_notices', array(__CLASS__, 'run_tests_warning'));
        add_action('admin_notices', array(__CLASS__, 'run_tests_warning2'));
        //++++++++++++++++++++++++++++++++++
        add_action( 'wp_ajax_fdx-scanner_file_scan', 'fdx_ajax_file_scan' );
        add_action( 'wp_ajax_fdx-run_end', 'fdx_run_end' );
    }
  }

/* Loads CSS or JS
*------------------------------------------------------------*/
function fdx_enqueue_scripts() {
      wp_enqueue_style('fdx-css', FDX2_PLUGIN_URL . 'css/fdx-inc.css', array(), FDX2_PLUGIN_VERSION);
      wp_enqueue_script('fdx-cookie', FDX2_PLUGIN_URL . 'js/jquery.cookie.js', array('jquery'), FDX2_PLUGIN_VERSION, true);
      wp_enqueue_script('fdx-block', FDX2_PLUGIN_URL . 'js/jquery.blockUI.js', array(), FDX2_PLUGIN_VERSION, true);

   if ( isset( $_GET['page'] ) && $_GET['page'] == FDX2_PLUGIN_P2)  {
     wp_enqueue_style('wp-jquery-ui-dialog');
     wp_enqueue_script('jquery-ui-dialog');
	 wp_enqueue_style('fdx-core-snippet', FDX2_PLUGIN_URL . 'css/snippet.min.css', array(), FDX2_PLUGIN_VERSION);
     wp_enqueue_script('fdx-core-snippet', FDX2_PLUGIN_URL . 'js/snippet.min.js', array(), FDX2_PLUGIN_VERSION, true);
    }
 }

/* Menu
*------------------------------------------------------------*/
function fdx_admin_menu(){
	add_menu_page('Total Security','Total Security', 'manage_options', FDX2_PLUGIN_P1, array(__CLASS__, 'fdx_tests_table'), FDX2_PLUGIN_URL . '/images/menu.png' );
    add_submenu_page(FDX2_PLUGIN_P1, __('Vulnerability Scan', 'fdx-lang'), __('Vulnerability Scan', 'fdx-lang'), 'manage_options', FDX2_PLUGIN_P1, array(__CLASS__, 'fdx_tests_table'));
    add_submenu_page(FDX2_PLUGIN_P1, __('Core Exploit Scanner', 'fdx-lang'), __('Core Exploit Scanner', 'fdx-lang'), 'manage_options', FDX2_PLUGIN_P2, array(__CLASS__, 'core_page'));
    add_submenu_page(FDX2_PLUGIN_P1, __('Unsafe Files Search', 'fdx-lang'), __('Unsafe Files Search', 'fdx-lang'), 'manage_options', FDX2_PLUGIN_P3, array(__CLASS__, 'unsafe_files'));
    add_submenu_page(FDX2_PLUGIN_P1, __('System Information', 'fdx-lang'), __('System Information', 'fdx-lang'), 'manage_options', FDX2_PLUGIN_P4, 'system_inf');

}

/* display warning if test were never run
*------------------------------------------------------------*/
  function run_tests_warning() {
    $tests = get_option(FDX_OPTIONS_KEY);
    if (!$tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Vulnerability Scan) <strong>tests were never run.</strong> Click <strong>"'.__('One Click Scan', 'fdx-lang').'"</strong> to run them now and analyze your site for security vulnerabilities.', 'fdx-lang').'</p></div>';
    } elseif ((current_time('timestamp') - 15*24*60*60) > $tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Vulnerability Scan) <strong>tests were not run for more than 30 days.</strong> It\'s advisable to run them once in a while. Click <strong>"'.__('One Click Scan', 'fdx-lang').'"</strong> to run them now and analyze your site for security vulnerabilities.', 'fdx-lang').'</p></div>';
    }
  }

/* PAGES
*------------------------------------------------------------*/

function fdx_tests_table() {
require_once( dirname(__FILE__) . '/admin/vulnerability_scan.php' );
  }

function unsafe_files() {
require_once( dirname(__FILE__) . '/admin/unsafe_files.php' );
}


/* run all tests; via AJAX
*------------------------------------------------------------*/
  function run_tests() {
    @set_time_limit(FDX_MAX_EXEC_SEC);
    $test_count = 0;
    $test_description = array('last_run' => current_time('timestamp'));
    foreach(fdx_tests::$security_tests as $test_name => $test){
      if ($test_name[0] == '_') {
        continue;
      }
      $response = fdx_tests::$test_name();
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
    update_option(FDX_OPTIONS_KEY, $test_description);
    die('1');
  }


/* convert status integer to button
*------------------------------------------------------------*/
  function status($int) {
    if ($int == 0) {
      $string = '<img src="'.FDX2_PLUGIN_URL.'images/critical.png" width="32" height="32" border="0" alt="*" />';
    } elseif ($int == 10) {
      $string = '<img src="'.FDX2_PLUGIN_URL.'images/clean.png" width="32" height="32" border="0" alt="*" />';
    } else {
      $string = '<img src="'.FDX2_PLUGIN_URL.'images/wan.png" width="32" height="32" border="0" alt="*" />';
    }
    return $string;
  }

/* ajax for viewing file source
*------------------------------------------------------------*/
 function get_file_source() {
    $out = array();
    if (!current_user_can('administrator') || md5(FDX_CS_SALT . stripslashes(@$_POST['filename'])) != $_POST['hash']) {
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
    $results['missing_ok'] =  $results['missing_bad'] = array();
    $results['changed_ok'] = $results['changed_bad'] = array();
     $results['missing_conf'] = $results['missing_conf'] = array();
    $results['ok'] = array();
    $results['last_run'] = current_time('timestamp');
    $results['total'] = 0;
    $i = 0;
    $missing_ok = array('readme.html', 'license.txt', 'wp-config-sample.php',
                        'wp-admin/install.php', 'wp-admin/upgrade.php');

    $changed_ok = array('wp-config.php', '.htaccess');

    $all_exclude = array('readme.html', 'license.txt', 'wp-config-sample.php',
                        'wp-admin/install.php', 'wp-admin/upgrade.php', 'wp-config.php', '.htaccess');

    if (file_exists(dirname(__FILE__) . '/libs/hashes-' . FDX2_LAST_WP_VER . '.php')) {
      require 'libs/hashes-' . FDX2_LAST_WP_VER . '.php';
      $results['total'] = sizeof($filehashes);
      foreach ($filehashes as $file => $hash) {
        clearstatcache();
        if (file_exists(ABSPATH . $file)) {
          if ($hash == md5_file(ABSPATH . $file)) {
            $results['ok'][] = $file;
          } elseif (in_array($file, $changed_ok)) {
            $results['changed_ok'][] = $file;
          } else {
            $results['changed_bad'][] = $file;
          }
         } else {
          if (in_array($file, $missing_ok)) {
            $results['missing_ok'][] = $file;
          } elseif (in_array($file, $changed_ok)) {
            $results['missing_conf'][] = $file;
          } else {
            $results['missing_bad'][] = $file;

          }
        }
      } // foreach file
      update_option(FDX_CS_OPTIONS_KEY, $results);
      die('1');
    } else {
      // no file definitions for this version of WP
      update_option(FDX_CS_OPTIONS_KEY, null);
      die('0');
    }
  }

/* display results
*------------------------------------------------------------*/
  function core_page() {
   	if ( isset($_GET['view']) && 'diff' == $_GET['view'] ) {
     fdx_diff_page();
   	} else {
  require_once( dirname(__FILE__) . '/admin/core_exploit_scanner.php' );
  }
}

/* check if files can be restored
*------------------------------------------------------------*/
  function check_file_write() {
    $url = wp_nonce_url('options.php?page='.FDX2_PLUGIN_P1 , 'fdx-file-rest');
    ob_start();
    $creds = request_filesystem_credentials($url, '', false, false, null);
    ob_end_clean();
    return (bool) $creds;
  }

/* restore the selected file
*------------------------------------------------------------*/
  function restore_file() {
    $file = str_replace(ABSPATH, '', stripslashes($_POST['filename']));
    $url = wp_nonce_url('options.php?page='.FDX2_PLUGIN_P1 , 'fdx-file-rest');
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
    if (!current_user_can('administrator') || md5(FDX_CS_SALT . stripslashes(@$_POST['filename'])) != $_POST['hash']) {
      $out['err'] = 'Cheating are you?';
      die(json_encode($out));
    }
    if (self::check_file_write()) {
      $out['out'] = '<p>'.__('By clicking the "restore file" button a copy of the original file will be downloaded from wordpress.org and the modified file will be overwritten. Please note that there is no undo!', 'fdx-lang').'<br /><br /><br />
      <input type="button" value="'.__('Restore file', 'fdx-lang').'" data-filename="' . stripslashes(@$_POST['filename']) . '" id="fdx-restore-file" class="button-primary" /></p>';
    } else {
      $out['out'] = '<p>'.__('Your WordPress core files are not writable from PHP. This is not a bad thing as it increases your security but you will have to restore the file manually by logging on to your FTP account and overwriting the file. You can', 'fdx-lang').'
       <a target="_blank" href="http://core.trac.wordpress.org/browser/tags/' . get_bloginfo('version') . '/' . str_replace(ABSPATH, '', stripslashes($_POST['filename'])) . '?format=txt' . '">'.__('download the file directly from worpress.org', 'fdx-lang').  '</a>.</p>';
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
        $out .= ' <a data-hash="' . md5(FDX_CS_SALT . ABSPATH . $file) . '" data-file="' . ABSPATH . $file . '" href="#source-dialog" class="fdx-show-source" title="'.__('View file source', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'images/ico2.png" width="16" height="16" border="0" alt="'.__('View file source', 'fdx-lang').'" /></a>';
       }
      if ($view && $restore ) {
        $url = add_query_arg( array( 'view' => 'diff', 'file' => $file ), menu_page_url( FDX2_PLUGIN_P2, false ) );
		$url = wp_nonce_url( $url );
		$out .= ' <a href="#" onclick="PopupCenter(\''.$url.'\', \''.esc_attr($file).'\',700,500,\'yes\');" title="'.__('See what has been modified', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'images/ico3.png" width="16" height="16" border="0" alt="'.__('See what has been modified', 'fdx-lang').'" /></a>';
       }
      if ($restore) {
        $out .= ' <a data-hash="' . md5(FDX_CS_SALT . ABSPATH . $file) . '" data-file="' . ABSPATH . $file . '" href="#restore-dialog" class="fdx-restore-source" title="'.__('Restore file', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'images/ico1.png" width="16" height="16" border="0" alt="'.__('Restore file', 'fdx-lang').'" /></a>';
      }
      $out .= '</li>';
    }
    $out .= '</ul>';
    return $out;
  }

/* display warning if test were never run
*------------------------------------------------------------*/
  function run_tests_warning2() {
    $tests = get_option(FDX_CS_OPTIONS_KEY);
    if (!@$tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Core Exploit Scanner) <strong>tests were never run.</strong> Click <strong>"'.__('One Click Scanner', 'fdx-lang'). '"</strong> to run them now and check your core files for exploits.', 'fdx-lang').'</p></div>';
    } elseif ((current_time('timestamp') - 15*24*60*60) > $tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Core Exploit Scanner) <strong>tests were not run for more than 30 days.</strong> It\'s advisable to run them once in a while. Click <strong>"'.__('One Click Scanner', 'fdx-lang'). '"</strong> to run them now check your core files for exploits.', 'fdx-lang').'</p></div>';
    }
  }

/* clean-up when deactivated
*------------------------------------------------------------*/
  function fdx_deactivate() {
    delete_option(FDX_OPTIONS_KEY);
    delete_option(FDX_CS_OPTIONS_KEY);
    //ver isso
    delete_transient( 'fdx_results_trans' );
	delete_transient( 'fdx_files' );
    //----------
//	delete_option( 'fdx' );
	delete_option( 'fdx_results' );
 }

} // end class


/* +++++ Diff Page
*------------------------------------------------------------*/
function fdx_diff_page() {
	$file = $_GET['file'];
    echo '<style> #adminmenuwrap,#adminmenuwrap, #adminmenuback, #wpadminbar, #message, #footer { display: none !important }</style>';
	echo '<h2>'.__('Changes made to file', 'fdx-lang'). ': <code>' . esc_html($file) . '</code></h2>';
	echo fdx_display_file_diff( $file );
}

/* +++++ Generate the diff of a modified core file.
*------------------------------------------------------------*/
function fdx_display_file_diff( $file ) {
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

/* +++++
*------------------------------------------------------------*/
include_once( ABSPATH . WPINC . '/wp-diff.php' );

if ( class_exists( 'Text_Diff_Renderer' ) ) :
class FDX_Text_Diff_Renderer extends Text_Diff_Renderer {
	function FDX_Text_Diff_Renderer() {
		parent::Text_Diff_Renderer();
	}
	function _startBlock( $header ) {
		return "<tr><td></td><td><code>$header</code></td></tr>\n";
	}
	function _lines( $lines, $prefix, $class ) {
		$r = '';
		foreach ( $lines as $line ) {
			$line = esc_html( $line );
			$r .= "<tr><td>{$prefix}</td><td class='{$class}'>{$line}</td></tr>\n";
		}
		return $r;
	}
	function _added( $lines ) {
		return $this->_lines( $lines, '+', 'diff-addedline' );
	}
	function _deleted( $lines ) {
		return $this->_lines( $lines, '-', 'diff-deletedline' );
	}
	function _context( $lines ) {
		return $this->_lines( $lines, '', 'diff-context' );
	}
	function _changed( $orig, $final ) {
		return $this->_deleted( $orig ) . $this->_added( $final );
	}
}
endif;

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                                            Unsafe
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

/**
 * AJAX callback to initiate a file scan.
 */
function fdx_ajax_file_scan() {
    @set_time_limit(FDX_MAX_EXEC_SEC);
	check_ajax_referer( 'fdx-scanner_scan' );

	if ( ! isset($_POST['start']) )
		die( json_encode( array( 'status' => 'error', 'data' => __('Error: start not set.', 'fdx-lang') ) ) );
	else
		$start = (int) $_POST['start'];

    $max = FDX_MAX_BATCH_SIZE;

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
		echo json_encode( array( 'status' => 'running', 'data' => __('Scanner filesystem', 'fdx-lang'). ': ' . ($start+$max) . '...' ) );
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
 * Exploit Scanner base class. Scanners should extend this.
 */
class FDX_C_Scanner {
	var $results;

	function add_result( $level, $info ) {
		$this->results[$level][] = $info;
	}

	function store_results( $done = false ) {
		$stored = get_transient( 'fdx_results_trans' );

		if ( empty($this->results) ) {
			if ( $done )
				update_option( 'fdx_results', $stored );
			return;
		}

		if ( $stored && is_array($stored) )
			$this->results = array_merge_recursive( $stored, $this->results );

		if ( $done ) {
			update_option( 'fdx_results', $this->results );
			delete_transient( 'fdx_results_trans' );
		} else {
			set_transient( 'fdx_results_trans', $this->results );
		}
	}
}
/**
 * File Scanner. Scans all files in given path for suspicious text.
 */
class File_FDX_Scanner extends FDX_C_Scanner {
	var $path;
	var $start;
	var $max_batch_size;
	var $paged = true;
	var $files = array();
	var $modified_files = array();
	var $skip;
	var $complete = false;


	function File_FDX_Scanner( $path, $args ) {
		$this->__construct( $path, $args );
	}

	function __construct( $path, $args ) {
		$this->path = $path;

		if ( ! empty($args['max']) )
			$this->max_batch_size = $args['max'];
		else
			$this->paged = false;

		$this->start = $args['start'];
		$this->skip = ltrim( str_replace( array( untrailingslashit( ABSPATH ), '\\' ), array( '', '/' ), __FILE__ ), '/' );
	}

	function run() {
		$this->get_files( $this->start );
		$this->store_results();
		return $this->complete;
	}

	function get_files( $s ) {
   		if ( 0 == $s ) {
			unset( $filehashes );
			$hashes = dirname(__FILE__) . '/libs/hashes-'. FDX2_LAST_WP_VER .'.php';
	 		include( $hashes );
  			$this->recurse_directory( $this->path );
  			foreach( $this->files as $k => $file ) {

//--------------------------------------------severe=03.core(php)
				if ( isset( $filehashes[$file] ) ) {
                    if ( $filehashes[$file] == md5_file( $this->path.'/'.$file ) ) {
						unset( $this->files[$k] );
						continue;
				   	}
				} else {
					list( $dir ) = explode( '/', $file );
					if ( $dir == 'wp-includes' || $dir == 'wp-admin') {
						$severity = substr( $file, -4 ) == '.php' ? '03' : '03';  //severe=03 warning=02 note=01
						$this->add_result( $severity, array(
							'loc' => $file,
   						) );
					}
				}
//--------------------------------------------severe=02.xxx
				if ( substr( $file, -4 ) == '.exe' ||
                     substr( $file, -4 ) == '.bat' ||
                     substr( $file, -4 ) == '.com' ||
                     substr( $file, -4 ) == '.scr' ||
                     substr( $file, -4 ) == '.msi')  {
					$this->add_result( '02', array(
						'loc' => $file,
					) );
//--------------------------------------------severe=02.xx
                } else if ( substr( $file, -3 ) == '.vb' ) {
					$this->add_result( '02', array(
						'loc' => $file,
					) );
//--------------------------------------------warning=01.xxx
                } else if ( substr( $file, -4 ) == '.rar' ||
                            substr( $file, -4 ) == '.zip' ||
                            substr( $file, -4 ) == '.tar' ||
                            substr( $file, -4 ) == '.bz2' ) {
					$this->add_result( '01', array(
						'loc' => $file,
					) );
//--------------------------------------------warning=01.xx
               } else if ( substr( $file, -3 ) == '.7z' ||
                           substr( $file, -3 ) == '.gz' ) {
					$this->add_result( '01', array(
						'loc' => $file,
					) );
//--------------------------------------------warning=00.xxx
               } else if ( substr( $file, -4 ) == '.log' ||
                           substr( $file, -4 ) == '.dat' ||
                           substr( $file, -4 ) == '.bin' ||
                           substr( $file, -4 ) == '.tmp' ) {
					$this->add_result( '00', array(
						'loc' => $file,
					) );
               }
			}
//--------------------------------------------end
			$this->files = array_values( $this->files );
			$result = set_transient( 'fdx_files', $this->files, 3600 );

			if ( ! $result ) {
				$this->paged = false;
				$data = array( 'files' => esc_html( serialize( $this->files ) ) );
				if ( ! empty($GLOBALS['EZSQL_ERROR']) )
					$data['db_error'] = $GLOBALS['EZSQL_ERROR'];
				$this->complete = new WP_Error( 'failed_transient', '$this->files was not properly saved as a transient', $data );
			}
		} else {
			$this->files = get_transient( 'fdx_files' );
		}

		if ( ! is_array( $this->files ) ) {
			$data = array(
				'start' => $s,
				'files' => esc_html( serialize( $this->files ) ),
			);

			if ( ! empty( $GLOBALS['EZSQL_ERROR'] ) )
				$data['db_error'] = $GLOBALS['EZSQL_ERROR'];

			$this->complete = new WP_Error( 'no_files_array', '$this->files was not an array', $data );
			$this->files = array();
			return;
		}

		// use files list to get a batch if paged
		if ( $this->paged && (count($this->files) - $s) > $this->max_batch_size ) {
			$this->files = array_slice( $this->files, $s, $this->max_batch_size );
		} else {
			$this->files = array_slice( $this->files, $s );
			if ( ! is_wp_error( $this->complete ) )
				$this->complete = true;
		}
	}

	function recurse_directory( $dir ) {
		if ( $handle = @opendir( $dir ) ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				if ( $file != '.' && $file != '..' ) {
					$file = $dir . '/' . $file;
					if ( is_dir( $file ) ) {
						$this->recurse_directory( $file );
					} elseif ( is_file( $file ) ) {
						$this->files[] = str_replace( $this->path.'/', '', $file );
					}
				}
			}
			closedir( $handle );
		}
	}


	function replace( $matches ) {
		return '$#$#' . $matches[0] . '#$#$';
	}

}
/**
 * RunEnd
 */
class RunEnd extends FDX_C_Scanner {
	function RunEnd() {$this->store_results(true);	}
}

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                                            Unsafe
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

function system_inf(){
require_once( dirname(__FILE__) . '/admin/system_information.php' );
}

/* hook everything up
*------------------------------------------------------------*/
add_action('init', array('fdx_class', 'fdx_init'));
register_deactivation_hook( __FILE__, array('fdx_class', 'fdx_deactivate')); // when deativated clean up
?>