<?php
 if (!function_exists('add_action')) {
  die('Please don\'t open this file directly!');
}

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

function getConfig() {
if ( file_exists( trailingslashit( ABSPATH ) . 'wp-config.php' ) ) {
return trailingslashit( ABSPATH ) . 'wp-config.php';
} else {
return trailingslashit( dirname( ABSPATH ) ) . 'wp-config.php';
}
}

### Function: Format Bytes Into KB/MB

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


?>
<div class="wrap"><?php echo get_screen_icon('fdx-lock');?>
<h2><?php echo FDX2_PLUGIN_NAME;?>: <?php _e('System Information', 'fdx-lang') ?></h2>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">

<?php include('_sidebar.php'); ?>

<div class="postbox-container">
<div class="meta-box-sortables">


<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('System Information', 'fdx-lang'); ?></span></h3>
<div class="inside">
     <br />

    <?php
 echo '<table style="width:100%;" class="widefat">';
	echo '<thead><tr>';
	echo '<th style="width:25%;text-align: right">'. __('File/Dir', 'fdx-lang') .'</th>';
    echo '<th style="width:25%;text-align: center">&nbsp;</th>';
    echo '<th style="width:25%;text-align: center">'. __('Recommended Chmod', 'fdx-lang') .'<a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="margin-left:5px" /></a></th>';
    echo '<th style="width:25%;text-align: center">'. __('Current Chmod', 'fdx-lang') .'</th>';
	echo '</tr></thead>';
    fdx_check_perms(__('root', 'fdx-lang'),"../","755");
    fdx_check_perms("wp-admin/","../wp-admin","755");
	fdx_check_perms("wp-includes/","../wp-includes","755");
	fdx_check_perms("wp-content/","../wp-content","755");
	fdx_check_perms(".htaccess","../.htaccess","444");
	fdx_check_perms("wp-config.php","../wp-config.php","400");
	fdx_check_perms("index.php","../index.php","644");
	fdx_check_perms("wp-blog-header.php","../wp-blog-header.php","644");

    fdx_check_perms("wp-admin/index.php","../wp-admin/index.php","644");
    ?>
     </table>

    <br />
  <table style="width:100%;" class="widefat">
 <thead><tr><th>File System</th> </tr></thead>
<tbody><tr><td>
<ul class="fdxlist">
 <?php
	echo '<li>Website Root Folder: <strong>'.get_site_url().'</strong></li>';
	echo '<li>Document Root Path: <strong>'.esc_html($_SERVER['DOCUMENT_ROOT']).'</strong></li>';
	echo '<li>WP ABSPATH: <strong>'.ABSPATH.'</strong></li>';
    echo '<li>Parent Directory: <strong>'.dirname(ABSPATH).'</strong></li>';
$conffile = getConfig();
if ( $f = @fopen( $conffile, 'a' ) ) {
@fclose( $f );
$copen = '<font color="red">';
$cclose = '</font>';
$wconf = __( 'writable', 'fdx-lang' );
} else {
$copen = '<font color="green">';
$cclose = '</font>';
$wconf = __( 'NO writable', 'fdx-lang' );
}
?>
<li><em>wp-config.php</em>: <strong><?php echo $conffile; ?></strong> <strong><?php echo $copen . $wconf . $cclose; ?></strong> </li>
<?php
$htaccess = ABSPATH . '.htaccess';
if ( $f = @fopen( $htaccess, 'a' ) ) {
@fclose( $f );
$copen = '<font color="red">';
$cclose = '</font>';
$htaw = __( 'writable', 'fdx-lang' );
} else {
$copen = '<font color="green">';
$cclose = '</font>';
$htaw = __( 'NO writable', 'fdx-lang' );
}
?>
<li><em>.htaccess</em>: <strong><?php echo $copen . $htaw . $cclose; ?></strong></li>
</ul>
</td>
</tr></tbody></table>
<br />





<table style="width:100%;" class="widefat">
 <thead><tr><th>Server</th> </tr></thead>
