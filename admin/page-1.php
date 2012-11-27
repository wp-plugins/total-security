<?php
/* DEFAULT OPTIONS
*------------------------------------------------------------*/
add_option('wp_twitter_fdx_widget_title', 'Widget Title');
add_option('wp_twitter_fdx_username', 'wordpress');
add_option('wp_twitter_fdx_width', '250');
add_option('wp_twitter_fdx_height', '300');
add_option('wp_twitter_fdx_scrollbar', '-1');
add_option('wp_twitter_fdx_shell_bg', '#333333');
add_option('wp_twitter_fdx_shell_text', '#ffffff');
add_option('wp_twitter_fdx_tweet_bg', '#000000');
add_option('wp_twitter_fdx_tweet_text', '#ffffff');
add_option('wp_twitter_fdx_links', '#4aed05');
add_option('wp_twitter_fdx_behavior', '-1');

add_option('wp_twitter_fdx_widget_search_query', 'wordpress');
add_option('wp_twitter_fdx_widget_search_title', 'Widget Search Title');
add_option('wp_twitter_fdx_widget_search_caption', 'Search Caption');
add_option('wp_twitter_fdx_search_width', '250');
add_option('wp_twitter_fdx_search_height', '300');
add_option('wp_twitter_fdx_search_scrollbar', '-1');
add_option('wp_twitter_fdx_search_shell_bg', '#333333');
add_option('wp_twitter_fdx_search_shell_text', '#ffffff');
add_option('wp_twitter_fdx_search_tweet_bg', '#000000');
add_option('wp_twitter_fdx_search_tweet_text', '#ffffff');
add_option('wp_twitter_fdx_search_links', '#4aed05');
add_option('wp_twitter_fdx_search_widget_sidebar_title', 'Sidebar Title');



function filter_wp_twitter_fdx_profile($content)
{
    if (strpos($content, "[--wp_twitter--]") !== FALSE)
    {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('[--wp_twitter--]', wp_twitter_fdx_profile(), $content);
    }
    return $content;
}

function filter_wp_twitter_fdx_search($content)
{
    if (strpos($content, "[--wp_twitter_search--]") !== FALSE)
    {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('[--wp_twitter_search--]', wp_twitter_fdx_search(), $content);
    }
    return $content;
}


function wp_twitter_fdx_profile()
{
	$account = get_option('wp_twitter_fdx_username');
	$height = get_option('wp_twitter_fdx_height');
	$width = get_option('wp_twitter_fdx_width');

	if (get_option('wp_twitter_fdx_scrollbar') == 1){
		$scrollbar = "true";
	}else
	{
		$scrollbar = "false";
	}

	if (get_option('wp_twitter_fdx_behavior') == 1){
		$loop1 = "false";
		$behavior1 = "all";
	}else
	{
		$loop1 = "true";
		$behavior1 = "default";
	}

	$shell_bg = get_option('wp_twitter_fdx_shell_bg');
	$shell_text = get_option('wp_twitter_fdx_shell_text');
	$tweet_bg = get_option('wp_twitter_fdx_tweet_bg');
	$tweet_text = get_option('wp_twitter_fdx_tweet_text');
	$links = get_option('wp_twitter_fdx_links');

		$T1 = "new TWTR.Widget({  version: 2,  type: 'profile',  rpp: 4,  interval: 5000,  width: ";
			$v1 = $width;
		$T2 = ",  height: ";
			$v2 = $height;
		$T3 = ",  theme: {    shell: {      background: '";
			$v3 = $shell_bg;
		$T4 = "',      color: '";
			$v4 = $shell_text;
		$T5 = "'    },    tweets: {      background: '";
			$v5 = $tweet_bg;
		$T6 = "',      color: '";
			$v6 = $tweet_text;
		$T7 = "',      links: '";
			$v7 = $links;
		$T8 = "'    }  },  features: {    scrollbar: ";
		    $v8 = $scrollbar;
		$T9 = ",    loop: ";
			$v9 = $loop1;
		$T10 = ",    live: true, behavior: '";
			$v10 = $behavior1;
		$T11 = "'  }}).render().setUser('";
			$v11 = $account;
		$T12 = "').start();";

	$output = '<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script><script>' . $T1 . $v1 . $T2 . $v2 . $T3 . $v3 . $T4 . $v4 . $T5 . $v5 . $T6 . $v6 . $T7 . $v7 . $T8 . $v8 . $T9 . $v9 . $T10 . $v10 . $T11 . $v11 . $T12 . '</script>';

	$output_profile = $output;

	return $output_profile;
}

