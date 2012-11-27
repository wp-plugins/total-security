<?php
/*
Plugin Name: Total Security
Plugin URI: http://fabrix.net/total-security/
Description: Checks your WordPress installation and provides detailed reporting on discovered vulnerabilities, anything suspicious and how to fix them.
Author: Fabrix DoRoMo
Version: 1.0
Author URI: http://fabrix.net/
*
*
*
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

define('FDX_DIC', plugin_dir_path(__FILE__) . 'libs/brute-force-dictionary.txt');
define('FDX_OPTIONS_KEY', 'wf_sn_results');
define('FDX_WPSS_PLUGIN_URL', plugin_dir_url(__FILE__));
include 'config.php';
require_once 'admin/vulnerability_scan_inc.php';

/*
*-------------------------core--------------------------------*/
define('FDX_CS_OPTIONS_KEY', 'wf_sn_cs_results');
define('FDX_CS_SALT', 'monkey');
define('FDX_LAST_WP_VER', '3.4.2'); //last up version
define('FDX_PLUGIN_URL', plugins_url('', __FILE__) );//plugin URL

/*
*---------------------extra----------------------------------*/
define('FDX2_PLUGIN_NAME', 'Total Security' ); //plugin name
define('FDX2_PLUGIN_VERSION', '1.0' ); //plugin version
define('FDX2_MINIMUM_PHP_VER', '5.0.0'); //minimum version of PHP

/*
*------------------------------------------------------------*/
$currentLocale = get_locale();
			if(!empty($currentLocale)) {
				$moFile = dirname(__FILE__) . "/languages/total-security-" . $currentLocale . ".mo";
				if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('fdx-lang', $moFile);
}

/*
*------------------------------------------------------------*/
class fdx_class {

  function fdx_init() {
    if (is_admin() && current_user_can('administrator')) {
      add_action('admin_menu', array(__CLASS__, 'fdx_admin_menu'));
      add_action('wp_ajax_sn_run_tests', array(__CLASS__, 'run_tests'));

      add_action( 'fdx_core_get_file_source', 'fdx_diff_page' );

       if ( isset( $_GET['page'] ) && $_GET['page'] == 'fdx-sn' || isset( $_GET['page'] ) && $_GET['page'] == 'fdx_core' || isset( $_GET['page'] ) && $_GET['page'] == 'fdx_sis')  {
         add_action('admin_enqueue_scripts', array(__CLASS__, 'fdx_enqueue_scripts'));
            }
        add_action('wp_ajax_sn_core_get_file_source', array(__CLASS__, 'get_file_source'));
        add_action('wp_ajax_sn_core_restore_file', array(__CLASS__, 'restore_file_dialog'));
        add_action('wp_ajax_sn_core_restore_file_do', array(__CLASS__, 'restore_file'));
        add_action('wp_ajax_sn_core_run_scan', array(__CLASS__, 'scan_files'));
        add_action('admin_notices', array(__CLASS__, 'run_tests_warning'));
       add_action('admin_notices', array(__CLASS__, 'run_tests_warning2'));
    }
  }

/*
*------------------------------------------------------------*/
function fdx_enqueue_scripts() {
      wp_enqueue_style('fdx-css', FDX_WPSS_PLUGIN_URL . 'css/fdx-inc.css', array(), '1.0');
      wp_enqueue_script('sn-cookie', FDX_WPSS_PLUGIN_URL . 'js/jquery.cookie.js', array('jquery'), '1.0', true);
      wp_enqueue_script('sn-block', FDX_WPSS_PLUGIN_URL . 'js/jquery.blockUI.js', array(), '1.0', true);

   if ( isset( $_GET['page'] ) && $_GET['page'] == 'fdx_core')  {
     wp_enqueue_style('wp-jquery-ui-dialog');
     wp_enqueue_script('jquery-ui-dialog');
	 wp_enqueue_style('sn-core-snippet', FDX_WPSS_PLUGIN_URL . 'css/snippet.min.css', array(), '1.0');
     wp_enqueue_script('sn-core-snippet', FDX_WPSS_PLUGIN_URL . 'js/snippet.min.js', array(), '1.0', true);
    }
 }

/*
*------------------------------------------------------------*/
function fdx_admin_menu(){
	add_menu_page('Total Security','Total Security', 'manage_options', 'fdx-sn', array(__CLASS__, 'fdx_tests_table'), FDX_PLUGIN_URL . '/images/menu.png' );
    add_submenu_page('fdx-sn', __('Vulnerability Scan', 'fdx-lang'), __('Vulnerability Scan', 'fdx-lang'), 'manage_options', 'fdx-sn', array(__CLASS__, 'fdx_tests_table'));
    add_submenu_page('fdx-sn', __('Scanning all your core WP files', 'fdx-lang'), __('Core Exploit Scanner', 'fdx-lang'), 'manage_options', 'fdx_core', array(__CLASS__, 'core_page'));
    add_submenu_page('fdx-sn', __('System Information', 'fdx-lang'), __('System Information', 'fdx-lang'), 'manage_options', 'fdx_sis', array(__CLASS__, 'system_inf'));
}

