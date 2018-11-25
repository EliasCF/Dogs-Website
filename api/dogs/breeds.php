<?php 
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
     */
    
    //Set http headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');

    $response_code = 200;
    $api_string = 'https://dog.ceo/api/';

    //Get query string(s)
    $query_string;
    parse_str($_SERVER['QUERY_STRING'], $query_string);

    $no_queries = (isset($query_string['breed']) || isset($query_string['amount'])) ? false : true;

    if (isset($query_string['breed'])) {
        $api_string .= 'breed/' . $query_string['breed'] . '/images';
    }

    //If no query string has been provided,
    //then get all breeds
    if ($no_queries) {
        $api_string .= 'breeds/list/all';
    }

    //Get api data
    ini_set("allow_url_fopen", 1);
    $data = file_get_contents($api_string);

    $result = $no_queries ? $data : json_decode($data)->message;

    if (isset($query_string['amount'])) {
        $result =  array_slice($result, 0, $query_string['amount']);
    }

    http_response_code($response_code);
    
    if ($no_queries) {
        echo $result;
    } else {
        echo implode(' ', $result);
    }
?>