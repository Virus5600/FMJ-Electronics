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
	$restoreActions = [
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
				'stocks',
				'prize',
				'archive'
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

	if (in_array($action, array_keys($restoreActions))) {
		$action = (object) $restoreActions[$action];

		$statement = prepareStatement(
			$conn,
			$action->table,
			$action->affectedCols,
			$action->id
		);

		$params = [];
		foreach($action->affectedCols as $col) {
			array_push($params, $post->{$col});
		}
		array_push($params, $post->id);

		$statement->bind_param(str_repeat("s", count($params)), ...$params);
		$success = $statement->execute();
		$statement->close();

		echo $success ? "success" : "error";
	}

}

/*
// Edit Product
if (isset($_POST['action']) && $_POST['action'] == "editProduct") {
	$edit_product_Id = $_POST['edit_product_Id'];
	// $edit_item_code = $_POST['edit_item_code'];
	// $edit_category_Id = $_POST['edit_category_Id'];
	// $edit_category_product_Id = $_POST['edit_category_product_Id'];
	// $edit_product_type_Id = $_POST['edit_product_type_Id'];
	// $edit_type_Id = $_POST['edit_type_Id'];

	$edit_bar_code = $_POST['edit_bar_code'];
	$stocks = $_POST['stocks'];
	$prize = $_POST['prize'];

	$sqlu = "UPDATE products SET barcode='$edit_bar_code', stocks='$stocks', prize='$prize' WHERE product_Id='$edit_product_Id'";
	$result = $conn->query($sqlu);

	if ($result) {
		echo "editedSuccess";
	} else {
		echo "error";
	}
}

// Edit Category
if (isset($_POST['action']) && $_POST['action'] == "editCategory") {
	$edit_categoryId = $_POST['edit_categoryId'];
	$edit_categoryName = $_POST['edit_categoryName'];

	$sqlu = "UPDATE category_table SET category_Name='$edit_categoryName' WHERE category_Id='$edit_categoryId'";
	$result = $conn->query($sqlu);

	if ($result) {
		echo "editedSuccess";
	} else {
		echo "error";
	}
}
// Edit Subcategory
if (isset($_POST['action']) && $_POST['action'] == "editCategoryProduct") {
	$edit_categoryId = $_POST['edit_categoryId'];
	$edit_category_productId = $_POST['edit_category_productId'];
	$edit_categoryName = $_POST['edit_categoryName'];

	$sqlu = "UPDATE category_product_table SET category_Id='$edit_categoryId', product_Name='$edit_categoryName' WHERE category_product_Id='$edit_category_productId'";
	$result = $conn->query($sqlu);

	if ($result) {
		echo "editedSuccess";
	} else {
		echo "error";
	}
}
// Edit Product Item
if (isset($_POST['action']) && $_POST['action'] == "editCategoryProductItem") {
	$edit_categoryId = $_POST['edit_categoryId'];
	$edit_category_productId = $_POST['edit_category_productId'];
	$edit_category_product_itemId = $_POST['edit_category_product_itemId'];
	$edit_ItemName = $_POST['edit_ItemName'];

	$sqlu = "UPDATE category_product_item_table SET category_Id='$edit_categoryId', category_product_Id='$edit_category_productId', product_item_name='$edit_ItemName' WHERE category_product_item_Id='$edit_category_product_itemId'";
	$result = $conn->query($sqlu);

	if ($result) {
		echo "editedSuccess";
	} else {
		echo "error";
	}
}
// Edit Product Type
if (isset($_POST['action']) && $_POST['action'] == "editCategoryProductItemType") {
	$edit_categoryId = $_POST['edit_categoryId'];
	$edit_category_productId = $_POST['edit_category_productId'];
	$edit_category_product_itemId = $_POST['edit_category_product_itemId'];
	$edit_category_product_itemTypeId = $_POST['edit_category_product_itemTypeId'];
	$edit_ItemTypeName = $_POST['edit_ItemTypeName'];

	$sqlu = "UPDATE category_product_item_type_table SET category_Id='$edit_categoryId', category_product_Id='$edit_category_productId', category_product_item_Id='$edit_category_product_itemId', product_item_type_name='$edit_ItemTypeName' WHERE category_product_item_type_Id='$edit_category_product_itemTypeId'";
	$result = $conn->query($sqlu);

	if ($result) {
		echo "editedSuccess";
	} else {
		echo "error";
	}
}

// Edit Accounts
if (isset($_POST['action']) && $_POST['action'] == "editAccount") {
	$edit_officials_Id = $_POST['edit_officials_Id'];
	$edit_first_name = $_POST['edit_first_name'];
	$edit_last_name = $_POST['edit_last_name'];
	$edit_email_address = $_POST['edit_email_address'];
	$edit_userType = $_POST['edit_userType'];
	$edit_status = $_POST['edit_status'];
	$edit_password = $_POST['edit_password'];
	$edit_password = sha1($edit_password);

	$sqlu = "UPDATE officials SET first_name='$edit_first_name', last_name='$edit_last_name', email_address='$edit_email_address', password='$edit_password', user_type='$edit_userType', status='$edit_status' WHERE officials_Id='$edit_officials_Id'";
	$result = $conn->query($sqlu);

	if ($result) {
		echo "editedSuccess";
	} else {
		echo "error";
	}
}
if (isset($_POST['action']) && $_POST['action'] == "editProfile") {
	$edit_officialId = $_POST['edit_officialId'];
	$edit_first_name = $_POST['edit_first_name'];
	$edit_last_name = $_POST['edit_last_name'];
	$edit_email = $_POST['edit_email'];
	$edit_password = $_POST['edit_password'];
	$edit_con_password = $_POST['edit_con_password'];

	if ($edit_password == NULL || $edit_con_password == NULL) {
		$sql = "UPDATE officials SET first_name='$edit_first_name', last_name='$edit_last_name', email_address='$edit_email' WHERE officials_Id='$edit_officialId'";
		$result = $conn->query($sql);
		if ($result) {
			echo "editedSuccess";
		} else {
			echo "NotSuccess";
		}
	} else if (!empty($edit_password) && !empty($edit_con_password)) {
		if ($edit_password == $edit_con_password) {
			$edit_password = shaw1($edit_password);
			$sql = "UPDATE officials SET first_name='$edit_first_name', last_name='$edit_last_name', email_address='$edit_email', password='$edit_password' WHERE officials_Id='$edit_officialId'";
			$result = $conn->query($sql);
			if ($result) {
				echo "editedSuccess";
			} else {
				echo "NotSuccess";
			}
		} else {
			echo "Mismatch";
		}
	}
}

// Edit Supplier
if (isset($_POST['action']) && $_POST['action'] == "editSupplier") {
	$edit_supplier_Id = $_POST['edit_supplier_Id'];
	$edit_name = $_POST['edit_name'];
	$edit_address = $_POST['edit_address'];
	$edit_contact_person = $_POST['edit_contact_person'];
	$edit_contact_no = $_POST['edit_contact_no'];
	$edit_status = $_POST['edit_status'];


	$sql = "UPDATE supplier SET name='$edit_name', address='$edit_address', contact_no='$edit_contact_no', contact_person='$edit_contact_person', status='$edit_status' WHERE supplier_Id='$edit_supplier_Id'";
	$result = $conn->query($sql);
	if ($result) {
		echo "editedSuccess";
	} else {
		echo "NotSuccess";
	}
}

// Edit Purchase Order
if (isset($_POST['action']) && $_POST['action'] == "editPurchaseOrder") {
	$edit_purchaseOrderId = $_POST['edit_purchaseOrderId'];
	$status_batch = $_POST['status_batch'];

	$sql = "UPDATE order_purchase SET status='$status_batch' WHERE order_Id='$edit_purchaseOrderId'";
	$result = $conn->query($sql);
	if ($result) {

		$sqlu = "UPDATE order_purchase_item SET status='$status_batch' WHERE order_Id='$edit_purchaseOrderId'";
		$resultu = $conn->query($sqlu);

		echo "editedSuccess";
	} else {
		echo "NotSuccess";
	}
}

// Edit Order
if (isset($_POST['action']) && $_POST['action'] == "editOrder") {
	$edit_purchaseOrderId = $_POST['edit_purchaseOrderId'];
	$edit_productId = $_POST['edit_productId'];
	$status_order = $_POST['status_order'];
	$no_of_item = $_POST['no_of_item'];
	$date_updated = date('Y-m-d');

	if ($status_order == "Added") {
		$sqld = "SELECT * FROM products WHERE product_Id='$edit_productId'";
		$resultd = $conn->query($sqld);
		$rowd = $resultd->fetch_assoc();

		$stocks = $rowd['stocks'];
		$updated = "Yes";

		$no_of_stocks_add = $no_of_item + $stocks;

		$sqld = "SELECT * FROM order_purchase_item WHERE updated='No' AND order_purchase_item_Id='$edit_purchaseOrderId' AND item='$edit_productId'";
		$resultd = $conn->query($sqld);

		if ($resultd->num_rows > 0) {

			$sql = "UPDATE products SET stocks='$no_of_stocks_add' WHERE product_Id='$edit_productId'";
			$result = $conn->query($sql);

			if ($result) {
				$sqlu = "UPDATE order_purchase_item SET status='$status_order', updated='$updated', date_updated='$date_updated' WHERE order_purchase_item_Id='$edit_purchaseOrderId'";
				$resultu = $conn->query($sqlu);
				echo "editedSuccess";
			} else {
				echo "NotSuccess";
			}
		} else {
			echo "addedFailed";
		}
	} else {
		$sqlu = "UPDATE order_purchase_item SET status='$status_order', date_updated='$date_updated' WHERE order_purchase_item_Id='$edit_purchaseOrderId'";
		$resultu = $conn->query($sqlu);

		if ($resultu) {
			echo "editedSuccess";
		} else {
			echo "NotSuccess";
		}
	}
}

if (isset($_POST['action']) && $_POST['action'] == "editAllStocks") {
	if (isset($_POST['order_purchase_item_Id'])) {
		$order_purchase_item_Ids = $_POST['order_purchase_item_Id'];

		$product_Ids = $_POST['product_Id'];
		$no_of_items = $_POST['no_of_item'];

		$status_order = "Added";
		$date_updated = date('Y-m-d');

		if (empty($order_purchase_item_Ids)) {
			echo "noEditItem";
		} else {
			foreach ($order_purchase_item_Ids as $key => $order_purchase_item_Id) {
				$product_Id = $product_Ids[$key];
				$no_of_item = $no_of_items[$key];



				$sqld = "SELECT * FROM products WHERE product_Id='$product_Id'";
				$resultd = $conn->query($sqld);
				$rowd = $resultd->fetch_assoc();

				$stocks = $rowd['stocks'];
				$updated = "Yes";

				$no_of_stocks_add = $no_of_item + $stocks;

				$sqld = "SELECT * FROM order_purchase_item WHERE order_purchase_item_Id='$order_purchase_item_Id' AND item='$product_Id'";
				$resultd = $conn->query($sqld);

				if ($resultd->num_rows > 0) {

					$sql = "UPDATE products SET stocks='$no_of_stocks_add' WHERE product_Id='$product_Id'";
					$result = $conn->query($sql);

					if ($result) {
						$sqlu = "UPDATE order_purchase_item SET status='$status_order', updated='$updated', date_updated='$date_updated' WHERE order_purchase_item_Id='$order_purchase_item_Id'";
						$resultu = $conn->query($sqlu);
					} else {
						echo "NotSuccess";
					}
				} else {
					echo "addedFailed";
				}
			}
		}

		if ($resultu) {
			echo "editedSuccess";
		} else {
			echo "NotSuccess";
		}
	} else {
		echo "noEditItem";
	}











	// print_r($order_purchase_item_Id);
	// print_r($product_Id);
	// $status_order = $_POST['status_order'];
	// $no_of_item = $_POST['no_of_item'];
	// $date_updated = date('Y-m-d');

	// if($status_order == "Added"){
	//     $sqld = "SELECT * FROM products WHERE product_Id='$edit_productId'";
	//     $resultd = $conn->query($sqld);
	//     $rowd = $resultd->fetch_assoc();

	//     $stocks = $rowd['stocks'];
	//     $updated = "Yes";

	//     $no_of_stocks_add = $no_of_item + $stocks;

	//     $sqld = "SELECT * FROM order_purchase_item WHERE updated='No' AND order_purchase_item_Id='$edit_purchaseOrderId' AND item='$edit_productId'";
	//     $resultd = $conn->query($sqld);

	//     if($resultd->num_rows > 0){

	//         $sql = "UPDATE products SET stocks='$no_of_stocks_add' WHERE product_Id='$edit_productId'";
	//         $result = $conn->query($sql);

	//         if($result){
	//             $sqlu = "UPDATE order_purchase_item SET status='$status_order', updated='$updated', date_updated='$date_updated' WHERE order_purchase_item_Id='$edit_purchaseOrderId'";
	//             $resultu = $conn->query($sqlu);
	//             echo "editedSuccess";
	//         }

	//         else{
	//             echo "NotSuccess";
	//         }


	//     }

	//     else{
	//         echo "addedFailed";
	//     }

	// }

	// else{
	//     $sqlu = "UPDATE order_purchase_item SET status='$status_order', date_updated='$date_updated' WHERE order_purchase_item_Id='$edit_purchaseOrderId'";
	//     $resultu = $conn->query($sqlu);

	//     if($resultu){
	//         echo "editedSuccess";
	//     }

	//     else{
	//         echo "NotSuccess";
	//     }

	// }
}
*/
