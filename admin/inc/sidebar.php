<div id="postbox-container-1" class="postbox-container">
<div id="side-sortables" class="meta-box-sortables">
<!-- <div class="postbox closed"> -->
<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php echo FDX1_PLUGIN_NAME;?> <small style="float: right">v<?php echo FDX1_PLUGIN_VERSION;?></small></span></h3>
<div class="inside">
<div style="float: right; margin: -15px 0 10px 0"><a href="http://fabrix.net/wp-twitter" target="_blank"><img src="<?php echo FDX1_PLUGIN_URL;?>/images/logo.png" width="91" height="119" border="0" alt="" /></a></div>
<a class="sm_button sm_autor" href="http://fabrix.net/wp-twitter" target="_blank"><?php _e('Plugin Homepage', 'fdx-lang') ?></a>
<a class="sm_button sm_code" href="http://wordpress.org/support/plugin/wp-twitter" target="_blank"><?php _e('Suggest a Feature', 'fdx-lang') ?></a>
<a class="sm_button sm_bug" href="http://wordpress.org/support/plugin/wp-twitter" target="_blank"><?php _e('Report a Bug', 'fdx-lang') ?></a>
<a class="sm_button sm_lang" href="http://translate.fabrix.net/projects/wp-twitter" target="_blank"><?php _e('Help Translate', 'fdx-lang') ?></a>

</div>
</div>

<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Notices', 'fdx-lang') ?></span></h3>
<div class="inside">
<?php // Do a WP version check
global $wp_version;
if (version_compare($wp_version, FDX1_MINIMUM_WP_VER, '>=')) { ?>
<span class="ico_button ico_button_ok"><?php _e('Your WordPress version', 'fdx-lang') ?>: <strong><a href="http://wordpress.org" target="_blank"><?php global $wp_version; echo $wp_version; ?></a></strong></span>
<?php } else {
	echo '<span class="ico_button ico_button_error">';
	echo (sprintf(__('Your WordPress version ('.$wp_version.') is old, please upgrade to a newer version (<strong><a href="http://wordpress.org" target="_blank">%s</a></strong>)', 'fdx-lang'), FDX1_MINIMUM_WP_VER ));
	echo "</span>\n";
   }
?>

<?php // Do a PHP version check
if (version_compare(PHP_VERSION, FDX1_MINIMUM_PHP_VER, '>=') ) { ?>
<span class="ico_button ico_button_ok"><?php _e('Your PHP version', 'fdx-lang') ?>: <strong><a href="http://www.php.net/" target="_blank"><?php echo phpversion();?></a></strong></span>
<?php } else {
	echo '<span class="ico_button ico_button_error">';
    echo (sprintf(__('Your PHP version (<strong><a href="http://www.php.net/" target="_blank">%s</a></strong>) is old, please upgrade to a newer version.', 'fdx-lang'), phpversion()));
	echo "</span>\n";
  }
?>

</div>
</div>


<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Like this plugin ?', 'fdx-lang') ?></span></h3>
<div class="inside">
<?php _e('Please help to support continued development of this plugin!', 'fdx-lang') ?>
<div align="center"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z9SRNRLLDAFZJ" target="_blank"><img src="<?php echo FDX1_PLUGIN_URL;?>/images/btn_donateCC_LG.gif" width="147" height="47" border="0" alt="" /></a></div>
<ul>
<li><a class="sm_button sm_star" href="http://wordpress.org/extend/plugins/wp-twitter/" target="_blank"><?php _e('Rate the plugin 5 star on WordPress.org', 'fdx-lang') ?>.</a></li>
<li><a class="sm_button sm_link" href="http://fabrix.net/wp-twitter/" target="_blank"><?php _e('Blog about it and link to the plugin page', 'fdx-lang') ?>.</a></li>
</ul>
<div align="center"><span class="st_sharethis_hcount" st_url="http://fabrix.net/wp-twitter/" st_title="<?php echo FDX1_PLUGIN_NAME;?>: <?php _e('the best wordpress plugin to integrate your website with Twitter. ', 'fdx-lang') ?>"></span></div>


</div>
</div>

</div>
</div>