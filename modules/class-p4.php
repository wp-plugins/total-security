<?php
class FDX_CLASS_P4 extends Total_Security {

/*
 * Constructor for each and every page load
 */
function __construct() {
        $settings = FDX_Process::fdx_get_settings();
               if ( $settings['p4_check_1'] ) {
                  add_action( 'wp_head', array( $this,'fdxcheck404' ) );
                  	}
}
		
/*
 * Checks if current resource is a 404 and logs accordingly
 */
function fdxcheck404() {
if ( is_404() ) { //if we're on a 404 page
$this->fdx_logevent( 2 );
			}
}

/**
* Returns the actual IP address of the user
*
* @return  String The IP address of the user
*
**/
		function fdxgetIp() {
			//Just get the headers if we can or else use the SERVER global
			if ( function_exists( 'apache_request_headers' ) ) {
				$headers = apache_request_headers();
			} else {
				$headers = $_SERVER;
			}
			if ( array_key_exists( 'X-Forwarded-For', $headers ) ) {
				$theIP = $headers['X-Forwarded-For'];
			} else {
				$theIP = $_SERVER['REMOTE_ADDR'];
			}
			return $theIP;
              		}
		
          function getRefe() {
    		//Just get the headers if we can or else use the SERVER global
			if ( function_exists( 'apache_request_headers' ) ) {
				$headers = apache_request_headers();
			} else {
				$headers = $_SERVER;
			}
			if ( array_key_exists( 'X-Forwarded-For', $headers ) ) {
				$theRefe = $headers['X-Forwarded-For'];
			} else {

                if ( isset( $_SERVER['HTTP_REFERER']  ) ) {
					$theRefe = $_SERVER['HTTP_REFERER'];
				} else {
 					$theRefe = '';
				}
        	}
			return $theRefe;
              		}


		/**
		 * Logs security related events to the database
		 *
		 * Logs security related events for bad logins or 404s to the database
		 *
		 *
		 **/
		function fdx_logevent() {
 			global $wpdb;

 //       define( 'DONOTCACHEPAGE', true );		// WP Super Cache and W3 Total Cache recognise this

 			//get default data
			$host = $wpdb->escape( $this->fdxgetIp() );
 			$url = $wpdb->escape( $_SERVER['REQUEST_URI'] );
            $referrer = $wpdb->escape( $this->getRefe() );

			//log to database
			$wpdb->insert(
				$wpdb->base_prefix . 'total_security_log',
				array(
					'timestamp' => current_time( 'timestamp' ),
					'host' => $host,
					'url' => $url,
					'referrer' => $referrer
				)
			);

                     		}
				
	} //end class


//--------------------------------------------table
//--------------------------------------------------------------------------------------------------------

