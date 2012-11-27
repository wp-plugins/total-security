<?php
function fdx_check_perms($name,$path,$perm) {
	clearstatcache();
	$current_perms = @substr(sprintf(".%o.", fileperms($path)), -4);
	echo '<table style="width:100%;font-size: 11px;>';
	echo '<tr style="background-color: #808080">';
    echo '<td style="background-color:#fff;padding:2px;width:25%;">' . $name . '</td>';
    echo '<td style="background-color:#fff;padding:2px;width:25%;">' . $path . '</td>';
    echo '<td style="background-color:#fff;padding:2px;width:25%;text-align: center">' . $perm . '</td>';
    echo '<td style="background-color:#fff;padding:2px;width:25%;text-align: center">' . $current_perms . '</td>';
    echo '</tr>';
	echo '</table>';
}

// Get WordPress Root Installation Folder - Borrowed from WP Core
function fdx_wp_get_root_folder() {
$site_root = parse_url(get_option('siteurl'));
	if ( isset( $site_root['path'] ) )
	$site_root = trailingslashit($site_root['path']);
	else
	$site_root = '/';
	return $site_root;
}

// Display Root or Subfolder Installation Type
function fdx_wp_get_root_folder_display_type() {
$site_root = parse_url(get_option('siteurl'));
	if ( isset( $site_root['path'] ) )
	$site_root = trailingslashit($site_root['path']);
	else
	$site_root = '/';
	if (preg_match('/[a-zA-Z0-9]/', $site_root)) {
	_e('Subfolder Installation', 'fdx-lang');
	} else {
	_e('Root Folder Installation', 'fdx-lang');
	}
}

// Check for Multisite
function fdx_multsite_check() {
	if ( is_multisite() ) {
	$text = __('Multisite: ', 'fdx-lang').'<strong>'.__('Multisite is enabled', 'fdx-lang').'</strong><br>';
	echo $text;
	} else {
	$text = __('Multisite: ', 'fdx-lang').'<strong>'.__('Multisite is Not enabled', 'fdx-lang').'</strong><br>';
	echo $text;
	}
}

// Check if Permalinks are enabled
$permalink_structure = get_option('permalink_structure');
function fdx_check_permalinks() {
	if ( get_option('permalink_structure') != '' ) {
	$text = __('Permalinks Enabled: ', 'fdx-lang').'<font color="green"><strong>&radic; '.__('Permalinks are Enabled', 'fdx-lang').'</strong></font>';
	echo $text;
	} else {
	$text = __('Permalinks Enabled: ', 'fdx-lang').'<font color="red"><strong>'.__('WARNING! Permalinks are NOT Enabled', 'fdx-lang').'<br>'.__('Permalinks MUST be enabled for BPS to function correctly', 'fdx-lang').'</strong></font>';
	echo $text;
	}
}

// Check PHP version
function fdx_check_php_version() {
	if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
    $text = __('PHP Version Check: ', 'fdx-lang').'<font color="green"><strong>&radic; '.__('Using PHP5', 'fdx-lang').'</strong></font><br>';
	echo $text;
}
	if (version_compare(PHP_VERSION, '5.0.0', '<')) {
    $text = '<font color="red"><strong>'.__('WARNING! BPS requires PHP5 to function correctly. Your PHP version is: ', 'fdx-lang').PHP_VERSION.'</strong></font><br>';
	echo $text;
	}
}
?>
<div class="wrap"><?php echo get_screen_icon('sn-lock');?>
<h2><?php echo FDX2_PLUGIN_NAME;?>: <?php _e('System Information', 'fdx-lang') ?></h2>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">

<?php include('_sidebar.php'); ?>

<div class="postbox-container">
<div class="meta-box-sortables">


<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Directory Info', 'fdx-lang'); ?></span></h3>
<div class="inside">
<!-- ############################################################################################################### -->
    <?php
 echo '<table style="width:100%;font-size: 11px;font-weight: bold">';
	echo '<tr>';
	echo '<td style="padding:2px;width:25%">File Name / Folder Name</td>';
    echo '<td style="padding:2px;width:25%">File Path / Folder Path</td>';
    echo '<td style="padding:2px;width:25%;text-align: center">Recommended Permissions</td>';
    echo '<td style="padding:2px;width:25%;text-align: center">Current Permissions</td>';
	echo '</tr>';
    echo '</table>';
    fdx_check_perms("root","../","755");
    fdx_check_perms("wp-admin/","../wp-admin","755");
	fdx_check_perms("wp-includes/","../wp-includes","755");
	fdx_check_perms("wp-content/","../wp-content","755");
	fdx_check_perms(".htaccess","../.htaccess","644");
	fdx_check_perms("wp-config.php","../wp-config.php","644");
	fdx_check_perms("index.php","../index.php","644");
	fdx_check_perms("wp-blog-header.php","../wp-blog-header.php","644");
 //	fdx_check_perms("wp-content/bps-backup/","../wp-content/bps-backup","755");
    ?>
