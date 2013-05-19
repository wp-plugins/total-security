<?php
// class fdx_tests extends fdx_class {
   class FDX_CLASS_P2 {
static $security_tests = array(

'ver_check' => array('msg_ok' => '%s',
                    'msg_bad' => '%s'
),

'plugins_ver_check' => array('msg_ok' => '%s',
                            'msg_bad' => '%s'
),

'themes_ver_check' => array('msg_ok' => '%s',
                           'msg_bad' => '%s'
),

'wp_header_meta' => array('msg_ok' => '%s',
                     'msg_warning' => '%s',
                         'msg_bad' => '%s'
),

'wp_xmlrpc_meta' => array('msg_ok' => '%s',
                     'msg_warning' => '%s',
                         'msg_bad' => '%s'
),

'wp_wlwmanifest_meta' => array('msg_ok' => '%s',
                          'msg_warning' => '%s',
                              'msg_bad' => '%s'
),

'readme_check' => array('msg_ok' => '%s',
                   'msg_warning' => '%s',
                       'msg_bad' => '%s'
),

'install_file_check' => array('msg_ok' => '%s',
                         'msg_warning' => '%s',
                             'msg_bad' => '%s'
),

'upgrade_file_check' => array('msg_ok' => '%s',
                         'msg_warning' => '%s',
                             'msg_bad' => '%s'
),

'config_chmod' => array('msg_ok' => '%s',
                   'msg_warning' => '%s',
                       'msg_bad' => '%s'
),

'htaccess_chmod' => array('msg_ok' => '%s',
                   'msg_warning' => '%s',
                       'msg_bad' => '%s'
),

'php_headers' => array('msg_ok' => '%s',
                      'msg_bad' => '%s'
),

'blog_site_url_check' => array('msg_ok' => '%s',
                              'msg_bad' => '%s'
),

'salt_keys_check' => array('msg_ok' => '%s',
                          'msg_bad' => '%s'
),

'db_table_prefix_check' => array('msg_ok' => '%s',
                                'msg_bad' => '%s'
),

'db_password_check' => array('msg_ok' => '%s',
                            'msg_bad' => '%s'
),

'debug_check' => array('msg_ok' => '%s',
                      'msg_bad' => '%s'
),

'db_debug_check' => array('msg_ok' => '%s',
                         'msg_bad' => '%s'
),

'script_debug_check' => array('msg_ok' => '%s',
                             'msg_bad' => '%s'
),


'expose_php_check' => array('msg_ok' => '%s',
                           'msg_bad' => '%s'
),

'display_errors_check' => array('msg_ok' => '%s',
                               'msg_bad' => '%s'
),

'register_globals_check' => array('msg_ok' => '%s',
                                 'msg_bad' => '%s'
),


'allow_url_include_check' => array('msg_ok' => '%s',
                                  'msg_bad' => '%s'
),

'safe_mode_check' => array('msg_ok' => '%s',
                          'msg_bad' => '%s'
),

'file_editor' => array('msg_ok' => '%s',
                      'msg_bad' => '%s'
),

'uploads_browsable' => array('msg_ok' => '%s',
                        'msg_warning' => '%s',
                            'msg_bad' => '%s'
),

'anyone_can_register' => array('msg_ok' => '%s',
                              'msg_bad' => '%s'
),

'user_exists' => array('msg_ok' => '%s',
                      'msg_bad' => '%s'
),

'check_failed_login_info' => array('msg_ok' => '%s',
                                  'msg_bad' => '%s'
),

'id1_user_check' => array('msg_ok' => '%s',
                                  'msg_bad' => '%s'
),

'mysql_external' => array('msg_ok' => '%s',
                              'msg_warning' => '%s'


),

//------end
'bruteforce_login' => array('msg_ok' => '%s',
                           'msg_bad' => '%s',
                       'msg_warning' => '%s')

); //end $security_tests

// check WP version
  function ver_check() {
   $msgTIT = __('UP to date: WordPress core', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

   $return = array();

    if (!function_exists('get_preferred_from_update_core') ) {
      require_once(ABSPATH . 'wp-admin/includes/update.php');
    }

    // get version
    wp_version_check();
    $latest_core_update = get_preferred_from_update_core();

    if (isset($latest_core_update->response) && ($latest_core_update->response == 'upgrade') ){
      $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
     } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
     }

    return $return;
  }

  // check if plugins are up to date
  function plugins_ver_check() {
   $msgTIT = __('UP to date: Plugins.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

    $return = array();

    //Get the current update info
    $current = get_site_transient('update_plugins');

    if (!is_object($current)) {
      $current = new stdClass;
    }

    set_site_transient('update_plugins', $current);

    // run the internal plugin update check
    wp_update_plugins();

    $current = get_site_transient('update_plugins');

    if (isset($current->response) && is_array($current->response) ) {
      $plugin_update_cnt = count($current->response);
    } else {
      $plugin_update_cnt = 0;
    }

    if($plugin_update_cnt > 0){
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error"><code>' . sizeof($current->response) . '</code> ' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }


  // check themes versions
  function themes_ver_check() {
   $msgTIT = __('UP to date: Themes', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

    $return = array();

    $current = get_site_transient('update_themes');

    if (!is_object($current)){
      $current = new stdClass;
    }

    set_site_transient('update_themes', $current);
    wp_update_themes();

    $current = get_site_transient('update_themes');

    if (isset($current->response) && is_array($current->response)) {
      $theme_update_cnt = count($current->response);
    } else {
      $theme_update_cnt = 0;
    }

    if($theme_update_cnt > 0){
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error"><code>' . sizeof($current->response) . '</code> ' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

  // check for WP version in meta tags
  function wp_header_meta() {
   $msgTIT = __('Header: Reveal full WordPress version info', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

   $return = array();

    if (!class_exists('WP_Http')) {
      require( ABSPATH . WPINC . '/class-http.php' );
    }

    $http = new WP_Http();
    $response = (array) $http->request(get_bloginfo('wpurl'));
    $html = $response['body'];

    if ($html) {
      $return['status'] = 10;
        $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
      // extract content in <head> tags
      $start = strpos($html, '<head');
      $len = strpos($html, 'head>', $start + strlen('<head'));
      $html = substr($html, $start, $len - $start + strlen('head>'));
      // find all Meta Tags
      preg_match_all('#<meta([^>]*)>#si', $html, $matches);
      $meta_tags = $matches[0];

      foreach ($meta_tags as $meta_tag) {
        if (stripos($meta_tag, 'generator') !== false &&
            stripos($meta_tag, get_bloginfo('version')) !== false) {
          $return['status'] = 0;
          $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
          break;
        }
      }
    } else {
      // error
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    }

    return $return;
  }
//###############################################################################################



 // ++++++++++ xmlrpc
  function wp_xmlrpc_meta() {
   $msgTIT = __('Header: RSD (Really Simple Discovery) mechanism used by XML-RPC clients', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

   $return = array();

    if (!class_exists('WP_Http')) {
      require( ABSPATH . WPINC . '/class-http.php' );
    }

    $http = new WP_Http();
    $response = (array) $http->request(get_bloginfo('wpurl'));
    $html = $response['body'];

    if ($html) {
      $return['status'] = 10;
        $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
      // extract content in <head> tags
      $start = strpos($html, '<head');
      $len = strpos($html, 'head>', $start + strlen('<head'));
      $html = substr($html, $start, $len - $start + strlen('head>'));
      // find all Meta Tags
      preg_match_all('#<link([^>]*)>#si', $html, $matches);
      $meta_tags = $matches[0];

      foreach ($meta_tags as $meta_tag) {
        if (stripos($meta_tag, 'EditURI') !== false) {
          $return['status'] = 0;
          $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
          break;
        }
      }
    } else {
      // error
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    }

    return $return;
  }

// ++++++++++ wlwmanifest
  function wp_wlwmanifest_meta() {
   $msgTIT = __('Header: Windows Live Writer or other blogging clients', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

   $return = array();

    if (!class_exists('WP_Http')) {
      require( ABSPATH . WPINC . '/class-http.php' );
    }

    $http = new WP_Http();
    $response = (array) $http->request(get_bloginfo('wpurl'));
    $html = $response['body'];

    if ($html) {
      $return['status'] = 10;
        $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
      // extract content in <head> tags
      $start = strpos($html, '<head');
      $len = strpos($html, 'head>', $start + strlen('<head'));
      $html = substr($html, $start, $len - $start + strlen('head>'));
      // find all Meta Tags
      preg_match_all('#<link([^>]*)>#si', $html, $matches);
      $meta_tags = $matches[0];

      foreach ($meta_tags as $meta_tag) {
        if (stripos($meta_tag, 'wlwmanifest') !== false) {
          $return['status'] = 0;
          $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
          break;
        }
      }
    } else {
      // error
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    }

    return $return;
  }




//###############################################################################################


  // does readme.html exist?
  function readme_check() {
   $msgTIT = '<code>readme.html</code> '.__('file is accessible via HTTP on the default location.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

   $return = array();
    $url = get_bloginfo('wpurl') . '/readme.html?rnd=' . rand();
    $response = wp_remote_get($url);

    if(is_wp_error($response)) {
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    } elseif ($response['response']['code'] == 200) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

 // check if php headers contain php version
  function php_headers() {
   $msgTIT = __('Check if server response headers contain detailed PHP version info.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

   $return = array();

    if (!class_exists('WP_Http')) {
      require( ABSPATH . WPINC . '/class-http.php' );
    }

    $http = new WP_Http();
    $response = (array) $http->request(get_bloginfo('siteurl'));

    if((isset($response['headers']['server']) && stripos($response['headers']['server'], phpversion()) !== false) || (isset($response['headers']['x-powered-by']) && stripos($response['headers']['x-powered-by'], phpversion()) !== false)) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
    $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }


    // check if expose_php is off
   function expose_php_check() {
   $msgTIT = 'PHP (<em>expose_php</em>): '.__('Check if directive is turned off.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

    $return = array();

    $check = (bool) ini_get('expose_php');
    if ($check) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
   }

   // check if certain username exists
  function user_exists($username = 'admin') {
   $msgTIT = __('Check if user with username <em>"admin"</em> exists.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

    $return = array();

    // Define the function
    require_once(ABSPATH . WPINC . '/registration.php');

    if (username_exists($username) ) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

   // check if anyone can register on the site
   function anyone_can_register() {
   $msgTIT = __('Check if <em>"anyone can register"</em> option is enabled.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

     $return = array();
     $test = get_option('users_can_register');

     if ($test) {
       $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
     } else {
       $return['status'] = 10;
       $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
     }

     return $return;
   }

  // check for unnecessary information on failed login
  function check_failed_login_info() {
   $msgTIT = __('Check for display of unnecessary information on failed login attempts.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

    $return = array();

    $params = array('log' => 'fdx-test_3453344355',
                    'pwd' => 'fdx-test_2344323335');

    if (!class_exists('WP_Http')) {
      require( ABSPATH . WPINC . '/class-http.php' );
    }

    $http = new WP_Http();
    $response = (array) $http->request(get_bloginfo('wpurl') . '/wp-login.php', array('method' => 'POST', 'body' => $params));

    if (stripos($response['body'], 'invalid username') !== false){
      $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

   // check DB table prefix
  function db_table_prefix_check() {
    global $wpdb;
    $msgTIT = 'Database: '.__('Check if table prefix is the default one (<em>wp_</em>).', $this->hook);
    $msgOK = __('No', $this->hook);
    $msgNO = __('Yes', $this->hook);

    $return = array();

    if ($wpdb->prefix == 'wp_' || $wpdb->prefix == 'wordpress_' || $wpdb->prefix == 'wp3_') {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

  // unique config keys check
  function salt_keys_check() {
   $msgTIT = __('Check if security keys and salts have proper values.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

    $return = array();
    $ok = true;
    $keys = array('AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY',
                  'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT');

    foreach ($keys as $key) {
      $constant = @constant($key);
      if (empty($constant) || trim($constant) == 'put your unique phrase here' || strlen($constant) < 50) {
        $bad_keys[] = $key;
        $ok = false;
      }
    } // foreach

    if ($ok == true) {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    } else {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<code>' . implode(', ', $bad_keys). '</code> <span class="fdx-error">' . $msgNO . '</span>)';
    }

    return $return;
  }

  // check database password
  function db_password_check() {
   $msgTIT = 'Database: '.__('Test the strength of config password.', $this->hook);
   $msgOK = __('Database password is strong enough.', $this->hook);

    $return = array();
    $password = DB_PASSWORD;

    if (empty($password)) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . __('password is empty', $this->hook) . '</span>)';
    } elseif (self::dictionary_attack($password)) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . __('password is a simple word from the dictionary', $this->hook). '</code>)';
    } elseif (strlen($password) < 6) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . __('password length is only', $this->hook). strlen($password) . __('chars', $this->hook) . '</span>)';
    } elseif (sizeof(count_chars($password, 1)) < 5) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . __('password is too simple', $this->hook) . '</span>)';

    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';

    }

    return $return;
  }

  // check if global WP debugging is enabled
  function debug_check() {
   $msgTIT = __('Check if Debug mode is enabled', $this->hook) .': '.__('General .', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

    $return = array();

    if (defined('WP_DEBUG') && WP_DEBUG) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

 // check if DB debugging is enabled
  function db_debug_check() {
    global $wpdb;
   $msgTIT = __('Check if Debug mode is enabled', $this->hook) .': '.__('Database .', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

    $return = array();

    if ($wpdb->show_errors == true) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

  // check if global WP JS debugging is enabled
  function script_debug_check() {
   $msgTIT = __('Check if Debug mode is enabled', $this->hook).': '.__('JavaScript .', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

    $return = array();

    if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

    // check if display_errors is off
   function display_errors_check() {
   $msgTIT = 'PHP (<em>display_errors</em>): '.__('Check if directive is turned off.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

    $return = array();

    $check = (bool) ini_get('display_errors');
    if ($check) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
   }

  // compare WP Blog Url with WP Site Url
  function blog_site_url_check() {
   $msgTIT = __('Check if WordPress installation address is the same as the site address.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

    $return = array();

    $siteurl = get_bloginfo('siteurl');
    $wpurl = get_bloginfo('wpurl');

    if ($siteurl == $wpurl) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

  // check if wp-config.php has the right chmod
  function config_chmod() {
   $msgTIT = '<code>wp-config.php</code> '.__(' file has the writable.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

    $return = array();

    $mode = substr(sprintf('%o', fileperms(ABSPATH . '/wp-config.php')), -4);
    $htaccess = ABSPATH . 'wp-config.php';

    if (!$mode) {
     $return['status'] = 5;
     $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    } elseif ($f = @fopen( $htaccess, 'a' )) {
      @fclose( $f );
       $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<code>chmod: '. $mode .'</code> <span class="fdx-error">' . $msgNO . '</span>)';
      } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<code>chmod: '. $mode .'</code> <span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

  // check if .htaccess has the right chmod
  function htaccess_chmod() {
   $msgTIT = '<code>.htaccess</code> '.__(' file has the writable.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

    $return = array();

    $mode = substr(sprintf('%o', fileperms(ABSPATH . '/.htaccess')), -4);
    $htaccess = ABSPATH . '.htaccess';

    if (!$mode) {
     $return['status'] = 5;
     $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    } elseif ($f = @fopen( $htaccess, 'a' )) {
      @fclose( $f );
       $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<code>chmod: '. $mode .'</code> <span class="fdx-error">' . $msgNO . '</span>)';
      } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<code>chmod: '. $mode .'</code> <span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }


  // does WP install.php file exist?
  function install_file_check() {
   $msgTIT = '<code>install.php</code> '.__('file is accessible via HTTP on the default location.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

    $return = array();
    $url = get_bloginfo('wpurl') . '/wp-admin/install.php?rnd=' . rand();
    $response = wp_remote_get($url);

    if(is_wp_error($response)) {
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    } elseif ($response['response']['code'] == 200) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

  // does WP install.php file exist?
  function upgrade_file_check() {
   $msgTIT = '<code>upgrade.php</code> '.__('file is accessible via HTTP on the default location.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

    $return = array();
    $url = get_bloginfo('wpurl') . '/wp-admin/upgrade.php?rnd=' . rand();
    $response = wp_remote_get($url);

    if(is_wp_error($response)) {
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
    } elseif ($response['response']['code'] == 200) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

// check if register_globals is off
   function register_globals_check() {
   $msgTIT = 'PHP (<em>register_globals</em>): '.__('Check if directive is turned off.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

   $return = array();

    $check = (bool) ini_get('register_globals');
    if ($check) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
       $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
   }

   // check if safe mode is off
   function safe_mode_check() {
   $msgTIT = 'PHP (safe mode): '.__('Check if is disabled.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

  $return = array();

    $check = (bool) ini_get('safe_mode');
    if ($check) {
      $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
       $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
   }

   // check if allow_url_include is off
   function allow_url_include_check() {
   $msgTIT = 'PHP (<em>allow_url_include</em>): '.__('Check if directive is turned off.', $this->hook);
   $msgOK = __('Yes', $this->hook);
   $msgNO = __('No', $this->hook);

   $return = array();

    $check = (bool) ini_get('allow_url_include');
    if ($check) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
   }

   // is theme/plugin editor disabled?
   function file_editor() {
   $msgTIT = __('Check if plugins/themes file editor is enabled.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

   $return = array();

    if (defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT) {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    } else {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
    }

    return $return;
   }

// check if user with DB ID 1 exists
   function id1_user_check() {
   $msgTIT = __('Test if user with ID "1" exists.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);

     $return = array();

     $check = get_userdata(1);
     if ($check) {
       $return['status'] = 0;
       $return['msg'] = $msgTIT . ' (<span class="fdx-error">' . $msgNO . '</span>)';
     } else {
       $return['status'] = 10;
       $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
     }

     return $return;
   }


   // check if the WP MySQL user can connect from an external host
   function mysql_external() {
       $msgTIT = __('Check if you can only connect to the MySQL from localhost.', $this->hook);
       $msgOK = __('Yes', $this->hook);
       $msgWA = __('Warning', $this->hook);

     $return = array();
     global $wpdb;

     $check = $wpdb->get_var('SELECT CURRENT_USER()');
     if (strpos($check, '@127.0.0.1') !== false || stripos($check, '@localhost') !== false) {
       $return['status'] = 10;
       $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
     } else {
       $return['status'] = 5;
       $return['msg'] = $check;
       $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
     }

     return $return;
   } // mysql_external




// uploads_browsable
 function uploads_browsable() {
  $msgTIT = __('Check if <em>uploads</em> folder is browsable by browsers.', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Unable', $this->hook);

    $return = array();
    $upload_dir = wp_upload_dir();

    $args = array('method' => 'GET', 'timeout' => 5, 'redirection' => 0,
                  'httpversion' => 1.0, 'blocking' => true, 'headers' => array(), 'body' => null, 'cookies' => array());
    $response = wp_remote_get(rtrim($upload_dir['baseurl'], '/') . '/?nocache=' . rand(), $args);

    if (is_wp_error($response)) {
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<code><a href="'.$upload_dir['baseurl'].'/" target="_blank">'.$upload_dir['baseurl'] . '/</a></code> <span class="fdx-warning">'. $msgWA . '</span>)';

    } elseif ($response['response']['code'] == '200' && stripos($response['body'], 'index') !== false) {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<code><a href="'.$upload_dir['baseurl'].'/" target="_blank">'.$upload_dir['baseurl'] . '/</a></code> <span class="fdx-error">' . $msgNO . '</span>)';
     } else {
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    }

    return $return;
  }

//bruteforce user login

 function try_login($username, $password) {
    $user = apply_filters('authenticate', null, $username, $password);

    if (isset($user->ID) && !empty($user->ID)) {
      return true;
    } else {
      return false;
    }
  }

function bruteforce_login() {
   $settings = FDX_Process::fdx_get_settings();

   $msgTIT = __('Brute-force attack: Check admin password strength with a <em>600 most commonly used</em>', $this->hook);
   $msgOK = __('No', $this->hook);
   $msgNO = __('Yes', $this->hook);
   $msgWA = __('Disabled.', $this->hook);

    $return = array();
    $passwords = file(plugins_url( 'libs/brute-force-dictionary.txt', dirname(__FILE__)), FILE_IGNORE_NEW_LINES);
    $bad_usernames = array();

    if ( !$settings['p2_check_1'] ) {
      $return['status'] = 5;
      $return['msg'] = $msgTIT . ' (<span class="fdx-warning">'. $msgWA . '</span>)';
      return $return;
    }

    $users = get_users(array('role' => 'administrator'));

    foreach ($users as $user) {
      foreach ($passwords as $password) {
        if (self::try_login($user->user_login, $password)) {
          $bad_usernames[] = $user->user_login;
          break;
        }
      } // foreach $passwords
    } // foreach $users

    if (empty($bad_usernames)){
      $return['status'] = 10;
      $return['msg'] = $msgTIT . ' (<span class="fdx-success">'. $msgOK . '</span>)';
    } else {
      $return['status'] = 0;
      $return['msg'] = $msgTIT . ' (<code>'.implode(', ', $bad_usernames).'</code> <span class="fdx-error">'. $msgNO . '</span> )';
    }

    return $return;
  } // bruteforce_login


// brute force attack on password
  function dictionary_attack($password) {
    $dictionary = file(plugins_url( 'libs/brute-force-dictionary.txt', dirname(__FILE__)), FILE_IGNORE_NEW_LINES);

    if (in_array($password, $dictionary)) {
      return true;
    } else {
      return false;
    }
  }


} // class fdx_tests
?>