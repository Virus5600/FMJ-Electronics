<?php 

require_once("../connection.php");

if(isset($_POST['category_ids'])){
    $category_ids = $_POST['category_ids'];

    $sql = "SELECT * FROM category_product_table WHERE category_id='$category_ids' ORDER BY category_product_Id DESC";
    $result = $conn->query($sql);

    echo '<option value="" class="font-weight-bold" style="font-size:18px;">Select Product</option>';

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo '<option value="'. $row["category_product_Id"].'" class="font-weight-bold" style="font-size:18px;">'.$row["product_Name"].'</option>';
        }
    }
}

?>