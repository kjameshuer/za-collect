<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       kjhuer.com
 * @since      1.0.0
 *
 * @package    Za_Collect
 * @subpackage Za_Collect/public
 */

/**
 *
 * Defines the plugin name, version,  enqueues the public-specific stylesheet and JavaScript.
 * Defines zaCollect function for getting collection feeds.
 * 
 * @package    Za_Collect
 * @subpackage Za_Collect/public
 * @author     Kevin J Huer <kjhuer@gmail.com>
 */
class Za_Collect_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_styles() {
                
            wp_register_style('bootstrap-grid',plugin_dir_url(__FILE__).'css/bootstrap.min.css');
            wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/za-collect-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_scripts() {

		           
            $options = get_option($this->plugin_name);
            $baseUrl = get_bloginfo('url');
            $fullPath = $baseUrl."/wp-content/plugins/za-collect/";
            $zaCollectOptions = array(
               'refId' => $options['referral_id'],
               'accentColor' => $options['accent_color'],
               'accentTextColor' => $options['accent_text_color'],
                'openNewWindow' => $options['new_window'],
               'buyNowText' => $options['buy_button_text'],
               'blogPath' => $fullPath
            );

            wp_register_script('za-collect-jmob',plugin_dir_url( __FILE__ ).'js/jquery.mobile.custom.min.js',array('jquery'),$this->version, false);
           // wp_register_script('collMaker',plugin_dir_url(__FILE__).'js/CollectionMaker.js',array('jquery'),$this->version, false);
            wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/za-collect-public.js', array( 'jquery','za-collect-jmob' ), $this->version, false );
            wp_localize_script($this->plugin_name,'zaCollectOptions',$zaCollectOptions);
                
                

	}
        public function za_collect_render($atts) {
            wp_enqueue_style('bootstrap-grid');
            wp_enqueue_style( 'dashicons' );
            wp_enqueue_style('za-collect');
            include_once( ABSPATH . WPINC . '/feed.php' );


            $a = shortcode_atts( array(
                'collection' => '119187790299772415',
                'tracking' => 'zaCollect',
                'count' => 0
            ), $atts );
            
            
             if ( !empty($a['collection']) && !preg_match( '/^[0-9]{18}$/', $a['collection']  ) ) { 
                    $a['collection'] = '119187790299772415';
                }
            
            
            $trackingNoSpaces = str_replace(' ','',$a['tracking']);
              //enqueue the script and stylesheet for za-collect
           $productArray = $this->get_zacollect_feed($a['collection'], $trackingNoSpaces, $a['count']);
            
            wp_enqueue_script('collMaker');
            wp_localize_script('za-collect','theProducts_'.$a['collection'],array('products'=>$productArray,'collectionID'=>$a['collection'],'tracking'=>$trackingNoSpaces));
            wp_enqueue_script('za-collect');
            wp_enqueue_script('za-collect-jmob');  
            

            return $productArray['collectionString'];
        }
        
            //Function sent to ajax-admin, verifies nonce, fetches rss feed then creates
    // and returns RSS data
        public function get_zacollect_feed($collection, $tracking, $count){
        
   

        $options = get_option($this->plugin_name);
        
        $openNewWin = '';
        
        if ($options['new_window'] > 0){
            $openNewWin = 'target="_blank"';
        }

        if (strlen($options['referral_id']) > 1) :

            $refID = '?rf=' . $options['referral_id'];
            if (isset($tracking)) :
                $track = '&tc=' . $tracking;
            endif;
        
        else :

            $refID = '';
            $track = '';
        endif;
    
   
   
        if (!isset($collection)) :
            $collection = '';
        endif;

        if (!isset($count) || $count == '') :
            $count = '6';
        else:
            if (intval($count)>100 || intval($count)==0){
                $count = '100';
            }
        endif;


        //fetch the collection feed with feed.php creating a simplePie object
        $rss = fetch_feed( 'http://feed.zazzle.com/collections/' . $collection . '/rss' );

        $maxitems = 0;

        if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

            // Figure out how many total items there are, but limit it to 5. 
            $maxitems = $rss->get_item_quantity( $count ); 

            // Build an array of all the items, starting with element 0 (first element).
            $rss_items = $rss->get_items( 0, $maxitems );

        endif;

        // set the collection values
        $collectionTitle = str_replace('Zazzle.com Collection: ','',htmlspecialchars_decode($rss->get_title(),ENT_QUOTES));

        $masterArray = array();

        $counter=0;
        
        $collectionString = '<a href="' . esc_url('http://www.zazzle.com/collections/' . $collection . $refID . $track ) . '" rel="nofollow" ' .  $openNewWin . '><h2 class="za-collect-collection-title">' . sanitize_text_field($collectionTitle) .'</h2></a>';
        $collectionString .= '<div style="display:none" id="za-collect-' . esc_attr($collection) . '" class="za-collect" zacollectionnum="' . esc_attr($collection) . '" zacollecttracking="' . esc_attr($tracking). '" zacollectcount="' . esc_attr($count) .'"></div>';
        $collectionString .= '<style>.za-collect-collection-holder-class {display:none; opacity: 0;transition: opacity 0.3s;}</style>';
        $collectionString .= '<div id="za-collection-holder-' . esc_attr($collection) . '" class="container za-collect-collection-holder-class" style="max-width: 100%;"><div class="row">';

        //grab product image/ information
        // display image, return collection products information in array
            foreach ( $rss_items as $item ) :
              
              $productString = '';
              $enclosure = $item->get_enclosure();
              $description = $this->truncate(sanitize_text_field(htmlspecialchars_decode($enclosure->get_description(),ENT_QUOTES)),200);

              $image = $enclosure->get_link();
              $thumb = str_replace('500.jpg','152.jpg',$image);
              $title =  htmlspecialchars_decode($item->get_title(),ENT_QUOTES);
              $authorObj = $item->get_author();
              $author = htmlspecialchars_decode($authorObj->get_email(),ENT_QUOTES);
              $content = $item->get_content();
              $link = $item->get_link();

              $productArray = array(
                    "title"=>$title,
                    "description"=>$description,
                    "author" => $author,
                    "content" => $content,
                    "link" => $link,
                   "image" => $image
                      );
             
                $productString .= '<div class="col-xs-6 col-sm-4 zazzle-product" >';
                $productString .= '<a href="' . esc_url($link . $refID . $track) . '" rel="nofollow">';
                $productString .= '<div class="zazzle-product-image za-collection-' . esc_attr($collection) . '-product-' . esc_attr($counter) . '">';
                $productString .= '<img src="' . esc_url($thumb) . '" alt="' . esc_attr($title) . " image" .'">';
                $productString .= '</div>';
                $productString .= '</a>';
                $productString .= '</div>';
    
              $collectionString .= $productString;
              $masterArray[]=$productArray;
              $counter++;
              
          endforeach; 
        $collectionString .= '</div></div>'; 

        $masterData = array(
            "collectionTitle" => $collectionTitle,
            "products" => $masterArray,
            "collectionString" => $collectionString
        );


        return $masterData;
 
    }
    private function truncate($str, $chars, $end = '...') {
        if (strlen($str) <= $chars) return $str;
        $new = substr($str, 0, $chars + 1);
        return substr($new, 0, strrpos($new, ' ')) . $end;
    }

}