function wp_twitter_fdx_search()
{
	$search_query = get_option('wp_twitter_fdx_widget_search_query');
	$search_title = get_option('wp_twitter_fdx_widget_search_title');
	$search_caption = get_option('wp_twitter_fdx_widget_search_caption');
	$account = get_option('wp_twitter_fdx_username');

	$search_sidebar_title = get_option('wp_twitter_fdx_search_widget_sidebar_title');



	$search_height = get_option('wp_twitter_fdx_search_height');
	$search_width = get_option('wp_twitter_fdx_search_width');


		if (get_option('wp_twitter_fdx_search_scrollbar') == 1){
			$search_scrollbar = "true";
		}else
		{
			$search_scrollbar = "false";
		}

		$search_shell_bg = get_option('wp_twitter_fdx_search_shell_bg');
		$search_shell_text = get_option('wp_twitter_fdx_search_shell_text');
		$search_tweet_bg = get_option('wp_twitter_fdx_search_tweet_bg');
		$search_tweet_text = get_option('wp_twitter_fdx_search_tweet_text');
		$search_links = get_option('wp_twitter_fdx_search_links');

		$T11 = "new TWTR.Widget({  version: 2,  type: 'search', search: '";
			$S1 = $search_query;
		$T12 = "', interval:6000, title: '";
			$S2 = $search_title;
		$T13 = "', subject: '";
			$S3 = $search_caption;
		$T14 = "', width: ";
			$v1 = $search_width;
		$T2 = ",  height: ";
			$v2 = $search_height;
		$T3 = ",  theme: {    shell: {      background: '";
			$v3 = $search_shell_bg;
		$T4 = "',      color: '";
			$v4 = $search_shell_text;
		$T5 = "'    },    tweets: {      background: '";
			$v5 = $search_tweet_bg;
		$T6 = "',      color: '";
			$v6 = $search_tweet_text;
		$T7 = "',      links: '";
			$v7 = $search_links;
		$T8 = "'    }  },  features: {    scrollbar: ";
			$v8 = $search_scrollbar;
		$T9 = ",    loop: ";
			$v9 = "true";
		$T10 = ",    live: true, behavior: 'default'  }}).render().start();";

		$output1 = '<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script><script>' . $T11 .$S1 . $T12 . $S2 . $T13 . $S3 . $T14 . $v1 . $T2 . $v2 . $T3 . $v3 . $T4 . $v4 . $T5 . $v5 . $T6 . $v6 . $T7 . $v7 . $T8 . $v8 . $T9 . $v9 . $T10 . '</script>';

		$output_search = $output1;

	return $output_search;
}

