<?php
require_once("../connection.php");

function prepareStatement(mysqli $connection, string $table, array $affectedCols, string $idCol = 'id'): mysqli_stmt
{
	$sql = "UPDATE $table SET";

	foreach ($affectedCols as $col)
		$sql .= " `$table`.`$col` = ?,";
	$sql = rtrim($sql, ',');
	$sql .= " WHERE `$table`.`$idCol` = ?";

	$stmt = $connection->prepare($sql);
	return $stmt;
}

// EDIT PROCESS
if (isset($_POST['action'])) {
	$post = (object) $_POST;
	$action = $_POST['action'];
	$editActions = [
		'editCategory' => [
			'table' => 'category_table',
			'id' => 'category_Id',
			'affectedCols' => [
				'category_Name'
			]
		],
		'editSubcategory' => [
			'table' => 'category_product_table',
			'id' => 'category_product_Id',
			'affectedCols' => [
				'product_Name'
			]
		],
		'editProductItem' => [
			'table' => 'category_product_item_table',
			'id' => 'category_product_item_Id',
			'affectedCols' => [
				'product_item_name'
			]
		],
		'editProductVariant' => [
			'table' => 'category_product_item_type_table',
			'id' => 'category_product_item_type_Id',
			'affectedCols' => [
				'product_item_type_name'
			]
		],
		'editProduct' => [
			'table' => 'products',
			'id' => 'product_Id',
			'affectedCols' => [
				'barcode',
				'prize',
			]
		],
		'editAccount' => [
			'table' => 'officials',
			'id' => 'officials_Id',
			'affectedCols' => [
				'first_name',
				'last_name',
				'email_address',
				'password',
				'user_type',
				'status',
				'archive'
			]
		],
		'editSupplier' => [
			'table' => 'supplier',
			'id' => 'supplier_Id',
			'affectedCols' => [
				'name',
				'address',
				'contact_no',
				'contact_person',
				'status',
				'archive'
			]
		],
	];

	if (in_array($action, array_keys($editActions))) {
		$action = (object) $editActions[$action];

		$statement = prepareStatement(
			$conn,
			$action->table,
			$action->affectedCols,
			$action->id
		);

		$params = [];
		foreach($action->affectedCols as $col) array_push($params, $post->{$col});
		array_push($params, $post->id);

		$statement->bind_param(str_repeat("s", count($params)), ...$params);
		$success = $statement->execute();
		$statement->close();

		if ($success) {
			response(
				[
					"status" => 200,
					"message" => "Success"
				],
				200
			);
		}
		else {
			response(
				[
					"status" => 500,
					"message" => "Error",
					"error" => $conn->error
				],
				500
			);
		}
	}
}
