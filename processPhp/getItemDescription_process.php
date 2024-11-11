<?php 

require_once("../connection.php");

if(isset($_POST['product_ids'])){
    $product_ids = $_POST['product_ids'];

    $sql = "SELECT * FROM products WHERE product_Id='$product_ids'";
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    else{
        echo "No results";
    }
}

?>