// Displays Wordpress Blog Twitter fdx Options menu
function wp_twitter_fdx_options_page() {


    if (isset($_POST['info_update']))
    {
		update_option('wp_twitter_fdx_widget_title', stripslashes_deep((string)$_POST["wp_twitter_fdx_widget_title"]));
        update_option('wp_twitter_fdx_username', (string)$_POST["wp_twitter_fdx_username"]);
        update_option('wp_twitter_fdx_height', (string)$_POST['wp_twitter_fdx_height']);
		update_option('wp_twitter_fdx_width', (string)$_POST['wp_twitter_fdx_width']);
		update_option('wp_twitter_fdx_scrollbar', (@$_POST['wp_twitter_fdx_scrollbar']=='1') ? '1':'-1' );
		update_option('wp_twitter_fdx_behavior', (@$_POST['wp_twitter_fdx_behavior']=='1') ? '1':'-1' );
		update_option('wp_twitter_fdx_shell_bg', (string)$_POST['wp_twitter_fdx_shell_bg']);
		update_option('wp_twitter_fdx_shell_text', (string)$_POST['wp_twitter_fdx_shell_text']);
		update_option('wp_twitter_fdx_tweet_bg', (string)$_POST['wp_twitter_fdx_tweet_bg']);
		update_option('wp_twitter_fdx_tweet_text', (string)$_POST['wp_twitter_fdx_tweet_text']);
		update_option('wp_twitter_fdx_links', (string)$_POST['wp_twitter_fdx_links']);
		update_option('wp_twitter_fdx_widget_search_query', stripslashes_deep((string)$_POST['wp_twitter_fdx_widget_search_query']));
		update_option('wp_twitter_fdx_widget_search_title', stripslashes_deep((string)$_POST['wp_twitter_fdx_widget_search_title']));
		update_option('wp_twitter_fdx_widget_search_caption', stripslashes_deep((string)$_POST['wp_twitter_fdx_widget_search_caption']));
        update_option('wp_twitter_fdx_search_height', (string)$_POST['wp_twitter_fdx_search_height']);
		update_option('wp_twitter_fdx_search_width', (string)$_POST['wp_twitter_fdx_search_width']);
		update_option('wp_twitter_fdx_search_scrollbar', (@$_POST['wp_twitter_fdx_search_scrollbar']=='1') ? '1':'-1' );
		update_option('wp_twitter_fdx_search_shell_bg', (string)$_POST['wp_twitter_fdx_search_shell_bg']);
		update_option('wp_twitter_fdx_search_shell_text', (string)$_POST['wp_twitter_fdx_search_shell_text']);
		update_option('wp_twitter_fdx_search_tweet_bg', (string)$_POST['wp_twitter_fdx_search_tweet_bg']);
		update_option('wp_twitter_fdx_search_tweet_text', (string)$_POST['wp_twitter_fdx_search_tweet_text']);
		update_option('wp_twitter_fdx_search_links', (string)$_POST['wp_twitter_fdx_search_links']);
		update_option('wp_twitter_fdx_search_widget_sidebar_title', (string)$_POST['wp_twitter_fdx_search_widget_sidebar_title']);
        echo '<div class="updated fade"><p><strong>' . __( 'Settings updated', 'fdx-lang' ) . '.</strong></p></div>';
        } else {

	}
//adicionar icones e lang patch

?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2><?php echo FDX1_PLUGIN_NAME;?>: <?php _e('Widgets Settings', 'fdx-lang') ?></h2>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">

<?php include('inc/sidebar.php'); ?>

<div class="postbox-container">
<div class="meta-box-sortables">

<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<input type="hidden" name="info_update" id="info_update" value="true" />

<div class="postbox">
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Twitter Profile Widget Options', 'fdx-lang') ?></span></h3>
<div class="inside">
<!-- ############################################################################################################### -->
 <table style="width: auto">
   <tr>
     <td style="width: 400px; text-align: left; vertical-align: top">
 <ul>
 <li><input name="wp_twitter_fdx_widget_title" type="text" size="20" value="<?php echo get_option('wp_twitter_fdx_widget_title'); ?>"> &raquo; <?php _e('Widget Title', 'fdx-lang') ?> *</li>
  <li><input name="wp_twitter_fdx_username" type="text" size="20" value="<?php echo get_option('wp_twitter_fdx_username'); ?>" /> &raquo; <?php _e('Twitter Username (for this Widget)', 'fdx-lang') ?></li>
    <li><input name="wp_twitter_fdx_width" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_width'); ?>" /> &raquo; <?php _e('Widget Width', 'fdx-lang') ?>  </li>
     <li><input name="wp_twitter_fdx_height" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_height'); ?>" /> &raquo; <?php _e('Widget Height', 'fdx-lang') ?></li>

     <li><p><input name="wp_twitter_fdx_scrollbar" type="checkbox"<?php if(get_option('wp_twitter_fdx_scrollbar')!='-1') echo 'checked="checked"'; ?> value="1" /> &raquo;  <?php _e('Include Scrollbar?', 'fdx-lang') ?></p></li>
      <li><p><input name="wp_twitter_fdx_behavior" type="checkbox"<?php if(get_option('wp_twitter_fdx_behavior')!='-1') echo 'checked="checked"'; ?> value="1" /> &raquo; <?php _e('Load all Tweets/Time Interval? <code>total 30</code>', 'fdx-lang') ?></p> </li>

       <li><input class="widget-colors" rel="shell-bg" id="sw-shell-background" name="wp_twitter_fdx_shell_bg" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_shell_bg'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Shell Background Color', 'fdx-lang') ?></li>
        <li><input class="widget-colors" rel="shell-color" id="sw-shell-color" name="wp_twitter_fdx_shell_text" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_shell_text'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Shell Text Color', 'fdx-lang') ?></li>
         <li><input class="widget-colors" rel="tweet-background" id="sw-tweet-background" name="wp_twitter_fdx_tweet_bg" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_tweet_bg'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Tweet Background Color', 'fdx-lang') ?></li>
       <li>	<input class="widget-colors" rel="tweet-color" id="sw-tweet-text" name="wp_twitter_fdx_tweet_text" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_tweet_text'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Tweet Text Color', 'fdx-lang') ?></li>
        <li><input class="widget-colors" rel="tweet-links" id="sw-tweet-links" name="wp_twitter_fdx_links" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_links'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Link Color', 'fdx-lang') ?></li>
 </ul>
 *<em><small>(<?php _e('The format will depend on your theme', 'fdx-lang') ?>)</small></em>
