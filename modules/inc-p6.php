<?php
$settings = FDX_Process::fdx_get_settings();
/* wrap
*********************************************************************************/
echo '<div class="wrap">'. get_screen_icon('fdx-lock');
echo '<h2>'. $this->pluginname . ' : ' . __('Settings and Setup', $this->hook) . '</h2>';


//mesages alert
if ( isset($_POST['fdx_page']) ) {
echo '<div class="updated fade"><p><strong>' . __( 'Settings updated', $this->hook ) . '.</strong></p></div>';
}


/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables">';

//form
echo '<form method="post" action="">';
      wp_nonce_field();
echo '<input type="hidden" name="fdx_page" value="fdx_form_all" />';

// postbox 1
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Secure Hidden Login', $this->hook) . '</span></h3>';
echo '<div class="inside"><div class="fdx-left-content">';//left

echo '<p>' . __('Allows you to create custom URLs for user\'s login, logout and admin\'s login page, without editing any .htaccess files. ', $this->hook).'</p>';

if ( $settings['p6_check_1'] ) {
  echo '<div class="light">' . __( 'Login url', $this->hook ) . ': <a href="/wp-login.php?login_key='. $settings['p6_key'] . '">../wp-login.php?login_key='. $settings['p6_key'] . '</a><br/>' . __( 'You need to remember new address to login!', $this->hook ). '</div>';

}
echo '<p>' . __('Those attempting to gain access to your login form will be automatcally redirected to a customizable URL.', $this->hook).'</p>';


echo '</div><div class="fdx-right-content">'; //right
?>

<p><input type="checkbox" class="check" id="p6_check_1" name="p6_check_1"<?php if ( $settings['p6_check_1'] ) echo ' checked'; ?> /> <strong><?php _e( 'Hide "wp-login.php" and "wp-admin" folder', $this->hook ); ?></strong></p>
<p><input type="text" name="p6_key" value="<?php echo( htmlentities( $settings['p6_key'], ENT_COMPAT, "UTF-8" ) ); ?>" /> <?php _e( 'Secret key', $this->hook ); ?></p>

<p><strong>URL to redirect unauthorized attempts</strong></p>
<p><input type="text" name="p6_url" value="<?php echo( htmlentities( $settings['p6_url'], ENT_COMPAT, "UTF-8" ) ); ?>" /> <?php _e( 'Leave blank for 404 page', $this->hook ); ?>
<br /><span class="description"><?php _e( 'Tip: add eg. /intrusion-detection/ for log in Error 404 Log, or "/" for home.', $this->hook ); ?></span>
</p>



<?php
echo '</div><div class="clear"></div></div></div>';//postbox1


// postbox 2
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Vulnerability Scan', $this->hook) . '</span></h3>';
echo '<div class="inside">';
echo '<div class="fdx-left-content">';
//left

echo '<p>' . __('Depending on various parameters of your site this can take from ten seconds to 2-3 minutes. Please don\'t reload the page until testing is done.', $this->hook) . '</p> ';
echo '<p>' . __('If no test results show up after the page reloads, please configure max script execution time.', $this->hook).'</p>';

//right
echo '</div><div class="fdx-right-content">';
//------------------------------------------?>


<p><strong><?php _e( 'Maximum script execution time', $this->hook ); ?></strong></p>
<p>
<select name="p2_select_1">
<option value="100"<?php if ( $settings['p2_op1'] == '100' ) echo " selected"; ?>>100</option>
<option value="200"<?php if ( $settings['p2_op1'] == '200' ) echo " selected"; ?>>200</option>
<option value="300"<?php if ( $settings['p2_op1'] == '300' ) echo " selected"; ?>>300</option>
<option value="400"<?php if ( $settings['p2_op1'] == '400' ) echo " selected"; ?>>400</option>
<option value="500"<?php if ( $settings['p2_op1'] == '500' ) echo " selected"; ?>>500</option>
<option value="0"<?php if ( $settings['p2_op1'] == '0' ) echo " selected"; ?>>~0~</option>
</select>
 <?php _e( 'Maximum number of seconds tests are allowed to run.', $this->hook ); ?>
</p>


<?php //--------------------------------------------- end
echo '</div><div class="clear"></div></div></div>';

// postbox 3
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Unsafe Files Search', $this->hook) . '</span></h3>';
echo '<div class="inside">';
echo '<div class="fdx-left-content">';
//left

echo '<p>' . __('To help reduce memory limit errors the scan processes a series of file batches.', $this->hook).'</p>';

//right
echo '</div><div class="fdx-right-content">';
//------------------------------------------?>

<p>
<select name="p3_select_1">
<option value="100"<?php if ( $settings['p3_op1'] == '100' ) echo " selected"; ?>>100</option>
<option value="200"<?php if ( $settings['p3_op1'] == '200' ) echo " selected"; ?>>200</option>
<option value="500"<?php if ( $settings['p3_op1'] == '500' ) echo " selected"; ?>>500</option>
<option value="1000"<?php if ( $settings['p3_op1'] == '1000' ) echo " selected"; ?>>1000</option>
<option value="2000"<?php if ( $settings['p3_op1'] == '2000' ) echo " selected"; ?>>2000</option>
</select>
<?php _e( 'Number of files per batch.', $this->hook ); ?>
</p>

<?php //--------------------------------------------- end
echo '</div><div class="clear"></div></div></div>';

// postbox 4
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Error 404 Log', $this->hook) . '</span></h3>';
echo '<div class="inside">';
echo '<div class="fdx-left-content">';
//left

echo '<p>' . __('Logs 404 (Page Not Found) errors on your site, this also gives the added benefit of helping you find hidden problems causing 404 errors on unseen parts of your site as all errors will be logged.', $this->hook).'</p>';

//right
echo '</div><div class="fdx-right-content">';
//------------------------------------------?>

<p><input type="checkbox" class="check" id="p4_check_1" name="p4_check_1"<?php if ( $settings['p4_check_1'] ) echo ' checked'; ?> /> <strong><?php _e( 'Enable the Error 404 Log reporting', $this->hook ); ?></strong></p>

<p style="margin-left: 15px"><input type="checkbox" class="check" id="p4_check_2" name="p4_check_2"<?php if ( $settings['p4_check_2'] ) echo ' checked'; ?> /> <?php _e( 'Ignore visits from robots', $this->hook ); ?>.</p>
<p style="margin-left: 15px"><input type="checkbox" class="check" id="p4_check_3" name="p4_check_3"<?php if ( $settings['p4_check_3'] ) echo ' checked'; ?> /> <?php _e( 'Ignore visits which don\'t have an HTTP Referrer', $this->hook ); ?>.</p>


<?php //--------------------------------------------- end
echo '</div><div class="clear"></div></div></div>';

// buttons
echo '<div class="button_submit">';
echo submit_button( __('Save all options', $this->hook ), 'primary', 'Submit', false, array( 'id' => '' ) ) ;
echo '</div>';
echo '</form>'; //form 1

echo '<div class="button_reset">';
echo '<form method="post" action="">';
echo '<input type="hidden" name="fdx_page" value="fdx_reset" />';
echo submit_button( __('Restore Defaults', $this->hook ), 'secondary', 'Submit' , false, array( 'id' => 'space', 'onclick' => 'return confirm(\'' . esc_js( __( 'Restore Default Settings?',  $this->hook ) ) . '\');' ) );
echo '</form>';//form 2
echo '</div>';

// meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//----------------------------------------- ?>
