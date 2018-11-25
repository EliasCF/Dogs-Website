<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');

    $query_string;
    parse_str($_SERVER['QUERY_STRING'], $query_string);
    echo $query_string['amount'];
    
    $result = file_get_contents('https://dog.ceo/api/breeds/list/all');

    http_response_code(200);
    echo $result;
?>