<tbody><tr><td>
<ul class="fdxlist">
<?php

	echo '<li>Server / Website IP Address: <strong>'.esc_html($_SERVER['SERVER_ADDR']).'</strong></li>';
	echo '<li>Host by Address: <strong>'.esc_html(gethostbyaddr($_SERVER['SERVER_ADDR'])).'</strong></li>';
	echo '<li>Public IP / Your Computer IP Address: <strong>'.esc_html($_SERVER['REMOTE_ADDR']).'</strong></li>';
	echo '<li>Server Type: <strong>'.esc_html($_SERVER['SERVER_SOFTWARE']).'</strong></li>';
	echo '<li>OS: <strong>'.PHP_OS.'</strong></li>';
	echo '<li>Server API: <strong>';
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == 'cgi' || substr($sapi_type, 0, 9) == 'litespeed' || substr($sapi_type, 0, 7) == 'caudium' || substr($sapi_type, 0, 8) == 'webjames' || substr($sapi_type, 0, 3) == 'tux' || substr($sapi_type, 0, 5) == 'roxen' || substr($sapi_type, 0, 6) == 'thttpd' || substr($sapi_type, 0, 6) == 'phttpd' || substr($sapi_type, 0, 10) == 'continuity' || substr($sapi_type, 0, 6) == 'pi3web' || substr($sapi_type, 0, 6) == 'milter') {
    echo $sapi_type;
	} else {
    echo $sapi_type;
	}
	echo '</strong></li>';
	echo '<li>Memcache: <strong>';
	if (extension_loaded('memcache')) {
	$memcache = new Memcache;
	@$memcache->connect('localhost', 11211);
	echo '<li>'.__('is Loaded', 'fdx-lang').__('Version: ', 'fdx-lang').@$memcache->getVersion();
	} else {
		_e('is NOT Loaded', 'fdx-lang');
	}
	echo '</strong></li>';
	echo '<li>Memcached: <strong>';
	if (extension_loaded('memcached')) {
	$memcached = new Memcached();
	@$memcached->addServer('localhost', 11211);
	echo '<li>'.__('is Loaded', 'fdx-lang').__('Version: ', 'fdx-lang').@$memcached->getVersion();
	} else {
		_e('is NOT Loaded', 'fdx-lang');
	}
	echo '</strong></li>';
    echo '<li>Browser Compression Supported: <strong>'.esc_html($_SERVER['HTTP_ACCEPT_ENCODING']).'</strong></li>';

	echo '<li>MySQL Database Version: ';
    global $wpdb;
    $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
	echo '<strong>'.$sqlversion.'</strong></li>';
	echo '<li>MySQL Client Version: <strong>'.mysql_get_client_info().'</strong></li>';
	echo '<li>Database Host: <strong>'.DB_HOST.'</strong></li>';
	echo '<li>Database Name: <strong>'.DB_NAME.'</strong></li>';
	echo '<li>Database User: <strong>'.DB_USER.'</strong></li>';
	echo '<li>SQL Mode: ';
	$mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
	if (is_array($mysqlinfo)) {
	$sql_mode = $mysqlinfo[0]->Value;
    if (empty($sql_mode)) {
	$sql_mode = '<strong>'.__('Not Set', 'fdx-lang').'</strong>';
	} else {
	$sql_mode = '<strong>'.__('Off', 'fdx-lang').'</strong>';
	}}
	echo $sql_mode;
	echo '<a href="http://fabrix.net/total-security/msys/#sql_mode" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	?>
     </ul>
             </td>
      </tr> </tbody> </table>

<br />
<table style="width:100%;" class="widefat">
 <thead><tr><th>PHP</th> </tr></thead>
