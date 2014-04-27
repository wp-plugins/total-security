 <style type="text/css">
@media (min-width: 785px){
.auto-fold #wpcontent,.auto-fold #wpfooter{margin-left:52px}
.auto-fold #adminmenuback,.auto-fold #adminmenuwrap,.auto-fold #adminmenu,.auto-fold #adminmenu li.menu-top{width:32px}
.auto-fold #adminmenu .wp-submenu.sub-open,.auto-fold #adminmenu .opensub .wp-submenu,.auto-fold #adminmenu .wp-has-current-submenu .wp-submenu.sub-open,.auto-fold #adminmenu .wp-has-current-submenu.opensub .wp-submenu,.auto-fold #adminmenu a.menu-top:focus+.wp-submenu,.auto-fold #adminmenu .wp-has-current-submenu a.menu-top:focus+.wp-submenu{top:-1px;left:32px}
.auto-fold #adminmenu a.wp-has-current-submenu:focus+.wp-submenu,.auto-fold #adminmenu .wp-has-current-submenu .wp-submenu{border-width:1px;border-style:solid;position:absolute;top:-1000em}
.auto-fold #adminmenu li.menu-top .wp-submenu>li>a{padding-left:12px}
.auto-fold #adminmenu .wp-menu-name{display:none}
.auto-fold #adminmenu .wp-submenu-head{display:block}
.auto-fold #adminmenu div.wp-menu-image{width:32px;position:absolute;z-index:25}
.auto-fold #adminmenu a.menu-top{height:28px}
.auto-fold #adminmenu li .wp-menu-arrow{-moz-transform:translate(32px);-webkit-transform:translate(32px);-o-transform:translate(32px);-ms-transform:translate(32px);transform:translate(32px)}
.auto-fold #adminmenu li .wp-menu-arrow div{display:none}
.auto-fold #adminmenu li.current .wp-menu-arrow,.auto-fold #adminmenu li.current .wp-menu-arrow div,.auto-fold #adminmenu li.wp-has-current-submenu .wp-menu-arrow div,.auto-fold #adminmenu li.wp-menu-open .wp-menu-arrow,.auto-fold #adminmenu li a:focus .wp-menu-arrow{display:block}
.auto-fold #adminmenu li.wp-menu-open{border:0 none}
.auto-fold #adminmenu li.wp-has-current-submenu{margin-bottom:1px}.auto-fold #adminmenu .wp-has-current-submenu.menu-top-last{margin-bottom:0}
.auto-fold #collapse-menu span{display:none}
#wpfooter {display:none  !important; }
}
</style>

<?php
/* wrap
*********************************************************************************/
echo '<div class="wrap">';
echo '<h2>'. $this->pluginname . ' : ' . __('Log Viewer', $this->hook) . '</h2>';

/* poststuff and sidebar
*********************************************************************************/
echo '<div id="poststuff">';
//form
echo '<form method="post" action="">';
// postbox 1
?>

	<?php
		if ( empty( FDX_CLASS_P7::$current_log ) ) {
			$html = '';
		} else {
		    $html = '<pre>';
			$regex = '/(\]\\s(.*?)\:)/'; // capture name error
			$html .= '<table width="350" class="table_log">';
			$html .= '<tbody><tr>';
     		for ( $i=0; $i < count( FDX_CLASS_P7::$current_log ); $i++ ) {
				if ( strpos( FDX_CLASS_P7::$current_log[$i], date( 'd-M-Y' ) ) !== false ) {
					preg_match_all( $regex, FDX_CLASS_P7::$current_log[$i], $lines[$i] );
					$errors[] = $lines[$i][2][0];
				}
			}
			foreach ( array_count_values( $errors ) as $error => $num ) {
				$html .=  '<tr><td class="left-widget">' . $error . '</td><td class="right-widget">' . $num . '</td></tr>';
			}
			$html .= '</tbody></table>';
			$html .= '</pre></div>';
 		}
		$html .= '<div class="clear"></div>';
		// end div.wpvl-widget
		echo $html;


      // buttons
    $numErrors = FDX_CLASS_P7::count_errors();
echo '<div class="button_submit"><p>';
echo submit_button( __('Clear Log', $this->hook ).': '.$numErrors, 'secondary', 'fdx-clear-log', false, array( 'id' => 'fdx-clear-log' ) ) ;
echo '</p></div>';
echo '</form>'; //form 1
?>

<?php
		   $html = '<pre>';
				if ( is_array( FDX_CLASS_P7::$current_log ) && !empty( FDX_CLASS_P7::$current_log ) ) {
					$html .= '<table class="table_log">';
					$html .= '<tbody><tr><td>';
					for ( $i=1; $i <= count( FDX_CLASS_P7::$current_log ); $i++ ) {
						$html .= '<div>' . $i . '</div>';
					}
					$html .= '</td><td>';
					foreach ( FDX_CLASS_P7::$current_log as $line => $string ) {
                         if(preg_match("/PHP Fatal error:/", $string)) {
                         $style2 = 'fdx-colo0';
                         } elseif  (preg_match("/PHP Deprecated:/", $string)) {
                         $style2 = 'fdx-colo2';
                         } elseif  (preg_match("/PHP Warning:/", $string)) {
                         $style2 = 'fdx-colo3';
                         } elseif  (preg_match("/PHP Notice:/", $string)) {
                         $style2 = 'fdx-colo1';
                         } elseif  (preg_match("/PHP Strict Standards:/", $string)) {
                         $style2 = '';
                         } else {
                         $style2 = 'fdx-colo4';
                         }
 						// eliminating occasional line breaks in the string
						$string = str_replace( array( "\r\n", "\r", "\n"), '', $string );
						$html .= '<div class="'.$style2.'">' .  $string  . '</div>';
					}
					$html .= '</td></tr></tbody></table>';
				} else {
					$html .= sprintf( __( '%1sWithout Error%2s','wpvllang' ), '<p class="str">','</p>' );
				}
		  $html .= '</pre>';

				echo $html;
?>



<div class="clear"></div>

<?php

// postbox-container | post-body | poststuff | wrap
echo '</div></div>';
//----------------------------------------- ?>
