<?php
$results = get_option($this->p5_options_key);

/* wrap
*********************************************************************************/
echo '<div class="wrap">'. screen_icon('options-general');
echo '<h2>'. $this->pluginname . ' : ' . __('Core Exploit Scanner', $this->hook) . '</h2>';

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
$tests = get_option($this->p5_options_key);
if (!$tests['last_run']) {
      echo '<div class="error" id="errorimg"><p>'.__('Not yet executed!', $this->hook).'</p></div>';
    } elseif ((current_time('timestamp') - 15*24*60*60) > $tests['last_run']) {
    echo '<div class="error" id="errorimg"><p>'. sprintf( __('Executed for more than <code>%s</code> days. Click in button "Execute" for a new analysis.' , $this->hook) , '15' ) . '</p></div>';
    }

}

/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables" id="hiddenoff">'; //if error

//------------postbox 1
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Info and Tips', $this->hook) . '</span></h3>';
echo '<div class="inside">';
//-----------------------------------------

echo '<p>'.__('Files are scanned and compared via the <em>MD5 hashing algorithm</em> to original WordPress core files available from <strong>wordpress.org</strong>. Not every change on core files is malicious and changes can serve a legitimate purpose. However if you are not a developer and you did not change the files yourself the changes most probably come from an exploit.', $this->hook).'</p>';

//--------------------
echo '<div class="clear"></div></div></div>';
//--------------------


//------------postbox 2
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Core Exploit Scanner', $this->hook) . '</span>&nbsp;&nbsp;&nbsp;';
submit_button( __('Execute', $this->hook ), 'primary', 'Submit', false, array( 'id' => 'fdx-run-scan' ) );
echo '</h3><div class="inside">';
//-----------------------------------------

