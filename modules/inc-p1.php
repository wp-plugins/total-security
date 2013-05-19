<?php
    function fdx_check_perms($name,$path,$perm) {
	clearstatcache();
	$current_perms = @substr(sprintf(".%o.", fileperms($path)), -4);
	echo '<tbody><tr>';
    echo '<td style="width:25%;">' . $name . '</td>';
    echo '<td style="width:25%;">' . $path . '</td>';
    echo '<td style="width:25%;text-align: center">' . $perm . '</td>';
    if ( $perm == $current_perms ) { echo '<td style="width:25%;text-align: center; color: #008000">';  } else { echo '<td style="width:25%;text-align: center; color: #FF0000;font-weight: bold;">';}
    if ($current_perms == '.0.') { echo '-----'; } else { echo $current_perms; };
    echo '</td></tr></tbody>';
}

function fdx_url_method() {
	if(function_exists('curl_init')) {
		return 'curl';
	} else if(ini_get('allow_url_fopen') && function_exists('stream_get_contents')) {
		return 'fopen';
	} else {
		return 'fsockopen';
	}
}

function fdx_format_size($rawSize) {
		if($rawSize / 1073741824 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' GB';
		else if ($rawSize / 1048576 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' MB';
		else if ($rawSize / 1024 > 1)
			return number_format_i18n($rawSize/1024, 1) . ' KB';
		else
			return number_format_i18n($rawSize, 0) . ' bytes';
	}

/* wrap
*********************************************************************************/
echo '<div class="wrap">'. screen_icon('options-general');
echo '<h2>'. $this->pluginname . ' : ' . __('Dashboard', $this->hook) . '</h2>';

/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables">';

//------------postbox 1
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('File Permissions', $this->hook) . '</span></h3>';
echo '<div class="inside">';
//-----------------------------------------

    echo '<br />';
    echo '<table style="width:100%;" class="widefat">';
	echo '<thead><tr>';
	echo '<th style="width:25%;text-align: right">'. __('File/Dir', $this->hook ) .'</th>';
    echo '<th style="width:25%;text-align: center">&nbsp;</th>';
    echo '<th style="width:25%;text-align: center">'. __('Recommended Chmod', $this->hook ) .'</th>';
    echo '<th style="width:25%;text-align: center">'. __('Current Chmod', $this->hook ) .'</th>';
	echo '</tr></thead>';
    fdx_check_perms(__('root', $this->hook),"../","755");
    fdx_check_perms("wp-admin/","../wp-admin","755");
	fdx_check_perms("wp-includes/","../wp-includes","755");
	fdx_check_perms("wp-content/","../wp-content","755");
	fdx_check_perms(".htaccess","../.htaccess","444");
	fdx_check_perms("wp-config.php","../wp-config.php","400");
    fdx_check_perms("index.php","../index.php","644");
	fdx_check_perms("wp-blog-header.php","../wp-blog-header.php","644");
    fdx_check_perms("wp-admin/index.php","../wp-admin/index.php","644");
    echo '</table>';

//--------------------
echo '<div class="clear"></div></div></div>';

//------------postbox 2
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('System Information', $this->hook) . '</span></h3>';
echo '<div class="inside">';
//-----------------------------------------

    echo '<div class="fdx-left-content">';
    echo '<strong>PHP</strong>'; //--------------------------------------
    $memory_limit = ini_get('memory_limit');
    $upload_max = ini_get('upload_max_filesize');
    $post_max = ini_get('post_max_size');
    echo '<ul class="fdxlist">';
	echo '<li>PHP Version: <code>'.PHP_VERSION.'</code></li>';
	echo '<li>PHP Memory Usage: <code>'.round(memory_get_usage() / 1024 / 1024, 2) . ' MB</code></li>';
	echo '<li>PHP Memory Limit: <code>'.$memory_limit.'</code><a href="http://fabrix.net/total-security/msys/#php_memory_limit" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Base Memory Limit: <code>'.WP_MEMORY_LIMIT.'</code></li>';
	echo '<li>PHP Actual Configuration Memory Limit: <code>'.get_cfg_var('memory_limit').'</code></li>';
	echo '<li>PHP Max Upload Size: <code>'.$upload_max.'</code><a href="http://fabrix.net/total-security/msys/#php_max_upload_size" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Max Post Size: <code>'.$post_max.'</code><a href="http://fabrix.net/total-security/msys/#php_max_post_size" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Safe Mode: ';
	if (ini_get('safe_mode') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text;
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text;
	}
	echo '<a href="http://fabrix.net/total-security/msys/#php_safe_mode" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li><li>PHP Allow URL fopen: ';
	if (ini_get('allow_url_fopen') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text;
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text;
	}
	echo '<a href="http://fabrix.net/total-security/msys/#php_allow_url_fopen" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li><li>PHP Allow URL Include: ';
	if (ini_get('allow_url_include') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Display Errors: ';
	if (ini_get('display_errors') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Display Startup Errors: ';
	if (ini_get('display_startup_errors') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Expose PHP: ';
	if (ini_get('expose_php') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Register Globals: ';
	if (ini_get('register_globals') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP MySQL Allow Persistent Connections: ';
	if (ini_get('mysql.allow_persistent') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Output Buffering: ';
	$output_buffering = ini_get('output_buffering');
	if (ini_get('output_buffering') != 0) {
	echo '<font color="red"><code>'.$output_buffering.'</code></font></li>';
	} else {
	echo '<font color="green"><code>'.$output_buffering.'</code></font></li>';
	}
	echo '<li>PHP Max Script Execution Time: '; $max_execute = ini_get('max_execution_time');
	echo '<code>'.$max_execute.' Seconds</code><a href="http://fabrix.net/total-security/msys/#php_max_script_execute_time" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Magic Quotes GPC: ';
	if (ini_get('magic_quotes_gpc') == 1) {
	$text = '<font color="red"><code>'.__('On', $this->hook).'</code></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><code>'.__('Off', $this->hook).'</code></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP open_basedir: ';
	$open_basedir = ini_get('open_basedir');
	if ($open_basedir != '') {
	echo '<code>'.$open_basedir.'</code></li>';
	} else {
	echo '<code>'.__('not in use', $this->hook).'</code></li>';
	}
	echo '<li>PHP XML Support: ';
	if (is_callable('xml_parser_create')) {
	$text = '<code>'.__('Yes', $this->hook).'</code></font>';
	echo $text;
	} else {
	$text = '<code>'.__('No', $this->hook).'</code></font>';
	echo $text;
	}
    echo '<a href="http://fabrix.net/total-security/msys/#php_xml_support" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP IPTC Support: ';
	if (is_callable('iptcparse')) {
	$text = '<code>'.__('Yes', $this->hook).'</code></font>';
	echo $text;
	} else {
	$text = '<code>'.__('No', $this->hook).'</code></font>';
	echo $text;
	}
      echo '<a href="http://fabrix.net/total-security/msys/#php_iptc_support" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Exif Support: ';
	if (is_callable('exif_read_data')) {
	$text = '<code>'.__('Yes', $this->hook).' (v' . substr(phpversion('exif'),0,4) . ')</code></font>';
	echo $text;
	} else {
	$text = '<code>'.__('No', $this->hook).'</code></font>';
	echo $text;
    }
     echo '<a href="http://fabrix.net/total-security/msys/#php_exif_support" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
     echo '</ul></div><div class="fdx-right-content">';

    echo '<strong>FILE SYSTEM</strong><ul class="fdxlist">';
    echo '<li>Website Root Folder: <code>'.get_site_url().'</code></li>';
	echo '<li>Document Root Path: <code>'.esc_html($_SERVER['DOCUMENT_ROOT']).'</code></li>';
	echo '<li>WP ABSPATH: <code>'.ABSPATH.'</code></li>';
    echo '<li>Parent Directory: <code>'.dirname(ABSPATH).'</code></li></ul>';
    echo '<br/><strong>SERVER</strong>'; //--------------------------------------
	echo '<ul class="fdxlist"><li>Server / Website IP Address: <code>'.esc_html($_SERVER['SERVER_ADDR']).'</code></li>';
	echo '<li>Host by Address: <code>'.esc_html(gethostbyaddr($_SERVER['SERVER_ADDR'])).'</code></li>';
	echo '<li>Public IP / Your Computer IP Address: <code>'.esc_html($_SERVER['REMOTE_ADDR']).'</code></li>';
	echo '<li>Server Type: <code>'.esc_html($_SERVER['SERVER_SOFTWARE']).'</code></li>';
	echo '<li>OS: <code>'.PHP_OS.'</code></li>';
	echo '<li>Server API: <code>';
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == 'cgi' || substr($sapi_type, 0, 9) == 'litespeed' || substr($sapi_type, 0, 7) == 'caudium' || substr($sapi_type, 0, 8) == 'webjames' || substr($sapi_type, 0, 3) == 'tux' || substr($sapi_type, 0, 5) == 'roxen' || substr($sapi_type, 0, 6) == 'thttpd' || substr($sapi_type, 0, 6) == 'phttpd' || substr($sapi_type, 0, 10) == 'continuity' || substr($sapi_type, 0, 6) == 'pi3web' || substr($sapi_type, 0, 6) == 'milter') {
    echo $sapi_type;
	} else {
    echo $sapi_type;
	}
	echo '</code></li>';
	echo '<li>Memcache: <code>';
	if (extension_loaded('memcache')) {
	$memcache = new Memcache;
	@$memcache->connect('localhost', 11211);
	echo '<li>'.__('is Loaded', $this->hook).__('Version: ', $this->hook).@$memcache->getVersion();
	} else {
		_e('is NOT Loaded', $this->hook);
	}
	echo '</code></li>';
	echo '<li>Memcached: <code>';
	if (extension_loaded('memcached')) {
	$memcached = new Memcached();
	@$memcached->addServer('localhost', 11211);
	echo '<li>'.__('is Loaded', $this->hook).__('Version: ', $this->hook).@$memcached->getVersion();
	} else {
		_e('is NOT Loaded', $this->hook);
	}
	echo '</code></li>';
    echo '<li>Browser Compression Supported: <code>'.esc_html($_SERVER['HTTP_ACCEPT_ENCODING']).'</code></li>';

	echo '<li>MySQL Database Version: ';
    global $wpdb;
    $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
	echo '<code>'.$sqlversion.'</code></li>';
	echo '<li>MySQL Client Version: <code>'.mysql_get_client_info().'</code></li>';
	echo '<li>Database Host: <code>'.DB_HOST.'</code></li>';
	echo '<li>Database Name: <code>'.DB_NAME.'</code></li>';
	echo '<li>Database User: <code>'.DB_USER.'</code></li>';
	echo '<li>SQL Mode: ';
	$mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
	if (is_array($mysqlinfo)) {
	$sql_mode = $mysqlinfo[0]->Value;
    if (empty($sql_mode)) {
	$sql_mode = '<code>'.__('Not Set', $this->hook).'</code>';
	} else {
	$sql_mode = '<code>'.__('Off', $this->hook).'</code>';
	}}
	echo $sql_mode;
	echo '<a href="http://fabrix.net/total-security/msys/#sql_mode" target="_blank" title="'.__('Information', $this->hook).'"><img src="'.plugins_url( 'images/h3_icons/info.png', dirname(__FILE__)).'" width="16" height="16" border="0"  alt="'.__('Information', $this->hook).'" style="vertical-align: middle; margin-left:5px" /></a></li>';
    echo ' </ul></div>';

//--------------------
echo '<div class="clear"></div></div></div>';

//------------postbox 3
echo '<div class="postbox closed">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Database: Tables Information', $this->hook) . '</span></h3>';
echo '<div class="inside">';
//-----------------------------------------

   global $wpdb;
        echo '<br />'; //--------------------------------------
     	echo '<table class="widefat"><thead><tr>';
    	echo '<th>N&deg;</th><th>Tables</th><th>Records</th><th>Data Usage</th><th>Index Usage</th><th>Overhead</th>';
    	echo '</tr></thead>';
    	$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
     	foreach($tablesstatus as  $tablestatus) {
    	if(@$no%2 == 0) {
			$style = '';
		} else {
			$style = ' class="alternate"';
		}
		@$no++;
		echo "<tr$style>\n";
		echo '<td>'.number_format_i18n($no).'</td>'."\n";
		echo "<td>$tablestatus->Name</td>\n";
		echo '<td>'.number_format_i18n($tablestatus->Rows).'</td>'."\n";
		echo '<td>'.fdx_format_size($tablestatus->Data_length).'</td>'."\n";
		echo '<td>'.fdx_format_size($tablestatus->Index_length).'</td>'."\n";;
		echo '<td>'.fdx_format_size($tablestatus->Data_free).'</td>'."\n";
		@$row_usage += $tablestatus->Rows;
		@$data_usage += $tablestatus->Data_length;
		@$index_usage +=  $tablestatus->Index_length;
		@$overhead_usage += $tablestatus->Data_free;
		echo '</tr>'."\n";
	}
	echo '<tr class="thead">'."\n";
	echo '<th>&nbsp;</th>'."\n";
	echo '<th>&nbsp;</th>'."\n";
	echo '<th>'.number_format_i18n($row_usage).'</th>'."\n";
	echo '<th>'.fdx_format_size($data_usage).'</th>'."\n";
	echo '<th>'.fdx_format_size($index_usage).'</th>'."\n";
	echo '<th>'.fdx_format_size($overhead_usage).'</th>'."\n";
	echo '</tr></table>';

//--------------------
echo '<div class="clear"></div></div></div>';

//------------postbox 4
echo '<div class="postbox closed">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Debug Information', $this->hook) . '</span></h3>';
echo '<div class="inside">';
//-----------------------------------------

global $wp_version;
echo '<p align="center">'.__('Debug information is used to provide help. You should include this information in your posts on support forum.' , $this->hook). '</p>';
echo '<form name="test"><p align="center"><a class="button" href="javascript:selectcopy(\'test.select1\')">'. __('Select All', $this->hook).' </a></p>';
echo '<div align="center"><textarea style="width:90%; height:200px;" name="select1">';
echo 'User Agent =  ' . esc_html($_SERVER['HTTP_USER_AGENT']) . "\n";
echo 'Server Software = ' . esc_html($_SERVER['SERVER_SOFTWARE']) . "\n";
echo 'PHP = ' .  phpversion() . "\n";
echo 'URLOpen Method = ' .  fdx_url_method() . "\n";
echo '------------------------------------------------------------'. "\n";
echo 'WP =  ' .$wp_version . "\n";
echo 'Language = ' . get_bloginfo('language'). "\n";
echo 'Charset = ' .  get_bloginfo('charset'). "\n";
echo 'Active Theme = ' . $theme = wp_get_theme();  $theme['Name'] . $theme['Version'] . "\n";
echo "\n". '------------------------------------------------------------'. "\n";
foreach (get_plugins() as $key => $plugin) {
    $isactive = "";
    if (is_plugin_active($key)) {
        $isactive = "(active)";
    }
    echo $plugin['Name'].' '.$plugin['Version'].' '.$isactive."\n";
}
echo '</textarea></div></form>';

//--------------------
echo '<div class="clear"></div></div></div>';
//--------------------


//------------ meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//----------------------------------------- ?>
