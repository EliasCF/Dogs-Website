<?php
	//This class defines the name and behavior of a query
	class QueryObject {
		//Url that will be appended to the root url
		public $url;
		
		//an annonymous function
		public $functionallity;
		
		public $do_before_call = false;
		
		public function __construct($_url, $_do_before_call, $_functionallity) {
			$this->url = $_url;
			$this->do_before_call = $_do_before_call;
			$this->functionallity = $_functionallity;
		}
	}

	class ApiWrapper {
		//QUERY_STRING from SERVER global
		public $query;
		
		//Root url to the rest api
		public $api_string;
		
		//The default string that is appended to the root url,
		//if there are no query strings
		public $no_query_url;
		
		//The array defining the acceptable queries and their behavior
		public $query_object_arr;
		
		//HTTP response status code
		public $response_code = 200;
		
		//Indicate whether an error has occurd
		public $error = false;
		
		
		/*
		 * Params:
		 *  	- $api_string: Base url to the REST API
		 *		- $query_object_arr: Contains the names of the query strings
		 */
		public function __construct($_query, $_api_string, $_no_query_url, $_query_object_arr) {
			$this->query = $_query;
			$this->api_string = $_api_string;
			$this->no_query_url = $_no_query_url;
			$this->query_object_arr = $_query_object_arr;
		}
		
		//Returns an array of string queries
		private function get_query_array() {
			$query_string;
            parse_str($this->query, $query_string);
            
            return $query_string;
		}
		
		private function call_api($api_url) {
			ini_set("allow_url_fopen", 1);
			return file_get_contents($api_url);
		}
		
		public function execute() {
			//Get query string(s)
			$query_string = $this->get_query_array();
			
			//Check if the defined queries are in the url
			$no_queries = (count($query_string) > 0 ? false : true);
            
            /*
             * Before API call
             */
            if ($no_queries) {
				$this->api_string .= $this->no_query_url;
			} else {
				foreach ($this->query_object_arr as $key => $value) {
					//If index is to be done before the api call,
					//and the url is not NULL
					if ($value->do_before_call && $value->url != NULL) {
						$this->api_string .= str_replace('@', $query_string[$key], $value->url);
					}
				}
			}

			$data = $this->call_api($this->api_string);
            
            /*
             * After API call
             */
			foreach ($this->query_object_arr as $key => $value) {
				if (!$value->do_before_call) {
					$data = $value->functionallity($this);
				}
			}
			
			//Set response code
			http_response_code($this->response_code);
			
			echo '';
		}
	}
?>