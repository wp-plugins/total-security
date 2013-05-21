<?php
define( 'fdx_HOMEPAGE', 'http://fabrix.net/total-security/' );
define( 'fdx_GLOTPRESS', 'http://translate.fabrix.net/projects/total-security/' );
define( 'fdx_SUPPORTPAGE', 'http://wordpress.org/extend/plugins/total-security/' );
define( 'fdx_PAYPALCODE', '8DHY4NXW35T4Y' );
define( 'fdx_RSS', 'http://feeds.feedburner.com/fdxplugins/' );

/*---------------------------------------*/
echo '<div id="postbox-container-1" class="postbox-container">';
echo '<div id="side-sortables" class="meta-box-sortables">';

/* class="postbox closed"
----------------------------------------*/
echo '<div class="postbox"><div class="handlediv" title="'.__('Click to toggle', $this->hook) .'"><br /></div><h3 class="hndle"><span>'. $this->pluginname . ' <small style="float: right">v'. $this->pluginversion . '</small></span></h3>';
echo '<div class="inside">';
echo '<div style="float: right;"><a href="'. fdx_HOMEPAGE . '" target="_blank"><img src="'.plugins_url( 'images/_91x100.png', dirname(__FILE__)).'" width="91" height="100" border="0" alt="*" /></a></div>';
echo '<a class="sm_button sm_autor" href="'. fdx_HOMEPAGE . '" target="_blank">' . __( 'Plugin Homepage', $this->hook ) . '</a>';
echo '<a class="sm_button sm_code" href="'. fdx_SUPPORTPAGE . '" target="_blank">' . __( 'Suggest a Feature', $this->hook ) . '</a>';
echo '<a class="sm_button sm_bug" href="'. fdx_SUPPORTPAGE . '" target="_blank">' . __( 'Report a Bug', $this->hook ) . '</a>';
echo '<a class="sm_button sm_lang" href="' . fdx_GLOTPRESS . '" target="_blank">' . __( 'Help translating it', $this->hook ) . '</a>';
echo '</div></div>';

