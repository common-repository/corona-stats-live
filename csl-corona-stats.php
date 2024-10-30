<?php
/*
Plugin Name: Corona Stats Live Lite
Plugin URI: https://www.hafeezonline.com/corona-stats-live-demo/
Description: Corona stats tracker is a WordPress plugin to provide live statistics of Corona virus Covid-19
Version: 1.2.0
Author: Hafeez Ansari
Author URI: https://www.hafeezonline.com
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'CSL_VERSION' ) ) {
	return;
}
/*
Defined constent for later use
*/
define( 'CSL_VERSION', '1.0.1' );
define( 'CSL_Cache_Timing', 20*MINUTE_IN_SECONDS );
define( 'CSL_FILE', __FILE__ );
define( 'CSL_DIR', plugin_dir_path( CSL_FILE ) );
define( 'CSL_URL', plugin_dir_url( CSL_FILE ) );
include(CSL_DIR . '/classes/csl-corona-charts.php');

class csl_CoronaStats {
	/**
	 * Plugin instance.
	 * */
	private static $instance = null;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		
		register_activation_hook( CSL_FILE, array( $this , 'csl_activate' ) );
		register_deactivation_hook(CSL_FILE, array($this , 'csl_deactivate' ) );

        //main plugin shortcode for list widget
        add_shortcode( 'csl_covid_table', array($this, 'csl_table' ));
        add_shortcode( 'csl_covid_stats', array($this, 'csl_stats' ));
		add_shortcode( 'csl_covid_widget', array($this, 'csl_widget' ));
		add_shortcode( 'csl_covid_sidebar', array($this, 'csl_sidebar' ));
		add_shortcode( 'csl_covid_topten', array($this, 'csl_top_ten' ));
		add_shortcode( 'csl_covid_chart', array($this, 'csl_chart' ));
		add_shortcode( 'csl_covid_donutchart', array($this, 'csl_donut_chart' ));
		add_action('wp_enqueue_scripts', array($this, 'csl_covid_styles'));
		add_action('wp_enqueue_scripts', array($this, 'csl_covid_scripts'));
		add_action('plugins_loaded', array($this, 'csl_localizations'));
    }	

    public function csl_get_pro() {
    	$link = "https://www.hafeezonline.com/corona-stats-live-demo/";
    	echo "This feature is not available in Corona Stats Live lite version. <br>";
    	echo "Please get pro version and unlock all features.<br>";
    	echo 'Please visit developer website and <a href="'. $link . '">Get Corona Stats Live Pro Version </a>';
    	return;
    }

	public function csl_table($atts) {
		$this->csl_get_pro();
		return;
	}

	public function csl_stats($atts) {
		$obj = new csl_CoronaCharts;
		$obj->cs_country_stats($atts);
		return;
	}	// csl_stats function end
	
	public function csl_widget($atts) {
		$this->csl_get_pro();
		return;
	}	
	
	public function csl_sidebar($atts) {
		$obj = new csl_CoronaCharts;
		$table = $obj->csl_create_sidebar($atts);
		return $table;
	}		
	
	public function csl_chart($atts) {
		$obj = new csl_CoronaCharts;
		$chart = $obj->csl_create_chart($atts);
		return;
	}
	
	public function csl_donut_chart($atts) {
		$chart = new csl_CoronaCharts;
		$chart->csl_create_donut($atts);
		return;
	}
	
	public function csl_top_ten($atts) {
		$this->csl_get_pro();
		return;
	}
	
	public function csl_covid_styles() {
			wp_register_style('bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__));
			wp_register_style('chart-style', plugins_url('css/Chart.min.css', __FILE__));
			wp_register_style('datatable-style', plugins_url('css/jquery.dataTables.css', __FILE__));
			wp_register_style('csl-style', plugins_url('css/csl-styles.css', __FILE__));

			wp_enqueue_style('bootstrap-style');
			wp_enqueue_style('chart-style');
			wp_enqueue_style('datatable-style');	
			wp_enqueue_style('csl-style');			

	}
	
	public function csl_covid_scripts() {
			wp_register_script('chart-script', plugins_url('js/Chart.min.js', __FILE__));
			wp_register_script('datatable-script', plugins_url('js/jquery.dataTables.js', __FILE__));
			wp_register_script('chartbundle-script', plugins_url('js/Chart.bundle.js', __FILE__));
			wp_register_script('utils-script', plugins_url('js/utils.js', __FILE__));
			wp_register_script('csl-custom-script', plugins_url('js/csl-custom.js', __FILE__));

			wp_enqueue_script('chart-script');
			wp_enqueue_script('datatable-script');
			wp_enqueue_script('chartbundle-script');	
			wp_enqueue_script('utils-script');
			wp_enqueue_script('csl-custom-script');	
			
	}

  	public function csl_localizations(){
        $plugin_dir = basename(dirname(dirname( __FILE__ )));
        load_plugin_textdomain( 'csl-corona-stats', false , $plugin_dir . '/lang/');
    }	
	
	public function csl_activate() {
		update_option("CSL-type","FREE");
		update_option("CSL_activation_time",date('Y-m-d h:i:s') );
		update_option("CSL-alreadyRated","no");
	}
	public function csl_deactivate(){
		delete_transient('csl_gs');
    }	
} // end class

function csl_CoronaStats() {
	return csl_CoronaStats::get_instance();
}

csl_CoronaStats();
