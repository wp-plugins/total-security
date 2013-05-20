<?php
$settings = FDX_Process::fdx_get_settings();
$tests = get_option($this->p2_options_key);

/* wrap
*********************************************************************************/
echo '<div class="wrap">'. screen_icon('options-general');
echo '<h2>'. $this->pluginname . ' : ' . __('Error 404 Log', $this->hook) . '</h2>';

 if( ! get_option( 'permalink_structure' ) ) {
 echo '<div class="error" id="errorimg"><p>'. sprintf( __('You need to be using pretty <a href="%s">permalinks</a> for this function.', $this->hook), admin_url('options-permalink.php') ) . '</p></div>';
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

//------------postbox 1
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Error 404 Log', $this->hook) . '</span></h3>';
echo '<div class="inside">';
//-----------------------------------------

echo '<form action="" method="post">';
$fdx_log_content_table = new fdx_log_content_table();
$fdx_log_content_table->fdx_prepare_items();
$fdx_log_content_table->search_box( 'Search Log', $this->hook );
$fdx_log_content_table->display();
echo '</form>';

//--------------------
echo '<div class="clear"></div></div></div>';
//--------------------



// buttons

echo '<div class="button_submit">';
echo '<form method="post" action="">';
echo '<input type="hidden" name="fdx_page" value="fdx_clean" />';
echo submit_button( __('Delete all log entries', $this->hook ), 'secondary', 'Submit' , false, array( 'id' => 'space', 'onclick' => 'return confirm(\'' . esc_js( __( 'Are you sure you want to delete all log entries?',  $this->hook ) ) . '\');' ) );
echo '</form>';//form 2
echo '</div>';

//------------ meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//----------------------------------------- ?>
	<script>
	jQuery(document).ready(function($){
		$("#doaction, #doaction2").click( function(e){
			if( $(this).parent().find("select").val() == "-1" ) {
				e.preventDefault();
				alert("You did not select an action to perform!");
			}
			else if( !$("#the-list :checked").length ) {
				e.preventDefault();
				alert("You did not select any items to delete!");
			}
		});
		$("#url-hide").parent().hide();	// can't hide this
	});
	</script>