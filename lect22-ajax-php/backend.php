<?php
	$php_array = [
		"first_name" => "Tommy",
		"last_name" => "Trojan",
		"age" => 21,
		"phone" => [
			"cell" => "123-123-1234",

			"home" => "456-456-4567"
		],
	];

	// Whatever is echoed out here is sent to the frontend. Note: you can only echo out STRINGS.
	//echo "hi";

	// can't echo out an assoc array
	// echo $php_array;

	// convert associative array into a JSON string
	echo json_encode($php_array);

	// $host = "303.itpwebdev.com";
	// $user = "";
	// $pass = "";
	// $db = "";

	// $mysqli = new mysqli($host, $user, $pass, $db);
	// if ( $mysqli->errno ) {
	// 	echo $mysqli->error;
	// 	exit();
	// }

	// $sql = "";

	// $results = $mysqli->query($sql);
	// if ( !$results ) {
	// 	echo $mysqli->error;
	// 	exit();
	// }

	// $mysqli->close();


?>