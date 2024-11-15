<?php
	$host = "localhost";
	$username = "root";
	$password = "";
	$database = "fmj_electronics";

	$conn = new mysqli($host, $username, $password, $database);

	include_once "processPhp/utilities.php";

	if ($conn->connect_error) {
		die("Connection Failed $conn->connect_error");
	}