</td>
<td style="width: auto; text-align: center; vertical-align: top;">


     <div style="padding: 2px;"><code> <?php echo get_option('wp_twitter_fdx_widget_title'); ?> </code> *</div>
             <div id="example-preview-widget"></div>
 </td>
</tr>
 </table>


<!-- ############################################################################################################### -->
</div>
</div>

<div class="postbox  closed" >
<div class="handlediv" title="<?php _e('Click to toggle', 'fdx-lang') ?>"><br /></div><h3 class='hndle'><span><?php _e('Twitter Search Widget Options', 'fdx-lang') ?></span></h3>
<div class="inside">
<!-- ############################################################################################################### -->





 <table style="width: auto">
   <tr>
     <td style="width: 400px; text-align: left; vertical-align: top">
<ul>
<li><input name="wp_twitter_fdx_search_widget_sidebar_title" type="text" size="20" value="<?php echo get_option('wp_twitter_fdx_search_widget_sidebar_title'); ?>"> &raquo; <?php _e('Widget Title', 'fdx-lang') ?> *</li>
<li><input name="wp_twitter_fdx_widget_search_query" type="text" size="20" value="<?php echo get_option('wp_twitter_fdx_widget_search_query'); ?>"> &raquo; <?php _e('Search Query', 'fdx-lang') ?> <span id="butpop"><a href="javascript:void(0);" onclick="PopupCenter('https://twitter.com/#!/search-home', 'page2_id1',900,500,'yes');"><code class="red">?</code></a></span></li>
<li><input name="wp_twitter_fdx_widget_search_title" type="text" size="20" value="<?php echo get_option('wp_twitter_fdx_widget_search_title'); ?>"> &raquo; <?php _e('Search Title', 'fdx-lang') ?></li>
<li><input name="wp_twitter_fdx_widget_search_caption" type="text" size="20" value="<?php echo get_option('wp_twitter_fdx_widget_search_caption'); ?>"> &raquo; <?php _e('Search Caption', 'fdx-lang') ?></li>
<li><input name="wp_twitter_fdx_search_width" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_width'); ?>" /> &raquo; <?php _e('Widget Width', 'fdx-lang') ?></li>
<li><input name="wp_twitter_fdx_search_height" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_height'); ?>" /> &raquo; <?php _e('Widget Height', 'fdx-lang') ?></li>
<li><p>Include Scrollbar?: <input name="wp_twitter_fdx_search_scrollbar" type="checkbox"<?php if(get_option('wp_twitter_fdx_search_scrollbar')!='-1') echo 'checked="checked"'; ?> value="1" /> <code><?php _e('Check to include Scrollbar', 'fdx-lang') ?> </code></p></li>

<li><input class="widget-colors2" rel="shell-bg2" id="sw-shell-background2" name="wp_twitter_fdx_search_shell_bg" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_shell_bg'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Shell Background Color', 'fdx-lang') ?></li>
<li><input class="widget-colors2" rel="shell-color2" id="sw-shell-color2" name="wp_twitter_fdx_search_shell_text" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_shell_text'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Shell Text Color', 'fdx-lang') ?></li>
<li><input class="widget-colors2" rel="tweet-background2" id="sw-tweet-background2" name="wp_twitter_fdx_search_tweet_bg" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_tweet_bg'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Tweet Background Color', 'fdx-lang') ?> </li>
<li><input class="widget-colors2" rel="tweet-color2" id="sw-tweet-text2" name="wp_twitter_fdx_search_tweet_text" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_tweet_text'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Widget Tweet Text Color', 'fdx-lang') ?> </li>
<li><input class="widget-colors2" rel="tweet-links2" id="sw-tweet-links2" name="wp_twitter_fdx_search_links" type="text" size="7" value="<?php echo get_option('wp_twitter_fdx_search_links'); ?>" /><b style=" padding: 4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> &raquo; <?php _e('Link Color', 'fdx-lang') ?> </li>
</ul>
*<em><small>(<?php _e('The format will depend on your theme', 'fdx-lang') ?>)</small></em>
</td>
<td style="width: auto; text-align: center; vertical-align: top;">

