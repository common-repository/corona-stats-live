<?php 

class csl_CoronaData {
	
	private $api = 'https://9kzzzfwgnwgef8dc.disease.sh/v2/';

	public function historical_data($country) {
		$cs_data = "";
		$csl_historical_transient = get_transient( 'csl_historical_transient_api_' . $country);
		if(empty($csl_historical_transient)) {
			$api_url = esc_url($this->api . 'historical/' . $country);
			$api_req = wp_remote_get($api_url, array('timeout' => 120));
			if (is_wp_error($api_req)) {
				return false; 
			}		
			
			$body = wp_remote_retrieve_body($api_req);
			$cs_data = json_decode($body);	
			set_transient( 'csl_historical_transient_api_' . $country, $cs_data, HOUR_IN_SECONDS );
		} else {
			$cs_data = $csl_historical_transient;
		}
		return $cs_data;
	}

	public function country_data($country) {
		$cs_data = "";
		$csl_country_transient = get_transient( 'csl_country_transient_api_' . $country);
		if(empty($csl_country_transient)) {
	       	$api_url =  $this->api .'countries/'. $country;
	       	$api_req = wp_remote_get($api_url,array('timeout' => 120));
				if (is_wp_error($api_req)) {
					return false; 
				}		
			
			$body = wp_remote_retrieve_body($api_req);
			$cs_data = json_decode($body);
			set_transient( '$csl_country_transient_api_' . $country, $cs_data, HOUR_IN_SECONDS );	
		} else {
			$cs_data = $csl_country_transient;
		}
		return $cs_data;
	}
}