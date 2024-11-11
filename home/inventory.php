<?php 

    require_once("../connection.php");

    session_start();

    if(!isset($_SESSION['officials_Id'])){
        header('Location: ../index.php');
    }

    $user_type = $_SESSION['user_type'];

    if($_SESSION['user_type']=="Cashier") {
        header('Location: dashboard.php');
    }


    // TOTAL USERS
    $sqlu = "SELECT * FROM officials";
    $resultu = $conn->query($sqlu);
    $countusers = $resultu->num_rows;


    // TOTAL CATEGORIES
    $sqlc = "SELECT * FROM category_table";
    $resultc = $conn->query($sqlc);
    $countcategory = $resultc->num_rows;

    // TOTAL SUM AMOUNT
    $sqlt = "SELECT SUM(final_total_amount) AS totalsales FROM transactions_table";
    $resultt = $conn->query($sqlt);
    
    if($resultt->num_rows > 0){
        $row = $resultt->fetch_assoc();
        $sum = $row['totalsales'];
    }

    // POPULAR PRODUCTS
    // $sqlp = "SELECT t.product_Id, SUM(t.final_total_amount) AS total_sold, p.product_Id, p. FROM transactions_table t GROUP BY t.product_Id ORDER BY total_sold DESC LIMIT 1";
    // $resultp = $conn->query($sqlp);
    
    // if($resultp->num_rows > 0){
    //     $row = $resultp->fetch_assoc();
    //     $transaction_Number = $row['product_Id'];
    // }

    // $query = "SELECT products.product_id, products.product_name, COUNT(sales.sale_id) as sales_count
    // FROM products
    // LEFT JOIN sales ON products.product_id = sales.product_id
    // GROUP BY products.product_id
    // ORDER BY sales_count DESC
    // LIMIT 10";
    
    // POPULAR PRODUCTS
    $sqlpop = "SELECT products.product_Id, products.type_Id, COUNT(transactions_table.transaction_Id) as sales_count, category_product_item_type_table.category_product_item_type_Id, category_product_item_type_table.product_item_type_name
    FROM products
    LEFT JOIN transactions_table ON products.product_Id = transactions_table.product_Id INNER JOIN category_product_item_type_table ON products.type_Id = category_product_item_type_table.category_product_item_type_Id
    GROUP BY products.product_Id
    ORDER BY sales_count DESC
    LIMIT 5";
    $resultpop = $conn->query($sqlpop);





    // Query to fetch sales data for Javascript
    $query1 = "SELECT YEAR(date_added) as year, SUM(total_amount) as total_sales FROM transactions_table GROUP BY YEAR(date_added) LIMIT 18446744073709551615 OFFSET 1";
    $result1 = mysqli_query($conn, $query1);

    // Fetch data into an array
    $data1 = array();
    while ($row1 = mysqli_fetch_assoc($result1)) {
        $data1[] = $row1;
    }
    $json_data1 = json_encode($data1);


    // For Dashboard Box Total Sales
    $query2 = "SELECT SUM(total_amount) as total_sales FROM transactions_table";
    $result2 = mysqli_query($conn, $query2);

    $row2 = mysqli_fetch_assoc($result2);
    $total_sales = $row2['total_sales'];

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMJ ELECTRONICS</title>

    <link rel="stylesheet" href="../styles/sidebar.css">
    <link rel="stylesheet" href="../styles/dashboard.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Font Links Start-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Links End-->

    <!-- JS for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>
        <?php require_once("../templates/topNav.php")?>

        <div class="main-container">

            <div class="left-container">
                <?php require_once("../templates/leftNav.php")?>
            </div>

            

            <div class="right-container">

                <div class="row m-0 p-0">
                    <div class="col-md-4 box">
                        <a href="purchase_order_list.php">
                            <div class="box-content-container d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <i class="fa-solid fa-truck" style="font-size: 58px; margin-bottom: 10px"></i>
                                <h3>PURCHASE ORDER</h3>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4 box">
                        <a href="inventory_view.php">
                            <div class="box-content-container d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <i class="fa-solid fa-warehouse" style="font-size: 58px; margin-bottom: 10px"></i>
                                <h3>INVENTORY</h3>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4 box">
                        <a href="return_view_list.php">
                            <div class="box-content-container d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <i class="fa-solid fa-rotate-left" style="font-size: 58px; margin-bottom: 10px"></i>
                                <h3>RETURN</h3>
                            </div>
                        </a>
                        
                    </div>
                </div>

            </div>
        </div>
    
    

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Sweetalert Cdn Start -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Sweetalert Cdn End -->

    <script>

    </script>

</body>
</html>