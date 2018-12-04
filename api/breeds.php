<?php 
    include 'wrapper/ApiWrapper.php';

    /* 
    * Examples:
    *      - Getting all breeds:
    *        URL: http://www.website.com/api/breeds.php
    *      
    *      - Getting a specific breed:
    *        URL: http://www.website.com/api/breeds.php?breed=beagle
    * 
    *      - Getting an X amount of dogs of a certain breed:
    *        URL: http://www.website.com/api/breeds.php?breed=beagle&amount=10
    *        NOTE: The 'amount' query string will return an error if used alone
	*
	*	   - Get an x amount of random dogs from a specific breed:
	*		 URL: http://www.website.com/api/breeds.php?breed=beagle&random=10
    */
    $Configuration = array(
        'breed' => new QueryObject('breed/@/images', true, NULL),
        'random' => new QueryObject('/random/@', true, NULL),
        'amount' => new QueryObject(NULL, false, function($ApiObject) {
            $query_string;
            parse_str($ApiObject->query, $query_string);

            if (intval($query_string['amount']) != 0) {
                return array_slice(json_decode($ApiObject->api_call_data)->message, 0, $query_string['amount']);
            }
			
			return '{ \'status\': \'error\' }';
        })
	);
	
	$a = new ApiWrapper(
		$_SERVER['QUERY_STRING'], //Query strings from URL
		'https://dog.ceo/api/', //Root URL
		'breeds/list/all', //String to append to root if there are no query strings
		$Configuration
	);
	$a->execute();
?>