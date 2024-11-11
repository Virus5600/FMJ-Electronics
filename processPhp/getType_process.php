<?php 

require_once("../connection.php");

if(isset($_POST['type_ids'])){
    $type_ids = $_POST['type_ids'];

    $sql = "SELECT * FROM category_product_item_type_table WHERE category_product_item_id='$type_ids' ORDER BY category_product_item_type_Id DESC";
    $result = $conn->query($sql);

    echo '<option value="" class="font-weight-bold" style="font-size:18px;">Select Product Type</option>';

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo '<option value="'. $row["category_product_item_type_Id"].'" class="font-weight-bold" style="font-size:18px;">'.$row["product_item_type_name"].'</option>';
        }
    }
}



?>