<div style="padding: 2px;"><code><?php echo get_option('wp_twitter_fdx_search_widget_sidebar_title'); ?></code> *</div>

<div id="example-preview-widget2"></div>
</td>
</tr>
 </table>

<!-- ############################################################################################################### -->
</div>
</div>



<div align="center"><input name="Submit" class="button-primary" type="submit" value="<?php _e('Save All Options', 'fdx-lang') ?>" /></div>
</form>
</div> <!-- /postbox-container -->
</div><!-- /meta-box-sortables -->



</div><!-- /post-body -->
</div><!-- /poststuff -->


</div><!-- /wrap -->



<?php include('inc/footer_js.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js " type="text/javascript"></script>

<script charset="utf-8"  src="<?php echo FDX1_PLUGIN_URL;?>/js/twitter-text-js.js"></script>
<script src="<?php echo FDX1_PLUGIN_URL;?>/js/colorpicker.js" type="text/javascript"></script>
<script src="<?php echo FDX1_PLUGIN_URL;?>/js/goodies2.js" type="text/javascript"></script>
<script src="<?php echo FDX1_PLUGIN_URL;?>/js/goodies.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
  var DemoWidget = new TWTR.Widget({
    version: 2,
    type: 'profile',
    width: <?php echo get_option('wp_twitter_fdx_width'); ?>,
    height: <?php echo get_option('wp_twitter_fdx_search_height'); ?>,
    creator: true,
    id: 'example-preview-widget',
    rpp: 4,
    features: {
      live: false,
      scrollbar: false,
      behavior: 'all',
      loop: false
    },
    theme: {
      shell: {
        background: '<?php echo get_option('wp_twitter_fdx_shell_bg'); ?>',
        color: '<?php echo get_option('wp_twitter_fdx_shell_text'); ?>'
      },
      tweets: {
        background: '<?php echo get_option('wp_twitter_fdx_tweet_bg'); ?>',
        color: '<?php echo get_option('wp_twitter_fdx_tweet_text'); ?>',
        links: '<?php echo get_option('wp_twitter_fdx_links'); ?>'
      }
    }
  }).render().setUser('<?php echo get_option('wp_twitter_fdx_username'); ?>').start();
  $('#rpp-range').rangeInput([1, 30], {
    id: 'rpp-input',
    def: 4
  });

  function testSettings(e) {
    if (e) {
      e.preventDefault();
    }
    var user = h($('#sw-username').val());
    if (!user.match(/^[@@]?([a-zA-Z0-9_]{1,20})$/)) {
      alert(_("Whoops! That's not a valid username"));
      return false;
    }
    var opts = goodies.getWidgetOptions();
    DemoWidget
      .destroy()
      .setFeatures({
        live: opts.live,
        scrollbar: opts.scrollbar,
        behavior: opts.behavior,
        loop: opts.loop
      })
      .setDimensions(250, 300)
      .setRpp(opts.rpp)
      .setTweetInterval(opts.interval)
      .setUser(user).render().start();
  }

  var getWidgetCode = function() {
    var user = h($('#sw-username').val());

    var theme = goodies.getWidgetTheme();
    var opts = goodies.getWidgetOptions();

    var codeHead = '<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></scr' + 'ipt>\n\<scr' + 'ipt>';
    var codeFoot = '</scr' + 'ipt>';
    var codeJavaScript = '\nnew TWTR.Widget({\n\
  version: 2,\n\
  type: \'profile\',\n\
  rpp: ' + opts.rpp + ',\n\
  interval: ' + opts.interval + ',\n\
  width: ' + opts.width + ',\n\
  height: ' + opts.height + ',\n\
  theme: {\n\
    shell: {\n\
      background: \'' + theme.shell.background + '\',\n\
      color: \'' + theme.shell.color + '\'\n\
    },\n\
    tweets: {\n\
      background: \'' + theme.tweets.background + '\',\n\
      color: \'' + theme.tweets.color + '\',\n\
      links: \'' + theme.tweets.links + '\'\n\
    }\n\
  },\n\
  features: {\n\
    scrollbar: ' + opts.scrollbar + ',\n\
    loop: ' + opts.loop + ',\n\
    live: ' + opts.live + ',\n\
    behavior: \'' + opts.behavior + '\'\n\
  }\n\
}).render().setUser(\'' + user + '\').start();\n';

    var code = codeHead + codeJavaScript + codeFoot;
    return code;
  };
      page.controller_name = 'GoodiesController';
      page.action_name = 'widget_profile';
      twttr.form_authenticity_token = 'd0c52c1551855ad7d883325e18399b6e9f890f00';
      $.ajaxSetup({ data: { authenticity_token: 'd0c52c1551855ad7d883325e18399b6e9f890f00' } });

      // FIXME: Reconcile with the kinds on the Status model.
      twttr.statusKinds = {
        UPDATE: 1,
        SHARE: 2
      };
      twttr.ListPerUserLimit = 20;
//]]>
</script>

<script type="text/javascript">
//<![CDATA[
  var DemoWidget = new TWTR.Widget({
    creator: true,
    version: 2,
    type: 'search',
    id: 'example-preview-widget2',
    search: '<?php echo get_option('wp_twitter_fdx_widget_search_query'); ?>',
    title: '<?php echo get_option('wp_twitter_fdx_widget_search_title'); ?>',
    subject: '<?php echo get_option('wp_twitter_fdx_widget_search_caption'); ?>',
    rpp: 30,
    width: <?php echo get_option('wp_twitter_fdx_search_width'); ?>,
    height: <?php echo get_option('wp_twitter_fdx_search_height'); ?>,
    interval: 6000,
    features: {
      loop: true,
      live: true,
      scrollbar: <?php  if (get_option('wp_twitter_fdx_search_scrollbar') == 1){$search_scrollbar = "true";}else{$search_scrollbar = "false";} echo $search_scrollbar;?> ,
      behavior: 'default'
    },
    theme: {
      shell: {
        background: '<?php echo get_option('wp_twitter_fdx_search_shell_bg'); ?>',
        color: '<?php echo get_option('wp_twitter_fdx_search_shell_text'); ?>'
      },
      tweets: {
        background: '<?php echo get_option('wp_twitter_fdx_search_tweet_bg'); ?>',
        color: '<?php echo get_option('wp_twitter_fdx_search_tweet_text'); ?>',
        links: '<?php echo get_option('wp_twitter_fdx_search_links'); ?>'
      }
    }
    ,

    ready: function() {
      $('#sw-widget-caption').mirror('#example-preview-widget h4');
      $('#sw-widget-title').mirror('#example-preview-widget h2');
    }
  }).render().start();

  $('#rpp-range').rangeInput([1, 100], {
    id: 'rpp-input',
    def: 30
  });

  function testSettings(e) {
    if (e) {
      e.preventDefault();
    }
    var searchTerms = $('#sw-search-terms').val();
    var title = h($('#sw-widget-title').val());
    var caption = h($('#sw-widget-caption').val());

    var opts = goodies.getWidgetOptions();

    DemoWidget
      .destroy()
      .setFeatures({
        live: opts.live,
        scrollbar: opts.scrollbar,
        behavior: opts.behavior,
        loop: opts.loop
      })
      .setDimensions(250, 300)
      .setSearch(searchTerms)
      .setTweetInterval(opts.interval)
      .setRpp(opts.rpp)
      .setTitle(title)
      .setCaption(caption)
      .render().start();
  }

    function getWidgetCode() {

      var search = addSlashes($('#sw-search-terms').val());
      var title = addSlashes(h($('#sw-widget-title').val()));
      var subject = addSlashes(h($('#sw-widget-caption').val()));

      var theme = goodies.getWidgetTheme();
      var opts = goodies.getWidgetOptions();

      var codeHead = '<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></scr' + 'ipt>\n\
<scr' + 'ipt>';
      var codeFoot = '</scr' + 'ipt>';
      var codeJavaScript = '\nnew TWTR.Widget({\n\
  version: 2,\n\
  type: \'search\',\n\
  search: \'' + search + '\',\n\
  interval: ' + opts.interval + ',\n\
  title: \'' + title + '\',\n\
  subject: \'' + subject + '\',\n\
  width: ' + opts.width + ',\n\
  height: ' + opts.height + ',\n\
  theme: {\n\
    shell: {\n\
      background: \'' + theme.shell.background + '\',\n\
      color: \'' + theme.shell.color + '\'\n\
    },\n\
    tweets: {\n\
      background: \'' + theme.tweets.background + '\',\n\
      color: \'' + theme.tweets.color + '\',\n\
      links: \'' + theme.tweets.links + '\'\n\
    }\n\
  },\n\
  features: {\n\
    scrollbar: ' + opts.scrollbar + ',\n\
    loop: ' + opts.loop + ',\n\
    live: ' + opts.live + ',\n\
    behavior: \'' + opts.behavior + '\'\n\
  }\n\
}).render().start();\n';

      var code = codeHead + codeJavaScript + codeFoot;
      return code;
    }


      page.controller_name = 'GoodiesController2';
      page.action_name = 'widget_search';
      twttr.form_authenticity_token = 'ed613f8a8ab1fe51325af8dbdc56fdc04306ad65';
      $.ajaxSetup({ data: { authenticity_token: 'ed613f8a8ab1fe51325af8dbdc56fdc04306ad65' } });

      // FIXME: Reconcile with the kinds on the Status model.
      twttr.statusKinds = {
        UPDATE: 1,
        SHARE: 2
      };
      twttr.ListPerUserLimit = 20;
//]]>
</script>



<div class="clear"></div>
<?php } //fim da pagina de opções

