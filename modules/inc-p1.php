<?php
$results = get_option($this->p5_options_key); //time
$tests = get_option($this->p2_options_key); //time
$p2_url2 = add_query_arg( array( 'popup' => 'pp_page', 'target' => 'phpinfo' ), menu_page_url( $this->hook , false ) );
$p2_url3 = add_query_arg( array( 'popup' => 'pp_page', 'target' => 'tableinfo' ), menu_page_url( $this->hook , false ) );
$p2_url4 = add_query_arg( array( 'popup' => 'pp_page', 'target' => 'debug' ), menu_page_url( $this->hook , false ) );


/* wrap
*********************************************************************************/
echo '<div class="wrap">'. get_screen_icon('fdx-lock');
echo '<h2>'. $this->pluginname . ' : ' . __('Dashboard', $this->hook) . '</h2>';

if (!$tests['last_run']) {
echo <<<END
<style type="text/css">
#hiddenoff3 {opacity:0.1!important;}
#showtime3  {display: inline-block}
</style>
END;
}
if( !get_site_option( 'p3_log_time' ) ) {
echo <<<END
<style type="text/css">
#hiddenoff2 {opacity:0.1!important;}
#showtime2  {display: inline-block}
</style>
END;
}
if (!$results['last_run']) {
echo <<<END
<style type="text/css">
#hiddenoff {opacity:0.1!important;}
#showtime  {display: inline-block}
</style>
END;
}

/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables">';

//------------postbox
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Security Status', $this->hook) . '</span> <div id="showtime"><code id="c1">WP Core: '.__('Unexecuted!', $this->hook).'</code></div> <div id="showtime2"><code id="c1">File System: '.__('Unexecuted!', $this->hook).'</code></div> <div id="showtime3"><code id="c1">Vulnerability: '.__('Unexecuted!', $this->hook).'</code></div></h3>';
echo '<div class="inside">';
//p2
$p2_yel_total = get_site_option( 'fdx_p2_yel_total' );
$p2_red_total = get_site_option( 'fdx_p2_red_total' );
$yel_total2 =  '2'+$p2_yel_total;
//p3
$p3_red_op1 = get_site_option( 'fdx_p3_red_op1' );
$p3_red_op2 = get_site_option( 'fdx_p3_red_op2' );
$p3_red_total = $p3_red_op1+$p3_red_op2;

//p5
$p5_red_total = get_site_option( 'fdx_p5_red_total' );
//######################################################################
      $rating = '10';
      if ( ($rating >= 0) AND ($rating <= 1) ) {$level = __('Low', $this->hook);}
      if ( ($rating >= 2) AND ($rating <= 4) ) {$level = __('Medium', $this->hook);}
      if ( ($rating >= 5) AND ($rating <= 7) ) {$level = __('High', $this->hook);}
      if ( ($rating >= 8) AND ($rating <= 10) ) {$level = __('Critical', $this->hook);}
//######################################################################
      echo '<table class="widefat topLoader">';
      echo '<thead><tr>';
      echo '<th>&nbsp;</th><th><small>'.__('Last run on', $this->hook).'</small></th><th style="text-align: center"><small>'.__('Medium Risk', $this->hook).'</small></th><th style="text-align: center"><small>'.__('High Risk', $this->hook).'</small></th><th style="text-align: center"><small>'. __('Overall Risk Rating', $this->hook) . '</small></th>';
      echo '</tr></thead><tbody><tr id="hiddenoff">';
      echo '<td><h1><a href="'. admin_url('admin.php?page='.$this->hook . '-'.$this->_p5). '">WP Core</a></h1></td><td class="ratingtime">' . date(get_option('date_format') . ', ' . get_option('time_format'), $results['last_run']) . '</td><td class="rating">';

      if ($p5_red_total == '0' ) {
      echo '<span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span id="r-0"></span>';
      } else {
      echo '<span class="pb_label pb_label-info">&#10003;</span></td><td class="rating"><span class="pb_label pb_label-important">1</span></td><td class="rating"><span id="r-9"></span>';
      }
      echo '</tr><tr class="alternate" id="hiddenoff2"><td><h1><a href="'. admin_url('admin.php?page='.$this->hook . '-'.$this->_p3). '">File System</a></h1></td><td class="ratingtime">' . date(get_option('date_format') . ', ' . get_option('time_format'), get_site_option( 'p3_log_time') ) . '</td><td class="rating">';

     if ($p3_red_total == '0') {
      echo '<span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span id="r-1"></span>';
     } else {
      echo '<span class="pb_label pb_label-info">&#10003;</span></td><td class="rating"><span class="pb_label pb_label-important">1</span></td><td class="rating"><span id="r-8"></span>';
     }

      echo '</td></tr><tr id="hiddenoff3"><td><h1><a href="'. admin_url('admin.php?page='.$this->hook . '-'.$this->_p2). '">Vulnerability</a></h1></td><td class="ratingtime">' . date(get_option('date_format') . ', ' . get_option('time_format'), $tests['last_run']) . '</td><td class="rating">';

      if ($p2_yel_total == '0' && $p2_red_total == '0') {
      echo '<span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span id="r-2"></span>';

      } elseif ($p2_yel_total != '0' && $p2_red_total == '0') {
      echo '<span class="pb_label pb_label-warning">'.$p2_yel_total.'</span></td><td class="rating"><span class="pb_label pb_label-important">'.$p2_red_total.'</span></td><td class="rating"><span id="r-'.$yel_total2.'"></span>';

      } elseif ($p2_yel_total == '0' && $p2_red_total != '0') {
      echo '<span class="pb_label pb_label-success">&#10003;</span></td><td class="rating"><span class="pb_label pb_label-important">'.$p2_red_total.'</span></td><td class="rating"><span id="r-9"></span>';

      } else {
      echo '<span class="pb_label pb_label-warning">'.$p2_yel_total.'</span></td><td class="rating"><span class="pb_label pb_label-important">'.$p2_red_total.'</span></td><td class="rating"><span id="r-9"></span>';
       }

     echo '</tr></tbody></table><div class="tab_legen">*'.__('Rerun the checks after changes in your configuration.', $this->hook).'</div>';

