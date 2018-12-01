<?php 
    include 'ApiWrapper.php';

    $array = array(
		'breed' => new QueryObject('breed/@/images', true, NULL),
		'amount' => new QueryObject(NULL, false, function($ApiObject) {
			if (intval($ApiObject->qs['amount']) != 0) {
				return array_slice($ApiObject->result, 0, $ApiObject->qs['amount']);
			}
			
			$ApiObject->response = 500;
			$ApiObject->error = true;
		})
	);
	
	$a = new ApiWrapper($_SERVER['QUERY_STRING'], 'https://dog.ceo/api/', 'breeds/list/all', $array);
	$a->execute();

    /*
     * This is a Rest API wrapper for the dog.ceo api
     * Its purpose is to be called in a url.
     * 
     * Examples:
     *      - Getting all breeds:
     *        URL: http://www.website.com/api/dogs/breeds.php
     *      
     *      - Getting a specific breed:
     *        URL: http://www.website.com/api/dogs/breeds.php?breed=beagle
     * 
     *      - Getting an X amount of dogs of a certain breed:
     *        URL: http://www.website.com/api/dogs/breeds.php?breed=beagle&amount=10
     *        NOTE: The 'amount' query string will return an error if used alone
     *
    
    //Set http headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');

    $response_code = 200;
    $api_string = 'https://dog.ceo/api/';
    $error = false;

    //Get query string(s)
    $query_string;
    parse_str($_SERVER['QUERY_STRING'], $query_string);

    //Indicates whether there are any query strings in the url
    $no_queries = count($query_string) > 0 ? false : true;

    //An error occurs if 'amount' is the only query string
    if (count($query_string) == 1 && isset($query_string['amount'])) {
        $response_code = 500;
        $error = true;
    }

    if (isset($query_string['breed'])) {
        $api_string .= 'breed/' . $query_string['breed'] . '/images';
    }

    //If no query strings have been provided, then get all breeds
    if ($no_queries) {
        $api_string .= 'breeds/list/all';
    }

    //Call dog api if no errors have occured yet
    if (!$error) {
        ini_set("allow_url_fopen", 1);
        $data = file_get_contents($api_string);

        $result = $no_queries ? $data : json_decode($data)->message;

        if (isset($query_string['amount'])) {
            //Check if amount can be converted to an integer
            if (intval($query_string['amount']) != 0) {
                $result =  array_slice($result, 0, $query_string['amount']);
            } else {
                $response_code = 500;
                $error = true;
            }
        }
    }

    http_response_code($response_code);

    if (!$error) {
        echo json_encode($no_queries ? json_decode($result)->message : $result); 
    } else {
        echo '{ \'status\': \'error\' }';
    }
    */
?>