function show_wp_twitter_fdx_profile_widget($args)
{
	extract($args);
	$wp_twitter_fdx_widget_title1 = get_option('wp_twitter_fdx_widget_title');
	echo $before_widget;
	echo $before_title . $wp_twitter_fdx_widget_title1 . $after_title;
    echo wp_twitter_fdx_profile();
    echo $after_widget;
}

function show_wp_twitter_fdx_search_widget($args)
{
	extract($args);
	$wp_twitter_fdx_widget_title1 = get_option('wp_twitter_fdx_search_widget_sidebar_title');
	echo $before_widget;
	echo $before_title . $wp_twitter_fdx_widget_title1 . $after_title;

    echo wp_twitter_fdx_search();
    echo $after_widget;
}


function wp_twitter_fdx_profile_widget_control()
{
printf(__('Please go to <b>%s | Widgets</b> for options. <br><br> Available options: <br> 1) Widget Title <br> 2) Twitter Username <br> 3) Widget Height <br> 4) Widget Width <br> 5) 5 different Shell and Tweet background and text color options', 'fdx-lang'), FDX1_PLUGIN_NAME);
}


function wp_twitter_fdx_search_widget_control()
{
printf(__('Please go to <b>%s | Widgets</b> for options. <br><br> Available options: <br> 1) Widget Title <br> 2) Search Query <br> 3) Search Title <br> 4) Search Caption <br> 5) 5 different Shell and Tweet background and text color options', 'fdx-lang'), FDX1_PLUGIN_NAME);
}

