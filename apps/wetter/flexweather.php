<?php

class flexweather{

	/**
	 * Variables to be returned
	 */
	public $title;
	public $icon;
	public $status;
	public $temp;
	public $feelslike;
	public $humidity;
	public $wind;

	public $updated;
	public $link;
	public $error;

	public function __construct($stadt){
		$weather = json_decode(file_get_contents("http://api.wunderground.com/api/c9036f05794113e8/conditions/lang:DL/q/AT/" . rawurlencode($stadt) . ".json"));

		if(!property_exists($weather, "current_observation")){
			$weather = json_decode(file_get_contents("http://api.wunderground.com/api/c9036f05794113e8/conditions/lang:DL/q/zmw:" . $weather->response->results[0]->zmw . ".json"));
		}

		/**
		 * Check feed Type
		 */
		$this->parseWeather($weather->current_observation);
	}

	private function toComma($string){
		return str_replace(".", ",", $string);
	}

	private function parseWeather($weather){
		$this->title = $weather->display_location->full;
		$this->icon = "icons/". $weather->icon .".png";
		$this->status = $weather->weather;
		$this->temp = $this->toComma($weather->temp_c);
		$this->feelslike = $this->toComma($weather->feelslike_c);
		$this->humidity = $weather->relative_humidity;
		$this->wind = $this->toComma($weather->wind_kph);

		$this->updated = $weather->observation_time_rfc822;
		$this->link = $weather->forecast_url;
	}
}
