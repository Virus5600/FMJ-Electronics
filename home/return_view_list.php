<?php 

    require_once("../connection.php");

    session_start();

    if(!isset($_SESSION['officials_Id'])){
        header('Location: ../index.php');
    }

    if($_SESSION['user_type']=="Cashier") {
        header('Location: dashboard.php');
    }

    $user_type = $_SESSION['user_type'];


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

    <!-- Bootstrap Select Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

</head>
<body>
        <?php require_once("../templates/topNav.php")?>

        <div class="main-container">

            <div class="left-container">
                <?php require_once("../templates/leftNav.php")?>
            </div>

            <!-- <i class="fa-solid fa-user"></i>
                            <i class="fa-solid fa-receipt"></i> -->

            <div class="right-container">
                <div class="row m-0 p-0">
                    <div class="col-md-12">
                        <div class="main-title">
                            <i class="fa-solid fa-layer-group"></i><span>RETURNED ORDER</span>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="table-container mt-3">

                                <a href="inventory.php" class="btn btn-dark text-light" >
                                    Back
                                </a>

                                <table class="table table-hover table-border table-sm mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">ID</th>
                                                <th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">PRODUCT ID</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">NO. OF ITEM</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STATUS</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">DATE RETURNED</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartTable">

                                            <?php 
                                                // $sql = "SELECT o.order_Id, o.supplier_Id, o.item, o.no_of_item, o.status, o.date_created, s.supplier_Id, s.name, p.product_Id, p.type_Id, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM order_purchase o INNER JOIN supplier s ON o.supplier_Id = s.supplier_Id INNER JOIN products p ON o.item = p.product_Id INNER JOIN category_product_item_type_table cpit ON cpit.category_product_item_type_Id = p.type_Id ORDER BY order_Id DESC";
                                                $sql = "SELECT o.order_purchase_item_Id, o.order_Id, o.item, o.no_of_item, o.status, o.date_updated, p.product_Id, p.type_Id, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM order_purchase_item o INNER JOIN products p ON o.item = p.product_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE o.status ='Returned' ORDER BY order_purchase_item_Id DESC";
                                                $result = $conn->query($sql);
                                            ?>
                                            
                                            <?php if($result->num_rows > 0){?>
                                                <?php 
                                                
                                                $x = 1;
                                                
                                                while($row = $result->fetch_assoc()){
                                                    
                                                    
                                                ?>
                                            <tr>
                                                <td class="text-center d-none" style="font-size: 20px;" ><?php echo $row['order_purchase_item_Id'];?></td>
                                                <td class="text-center d-none" style="font-size: 20px;"><?php echo $row['product_Id'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['product_item_type_name'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['no_of_item'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['status'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['date_updated'];?></td>
                                                </tr>
                                                <?php 
                                                } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        
                        
                    </div>
                </div>
            </div>
        </div>
    

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Sweetalert Cdn Start -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Sweetalert Cdn End -->

    <!-- Bootstrap Select Picker -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>

        $(document).ready(function(){

            $('.editBtn').on('click', function(){
                console.log("Clikced")
                $('#edit_status').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();

                


                $('#edit_purchaseOrderId').val(data[0]);
                $('#edit_productId').val(data[1]);
                $('#item').val(data[2]);
                $('#no_of_item').val(data[3]);
                $('#status_order').val(data[4]);

                
                $('#status option').filter(function() {
                    return $(this).text() === data[5];
                }).prop('selected', true);
            });


            

            $("#update_statusBtn").click(function(e){
            console.log("napindot");
            e.preventDefault();

                $.ajax({
                    url: "../processPhp/edit_process.php",
                    method: "POST",
                    data: $("#edit-form").serialize() + "&action=editOrder",
                    success : function (response){
                        if(response == "editedSuccess"){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Successfully Edited Order!',
                                showConfirmButton: false,
                                timer: 1300  
                            }).then(function(){
                                window.location = "purchase_order_list.php";
                            })
                        }

                        else if(response == "error"){
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'There is an error Please try again Thankyou!',
                                showConfirmButton: false,
                                timer: 1300  
                            })
                        }
                    }
                })

            })




            $('#selectpicker').selectpicker();

            $("#addOrder").click(function(e){
            e.preventDefault();

            console.log("Napindot si CheckVoucherBtn")

            $("#divNextPurchaseOrder").append(
                `
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product</label>
                        <select name="item-dropdown[]" id="selectpicker" data-live-search="true" class="form-control selectpicker" style="">
                            <option value="">Select Item</option>
                            <?php 
                                $sqlp = "SELECT p.product_Id, p.item_code, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive='No'";
                                $resultp = $conn->query($sqlp);

                                if($resultp->num_rows > 0){
                                    while($rowsp = $resultp->fetch_assoc()){
                                        echo '<option value="'.$rowsp['product_Id'].'" style="text-align-center;">'.$rowsp['product_item_type_name'].'</option>';
                                    }
                                }
                            ?>
                            
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>No. of Items</label>
                        <input type="number" name="no_of_item[]" id="name" class="form-control" >
                    </div>

                    <div class="col-md-2">
                        <label for="" style="font-size: 18px; font-weight: 600; visibility: hidden" ><span class="text-danger " >* </span>No. of Items</label>
                        <input type="button" name="" id="removeOrder" class="removeOrder btn btn-danger btn-block ml-0" value="Remove" required>
                    </div>
                </div>`);

                $('.selectpicker').selectpicker();
    
        })

        $(document).on('click', '#removeOrder', function(e){
                e.preventDefault();

                let row_item = $(this).parent().parent();
                let input_item = $(this).parent();
                
                $(row_item).remove();

        })

            

            $("#addBtn").click(function(e){
            console.log("napindot");
            e.preventDefault();

            $.ajax({
                url: "../processPhp/add_process.php",
                method: "POST",
                data: $("#add-form").serialize() + "&action=addOrder",
                success : function (response){
                    if(response == "addedSuccess"){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully Added Order!',
                            showConfirmButton: false,
                            timer: 1300  
                        }).then(function(){
                            window.location = "purchase_order_list.php";
                        })
                    }

                    else if(response == "error"){
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'There is an error Please try again Thankyou!',
                            showConfirmButton: false,
                            timer: 1300  
                        })
                    }

                }
            })

        })


        })

        

    </script>

</body>
</html>