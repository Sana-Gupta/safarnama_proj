<?php

try {

    if (!file_exists('connection-pdo.php' ))
        throw new Exception();
    else
        require_once('connection-pdo.php' ); 
		
} catch (Exception $e) {

	$arr = array ('code'=>"01",'msg'=>"There were some problem in the Server! Try after some time!");

	echo json_encode($arr);

	exit();
	
}

if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
	$arr = array ('code'=>"02",'msg'=>"Invalid POST variable keys! Refresh the page!");

	echo json_encode($arr);
	exit();
}

//$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//$regex_name = '/^[(A-Z)?(a-z)?(0-9)?\s*]+$/';

//$regex_password = '/^[(A-Z)?(a-z)?(0-9)?!?@?#?-?_?%?]+$/';
$regex_password = '^[A-Za-z0-9]+$';
$email = test_input($_POST['email']);
$name = test_input($_POST['name']);

if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match($regex_password, $_POST['password'])) {

	$arr = array ('code'=>"03",'msg'=>"Whoa! Invalid Inputs!");

	echo json_encode($arr);

	exit();

} else {

	date_default_timezone_set("Asia/Kolkata");

	$email = $_POST['email'];
	$name = $_POST['name'];
	$password = $_POST['password'];

	$timest = date("d:m:Y h:i:sa");


	$sql = "SELECT * FROM users WHERE email=?";

	$query  = $pdoconn->prepare($sql);
	$query->execute([$email]);
	$arr_login=$query->fetchAll(PDO::FETCH_ASSOC);

	if (count($arr_login) != 0) {
		$arr = array ('code'=>"04",'msg'=>"Duplicate entry found! Try registering with different email id!");

		echo json_encode($arr);

		exit();

	} else {

		$sql = "INSERT INTO users(name,email,password,timestamp) VALUES(?,?,?,?)";
	    $query  = $pdoconn->prepare($sql);
	    if ($query->execute([$name, $email, $password, $timest])) {

	    	$arr = array ('code'=>"1",'msg'=>"You have been registered! Please Go to the Login option for logging in!");

			echo json_encode($arr);
	    	
	    } else {
	    	$arr = array ('code'=>"05",'msg'=>"There were some problem in the server! Please try again after some time!");

			echo json_encode($arr);
	    }
	}

}