//make syre we have the WordPress class
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//table of all 404's
	class fdx_log_content_table extends WP_List_Table {

		/**
		 * Define Columns
		 *
		 * @return array array of column titles
		 *
		 **/
		function get_columns() {
           global $fdx_lg;

			return array(
                'cb'        => '<input type="checkbox" />',
                'time'		=> __( 'Data', $fdx_lg->hook),
                'uri'		=> __( 'URI', $fdx_lg->hook ),
                'referrer' 	=> __( 'Referrer', $fdx_lg->hook ),
				'host'		=> __( 'Host', $fdx_lg->hook ),
			);

		}

		/**
		 * Define Sortable Columns
		 *
		 * @return array of column titles that can be sorted
		 *
		 **/
		function get_sortable_columns() {

        $count_order = ( empty( $_GET['order'] ) ) ? false : true;
		$sortable_columns = array(
			'time'  => array('timestamp',true),
            'uri'   => array('url',true),
            'referrer'   => array('referrer',true),
            'host'   => array('host',true),
		);
		return $sortable_columns;
        }






/*-----------------------FDX---------------------------*/
        function get_bulk_actions() {
        global $fdx_lg;
        return array(
            'delete'    => __( 'Delete', $fdx_lg->hook ),
        );
    }
/*-----------------------FDX---------------------------*/



		/**
		 * Define time column
		 *
		 * @param array $item array of row data
		 * @return string formatted output
		 *
		 **/
		function column_time( $item ) {
            $r = date( 'Y/m/d H:i:s', $item['timestamp'] );
            return $r;
		}

		/**
		 * Define host column
		 *
		 * @param array $item array of row data
		 * @return string formatted output
		 *
		 **/
		function column_host( $item ) {
 	         $r = '<a href="http://whois.domaintools.com/' . $item['host'] . '" target="_blank">' . $item['host'] . '</a>';
            return $r;


		}

		/**
		 * Define added column
  		 *
		 **/
		function column_uri( $item ) {
            $r = '<div class="comment more">' . $item['uri'] . '</div>';
            return $r;
       		}

		/**
		 *
		 *
		 **/
		function column_referrer( $item ) {
             $r = '<div class="crop"><a href="' . $item['referrer'] . '" target="_blank" title="' . $item['referrer'] . '">' . $item['referrer'] . '</a></div>';
            return $r;
		}


/*-----------------------FDX---------------------------*/
        function column_cb( $item ) {
        $id = $item['id'];
//o cochete "[]" faz selecionar multiplos, sem ele somente seleciona 1
        return "<input type='checkbox' name='404s[]' id='404s' value='$id' />";
  		}

    private function bulk_delete() {
    	global $wpdb;
		if( empty( $_REQUEST['404s'] ) )
		return;
        $item_ids = (array) $_REQUEST['404s'];
	    $item_ids = implode(',', $item_ids);
        return $wpdb->query( "DELETE FROM " . $wpdb->base_prefix . "total_security_log WHERE id IN ($item_ids)" );
    }
/*-----------------------FDX---------------------------*/


		/**
		 * Prepare data for table
		 *
		 **/
		function fdx_prepare_items() {
            global $wpdb;

/*-----------------------FDX---------------------------*/
       // process bulk deletes
	        if( 'delete' === $this->current_action() ) {
			$deleted = $this->bulk_delete();
			echo '<div id="message" class="updated"><p>' . $deleted . ' rows deleted</p></div>';
	    	}
/*-----------------------FDX---------------------------*/

			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();

            $search = isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '' ;
            if ( ! empty( $search ) ) {
			$search = like_escape( $search );
			$search = "WHERE url LIKE '%$search%' OR host LIKE '%$search%' OR referrer LIKE '%$search%'";
            }

	 		$this->_column_headers = array( $columns, $hidden, $sortable );
            $data = $wpdb->get_results( "SELECT id, referrer, timestamp, host, url FROM `" . $wpdb->base_prefix . "total_security_log` $search ORDER BY timestamp DESC;", ARRAY_A );


            usort ( $data, array( &$this, 'sortrows' ) );

			$per_page = 50; //50 items per page
			$current_page = $this->get_pagenum();
			$total_items = count( $data );

			$data = array_slice( $data,( ( $current_page - 1 ) * $per_page ), $per_page );




			$rows = array();
			$count = 0;

				//Loop through results and take data we need
			foreach ( $data as $item => $attr ) {

				$rows[$count]['timestamp'] = $attr['timestamp'];
				$rows[$count]['id'] = $attr['id'];
				$rows[$count]['host'] = $attr['host'];
				$rows[$count]['uri'] = $attr['url'];
				$rows[$count]['referrer'] = $attr['referrer'];
               	$count++;

			}

			$this->items = $rows;
			$this->set_pagination_args(
				array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items/$per_page )
				)
			);

		}

       	/**
		 * Sort rows
		 *
		 * Sorts rows by count in descending order
		 *
		 * @param array $a first array to compare
		 * @param array $b second array to compare
		 * @return int comparison result
		 *
		 **/
		function sortrows( $a, $b ) {
			// If no sort, default to count
			$orderby = ( !empty( $_GET['orderby'] ) ) ? esc_attr( $_GET['orderby'] ) : 'timestamp';
			// If no order, default to desc
			$order = ( !empty( $_GET['order'] ) ) ? esc_attr( $_GET['order'] ) : 'desc';
			// Determine sort order
			$result = strcmp( $a[$orderby], $b[$orderby] );
			// Send final sort direction to usort
			return ( $order === 'asc' ) ? $result : -$result;
		}


	}

