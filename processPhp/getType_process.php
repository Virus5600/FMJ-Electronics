<?php
require_once("../connection.php");

if(isset($_POST['product_type_Id'])) {
    $id = $_POST['product_type_Id'];

	$connection = $conn->query("SELECT category_product_item_type_Id as id, product_item_type_name as name FROM category_product_item_type_table WHERE category_product_item_Id='$id' ORDER BY category_product_item_type_Id DESC");
    $types = fetchAll($connection);

    $content = '<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Type</option>';

	if (count($types) > 0)
		foreach ($types as $p)
			$content .= "<option value=\"$p->id\" class=\"font-weight-bold\" style=\"font-size:18px;\">$p->name</option>";

	response([
		"status" => 200,
		"data"=> $content
	], 200);
}
else {
	response([
		"status" => 400,
		"message"=> "Malformed request syntax: `product_type_Id` is not provided."
	], 400);
}