<!-- ############################################################################################################### -->
</div>
</div>








<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('System Information', 'fdx-lang'); ?></span></h3>
<div class="inside">
<!-- ############################################################################################################### -->
  <?php
	echo __('Website Root Folder', 'fdx-lang').': <strong>'.get_site_url().'</strong><br>';
	echo __('Document Root Path', 'fdx-lang').': <strong>'.esc_html($_SERVER['DOCUMENT_ROOT']).'</strong><br>';
	echo __('WP ABSPATH', 'fdx-lang').': <strong>'.ABSPATH.'</strong><br>';
	echo __('Parent Directory', 'fdx-lang').': <strong>'.dirname(ABSPATH).'</strong><br>';
	echo __('Server / Website IP Address', 'fdx-lang').': <strong>'.esc_html($_SERVER['SERVER_ADDR']).'</strong><br>';
	echo __('Host by Address', 'fdx-lang').': <strong>'.esc_html(gethostbyaddr($_SERVER['SERVER_ADDR'])).'</strong><br>';
	echo __('DNS Name Server', 'fdx-lang').': <strong>-</strong><br>';
	echo __('Public IP / Your Computer IP Address', 'fdx-lang').': <strong>'.esc_html($_SERVER['REMOTE_ADDR']).'</strong><br>';
	echo __('Server Type', 'fdx-lang').': <strong>'.esc_html($_SERVER['SERVER_SOFTWARE']).'</strong><br>';
	echo __('Operating System', 'fdx-lang').': <strong>'.PHP_OS.'</strong><br>';
	echo __('Server API', 'fdx-lang').': <strong>';
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == 'cgi' || substr($sapi_type, 0, 9) == 'litespeed' || substr($sapi_type, 0, 7) == 'caudium' || substr($sapi_type, 0, 8) == 'webjames' || substr($sapi_type, 0, 3) == 'tux' || substr($sapi_type, 0, 5) == 'roxen' || substr($sapi_type, 0, 6) == 'thttpd' || substr($sapi_type, 0, 6) == 'phttpd' || substr($sapi_type, 0, 10) == 'continuity' || substr($sapi_type, 0, 6) == 'pi3web' || substr($sapi_type, 0, 6) == 'milter') {
    echo $sapi_type.' - '.__('Your Host Server is using CGI.', 'fdx-lang');
	} else {
    echo $sapi_type.' - '.__('Your Host Server is using DSO or another SAPI type.', 'fdx-lang');
	}
	echo '</strong><br>';
	echo __('Memcache', 'fdx-lang').': <strong>';
	if (extension_loaded('memcache')) {
	$memcache = new Memcache;
	@$memcache->connect('localhost', 11211);
	echo __('Memcache Extension is Loaded', 'fdx-lang').__('Version: ', 'fdx-lang').@$memcache->getVersion();
	} else {
		_e('Memcache Extension is Not Loaded', 'fdx-lang');
	}
	echo '</strong><br>';
	echo __('Memcached', 'fdx-lang').': <strong>';
	if (extension_loaded('memcached')) {
	$memcached = new Memcached();
	@$memcached->addServer('localhost', 11211);
	echo __('Memcached Extension is Loaded', 'fdx-lang').__('Version: ', 'fdx-lang').@$memcached->getVersion();
	} else {
		_e('Memcached Extension is Not Loaded', 'fdx-lang');
	}
	echo '</strong><br>';
	?>

       <h2>SQL Database / Permalink Structure / WP Installation Folder</h2>

 	<?php
	echo __('MySQL Database Version', 'fdx-lang').': ';
    global $wpdb;
    $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
	echo '<strong>'.$sqlversion.'</strong><br>';
	echo __('MySQL Client Version', 'fdx-lang').': <strong>'.mysql_get_client_info().'</strong><br>';
	echo __('Database Host', 'fdx-lang').': <strong>'.DB_HOST.'</strong><br>';
	echo __('Database Name', 'fdx-lang').': <strong>'.DB_NAME.'</strong><br>';
	echo __('Database User', 'fdx-lang').': <strong>'.DB_USER.'</strong><br>';
	echo __('SQL Mode', 'fdx-lang').': ';
	$mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
	if (is_array($mysqlinfo)) {
	$sql_mode = $mysqlinfo[0]->Value;
    if (empty($sql_mode)) {
	$sql_mode = '<strong>'.__('Not Set', 'fdx-lang').'</strong>';
	} else {
	$sql_mode = '<strong>'.__('Off', 'fdx-lang').'</strong>';
	}}
	echo $sql_mode;
	echo '<br><br>';
	echo __('WordPress Installation Folder', 'fdx-lang').': <strong>';
	echo fdx_wp_get_root_folder().'</strong><br>';
	echo __('WordPress Installation Type', 'fdx-lang').': ';
	echo fdx_wp_get_root_folder_display_type().'<br>';
	echo __('Network/Multisite', 'fdx-lang').': ';
	echo fdx_multsite_check().'<br>';
	echo __('WP Permalink Structure', 'fdx-lang').': <strong>';
	$permalink_structure = get_option('permalink_structure');
	echo $permalink_structure.'</strong><br>';
	echo fdx_check_permalinks().'<br>';
	echo fdx_check_php_version().'<br>';
	echo __('Browser Compression Supported', 'fdx-lang').': <strong>'.esc_html($_SERVER['HTTP_ACCEPT_ENCODING']).'</strong>';
	?>




 <h2>PHP Server / PHP.ini Info</h2>


