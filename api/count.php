<?php
    include 'wrapper/ApiWrapper.php';

    /* 
     * Examples:
     *      - Getting the amount of images for a breed:
     *        URL: http://www.website.com/api/count.php?breed=akita
     */
    $Configuration = array
    (
        'breed' => new QueryObject('breed/@/images', true, NULL)
    );

    $api = new ApiWrapper
    (
		$_SERVER['QUERY_STRING'], //Query strings from URL
		'https://dog.ceo/api/', //Root URL
		'breeds/list/all', //String to append to root if there are no query strings
		$Configuration
    );

    $result = $api->execute();

    echo count(explode(",", $result));
?>