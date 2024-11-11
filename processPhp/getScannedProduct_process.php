<?php 

require_once("../connection.php");

if(isset($_POST['scanned_product'])){
    $scanned_product = $_POST['scanned_product'];

    $data = array();


    if(empty($scanned_product)){
        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    else{
        $sql = "SELECT * FROM products WHERE barcode='$scanned_product'";
        $result = $conn->query($sql);

        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($data);
        }

        else{
            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
    
    
}

?>