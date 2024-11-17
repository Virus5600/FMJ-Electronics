<?php
require_once("../connection.php");

include_once("../processPhp/Query.php");
use ProcessPhp\Query;

if (isset($_POST['type'])) {
	$type = $_POST['type'];
	/**
	 * Contains the list of actions. Its key is the action name and while its value is an array containing
	 * the table name, id column name, and the archive column name respectively, allowing for easy retrieval
	 * of the table and id column name.
	 * @var array $restoreActions
	 */
	$ajaxType = [
		"category" => [
			"table" => "category_table",
			"id" => "category_Id",
			"targetCol" => "archive"
		],
		"subcategory" => [
			"table" => "category_product_table",
			"id" => "category_product_Id",
			"targetCol" => "archive"
		],
		"productItem" => [
			"table" => "category_product_item_table",
			"id" => "category_product_item_Id",
			"targetCol" => "archive"
		],
		"productVariant" => [
			"table" => "category_product_item_type_table",
			"id" => "category_product_item_type_Id",
			"targetCol" => "archive"
		],
		"product" => [
			"table" => "products",
			"id" => "product_Id",
			"targetCol" => "archive"
		],
		"account" => [
			"table" => "officials",
			"id" => "officials_Id",
			"targetCol" => "status"
		],
		"supplier" => [
			"table" => "supplier",
			"id" => "supplier_Id",
			"targetCol" => "status"
		],
	];

	/**
	 * Throws an error response message when a parameter is missing, which is
	 * very useful when used with AJAX requests. Only use when a parameter is
	 * missing.
	 *
	 * For a more general error response, use the `response` function instead
	 * to allow for more flexibility.
	 *
	 * @param string $message The message to be displayed.
	 *
	 * @return void
	 */
	function missingParam(string $message): void
	{
		response([
			"status" => 400,
			"message" => $message
		], 400);
		die();
	}

	if (array_key_exists($type, $ajaxType)) {
		$table = $ajaxType[$type]['table'];
		$idColumn = $ajaxType[$type]['id'];
		$id = $_POST['id'] ?? missingParam("Malformed request syntax: `id` is not provided.");
		$targetCol = $ajaxType[$type]['targetCol'];

		$archive = "";
		if (isset($_POST['action'])) {
			if ($_POST['action'] == "archive")
				$archive = $ajaxType[$type]['targetCol'] == "archive" ? "Yes" : "Inactive";
			else if ($_POST['action'] == "restore")
				$archive = $ajaxType[$type]['targetCol'] == "archive" ? "No" : "Active";
		}
		else {
			missingParam("Malformed request syntax: `action` is not provided.");
		}

		$result = Query::table($table)
			->update($targetCol, $archive)
			->where($idColumn, $id)
			->apply();

		if ($result)
			response([
				"status" => 200,
				"message" => "Success"
			], 200);
		else
			response([
				"status" => 500,
				"message" => "Failed",
			], 500);
	}
}
