<?php
/* wrap
*********************************************************************************/
echo '<div class="wrap">'. get_screen_icon('fdx-lock');
echo '<h2>'. $this->pluginname . ' : ' . __('Backup WordPress Database', $this->hook) . '</h2>';




/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
include('inc-sidebar.php'); //include
echo '<div class="postbox-container"><div class="meta-box-sortables">';


// postbox 1
echo '<div class="postbox">';
echo '<div class="handlediv" title="' . __('Click to toggle', $this->hook) . '"><br /></div><h3 class="hndle"><span>'. __('Backup your WordPress Database', $this->hook) . '</span></h3>';
echo '<div class="inside"><div class="fdx-left-content">';//left


echo '<p><h2>Coming Soon!</h2></p>';



echo '</div><div class="fdx-right-content">'; //right


echo '<p><h4>Under development... Database Backup to Dropbox</h4></p>';


echo '</div><div class="clear"></div></div></div>';//postbox1



//--------------------------------------------- end
echo '</div><div class="clear"></div></div></div>';



// meta-box-sortables | postbox-container | post-body | poststuff | wrap
echo '</div></div></div></div></div>';
//----------------------------------------- ?>