//--------------------
echo '<div class="clear"></div></div></div>';


//------------postbox
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Additional Info', $this->hook) . '</span></h3>';
echo '<div class="inside">';

echo '<div class="fdx-left-content">';
echo '<p>'.__(' You can see all identified security problems of your website at one glance.', $this->hook).'</p>';
echo '<p>'.__(' Each security problem comes with a detailed description and all the information needed so you can eliminate the problems and get secure.', $this->hook).'</p>';
echo '<p>'.__('Any red or orange dots? Follow the instructions and turn them into green dots!', $this->hook).'</p>';
echo '<hr class="sep">';
echo '<div class="button_submit"><a class="button" href="javascript:void(0);" onclick="PopupCenter(\''.$p2_url2.'\', \'phpinfo\',800,690,\'yes\');" title="'.__('Display Extended PHP Settings via phpinfo()', $this->hook).'">Phpinfo()</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="'.$p2_url4.'" class="button fdx-dialog" title="'.__('Debug information is used to provide help. You should include this information in your posts on support forum.', $this->hook).'">'.__('Debug', $this->hook).'</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="'.$p2_url3.'" class="button fdx-dialog">'.__('Database Info', $this->hook).'</a></div>';

//------------------------------------------
echo '</div><div class="fdx-right-content">';
echo '<p><strong>'.__('All security checks are assigned one of the following risks', $this->hook).':</strong></p>';
echo '<p><span class="pb_label pb_label-success">&#10003;</span> '.__('No security risk has been identified.', $this->hook).'</p>';
echo '<p><span class="pb_label pb_label-warning">!</span> '.__('A medium security risk, resolve it as soon as possible.', $this->hook).'</p>';
echo '<p><span class="pb_label pb_label-important">X</span> '.__('The identified security issues have to be <strong>resolved immediately</strong>. ', $this->hook).'</p>';
echo '<hr class="sep">';
echo '<p><span class="pb_label pb_label-info">&#10003;</span> '.__('No security risk.', $this->hook). ' <em>('.__('If possible, replace', $this->hook).')</em></p>';
echo '<p><span class="pb_label pb_label-desat">&Oslash;</span> '.__('Error / Unable / Deactivated', $this->hook). ' <em>('.__('No risk assessment', $this->hook).')</em></p>';


//--------------------
echo '</div><div class="clear"></div></div></div>';


//------------postbox
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Security Considerations for External Factors', $this->hook) . '</span></h3>';
echo '<div class="inside">';
echo '<p><strong>i) '.__('SECURITY ON YOUR COMPUTER', $this->hook).'</strong><br/>';
echo __('Make sure that your computer is free from malware, by regularly updating your operating system, browser and other installed software. Additionally, use antivirus software to identify some of the potential threats on your computer. Additionally, never store your FTP passwords in your FTP client.', $this->hook).'</p>';

echo '<p><strong>ii) '.__('SECURITY ON YOUR WEB SERVER', $this->hook).'</strong><br/>';
echo __('When relying on a webhosting provider for your WordPress setup, make sure that they take security seriously. Rely on well known hosting providers that have a good reputation for security and that always keep their installations updated.', $this->hook).'</p>';

echo '<p><strong>iii) '.__('NETWORK SECURITY', $this->hook).'</strong><br/>';
echo __('Be careful when using public or untrusted networks when working with your WordPress setup, especially free and unencrypted WiFi connections pose a high risk of attackers getting access to sensitive information such as passwords or session IDs. Communicate over secure networks, and rely on encrypted protocols such as FTPS and HTTPS that send all information over encrypted channels.', $this->hook).'</p>';
echo '<hr class="sep">';
echo '<p>'.sprintf(__('Use <strong><a href="%1s" target="_blank">%2s</a></strong> to generate your passwords. It creates unique, secure passwords that are very easy for you to retrieve but no one else. Nothing is stored anywhere, anytime, so there\'s nothing to be hacked, lost, or stolen.', $this->hook), 'http://passwordmaker.org/', 'Password Maker' ) . '</p>';


echo '</div></div>';

echo '<div id="fdx-dialog-wrap"><div id="fdx-dialog"></div></div>'; //popup


//------------ meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//-----------------------------------------
?>
<script language="JavaScript" type="text/javascript">
jQuery(document).ready(function($){
 $('a.fdx-dialog').click(function(event) {
              event.preventDefault();
              var link = $(this).attr('href');
              $("#fdx-dialog").load(link,function(){
               $( "#fdx-dialog-wrap" ).dialog( "open" );
              });
              return false;
});
$('#fdx-dialog-wrap').dialog({'dialogClass': 'wp-dialog',
                               'modal': true,
                               'resizable': false,
                               'zIndex': 9999,
                               'width': 700,
                               'title': '',
                               'height': 550,
                               'hide': 'fade',
                               'show': 'fade',
                               'autoOpen': false,
                               'closeOnEscape': true
                              });
});
</script>
