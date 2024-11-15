<?php
include_once("../processPhp/Query.php");
use ProcessPhp\Query;

require_once("../connection.php");

const ACTIONS = [
	"addProduct" => [
		"table" => "products",
		"columns" => [
			"item_code" => null,
			"barcode" => null,
			"category_Id" => null,
			"category_product_Id" => null,
			"product_type_Id" => null,
			"type_Id" => null,
			"stocks" => 0,
			"prize" => 0,
			"archive" => "No"
		]
	],
];

if (isset($_POST["action"])) {
	$action = $_POST["action"];

	if (empty($action)) {
		response([
			"status"=> 400,
			"message"=> "Malformed request syntax: `action` is not provided."
		], 400);
	}

	// TODO: Add the creation of the `addProducts` action here.
	if (array_key_exists($action, ACTIONS)) {
		$action = ACTIONS[$action];
		$query = Query::table($action["table"]);

		foreach ($action["columns"] as $column => $value) {
			if (isset($_POST[$column])) {
				$query->insert($column, $_POST[$column]);
			}
		}

		$itemCode = "";
		$char = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		for ($i = 0; $i < 7; $i++)
			$itemCode .= $char[rand(0, strlen($char) - 1)];
		$query->insert('item_code', $itemCode);
		$query->insert('archive', "No");

		if ($query->push()) {
			response([
				"status" => 200,
				"message" => "Product added successfully."
			]);
			return;
		}

		response([
			"status" => 400,
			"message" => "Failed to add product.",
			"error" => $query->error()
		], 400);
	}
}

// ADD PRODUCTS

function addProduct($data): void
{
	global $conn;
	$conn->begin_transaction();
}

// ADD PRODUCTS
if (isset($_POST['action']) && $_POST['action'] == "addProducts") {
	$category_id = $_POST['category_id'];
	$product_id = $_POST['product_id'];
	$produt_type_id = $_POST['produt_type_id'];
	$type_id = $_POST['type_id'];
	$stocks = $_POST['stocks'];
	$prize = $_POST['prize'];
	$archive = "No";
	$barcode = $_POST['barcode'];

	if (empty($stocks) || empty($prize)) {
		echo "fieldRequired";
	} else {
		$sqld = "SELECT * FROM products WHERE type_Id='$type_id'";
		$resultd = $conn->query($sqld);

		if ($resultd->num_rows > 0) {
			echo "productExist";
		} else {
			$char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$itemcode = '';

			for ($i = 0; $i < 7; $i++) {
				$itemcode .= $char[rand(0, strlen($char) - 1)];
			}

			$sqli = "INSERT INTO products (item_code, category_Id, category_product_Id, product_type_Id, type_Id, stocks, prize, archive, barcode) VALUES ('$itemcode', '$category_id', '$product_id', '$produt_type_id', '$type_id', '$stocks', '$prize', '$archive', '$barcode')";
			$result = $conn->query($sqli);

			if ($result) {
				echo "addedSuccess";
			} else {
				echo "error";
			}
		}
	}
}





// ADD CATEGORY

if (isset($_POST['action']) && $_POST['action'] == "addCategory") {
	$categoryName = $_POST['categoryName'];
	$archive = "No";

	$sqli = "INSERT INTO category_table (category_Name, archive) VALUES ('$categoryName', '$archive')";
	$result = $conn->query($sqli);

	if ($result) {
		echo "addedSuccess";
	} else {
		echo "error";
	}
}

if (isset($_POST['action']) && $_POST['action'] == "addCategoryProduct") {
	$categoryId = $_POST['categoryId'];
	$categoryProductName = $_POST['categoryProductName'];
	$archive = "No";


	$sqli = "INSERT INTO category_product_table (category_Id, product_Name, archive) VALUES ('$categoryId', '$categoryProductName', '$archive')";
	$result = $conn->query($sqli);

	if ($result) {
		echo "addedSuccess";
	} else {
		echo "error";
	}
}

if (isset($_POST['action']) && $_POST['action'] == "addProductItem") {
	$categoryId = $_POST['categoryId'];
	$categoryProductId = $_POST['categoryProductId'];
	$categoryProductItemName = $_POST['categoryProductItemName'];
	$archive = "No";

	$sqli = "INSERT INTO category_product_item_table (category_Id, category_product_Id, product_item_name, archive) VALUES ('$categoryId', '$categoryProductId', '$categoryProductItemName', '$archive')";
	$result = $conn->query($sqli);

	if ($result) {
		echo "addedSuccess";
	} else {
		echo "error";
	}
}

if (isset($_POST['action']) && $_POST['action'] == "addProductItemType") {
	$categoryId = $_POST['categoryId'];
	$categoryProductId = $_POST['categoryProductId'];
	$categoryProductItemId = $_POST['categoryProductItemId'];
	$categoryProductItemTypeName = $_POST['categoryProductItemTypeName'];
	$archive = "No";

	$sqli = "INSERT INTO category_product_item_type_table (category_Id, category_product_Id, category_product_item_Id, product_item_type_name, archive) VALUES ('$categoryId', '$categoryProductId', '$categoryProductItemId', '$categoryProductItemTypeName', '$archive')";
	$result = $conn->query($sqli);

	if ($result) {
		echo "addedSuccess";
	} else {
		echo "error";
	}
}

