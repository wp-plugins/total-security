<?php
$settings = FDX_Process::fdx_get_settings();

/* wrap
*********************************************************************************/
echo '<div class="wrap">'. screen_icon('options-general');
echo '<h2>'. $this->pluginname . ' : ' . __('Unsafe Files Search', $this->hook) . '</h2>';

// minimal version of WP core
 if (!version_compare(get_bloginfo('version'), $this->min_wp_ver,  '>=')) {

 echo '<div class="error" id="errorimg"><p>'. sprintf( __('This feature requires the WordPress version <code>%1s</code> or above, to function properly. You\'re using WordPress version <code>%2s</code>, please <a href="%3s">update</a>.' , $this->hook) , $this->min_wp_ver, get_bloginfo('version'), admin_url('update-core.php') ) . '</p></div>';
 echo <<<END
<style type="text/css">
#hiddenoff {opacity:0.5 !important;}
</style>
<script>
jQuery(document).ready(function($){
$("#hiddenoff  :input").attr("disabled", true);
});
</script>
END;

} else {
//display warning if test were never run
}



//abc
if ( isset($_POST['fdx_page']) ) {
echo '<div class="updated fade"><p><strong>' . __( 'Settings updated', $this->hook ) . '.</strong></p></div>';
}

/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables" id="hiddenoff">'; //if error

 if ( isset($_POST['action']) && 'scan' == $_POST['action'] ) {
		check_admin_referer( 'fdx-scan_all' );

		$scanner = new File_FDX_Scanner( ABSPATH, array( 'start' => 0) );
        $scanner->run();
    	$scanner = new RunEnd();
		$scanner->RunEnd();
	}

//------------postbox 1
echo '<form action="" method="post">';
echo '<input type="hidden" name="action" value="scan" />';
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Unsafe Files Search', $this->hook) . '</span>&nbsp;&nbsp;&nbsp;';
submit_button( __('Execute', $this->hook ), 'primary', 'Submit', false, array( 'id' => 'run-scanner' ) );
echo '</h3><div class="inside">';
//-----------------------------------------

/**
 * Display scan initiation form and any stored results.
 */
self::fdx_results_page();

//--------------------
echo '<div class="clear"></div></div></div></form>';
//--------------------

//form
echo '<form method="post" action="">';
      wp_nonce_field();
echo '<input type="hidden" name="fdx_page" value="fdx_form_p3" />';

//------------postbox 2
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Basic Settings', $this->hook) . '</span></h3>';
echo '<div class="inside">';
echo '<div class="fdx-left-content">';
//----------------------------------------- ?>

 <p> <?php _e( 'To help reduce memory limit errors the scan processes a series of file batches.', $this->hook ); ?> </p>


<?php
//--------------------right
echo '</div><div class="fdx-right-content">';
//----------------------------------------- ?>

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

<?php
//------------ right content | #clear# | inside | postbox
echo '</div><div class="clear"></div></div></div>';
//-----------------------------------------

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

//------------ meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//----------------------------------------- ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#run-scanner').click( function() {

          $('#diableclick').attr('disabled', 'disabled');
		   //-----------------------------------
		    $(this).attr('disabled', 'disabled')
           .val('<?php _e('Executing, please wait!', $this->hook) ?>');
    		$.blockUI({ message: '<img src="<?php echo plugins_url( 'images/loading3.gif',dirname(__FILE__));?>" width="24" height="24" border="0" alt="" /><br /><div id="scan-loader" style="display:none;"><span><?php _e('Scanner filesystem', $this->hook) ?>: 0...</span></div>' });
           //-----------------------------------
          max = <?php echo $settings['p3_op1']; ?> ;
  			$.ajaxSetup({
				type: 'POST',
				url: ajaxurl,
				complete: function(xhr,status) {
					if ( status != 'success' ) {
						$('#scan-loader img').hide();
						$('#scan-loader span').html( '<?php _e('An error occurred. Please try again later', $this->hook); ?>.' );
					}
				}
			});

			$('#scan-results').hide();
			$('#scan-loader').show();
	  fdx_file_scan(0);
			return false;
		});

	});

	var fdx_nonce = '<?php echo wp_create_nonce( 'fdx-scanner_scan' ); ?>',
	fdx_file_scan = function(s) {
		jQuery.ajax({
			data: {
				action: 'fdx-scanner_file_scan',
				start: s,
   			   	_ajax_nonce: fdx_nonce
			}, success: function(r) {
				var res = jQuery.parseJSON(r);
				if ( 'running' == res.status ) {
					jQuery('#scan-loader span').html(res.data);
					fdx_file_scan(s+max, max);
				} else if ( 'error' == res.status ) {
					// console.log( r );
					jQuery('#scan-loader img').hide();
					jQuery('#scan-loader span').html(
						'An error occurred: <pre style="overflow:auto">' + r.toString() + '</pre>'
					);
				} else {
                    fdx_db_scan();
				}
			}
		});
	}, fdx_db_scan = function() {
		jQuery('#scan-loader span').html('<?php _e('Scan complete', $this->hook); ?>...');
		jQuery.ajax({
			data: {
				action: 'fdx-run_end',
				_ajax_nonce: fdx_nonce
			}, success: function(r) {
				jQuery('#scan-loader img').hide();
				jQuery('#scan-loader span').html('<?php _e('Refresh the page to view the results', $this->hook); ?>.');
				window.location.reload(false);
			}
		});
	};
</script>