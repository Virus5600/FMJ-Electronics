<?php
require_once("../connection.php");

if (isset($_POST['action'])) {
	$action = $_POST['action'];
	/**
	 * Contains the list of actions. Its key is the action name and while its value is an array containing
	 * the table name, id column name, and the archive column name respectively, allowing for easy retrieval
	 * of the table and id column name.
	 * @var array $restoreActions
	 */
	$restoreActions = [
		"restoreCategory" => [
			"table" => "category_table",
			"id" => "category_Id",
			"targetCol" => "archive"
		],
		"restoreSubcategory" => [
			"table" => "category_product_table",
			"id" => "category_product_Id",
			"targetCol" => "archive"
		],
		"restoreProductItem" => [
			"table" => "category_product_item_table",
			"id" => "category_product_item_Id",
			"targetCol" => "archive"
		],
		"restoreProductVariant" => [
			"table" => "category_product_item_type_table",
			"id" => "category_product_item_type_Id",
			"targetCol" => "archive"
		],
		"restoreProduct" => [
			"table" => "products",
			"id" => "product_Id",
			"targetCol" => "archive"
		],
		"restoreAccount" => [
			"table" => "officials",
			"id" => "officials_Id",
			"targetCol" => "status"
		],
		"restoreSupplier" => [
			"table" => "supplier",
			"id" => "supplier_Id",
			"targetCol" => "status"
		],
	];

	if (array_key_exists($action, $restoreActions)) {
		$table = $restoreActions[$action]['table'];
		$idColumn = $restoreActions[$action]['id'];
		$id = $_POST['id'];
		$targetCol = $restoreActions[$action]['targetCol'];
		$archive = $targetCol == "archive" ? "No" : "Active";

		$sql = "UPDATE `$table` SET `$targetCol` = '$archive' WHERE `$idColumn` = '$id'";
		// dd($sql);
		$result = $conn->query($sql);
		if ($result)
			echo "success";
		else
			echo "error";
	}

	// TODO: Implement the archiveActions below
}
