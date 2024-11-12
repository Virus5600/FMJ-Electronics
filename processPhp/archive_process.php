<?php
require_once("../connection.php");

// ARCHIVE MAIN PRODUCT
if (isset($_POST['action']) && $_POST['action'] == "archivemainProduct") {
	$productId = $_POST['id'];
	$archive = "Yes";
	$sqlu = "UPDATE products SET archive='$archive' WHERE product_Id='$productId'";
	$resultu = $conn->query($sqlu);
	if ($resultu) {
		echo "successArchive";
	} else {
		echo "errorArchive";
	}
}

// ARCHIVE CATEGORY
if (isset($_POST['action']) && $_POST['action'] == "archiveCategory") {
	$categoryId = $_POST['id'];
	$archive = "Yes";
	$sqlu = "UPDATE category_table SET archive='$archive' WHERE category_Id='$categoryId'";
	$resultu = $conn->query($sqlu);
	if ($resultu) {
		echo "successArchive";
	} else {
		echo "errorArchive";
	}
}

// ARCHIVE PRODUCT
if (isset($_POST['action']) && $_POST['action'] == "archiveProduct") {
	$productId = $_POST['id'];
	$archive = "Yes";
	$sqlu = "UPDATE category_product_table SET archive='$archive' WHERE category_product_Id='$productId'";
	$resultu = $conn->query($sqlu);
	if ($resultu) {
		echo "successArchive";
	} else {
		echo "errorArchive";
	}
}

// ARCHIVE PRODUCT TYPE
if (isset($_POST['action']) && $_POST['action'] == "archiveProductType") {
	$productTypeId = $_POST['id'];
	$archive = "Yes";
	$sqlu = "UPDATE category_product_item_table SET archive='$archive' WHERE category_product_item_Id='$productTypeId'";
	$resultu = $conn->query($sqlu);
	if ($resultu) {
		echo "successArchive";
	} else {
		echo "errorArchive";
	}
}

// ARCHIVE TYPE
if (isset($_POST['action']) && $_POST['action'] == "archiveType") {
	$typeId = $_POST['id'];
	$archive = "Yes";
	$sqlu = "UPDATE category_product_item_type_table SET archive='$archive' WHERE category_product_item_type_Id='$typeId'";
	$resultu = $conn->query($sqlu);
	if ($resultu) {
		echo "successArchive";
	} else {
		echo "errorArchive";
	}
}

// RESTORATION PART //

// Restore Category
if (isset($_POST['action'])) {
	$action = $_POST['action'];
	/**
	 * Contains the list of actions. Its key is the action name and while its value is an array containing
	 * the table name, id column name, and the archive column name respectively, allowing for easy retrieval
	 * of the table and id column name.
	 * @var array $actions
	 */
	$actions = [
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

	if (array_key_exists($action, $actions)) {
		$table = $actions[$action]['table'];
		$idColumn = $actions[$action]['id'];
		$id = $_POST['id'];
		$targetCol = $actions[$action]['targetCol'];
		$archive = $targetCol == "archive" ? "No" : "Active";

		$sql = "UPDATE `$table` SET `$targetCol` = '$archive' WHERE `$idColumn` = '$id'";
		// dd($sql);
		$result = $conn->query($sql);
		if ($result)
			echo "success";
		else
			echo "error";
	}
}
