<?php
require_once("../connection.php");

if(isset($_POST['category_product_Id'])) {
    $id = $_POST['category_product_Id'];

	$connection = $conn->query("SELECT category_product_item_Id as id, product_item_name as name FROM category_product_item_table WHERE category_product_Id='$id' ORDER BY category_product_item_Id DESC");
    $productTypes = fetchAll($connection);

    $content = '<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Product Type</option>';

	if (count($productTypes) > 0)
		foreach ($productTypes as $p)
			$content .= "<option value=\"$p->id\" class=\"font-weight-bold\" style=\"font-size:18px;\">$p->name</option>";

	response([
		"status" => 200,
		"data"=> $content
	], 200);
}
else {
	response([
		"status" => 400,
		"message"=> "Malformed request syntax: `category_product_Id` is not provided."
	], 400);
}
