<?php
	define('ERROR_MESSAGE', '{ \'status\': \'error\' }');

	class QueryObject {
		//Url that will be appended to the root url
		public $url;
		
		//an annonymous function
		public $functionallity;
		
		public $do_before_call = false;
		
		public function __construct($_url, $_do_before_call, $_functionallity) 
		{
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

		//Data fetched from the api
		public $api_call_data;

		/*
		 * Params:
		 *  	- $api_string: Base url to the REST API
		 *		- $query_object_arr: Contains the names of the query strings
		 */
		public function __construct($_query, $_api_string, $_no_query_url, $_query_object_arr) 
		{
			$this->query = $_query;
			$this->api_string = $_api_string;
			$this->no_query_url = $_no_query_url;
			$this->query_object_arr = $_query_object_arr;
		}

		/*
		 * Description:
		 * 		This function appends the appropriate string, to the root url in $this->api_string
		 */
		public function execute() 
		{
			//Get query string(s)
			$query_string;
			parse_str($this->query, $query_string);
			
			//Check if the defined queries are in the url
			$no_queries = (count($query_string) > 0 ? false : true);

			$this->append_url_path($no_queries, $query_string);

			if (!$this->error) 
			{
				ini_set("allow_url_fopen", 1);
				$this->api_call_data = @file_get_contents($this->api_string);

				$this->execute_query_object_functions($query_string);
			}

			//Send an error if the api call was not successful
			if ($this->api_call_data === false) 
			{
				$this->ErrorOccured();
			}

			//Set response headers
			header('Access-Control-Allow-Origin: *');
			header('Content-Type: application/json; charset=UTF-8');
			
			//Set response code
			http_response_code($this->response_code);

			//Print json data
			return json_encode($this->api_call_data);
		}
		
		/*
		 * Description:
		 * 		Appends the appropriate string, to the root url in $this->api_string
		 */
		private function append_url_path($no_queries, $query_string) 
		{
			if ($no_queries) 
			{
				//If there is no queries and no, no_query_url has been defined, and error will occur
				if ($this->no_query_url == null) 
				{
					$this->ErrorOccured();

					return;
				}

				$this->api_string .= $this->no_query_url;
			}
			else
			{
				foreach ($this->query_object_arr as $key => $value) 
				{
					//If index is to be done before the api call,
					//and the url is not NULL
					if (isset($query_string[$key])) 
					{
						if ($value->do_before_call && $value->url != NULL) 
						{
							$this->api_string .= str_replace('@', $query_string[$key], $value->url);
						}
					}
				}
			}

			return;
		}

		/*
		 * Description:
		 * 		Executes functions of any $this->query_object_arr QueryObjects that has a function and is in the array
		 */
		private function execute_query_object_functions($query_string) 
		{
			if($this->api_call_data !== false) 
			{
				$function_flag = false;
				foreach ($this->query_object_arr as $key => $value) 
				{
					if (isset($query_string[$key]) && !$value->do_before_call) 
					{
							$this->api_call_data = $value->functionallity->__invoke($this);

							$function_flag = true;
					}
				}

				if (!$function_flag) 
				{
					$this->api_call_data = json_decode($this->api_call_data)->message;
				}
			}
		}

		/*
		 * Description:
		 * 		Cause an error to occur.
		 * 		This will cause the execution() function to send a response code of 500,
		 * 		and a json error-string.
		 */
		private function ErrorOccured() 
		{
			$this->error = true;
			$this->response_code = 500;
			$this->api_call_data = ERROR_MESSAGE;
		}
	}
?>