function widget_wp_twitter_fdx_profile_init()
{
    $widget_options = array('classname' => 'widget_wp_twitter_fdx_profile', 'description' => __('Display Twitter Profile Widget', 'fdx-lang') );
    wp_register_sidebar_widget('wp_twitter_fdx_profile_widgets', FDX1_PLUGIN_NAME . __(' - Profile Widget', 'fdx-lang'), 'show_wp_twitter_fdx_profile_widget', $widget_options);
    wp_register_widget_control('wp_twitter_fdx_profile_widgets', FDX1_PLUGIN_NAME . __(' - Profile Widget', 'fdx-lang'), 'wp_twitter_fdx_profile_widget_control' );
}

function widget_wp_twitter_fdx_search_init()
{
    $widget_options = array('classname' => 'widget_wp_twitter_fdx_search', 'description' => __('Display Twitter Search Widget', 'fdx-lang') );
    wp_register_sidebar_widget('wp_twitter_fdx_search_widgets', FDX1_PLUGIN_NAME. __(' - Search Widget', 'fdx-lang'), 'show_wp_twitter_fdx_search_widget', $widget_options);
    wp_register_widget_control('wp_twitter_fdx_search_widgets', FDX1_PLUGIN_NAME. __(' - Search Widget', 'fdx-lang'), 'wp_twitter_fdx_search_widget_control' );
}

add_filter('the_content', 'filter_wp_twitter_fdx_profile');
add_filter('the_content', 'filter_wp_twitter_fdx_search');

add_action('init', 'widget_wp_twitter_fdx_profile_init');
add_action('init', 'widget_wp_twitter_fdx_search_init');

?>