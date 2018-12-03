<?php 
    include 'wrapper/ApiWrapper.php';

    $array = array(
		'breed' => new QueryObject('breed/@/images', true, NULL),
		'amount' => new QueryObject(NULL, false, function($ApiObject) {
            $query_string;
            parse_str($ApiObject->query, $query_string);

            if (intval($query_string['amount']) != 0) {
                return array_slice(json_decode($ApiObject->data)->message, 0, $query_string['amount']);
            }
			
			return '{ \'status\': \'error\' }';
		})
	);
	
	$a = new ApiWrapper($_SERVER['QUERY_STRING'], 'https://dog.ceo/api/', 'breeds/list/all', $array);
	$a->execute();
?>