//----------------------------------------
echo '<div class="postbox"><div class="handlediv" title="'.__('Click to toggle', $this->hook) .'"><br /></div><h3 class="hndle"><span>'. __( 'Do you like this Plugin?', $this->hook ) . '</span></h3>';
echo '<div class="inside">'.__( 'Please help to support continued development of this plugin!', $this->hook );
echo '<div align="center"><strong style="font-size: 15px">' . __( 'DONATE', $this->hook ) . '</strong><br />';
echo '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=' . fdx_PAYPALCODE . '" target="_blank"><img src="'.plugins_url( 'images/h3_icons/paypal.png', dirname(__FILE__)).'" width="101" height="64" border="0"  alt=""/></a>';
echo '<a href="http://www.neteller.com/personal/send-money/" id="cl" target="_blank" title="fabrix@fabrix.net"><img src="'. plugins_url( 'images/h3_icons/neteller.png', dirname(__FILE__)).'" width="102" height="64" border="0" alt=""  style="margin-left: 25px" /></a></div>';
echo '<ul><li><a class="sm_button sm_star" href="http://wordpress.org/extend/plugins/wp-twitter" target="_blank">'. __( 'Rate the plugin 5 star on WordPress.org', $this->hook ) .'.</a></li>';
echo '<li><a class="sm_button sm_link" href="'. fdx_HOMEPAGE . '" target="_blank">'. __( 'Blog about it and link to the plugin page', $this->hook ) .'.</a></li></ul>';
echo '<div align="center"><a href="javascript:void(0);" onclick="PopupCenter(\'http://www.facebook.com/sharer.php?u='. fdx_HOMEPAGE . '&t='. $this->pluginname . ':\', \'facebook\',800,550,\'no\');" title="'. __( 'Share on', $this->hook ) .' Facebook" rel="nofollow"><img src="'. plugins_url( 'images/h3_icons/facebook.png', dirname(__FILE__)).'" width="32" height="32" border="0"  alt="*" style="margin-right: 15px" /></a>';
echo'<a href="javascript:void(0);" onclick="PopupCenter(\'https://plus.google.com/share?url='. fdx_HOMEPAGE . '\', \'googleplus\',800,550,\'no\');" title="'. __( 'Share on', $this->hook ) .' Google Plus" rel="nofollow"><img src="'. plugins_url( 'images/h3_icons/googleplus.png', dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" style="margin-right: 15px" /></a>';
echo '<a href="javascript:void(0);" onclick="PopupCenter(\'http://twitter.com/share?text=Plugin '. $this->pluginname . ':&amp;url='. fdx_HOMEPAGE . '\', \'twitter\',600,450,\'no\');" title="'. __( 'Share on', $this->hook ) .' Twitter" rel="nofollow"><img src="'. plugins_url( 'images/h3_icons/twitter.png', dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" style="margin-right: 15px" /></a>';
echo '<a href="javascript:void(0);" onclick="PopupCenter(\'http://api.addthis.com/oexchange/0.8/offer?title='. $this->pluginname . '&amp;url='. fdx_HOMEPAGE . '\', \'addthis\',550,760,\'yes\');" title="'. __( 'Share on', $this->hook ) .' Addthis" rel="nofollow"><img src="'. plugins_url( 'images/h3_icons/addthis.png', dirname(__FILE__)).'" width="32" height="32" border="0" alt="*" /></a></div>';
echo '</div></div>';

//----------------------------------------
echo '<div class="postbox"><div class="handlediv" title="'.__('Click to toggle', $this->hook) .'"><br /></div><h3 class="hndle"><span>'. __( 'Translation', $this->hook ) . '</span></h3>';
echo '<div class="inside">';
if (WPLANG == '' || WPLANG == 'en' || WPLANG == 'en_US'  ){
echo '<strong>Would you like to help translating this plugin?</strong><br/> Contribute a translation using the GlotPress web interface - no technical knowledge required (<strong><a href="' . fdx_GLOTPRESS . '" target="_blank">how to</a></strong>)';
} else {
echo '<span class="ico_button ico_button_'.WPLANG.'">' . __( 'Translated by: <a href="http://YOUR-LINK.COM"><strong>Your Name</strong></a>', $this->hook ) . '</span>';
echo '<p>' . __( 'If you find any spelling error in this translation or would like to contribute', $this->hook ) . ', <a href="' . fdx_GLOTPRESS . '" target="_blank">' . __( 'click here', $this->hook ) . '.</a></p>';
}
echo '</div></div>';

//----------------------------------------
echo '<div class="postbox"><div class="handlediv" title="'.__('Click to toggle', $this->hook) .'"><br /></div><h3 class="hndle"><span>'. __( 'Notices', $this->hook ) . '</span></h3>';
echo '<div class="inside">';

$rss = @fetch_feed( fdx_RSS );
     if ( is_object($rss) ) {
        if ( is_wp_error($rss) ) {
            echo 'Newsfeed could not be loaded.';
    		return;
        }
echo '<ul>';
		foreach ( $rss->get_items(0, 5) as $item ) {
    		$link = $item->get_link();
    		while ( stristr($link, 'http') != $link )
    			$link = substr($link, 1);
    		$link = esc_url(strip_tags($link));
    		$title = esc_attr(strip_tags($item->get_title()));
    		if ( empty($title) )
    			$title = __('Untitled');
			$date = $item->get_date();
            $diff = '';
			if ( $date ) {
                $diff = human_time_diff( strtotime($date, time()) );
				if ( $date_stamp = strtotime( $date ) )
					$date =  date_i18n( get_option( 'date_format' ), $date_stamp );
				else
					$date = '';
			}
echo '<li style=" margin-top: -2px; margin-bottom: -2px"><a class="sm_button sm_bullet" title="'. $date .'" target="_blank" href="'. $link .'">'. $title.' <em class="none">'. $diff.'</em></a></li>';
    }
       echo'</ul>';
      }
echo '</div></div>';
//----------------------------------------
echo '</div></div>';