<tbody><tr><td>
<?php
$memory_limit = ini_get('memory_limit');
$upload_max = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size')
?>
  <ul class="fdxlist"><?php
	echo '<li>PHP Version: <strong>'.PHP_VERSION.'</strong></li>';
	echo '<li>PHP Memory Usage: <strong>'.round(memory_get_usage() / 1024 / 1024, 2) . ' MB</strong></li>';
	echo '<li>PHP Memory Limit: <strong>'.$memory_limit.'</strong><a href="http://fabrix.net/total-security/msys/#php_memory_limit" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Base Memory Limit: <strong>'.WP_MEMORY_LIMIT.'</strong></li>';
	echo '<li>PHP Actual Configuration Memory Limit: <strong>'.get_cfg_var('memory_limit').'</strong></li>';
	echo '<li>PHP Max Upload Size: <strong>'.$upload_max.'</strong><a href="http://fabrix.net/total-security/msys/#php_max_upload_size" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Max Post Size: <strong>'.$post_max.'</strong><a href="http://fabrix.net/total-security/msys/#php_max_post_size" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Safe Mode: ';
	if (ini_get('safe_mode') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text;
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text;
	}
	echo '<a href="http://fabrix.net/total-security/msys/#php_safe_mode" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li><li>PHP Allow URL fopen: ';
	if (ini_get('allow_url_fopen') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text;
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text;
	}
	echo '<a href="http://fabrix.net/total-security/msys/#php_allow_url_fopen" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li><li>PHP Allow URL Include: ';
	if (ini_get('allow_url_include') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Display Errors: ';
	if (ini_get('display_errors') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Display Startup Errors: ';
	if (ini_get('display_startup_errors') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Expose PHP: ';
	if (ini_get('expose_php') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Register Globals: ';
	if (ini_get('register_globals') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP MySQL Allow Persistent Connections: ';
	if (ini_get('mysql.allow_persistent') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP Output Buffering: ';
	$output_buffering = ini_get('output_buffering');
	if (ini_get('output_buffering') != 0) {
	echo '<font color="red"><strong>'.$output_buffering.'</strong></font></li>';
	} else {
	echo '<font color="green"><strong>'.$output_buffering.'</strong></font></li>';
	}
	echo '<li>PHP Max Script Execution Time: '; $max_execute = ini_get('max_execution_time');
	echo '<strong>'.$max_execute.' Seconds</strong><a href="http://fabrix.net/total-security/msys/#php_max_script_execute_time" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Magic Quotes GPC: ';
	if (ini_get('magic_quotes_gpc') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'</li>';
	}
	echo '<li>PHP open_basedir: ';
	$open_basedir = ini_get('open_basedir');
	if ($open_basedir != '') {
	echo '<strong>'.$open_basedir.'</strong></li>';
	} else {
	echo '<strong>'.__('not in use', 'fdx-lang').'</strong></li>';
	}
	echo '<li>PHP XML Support: ';
	if (is_callable('xml_parser_create')) {
	$text = '<strong>'.__('Yes', 'fdx-lang').'</strong></font>';
	echo $text;
	} else {
	$text = '<strong>'.__('No', 'fdx-lang').'</strong></font>';
	echo $text;
	}
    echo '<a href="http://fabrix.net/total-security/msys/#php_xml_support" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP IPTC Support: ';
	if (is_callable('iptcparse')) {
	$text = '<strong>'.__('Yes', 'fdx-lang').'</strong></font>';
	echo $text;
	} else {
	$text = '<strong>'.__('No', 'fdx-lang').'</strong></font>';
	echo $text;
	}
      echo '<a href="http://fabrix.net/total-security/msys/#php_iptc_support" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';
	echo '<li>PHP Exif Support: ';
	if (is_callable('exif_read_data')) {
	$text = '<strong>'.__('Yes', 'fdx-lang').' (v' . substr(phpversion('exif'),0,4) . ')</strong></font>';
	echo $text;
	} else {
	$text = '<strong>'.__('No', 'fdx-lang').'</strong></font>';
	echo $text;
    }
     echo '<a href="http://fabrix.net/total-security/msys/#php_exif_support" target="_blank" title="'.__('Information', 'fdx-lang').'"><img src="'.FDX2_PLUGIN_URL.'/images/info.png" width="16" height="16" border="0"  alt="'.__('Information', 'fdx-lang').'" style="vertical-align: middle; margin-left:5px" /></a></li>';

	?>

    </ul>
       </td>
      </tr> </tbody> </table>

</div>
</div>

<!-- ######################################################### -->

 <div class="postbox closed">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Database: Tables Information', 'fdx-lang'); ?></span></h3>
<div class="inside">
          <br />
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('No.', 'fdx-lang'); ?></th>
				<th><?php _e('Tables', 'fdx-lang'); ?></th>
				<th><?php _e('Records', 'fdx-lang'); ?></th>
				<th><?php _e('Data Usage', 'fdx-lang'); ?></th>
				<th><?php _e('Index Usage', 'fdx-lang'); ?></th>
				<th><?php _e('Overhead', 'fdx-lang'); ?></th>
			</tr>
		</thead>
<?php
// If MYSQL Version More Than 3.23, Get More Info
$sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
if($sqlversion >= '3.23') {
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
	echo '<th>'.sprintf(_n('%s Table', '%s Tables', $no, 'fdx-lang'), number_format_i18n($no)).'</th>'."\n";
	echo '<th>'.sprintf(_n('%s Record', '%s Records', $row_usage, 'fdx-lang'), number_format_i18n($row_usage)).'</th>'."\n";
	echo '<th>'.fdx_format_size($data_usage).'</th>'."\n";
	echo '<th>'.fdx_format_size($index_usage).'</th>'."\n";
	echo '<th>'.fdx_format_size($overhead_usage).'</th>'."\n";
	echo '</tr>';
} else {
	echo '<tr><td colspan="6" align="center"><strong>'.__('Could Not Show Table Status Due To Your MYSQL Version Is Lower Than 3.23.', 'fdx-lang').'</strong></td></tr>';
}
?>
	</table>

 </div>
</div>

<!-- ################################################################# -->


<div class="postbox closed">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Debug Information', 'fdx-lang'); ?></span></h3>
<div class="inside">
<p align="center"><?php _e('Debug information is used to provide help. You should include this information in your posts on support forum. ', 'fdx-lang') ?> </p>
 <form name="test">
<p align="center"><a class="button" href="javascript:selectcopy('test.select1')"><?php _e('Select All', 'fdx-lang') ?></a></p>
 <div align="center">
<textarea style="width:90%; height:200px;" name="select1">
OS: <?php echo PHP_OS; ?>

PHP: <?php echo phpversion(); ?>

WP: <?php  global $wp_version; echo $wp_version; ?>

Active Theme: <?php $theme = get_theme(get_current_theme()); echo $theme['Name'].' '.$theme['Version']; ?>

URLOpen Method: <?php echo fdx_url_method(); ?>


---------Plugins---------
<?php
foreach (get_plugins() as $key => $plugin) {
    $isactive = "";
    if (is_plugin_active($key)) {
        $isactive = "(active)";
    }
    echo $plugin['Name'].' '.$plugin['Version'].' '.$isactive."\n";
}
?>

---------MD5 hashes---------
#1: <?php echo md5_file( FDX2_PLUGIN_URL. 'total-security.php'); ?>

#2: <?php echo md5_file( FDX2_PLUGIN_URL. 'libs/hashes-'. $wp_version .'.php'); ?>

#3: <?php echo md5_file( FDX2_PLUGIN_URL. 'admin/core_exploit_scanner.php'); ?>

#4: <?php echo md5_file( FDX2_PLUGIN_URL. 'admin/unsafe_files.php'); ?>

#5: <?php echo md5_file( FDX2_PLUGIN_URL. 'admin/vulnerability_scan.php'); ?>

</textarea>
</div>
   </form>
 </div>
</div>



</div> <!-- /postbox-container -->
</div><!-- /meta-box-sortables -->



</div><!-- /post-body -->
</div><!-- /poststuff -->


</div><!-- /wrap -->



<?php include('_footer_js.php'); ?>




<div class="clear"></div>
<?php

?>