  // display warning if test were never run
  function run_tests_warning() {
    $tests = get_option(FDX_OPTIONS_KEY);

    if (!$tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Vulnerability Scan) <strong>tests were never run.</strong> Click <strong>"'.__('One Click Scan', 'fdx-lang').'"</strong> to run them now and analyze your site for security vulnerabilities.', 'fdx-lang').'</p></div>';
    } elseif ((current_time('timestamp') - 15*24*60*60) > $tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Vulnerability Scan) <strong>tests were not run for more than 30 days.</strong> It\'s advisable to run them once in a while. Click <strong>"'.__('One Click Scan', 'fdx-lang').'"</strong> to run them now and analyze your site for security vulnerabilities.', 'fdx-lang').'</p></div>';
    }
  } // run_tests_warning


function system_inf(){
require_once( dirname(__FILE__) . '/admin/system_information.php' );
}

// display tests table
function fdx_tests_table() {
require_once( dirname(__FILE__) . '/admin/vulnerability_scan.php' );
  }


  // run all tests; via AJAX
  function run_tests() {
    @set_time_limit(FDX_MAX_EXEC_SEC);
    $test_count = 0;
    $test_description = array('last_run' => current_time('timestamp'));

    foreach(fdx_tests::$security_tests as $test_name => $test){
      if ($test_name[0] == '_') {
        continue;
      }
      $response = fdx_tests::$test_name();

      $test_description['test'][$test_name]['title'] = $test['title'];
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
  } // run_test


  // convert status integer to button
  function status($int) {
    if ($int == 0) {
      $string = '<img src="'.FDX_WPSS_PLUGIN_URL.'images/critical.png" width="32" height="32" border="0" alt="*" />';
    } elseif ($int == 10) {
      $string = '<img src="'.FDX_WPSS_PLUGIN_URL.'images/clean.png" width="32" height="32" border="0" alt="*" />';
    } else {
      $string = '<img src="'.FDX_WPSS_PLUGIN_URL.'images/wan.png" width="32" height="32" border="0" alt="*" />';
    }

    return $string;
  } // status

//###############################################################################################

  // ajax for viewing file source
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
  } // get_file_source


    // do the actual scanning
  function scan_files() {
    $results['missing_ok'] =  $results['missing_bad'] = array();
    $results['changed_ok'] = $results['changed_bad'] = array();
    $results['ok'] = array();
    $results['last_run'] = current_time('timestamp');
    $results['total'] = 0;

    $i = 0;

    $missing_ok = array('readme.html', 'license.txt', 'wp-config-sample.php',
                        'wp-admin/install.php', 'wp-admin/upgrade.php');
    $changed_ok = array('wp-config.php', '.htaccess');

    if (file_exists(dirname(__FILE__) . '/libs/hashes-' . FDX_LAST_WP_VER . '.php')) {
      require 'libs/hashes-' . FDX_LAST_WP_VER . '.php';

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
  } // scan_files

   // display results
  function core_page() {
   	if ( isset($_GET['view']) && 'diff' == $_GET['view'] ) {
     fdx_diff_page();
   	} else {
  require_once( dirname(__FILE__) . '/admin/core_exploit_scanner.php' );
  }

} // core_page


     // check if files can be restored
  function check_file_write() {
    $url = wp_nonce_url('options.php?page=fdx-sn', 'fdx-sn-cs');
    ob_start();
    $creds = request_filesystem_credentials($url, '', false, false, null);
    ob_end_clean();

    return (bool) $creds;
  } // check_file_write

   // restore the selected file
  function restore_file() {
    $file = str_replace(ABSPATH, '', stripslashes($_POST['filename']));

    $url = wp_nonce_url('options.php?page=fdx-sn', 'fdx-sn-cs');
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
  } // restore_file

    // render restore file dialog
  function restore_file_dialog() {
    $out = array();

    if (!current_user_can('administrator') || md5(FDX_CS_SALT . stripslashes(@$_POST['filename'])) != $_POST['hash']) {
      $out['err'] = 'Cheating are you?';
      die(json_encode($out));
    }

    if (self::check_file_write()) {
      $out['out'] = '<p>'.__('By clicking the "restore file" button a copy of the original file will be downloaded from wordpress.org and the modified file will be overwritten. Please note that there is no undo!', 'fdx-lang').'<br /><br /><br />
      <input type="button" value="'.__('Restore file', 'fdx-lang').'" data-filename="' . stripslashes(@$_POST['filename']) . '" id="sn-restore-file" class="button-primary" /></p>';
    } else {
      $out['out'] = '<p>'.__('Your WordPress core files are not writable from PHP. This is not a bad thing as it increases your security but you will have to restore the file manually by logging on to your FTP account and overwriting the file. You can', 'fdx-lang').'
       <a target="_blank" href="http://core.trac.wordpress.org/browser/tags/' . get_bloginfo('version') . '/' . str_replace(ABSPATH, '', stripslashes($_POST['filename'])) . '?format=txt' . '">'.__('download the file directly from worpress.org', 'fdx-lang').  '</a>.</p>';
    }

    die(json_encode($out));
  } // restore_file

   // helper function for listing files
  function list_files($files, $view = false, $restore = false) {
    $out = '';
    $out .= '<ul class="fdx-list">';

    foreach ($files as $file) {
      $out .= '<li>';
      $out .= '<code>' . ABSPATH . $file . '</code>';
      if ($view) {
        $out .= ' <a data-hash="' . md5(FDX_CS_SALT . ABSPATH . $file) . '" data-file="' . ABSPATH . $file . '" href="#source-dialog" class="sn-show-source" title="'.__('View file source', 'fdx-lang').'"><img src="'.FDX_WPSS_PLUGIN_URL.'images/ico2.png" width="16" height="16" border="0" alt="'.__('View file source', 'fdx-lang').'" /></a>';
       }
      if ($view && $restore ) {
        $url = add_query_arg( array( 'view' => 'diff', 'file' => $file ), menu_page_url( 'fdx_core', false ) );
		$url = wp_nonce_url( $url );
		$out .= ' <a href="#" onclick="PopupCenter(\''.$url.'\', \''.esc_attr($file).'\',700,500,\'yes\');" title="'.__('See what has been modified', 'fdx-lang').'"><img src="'.FDX_WPSS_PLUGIN_URL.'images/ico3.png" width="16" height="16" border="0" alt="'.__('See what has been modified', 'fdx-lang').'" /></a>';
       }
      if ($restore) {
        $out .= ' <a data-hash="' . md5(FDX_CS_SALT . ABSPATH . $file) . '" data-file="' . ABSPATH . $file . '" href="#restore-dialog" class="sn-restore-source" title="'.__('Restore file', 'fdx-lang').'"><img src="'.FDX_WPSS_PLUGIN_URL.'images/ico1.png" width="16" height="16" border="0" alt="'.__('Restore file', 'fdx-lang').'" /></a>';
      }
      $out .= '</li>';
    } // foreach $files

    $out .= '</ul>';

    return $out;
  } // list_files

//###########################################################################################################





//##########################################################################################################
  // display warning if test were never run
  function run_tests_warning2() {
    $tests = get_option(FDX_CS_OPTIONS_KEY);

    if (!@$tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Core Exploit Scanner) <strong>tests were never run.</strong> Click <strong>"'.__('One Click Scanner', 'fdx-lang'). '"</strong> to run them now and check your core files for exploits.', 'fdx-lang').'</p></div>';
    } elseif ((current_time('timestamp') - 15*24*60*60) > $tests['last_run']) {
      echo '<div id="message" class="error"><p>Total Security '.__('(Core Exploit Scanner) <strong>tests were not run for more than 30 days.</strong> It\'s advisable to run them once in a while. Click <strong>"'.__('One Click Scanner', 'fdx-lang'). '"</strong> to run them now check your core files for exploits.', 'fdx-lang').'</p></div>';
    }
  } // run_tests_warning

//###############################################################################################

  // clean-up when deactivated
  function fdx_deactivate() {
    delete_option(FDX_OPTIONS_KEY);
    delete_option(FDX_CS_OPTIONS_KEY);
  } // deactivate


} // fdx_sn class



//######################################DIF###################################################
function fdx_diff_page() {
	$file = $_GET['file'];
    echo '<style> #adminmenuwrap,#adminmenuwrap, #adminmenuback, #wpadminbar, #message, #footer { display: none !important }</style>';
	echo '<h2>'.__('Changes made to file', 'fdx-lang'). ': <code>' . esc_html($file) . '</code></h2>';
	echo fdx_display_file_diff( $file );
//	echo '<p><a href="' . menu_page_url('fdx_core',false) . '">Go back.</a></p>';
}

/**
 * Generate the diff of a modified core file.
 */
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

//####################################FIM DIF#################################################


// hook everything up
add_action('init', array('fdx_class', 'fdx_init'));

// when deativated clean up
register_deactivation_hook( __FILE__, array('fdx_class', 'fdx_deactivate'));
?>