if (isset($results['last_run']) && $results['last_run']) {
      echo '<div class="last_scan"><code>'.__('Last run on', $this->hook).': ' . date(get_option('date_format') . ', ' . get_option('time_format'), $results['last_run']) . '</code></div>';
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
    if ($results['changed_bad']) {
      echo '<table class="widefat">';
      echo '<thead><tr>';
      echo '<th><img src="'.plugins_url( 'images/critical.png',dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'.__('Core files', $this->hook).' (<strong style="color:red;">'. __('modified', $this->hook). '</strong>)</th>';
      echo '</tr></thead>';
      echo '<tbody><tr class="alternate"><td>';
      echo '<div style="font-size: 11px">'.__('If you didn\'t modify the following files and don\'t know who did they are most probably infected by a party malicious code.', $this->hook).'</div></td></tr><td>';
      echo self::list_files($results['changed_bad'], true, true);
      echo '</td></tr></tbody>';
      echo '</table>';
    }

    if ($results['missing_bad']) {
      echo '<p>&nbsp;</p><table class="widefat">';
      echo '<thead><tr>';
      echo '<th><img src="'.plugins_url( 'images/critical.png',dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'.__('Core files', $this->hook).' (<strong style="color:red;">'. __('missing', $this->hook). '</strong>)</th>';
      echo '</tr></thead>';
      echo '<tbody><tr class="alternate"><td>';
      echo '<div style="font-size: 11px">'.__('Missing core files my indicate a bad auto-update or they simply were not copied on the server when the site was setup. Use the restore action to create them.', $this->hook).'</div></td></tr><td>';
      echo self::list_files($results['missing_bad'], false, true);
      echo '</td></tr></tbody>';
      echo '</table>';
    }

   if ($results['missing_ok']) {
      echo '<p>&nbsp;</p><table class="widefat">';
      echo '<thead><tr>';
      echo '<th><img src="'.plugins_url( 'images/clean.png',dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'.__('Core files', $this->hook).' (<strong style="color:green;">'. __('missing', $this->hook). '</strong>)</th>';
      echo '</tr></thead>';
      echo '<tbody><tr class="alternate"><td>';
      echo '<div style="font-size: 11px">'.__('Some files like (<strong><em>/readme.html, /license.txt, /wp-config-sample.php, /wp-admin/install.php, /wp-admin/upgrade.php</em></strong>) are not vital and should be removed. Do not restore them unless you really need them and know what you are doing.', $this->hook).'</div></td></tr><td>';
      echo self::list_files($results['missing_ok'], false, true);
      echo '</td></tr></tbody>';
      echo '</table>';
  }

    if ($results['changed_ok']) {
      echo '<p>&nbsp;</p><table class="widefat">';
      echo '<thead><tr>';
      echo '<th><img src="'.plugins_url( 'images/clean.png',dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" style="vertical-align: middle" />'.__('Config files', $this->hook).' <strong style="color:green;font-size:20px">&#10003;</strong></th>';
      echo '</tr></thead>';
      echo '<tbody><tr class="alternate"><td>';
      echo '<div style="font-size: 11px">'.__('Look at their source to check for any suspicious code.', $this->hook).'</small></td></tr><td>';
      echo self::list_files($results['changed_ok'], true, false);
      echo '</td></tr></tbody>';
      echo '</table>';
    }

    if ($results['ok']) {
      $diference = $results['total'] - sizeof($results['ok']);
      echo '<div class="clear"></div><div align="center">';
      echo '<p>'.__('A total of', $this->hook). ' <strong>"' . $results['total'] . '"</strong> '.__('files were scanned', $this->hook).', <strong>"' . sizeof($results['ok']) . '"</strong> '.__('are unmodified and safe, and', $this->hook). ' <strong>"'. $diference .'"</strong> '.__('are files modified or missing', $this->hook).  '.</p> ';
      echo '</div>';
    }

    // dialogs
    echo '<div id="source-dialog" style="display: none;" title="File source"><p>'.__('Please wait', $this->hook).'.</p></div>';
    echo '<div id="restore-dialog" style="display: none;" title="'.__('Restore file', $this->hook).'"><p>'.__('Please wait', $this->hook).'.</p></div>';

    //--------------------
    echo '<div class="clear"></div></div></div>';
    //--------------------

//------------ meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//----------------------------------------- ?>

<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
jQuery(document).ready(function($){
  $('a.fdx-show-source').click(function() {
     $($(this).attr('href')).dialog('option', { title: '<?php _e('File source', $this->hook); ?>' + $(this).attr('data-file'), file_path: $(this).attr('data-file'), file_hash: $(this).attr('data-hash') } ).dialog('open');
      return false;
  });
  $('a.fdx-restore-source').click(function() {
      $($(this).attr('href')).dialog('option', { title: '<?php _e('Restore file source', $this->hook); ?>: ' + $(this).attr('data-file'), file_path: $(this).attr('data-file'), file_hash: $(this).attr('data-hash') } ).dialog('open');
      return false;
  });
  $('#source-dialog').dialog({'dialogClass': 'wp-dialog',
                              'modal': true,
                              'resizable': false,
                              'zIndex': 9999,
                              'width': 800,
                              'height': 550,
                              'hide': 'fade',
                              'open': function(event, ui) { renderSource(event, ui); fixDialogClose(event, ui); },
                              'close': function(event, ui) { jQuery('#source-dialog').html('<p><?php _e('Please wait', $this->hook) ?>.</p>') },
                              'show': 'fade',
                              'autoOpen': false,
                              'closeOnEscape': true
                              });
  $('#restore-dialog').dialog({'dialogClass': 'wp-dialog',
                               'modal': true,
                               'resizable': false,
                               'zIndex': 9999,
                               'width': 450,
                               'height': 350,
                               'hide': 'fade',
                               'open': function(event, ui) { renderRestore(event, ui); fixDialogClose(event, ui); },
                               'close': function(event, ui) { jQuery('#restore-dialog').html('<p><?php _e('Please wait', $this->hook) ?>.</p>') },
                               'show': 'fade',
                               'autoOpen': false,
                               'closeOnEscape': true
                              });
  // scan files
  $('#fdx-run-scan').click(function(){
    var data = {action: 'sn_core_run_scan'};

    $(this).attr('disabled', 'disabled')
           .val('<?php _e('Executing, please wait!', $this->hook) ?>');
    $.blockUI({ message: '<img src="<?php echo plugins_url( 'images/loading2.gif',dirname(__FILE__));?>" width="24" height="24" border="0" alt="" /><br /><?php _e('Executing, please wait!', $this->hook) ?> <?php _e('it can take a few minutes.', $this->hook) ?>' });

    $.post(ajaxurl, data, function(response) {
     window.location.reload();
    /*  if (response != '1') {
        alert('Ajax error - js#01');
        window.location.reload();
      } else {
        window.location.reload();
      } */
    });
  }); // run tests
}); // onload

function renderSource(event, ui) {
  dialog_id = '#' + event.target.id;

  jQuery.post(ajaxurl, {action: 'sn_core_get_file_source', filename: jQuery('#source-dialog').dialog('option', 'file_path'), hash: jQuery('#source-dialog').dialog('option', 'file_hash')}, function(response) {
      if (response) {
        if (response.err) {
          jQuery(dialog_id).html('<p><b><?php _e('An error occured', $this->hook) ?>.</b> ' + response.err + '</p>');
        } else {
          jQuery(dialog_id).html('<pre class="brush: php"></pre>');
          jQuery('pre', dialog_id).text(response.source);
          jQuery('pre', dialog_id).snippet(response.ext, {style: '<?php echo $this->p5_snippet ?>'});
        }
      } else {
        alert('<?php echo _e('An undocumented error occured. The page will reload', $this->hook); ?>.');
        window.location.reload();
      }
    }, 'json');
} // renderSource
function renderRestore(event, ui) {
  dialog_id = '#' + event.target.id;

  jQuery.post(ajaxurl, {action: 'sn_core_restore_file', filename: jQuery(dialog_id).dialog('option', 'file_path'), hash: jQuery(dialog_id).dialog('option', 'file_hash')}, function(response) {
      if (response) {
        if (response.err) {
          jQuery(dialog_id).html('<p><b><?php _e('An error occured', $this->hook) ?>.</b> ' + response.err + '</p>');
        } else {
          jQuery(dialog_id).html(response.out);

            jQuery('#fdx-restore-file').on('click', function(event){
              jQuery(this).attr('disabled', 'disabled').attr('value', '<?php _e('Please wait', $this->hook) ?> ...');
              jQuery.post(ajaxurl, {action: 'sn_core_restore_file_do', filename: jQuery(this).attr('data-filename')}, function(response) {
                if (response == '1') {
                  alert('<?php _e('File successfully restored!\nThe page will reload and files will be rescanned', $this->hook) ?>.');
                  window.location.reload();
                } else {
                  alert('<?php _e('An error occured', $this->hook) ?> - ' + response);
                  jQuery(this).attr('disabled', '').attr('value', '<?php _e('Restore file', $this->hook) ?>');
                }
              });
            });
        }
      } else {
        alert('<?php _e('An undocumented error occured. The page will reload', $this->hook) ?>.');
        window.location.reload();
      }
    }, 'json');
} // renderSource
function fixDialogClose(event, ui) {
  jQuery('.ui-widget-overlay').bind('click', function(){ jQuery('#' + event.target.id).dialog('close'); });
} // fixDialogClose
/*]]>*/
</script>