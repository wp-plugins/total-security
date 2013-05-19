<?php
$settings = FDX_Process::fdx_get_settings();
$tests = get_option($this->p2_options_key);

/* wrap
*********************************************************************************/
echo '<div class="wrap">'. screen_icon('options-general');
echo '<h2>'. $this->pluginname . ' : ' . __('Vulnerability Scan', $this->hook) . '</h2>';

//display warning if test were never run
if (!$tests['last_run']) {
    echo '<div class="error" id="errorimg"><p>'.__('Not yet executed!', $this->hook).'</p></div>';
    } elseif ((current_time('timestamp') - 1*24*60*60) > $tests['last_run']) {
    echo '<div class="error" id="errorimg"><p>'. sprintf( __('Executed for more than <code>%s</code> days. Click in button "Execute" for a new analysis.' , $this->hook) , '1' ) . '</p></div>';
    }

//abc
if ( isset($_POST['fdx_page']) ) {
echo '<div class="updated fade"><p><strong>' . __( 'Settings updated', $this->hook ) . '.</strong></p></div>';
}

/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables">';

//------------postbox 1
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Vulnerability Scan', $this->hook) . '</span>&nbsp;&nbsp;&nbsp;';
submit_button( __('Execute', $this->hook ), 'primary', 'Submit', false, array( 'id' => 'run-tests' ) );
echo '</h3><div class="inside">';
//-----------------------------------------

     if ($tests['last_run']) {
      echo '<div class="last_scan"><code>'.__('Last run on', $this->hook).': ' . date(get_option('date_format') . ', ' . get_option('time_format'), $tests['last_run']) . '</code></div>';
      echo '<table class="widefat">';
      echo '<thead><tr>';
      echo '<th>&nbsp;</th>';
      echo '<th class="fdx-status">'.__('Results', $this->hook).'</th>';
      echo '<th>&nbsp;</th>';
      echo '</tr></thead>';
      echo '<tbody>';
      if (is_array($tests['test'])) {
        // test Results
        foreach($tests['test'] as $test_name => $details) {
          echo '<tr>
                  <td class="fdx-status">' . self::status($details['status']) . '</td>
                  <td class="fdx-details">' . $details['msg'] . '</td>
                  <td class="fdx-status"><a href="http://fabrix.net/total-security/mscan/#' . $test_name . '" class="button" target="_blank" title="'.__('Details, tips &amp; help', $this->hook).'"><strong>?</strong></a>&nbsp;</td>
                </tr>';
        } // foreach ($tests)
      } else { // no test results
        echo '<tr>
                <td colspan="4">'.__('No results available!', $this->hook). '</td>
              </tr>';
      } // if tests
      echo '</tbody>';
      echo '</table>';
      } else {
      echo '<table class="widefat">';
      echo '<thead><tr>';
      echo '<th>&nbsp;</th>';
      echo '<th class="fdx-status" id="red">'.__('Unexecuted!', $this->hook).'</th>';
      echo '<th>&nbsp;</th>';
      echo '</tr></thead>';
      echo '<tbody>';
      echo '</table>';
      }

//--------------------
echo '<div class="clear"></div></div></div>';
//--------------------

//form
echo '<form method="post" action="">';
      wp_nonce_field();
echo '<input type="hidden" name="fdx_page" value="fdx_form_p2" />';

//------------postbox 2
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Basic Settings', $this->hook) . '</span></h3>';
echo '<div class="inside">';
echo '<div class="fdx-left-content">';
//----------------------------------------- ?>

<p><?php echo sprintf( __('This analyze contains <strong>%s</strong> separate security tests. Once you click the "Execute" button all tests will be run.', $this->hook ) , '32' ) ?></p>
<p><?php _e('Depending on various parameters of your site this can take from ten seconds to 2-3 minutes. Please don\'t reload the page until testing is done.', $this->hook) ?></p>
<p><?php _e('If no test results show up after the page reloads, please configure max script execution time.', $this->hook) ?></p>

<?php
//--------------------right
echo '</div><div class="fdx-right-content">';
//----------------------------------------- ?>

<p><strong><?php _e( 'Brute-force attack', $this->hook ); ?> </strong></p>
<p><input type="checkbox" class="check" id="p2_check_1" name="p2_check_1"<?php if ( $settings['p2_check_1'] ) echo ' checked'; ?> /> <?php echo sprintf( __('Check admin password strength with a <strong>%s</strong> most commonly used.', $this->hook ) , '600' ) ?></p>

<hr class="sep">

<p><strong><?php _e( 'Maximum script execution time', $this->hook ); ?></strong></p>
<p>
<select name="p2_select_1">
<option value="200"<?php if ( $settings['p2_op1'] == '200' ) echo " selected"; ?>>200</option>
<option value="300"<?php if ( $settings['p2_op1'] == '300' ) echo " selected"; ?>>300</option>
<option value="400"<?php if ( $settings['p2_op1'] == '400' ) echo " selected"; ?>>400</option>
<option value="500"<?php if ( $settings['p2_op1'] == '500' ) echo " selected"; ?>>500</option>
<option value="0"<?php if ( $settings['p2_op1'] == '0' ) echo " selected"; ?>>~0~</option>
</select>
 <?php _e( 'Maximum number of seconds tests are allowed to run.', $this->hook ); ?>
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
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
jQuery(document).ready(function($){
//  $('#run-tests').removeAttr('disabled');
  // run tests, via ajax
  $('#run-tests').click(function(){
    var data = {action: 'sn_run_tests'};
     $(this).attr('disabled', 'disabled')
           .val('<?php _e('Executing, please wait!', $this->hook) ?>');
           $.blockUI({ message: '<img src="<?php echo plugins_url( 'images/loading.gif',dirname(__FILE__));?>" width="24" height="24" border="0" alt="" /><br /><?php _e('Executing, please wait!', $this->hook) ?> <?php _e('it can take a few minutes.', $this->hook) ?>' });
    $.post(ajaxurl, data, function(response) {
          window.location.reload();
    });
  }); // run tests
});
/*]]>*/
</script>