<!-- ############################################################################################################### -->
	<?php
	echo __('PHP Version', 'fdx-lang').': <strong>'.PHP_VERSION.'</strong><br>';
	echo __('PHP Memory Usage', 'fdx-lang').': <strong>'.round(memory_get_usage() / 1024 / 1024, 2) . __(' MB').'</strong><br>';
	echo __('WordPress Admin Memory Limit', 'fdx-lang').': '; $memory_limit = ini_get('memory_limit');
	echo '<strong>'.$memory_limit.'</strong><br>';
	echo __('WordPress Base Memory Limit', 'fdx-lang').': <strong>'.WP_MEMORY_LIMIT.'</strong><br>';
	echo __('PHP Actual Configuration Memory Limit', 'fdx-lang').': <strong>'.get_cfg_var('memory_limit').'</strong><br>';
	echo __('PHP Max Upload Size', 'fdx-lang').': '; $upload_max = ini_get('upload_max_filesize');
	echo '<strong>'.$upload_max.'</strong><br>';
	echo __('PHP Max Post Size', 'fdx-lang').': '; $post_max = ini_get('post_max_size');
	echo '<strong>'.$post_max.'</strong><br>';
	echo __('PHP Safe Mode', 'fdx-lang').': ';
	if (ini_get('safe_mode') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Allow URL fopen', 'fdx-lang').': ';
	if (ini_get('allow_url_fopen') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Allow URL Include', 'fdx-lang').': ';
	if (ini_get('allow_url_include') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Display Errors', 'fdx-lang').': ';
	if (ini_get('display_errors') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Display Startup Errors', 'fdx-lang').': ';
	if (ini_get('display_startup_errors') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Expose PHP', 'fdx-lang').': ';
	if (ini_get('expose_php') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Register Globals', 'fdx-lang').': ';
	if (ini_get('register_globals') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP MySQL Allow Persistent Connections', 'fdx-lang').': ';
	if (ini_get('mysql.allow_persistent') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Output Buffering', 'fdx-lang').': ';
	$output_buffering = ini_get('output_buffering');
	if (ini_get('output_buffering') != 0) {
	echo '<font color="red"><strong>'.$output_buffering.'</strong></font><br>';
	} else {
	echo '<font color="green"><strong>'.$output_buffering.'</strong></font><br>';
	}
	echo __('PHP Max Script Execution Time', 'fdx-lang').': '; $max_execute = ini_get('max_execution_time');
	echo '<strong>'.$max_execute.' Seconds</strong><br>';
	echo __('PHP Magic Quotes GPC', 'fdx-lang').': ';
	if (ini_get('magic_quotes_gpc') == 1) {
	$text = '<font color="red"><strong>'.__('On', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<font color="green"><strong>'.__('Off', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP open_basedir', 'fdx-lang').': ';
	$open_basedir = ini_get('open_basedir');
	if ($open_basedir != '') {
	echo '<strong>'.$open_basedir.'</strong><br>';
	} else {
	echo '<strong>'.__('not in use', 'fdx-lang').'</strong><br>';
	}
	echo __('PHP XML Support', 'fdx-lang').': ';
	if (is_callable('xml_parser_create')) {
	$text = '<strong>'.__('Yes', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<strong>'.__('No', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP IPTC Support', 'fdx-lang').': ';
	if (is_callable('iptcparse')) {
	$text = '<strong>'.__('Yes', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<strong>'.__('No', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	echo __('PHP Exif Support', 'fdx-lang').': ';
	if (is_callable('exif_read_data')) {
	$text = '<strong>'.__('Yes', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	} else {
	$text = '<strong>'.__('No', 'fdx-lang').'</strong></font>';
	echo $text.'<br>';
	}
	?>



<!-- ############################################################################################################### -->
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