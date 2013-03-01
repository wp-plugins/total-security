<?php
  // this plugin requires Last version
        if (!version_compare(get_bloginfo('version'), FDX2_LAST_WP_VER,  '>=')) {
         echo '<div id="message" class="error" style="top: 250px; position: absolute; padding: 15px"><div align="center"><img src="'.FDX2_PLUGIN_URL.'/images/warning.png" width="48" height="48" border="0" alt="" /></div><strong>'.__('Unsafe Files Search', 'fdx-lang').'</strong> '.__('requires the latest WordPress version', 'fdx-lang').' (<a href="' . admin_url('update-core.php') . '" title="'.__('Update WP core', 'fdx-lang').'">' . FDX2_LAST_WP_VER . '</a>) '.__('to function properly. You\'re using WordPress version', 'fdx-lang').' (<strong>' . get_bloginfo('version') . ')</strong>. '.__('Please', 'fdx-lang'). ' <strong><a href="' . admin_url('update-core.php') . '" title="'.__('Update WP core', 'fdx-lang').'">'.__('update', 'fdx-lang').'</a></strong>.</div>';
    return;
    }
?>
<div class="wrap"><?php echo get_screen_icon('fdx-lock');?>
<h2><?php echo FDX2_PLUGIN_NAME;?>: <?php _e('Unsafe Files Search', 'fdx-lang') ?></h2>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">

<?php include('_sidebar.php'); ?>

<div class="postbox-container">
<div class="meta-box-sortables">


<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Unsafe Files Search', 'fdx-lang'); ?></span></h3>
<div class="inside">
<!-- ****************************************************** -->
<?php
if ( isset($_POST['action']) && 'scan' == $_POST['action'] ) {
		check_admin_referer( 'fdx-scan_all' );

		$scanner = new File_FDX_Scanner( ABSPATH, array( 'start' => 0) );
		$scanner->run();
    	$scanner = new RunEnd();
		$scanner->RunEnd();
	}

 /**
 * Display scan initiation form and any stored results.
 */
function fdx_results_page() {
	global $wp_version;
	delete_transient( 'fdx_results_trans' );
	delete_transient( 'fdx_files' );
	$results = get_option( 'fdx_results' );
?>
<?php _e('Scours your file system by suspicious or potentially malicious files, compressed, log, binary, data, and temporary files. And any unknown file in WP core.', 'fdx-lang'); ?>
	<form action="<?php admin_url( 'tools.php?page=fdx-scanner' ); ?>" method="post">
		<?php wp_nonce_field( 'fdx-scan_all' ); ?>
		<input type="hidden" name="action" value="scan" />
	 		<div align="center" class="submit"><input type="submit" id="run-scanner" class="button-primary" value="<?php _e('One Click Search', 'fdx-lang'); ?>" /></div>
	</form>

	<div id="scan-results">
	<?php if ( ! $results ) : ?>
		<p align="center"><img src="<?php echo FDX2_PLUGIN_URL ?>images/no.png" width="48" height="48" border="0" alt="" /></p>
	<?php else : fdx_show_results( $results ); endif; ?>
	</div>
<?php }

fdx_results_page();

/**
 * Display table of results.
 */
function fdx_show_results( $results ) {
	if ( ! is_array($results) ) {
		echo __('Unfortunately the results appear to be malformed/corrupted. Try scanning again.', 'fdx-lang');
		return;
	}

	$result = '';
     //  severe=03 warning=02 note=01
	foreach ( array('03','02','01', '00') as $l ) {
		if ( ! empty($results[$l]) ) {
            $result .= '<p>&nbsp;</p><table class="widefat"><thead><tr>';
            if ( $l == '00' ) $result .= '<th scope="col"><img src="'.FDX2_PLUGIN_URL.'images/wan.png" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Log, Binary, Data and Temporary files', 'fdx-lang').'. (<strong style="color:#EFA800;">'. count($results[$l]) .' '. __('matches', 'fdx-lang').'</strong>)</th>
                                          <th scope="col" style="width:157px"><img src="'.FDX2_PLUGIN_URL.'images/00.png" width="157" height="32" border="0" alt="*"></th>';
            if ( $l == '01' ) $result .= '<th scope="col"><img src="'.FDX2_PLUGIN_URL.'images/wan.png" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Compressed files', 'fdx-lang').'. (<strong style="color:#EFA800;">'. count($results[$l]) .' '. __('matches', 'fdx-lang').'</strong>)</th>
                                          <th scope="col" style="width:190px"><img src="'.FDX2_PLUGIN_URL.'images/01.png" width="190" height="31" border="0" alt="*"></th>';
            if ( $l == '02' ) $result .= '<th scope="col"><img src="'.FDX2_PLUGIN_URL.'images/critical.png" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Dangerous and malicious files', 'fdx-lang').'. (<strong style="color:red;">'. count($results[$l]) .' '. __('matches', 'fdx-lang').' </strong>)</th>
                                          <th scope="col" style="width:222px"><img src="'.FDX2_PLUGIN_URL.'images/02.png" width="222" height="31" border="0" alt="*"></th>';
            if ( $l == '03' ) $result .= '<th scope="col"><img src="'.FDX2_PLUGIN_URL.'images/critical.png" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'. __('Unknown file found in WP core', 'fdx-lang').'. (<strong style="color:red;">'. count($results[$l]) .' '. __('matches', 'fdx-lang').' </strong>)</th>
                                        <th scope="col" style="width:124px"><img src="'.FDX2_PLUGIN_URL.'images/03.png" width="124" height="32" border="0" alt="*"></th>';
         	$result .= '</tr></thead><tbody>';
        		foreach ( $results[$l] as $r )
				$result .= fdx_draw_row( $r );

			$result .= '</tbody></table>';
		}
	}

	echo $result;
}

/**
 * Draw a single result row.
 */
function fdx_draw_row( $r ) {
	$html = '<tr><td>' . esc_html( $r['loc'] );
	$html .= '</td><td>';

	return $html . '</td></tr>';
}
?>
<!-- ****************************************************** -->
</div>
</div>
</div>
</div>
</div>
</div>
</div><!-- /wrap -->
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#run-scanner').click( function() {
		   //-----------------------------------
		    $(this).attr('disabled', 'disabled')
           .val('<?php _e('Searching filesystem, please wait!', 'fdx-lang') ?>');
		$.blockUI({ message: '<img src="<?php echo FDX2_PLUGIN_URL; ?>images/loading3.gif" width="24" height="24" border="0" alt="" /><br /><div id="scan-loader" style="display:none;"><span><?php _e('Scanner filesystem', 'fdx-lang') ?>: 0...</span></div>' });
           //-----------------------------------
		   max = <?php echo FDX_MAX_BATCH_SIZE; ?> ;
  			$.ajaxSetup({
				type: 'POST',
				url: ajaxurl,
				complete: function(xhr,status) {
					if ( status != 'success' ) {
						$('#scan-loader img').hide();
						$('#scan-loader span').html( '<?php _e('An error occurred. Please try again later', 'fdx-lang'); ?>.' );
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
		jQuery('#scan-loader span').html('<?php _e('Scan complete', 'fdx-lang'); ?>...');
		jQuery.ajax({
			data: {
				action: 'fdx-run_end',
				_ajax_nonce: fdx_nonce
			}, success: function(r) {
				jQuery('#scan-loader img').hide();
				jQuery('#scan-loader span').html('<?php _e('Refresh the page to view the results', 'fdx-lang'); ?>.');
				window.location.reload(false);
			}
		});
	};
</script>
<div class="clear"></div>
<?php
//
?>