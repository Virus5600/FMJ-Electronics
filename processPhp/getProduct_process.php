<?php
require_once("../connection.php");

if(isset($_POST['category_Id'])) {
    $id = $_POST['category_Id'];

	$connection = $conn->query("SELECT category_product_Id as id, product_Name as name FROM category_product_table WHERE category_id='$id' ORDER BY category_product_Id DESC");
    $products = fetchAll($connection);

	$content = '<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Product</option>';

	if (count($products) > 0)
		foreach ($products as $p)
			$content .= "<option value=\"$p->id\" class=\"font-weight-bold\" style=\"font-size:18px;\">$p->name</option>";

	response([
		"status" => 200,
		"data"=> $content
	], 200);
}
else {
	response([
		"status" => 400,
		"message"=> "Malformed request syntax: `category_Id` is not provided."
	], 400);
}
