<?php
// Include your database connection file here
include '../connection.php';

// Fetch product options
$sqlp = "SELECT p.product_Id, p.item_code, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive='No'";
$resultp = $conn->query($sqlp);

$options = array();
if ($resultp->num_rows > 0) {
    while ($rowsp = $resultp->fetch_assoc()) {
        $options[] = $rowsp;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($options);
?>