if (isset($_POST['carts'])) {

	$carts = $_POST['carts'];
	$totalAmount = $_POST['totals'];
	$payments = $_POST['payments'];
	$payment_method = $_POST['payment_methods'];
	$changes = $_POST['changes'];
	$transactioNumbers = $_POST['transactioNumbers'];

	if ($totalAmount <= $payments) {

		foreach ($carts as $item) {

			$transactionNo =  $item["transactionNo"];
			$product_Id =  $item["product_Id"];
			$selectedOptionTextPicker =  $item["selectedOptionTextPicker"];
			$itemCode =  $item["itemCode"];
			$price =  $item["price"];
			$stocks =  $item["stocks"];
			$quantity =  $item["quantity"];
			$amount =  $item["amount"];
			$date_added = date("Y-m-d H:i:s");


			$sqli = "INSERT INTO `transactions_table` (`transaction_Number`, `product_Id`, `item_code`, `qty`, `price`, `total_amount`, `final_total_amount`, `payment`, `payment_method`, `change`, `date_added`) VALUES ('$transactioNumbers', '$product_Id', '$itemCode', '$quantity', '$price', '$amount', '$totalAmount', '$payments', '$payment_method', '$changes', '$date_added')";
			$resulti = $conn->query($sqli);

			if ($resulti) {

				$newStocks = $stocks - $quantity;

				$sqlu = "UPDATE products SET stocks='$newStocks' WHERE product_Id='$product_Id'";
				$resultu = $conn->query($sqlu);
			} else {
				echo "error";
			}
		}

		if ($resultu) {

			session_start();

			$officials_Id = $_SESSION['officials_Id'];

			$action = "Added Transaction";

			$sqli = "INSERT INTO audit_trail_table (action, officials_Id) VALUES ('$action', '$officials_Id')";
			$result = $conn->query($sqli);

			echo "addedSuccess";
		} else {
			echo "error";
		}
	} else {
		echo "Errorpayment";
	}
}

// ADD ACOUNT
if (isset($_POST['action']) && $_POST['action'] == "addAccount") {
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email_address = $_POST['email_address'];
	$password = $_POST['password'];
	$cpassword = $_POST['cpassword'];
	$user_type = $_POST['userType'];
	$status = "Active";
	$date_added = date("Y-m-d H:i:s");
	$token = "";

	if (empty($first_name) || empty($last_name) || empty($email_address) || empty($password) || empty($cpassword) || empty($user_type)) {
		echo "fieldrequired";
	} else {

		$sql = "SELECT email_address FROM officials WHERE email_address = '$email_address'";
		$results = $conn->query($sql);

		if ($results->num_rows == 1) {
			echo "sameemail";
		} else {

			if ($password == $cpassword) {
				$password = sha1($password);

				$sqli = "INSERT INTO officials (first_name, last_name, email_address, password, user_type, status, date_created, token) VALUES ('$first_name', '$last_name', '$email_address', '$password', '$user_type', '$status', '$date_added', '$token')";
				$result = $conn->query($sqli);

				if ($result) {
					echo "addedSuccess";
				} else {
					echo "error";
				}
			} else {
				echo "NotEqual";
			}
		}
	}
}



// ADD ACOUNT
if (isset($_POST['action']) && $_POST['action'] == "addSupplier") {
	$name = $_POST['name'];
	$address = $_POST['address'];
	$contact_person = $_POST['contact_person'];
	$contact_no = $_POST['contact_no'];
	$status = $_POST['status'];
	// $date_added = date("Y-m-d H:i:s");

	if (empty($name) || empty($address) || empty($contact_person) || empty($contact_no) || empty($status)) {
		echo "emptyFields";
	} else {
		$sqli = "INSERT INTO supplier (name, address, contact_no, contact_person, status) VALUES ('$name', '$address', '$contact_no', '$contact_person', '$status')";
		$result = $conn->query($sqli);

		if ($result) {
			echo "addedSuccess";
		} else {
			echo "error";
		}
	}
}


// ADD ORDER
if (isset($_POST['action']) && $_POST['action'] == "addOrder") {

	$supplier_Id = $_POST['supplier_Id'];
	$item_dropdownArr = $_POST['item-dropdown'];
	$no_of_itemArr = $_POST['no_of_item'];
	$status = "Pending";
	$statusBatch = "Processing";
	$updated = "No";
	// $date_added = date("Y-m-d H:i:s");

	$sqli = "INSERT INTO order_purchase (supplier_Id, status) VALUES ('$supplier_Id', '$status')";
	$result = $conn->query($sqli);

	$queryO = "SELECT order_Id FROM order_purchase ORDER BY order_Id DESC LIMIT 1";
	$resultO = $conn->query($queryO);

	$rowO = $resultO->fetch_assoc();
	$row_Id = $rowO['order_Id'];

	// Assuming both arrays have the same length
	$count = count($item_dropdownArr);

	for ($i = 0; $i < $count; $i++) {
		$item_dropdown = $item_dropdownArr[$i];
		$no_of_item = $no_of_itemArr[$i];

		$sqlitem = "INSERT INTO order_purchase_item (order_Id, item, no_of_item, status, updated) VALUES ('$row_Id','$item_dropdown', '$no_of_item', '$statusBatch', '$updated')";
		$resultitem = $conn->query($sqlitem);
	}


	if ($resultitem) {
		echo "addedSuccess";
	} else {
		echo "error";
	}
}
