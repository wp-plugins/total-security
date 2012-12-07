<div id="postbox-container-1" class="postbox-container">
<div id="side-sortables" class="meta-box-sortables">
<!-- <div class="postbox closed"> -->
<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php echo FDX2_PLUGIN_NAME;?> <small style="float: right">v<?php echo FDX2_PLUGIN_VERSION;?></small></span></h3>
<div class="inside" style="padding-bottom: 5px">
<div style="float: right;"><a href="<?php echo FDX2_PLUGINPAGE;?>" target="_blank"><img src="<?php echo FDX2_PLUGIN_URL;?>/images/logo.png" width="91" height="106" border="0" alt="*" /></a></div>
<a class="sm_button sm_autor" href="<?php echo FDX2_PLUGINPAGE;?>" target="_blank"><?php _e('Plugin Homepage', 'fdx-lang') ?></a>
<a class="sm_button sm_code" href="<?php echo FDX2_SUPFORUM;?>" target="_blank"><?php _e('Suggest a Feature', 'fdx-lang') ?></a>
<a class="sm_button sm_bug" href="<?php echo FDX2_SUPFORUM;?>" target="_blank"><?php _e('Report a Bug', 'fdx-lang') ?></a>
<a class="sm_button sm_lang" href="<?php echo FDX2_GLOTPRESS;?>" target="_blank"><?php _e('Help translating it', 'fdx-lang') ?></a>
</div>
</div>

<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Translation', 'fdx-lang') ?> </span></h3>
<div class="inside">

<?php if (WPLANG == '' || WPLANG == 'en' || WPLANG == 'en_US'  ){ ?>

<strong>Would you like to help translating this plugin?</strong> Contribute a translation using the GlotPress web interface - no technical knowledge required (<strong><a href="<?php echo FDX2_GLOTPRESS;?>" target="_blank">how to</a></strong>)

<?php } else { ?>

<span class="ico_button ico_button_<?php echo WPLANG;?>"><?php _e('Translated by: <a href="http://fabrix.net"><strong>Fabrix DoRoMo</strong></a>', 'fdx-lang') ?></span>

<p><?php _e('If you find any spelling error in this translation or would like to contribute', 'fdx-lang') ?>, <a href="<?php echo FDX2_GLOTPRESS;?>" target="_blank"><?php _e('click here', 'fdx-lang') ?>.</a></p>

<?php } ?>
</div>
</div>

<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Notices', 'fdx-lang') ?></span></h3>
<div class="inside">
 <?php
    $rss = @fetch_feed( 'http://feeds.feedburner.com/fdxplugins/' ); //http://fabrix.net/category/_fdx-feed/feed/

    if ( is_object($rss) ) {

        if ( is_wp_error($rss) ) {
            echo '<p>' . sprintf(__('Newsfeed could not be loaded.  Check the <a href="%s">Newsfeed</a> to check for updates.', 'fdx-lang'), 'http://feeds.feedburner.com/fdxplugins/') . '</p>';
    		return;
        }

        echo '<ul>';
		foreach ( $rss->get_items(0, 3) as $item ) {
    		$link = $item->get_link();
    		while ( stristr($link, 'http') != $link )
    			$link = substr($link, 1);
    		$link = esc_url(strip_tags($link));
    		$title = esc_attr(strip_tags($item->get_title()));
    		if ( empty($title) )
    			$title = __('Untitled');

    		$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
    		$desc = wp_html_excerpt( $desc, 360 );

    		// Append ellipsis. Change existing [...] to [&hellip;].
    		if ( '[...]' == substr( $desc, -5 ) )
    			$desc = substr( $desc, 0, -5 ) . '[&hellip;]';
    		elseif ( '[&hellip;]' != substr( $desc, -10 ) )
    			$desc .= ' [&hellip;]';

    		$desc = esc_html( $desc );

			$date = $item->get_date();
            $diff = '';

			if ( $date ) {

                $diff = human_time_diff( strtotime($date, time()) );

				if ( $date_stamp = strtotime( $date ) )
					$date =  date_i18n( get_option( 'date_format' ), $date_stamp );
				else
					$date = '';
			}
        ?>
          <li><a title="" target="_blank" href='<?php echo $link; ?>'><?php echo $title; ?></a>
		  <em style="font-size: 10px"><?php echo $date; ?> ~ <strong><?php echo $diff; ?></strong></em><br /><?php echo $desc; ?></li>
        <?php
        }
        echo '</ul>';
      }
    ?>

</div>
</div>







<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Do you like this Plugin?', 'fdx-lang') ?></span></h3>
<div class="inside">
<?php _e('Please help to support continued development of this plugin!', 'fdx-lang') ?>
<div align="center"><a href="<?php echo FDX2_DONATELINK;?>" target="_blank"><img src="<?php echo FDX2_PLUGIN_URL;?>/images/btn_donateCC_LG.gif" width="147" height="47" border="0" alt="" /></a></div>
<ul>
<li><a class="sm_button sm_star" href="<?php echo FDX2_WPPAGE;?>" target="_blank"><?php _e('Rate the plugin 5 star on WordPress.org', 'fdx-lang') ?>.</a></li>
<li><a class="sm_button sm_link" href="<?php echo FDX2_PLUGINPAGE ;?>" target="_blank"><?php _e('Blog about it and link to the plugin page', 'fdx-lang') ?>.</a></li>
</ul>

<div align="center">
<a href="javascript:void(0);" onclick="PopupCenter('http://www.facebook.com/sharer.php?u=<?php echo FDX2_PLUGINPAGE;?>&t=<?php echo FDX2_PLUGIN_NAME;?>:', 'facebook',800,550,'no');" title="<?php _e('Share on', 'fdx-lang') ?> Facebook" rel="nofollow"><img src="<?php echo FDX2_PLUGIN_URL;?>/images/facebook.png" width="24" height="24" border="0"  alt="*" style="margin-right: 10px" /></a>
<a href="javascript:void(0);" onclick="PopupCenter('http://twitter.com/share?text=Plugin <?php echo FDX2_PLUGIN_NAME;?>:&amp;url=<?php echo FDX2_PLUGINPAGE;?>', 'twitter',600,450,'no');" title="<?php _e('Share on', 'fdx-lang') ?> Twitter" rel="nofollow"><img src="<?php echo FDX2_PLUGIN_URL;?>/images/twitter.png" width="24" height="24" border="0" alt="*" style="margin-right: 10px" /></a>
<a href="javascript:void(0);" onclick="PopupCenter('https://plus.google.com/share?url=<?php echo FDX2_PLUGINPAGE;?>', 'googleplus',800,550,'no');" title="<?php _e('Share on', 'fdx-lang') ?> Google Plus" rel="nofollow"><img src="<?php echo FDX2_PLUGIN_URL;?>/images/googleplus.png" width="24" height="24" border="0" alt="*" /></a>
</div>

</div>
</div>

</div>
</div>