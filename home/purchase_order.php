<?php 

    require_once("../connection.php");

    session_start();

    if(!isset($_SESSION['officials_Id'])){
        header('Location: ../index.php');
    }

    if($_SESSION['user_type']=="Cashier" || $_SESSION['user_type']=="Staff") {
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
                            <i class="fa-solid fa-layer-group"></i><span>PURCHASE ORDER</span>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-1">

                            </div>
                            <div class="col-md-10">
                                <div class="table-container mt-3">

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <a href="purchase_order_list.php" class="btn btn-dark text-light" >
                                                Back
                                            </a>
                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                        
                                        <div class="col-md-3 ">
                                            <input type="button" onclick="" class="addOrder btn btn-dark  mb-0 mt-2 p-1" style="border: 0; cursor: pointer; width: 100%;" value="ADD ORDER" id="addOrder">
                                        </div>
                                    </div>

                                    <form id="add-form" class="mt-3">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Supplier Name</label>
                                                <select name="supplier_Id" class="form-control" style="" >
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                                        $sqlp = "SELECT * FROM supplier WHERE status='Active'";
                                                        $resultp = $conn->query($sqlp);

                                                        if($resultp->num_rows > 0){
                                                            while($rowsp = $resultp->fetch_assoc()){
                                                                echo '<option value="'.$rowsp['supplier_Id'].'" style="text-align-center;">'.$rowsp['name'].'</option>';
                                                            }
                                                        }
                                                    ?>
                                                    
                                                </select>
                                            </div>
                                
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col-md-8">
                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product</label>
                                                <select name="item-dropdown[]" id="selectpicker" data-live-search="true" class="form-control" style="">
                                                    <option value="" class="text-center">Select Item</option>
                                                    <?php 
                                                        $sqlp = "SELECT p.product_Id, p.item_code, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive='No'";
                                                        $resultp = $conn->query($sqlp);

                                                        if($resultp->num_rows > 0){
                                                            while($rowsp = $resultp->fetch_assoc()){
                                                                echo '<option value="'.$rowsp['product_Id'].'" style="text-align-center;"  class="text-center">'.$rowsp['product_item_type_name'].'</option>';
                                                            }
                                                        }
                                                    ?>
                                                    
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>No. of Items</label>
                                                <input type="number" name="no_of_item[]" id="name" class="form-control" >
                                            </div>
                                        </div>

                                        <div id="divNextPurchaseOrder">

                                        </div>

                                        <div class="mt-5">
                                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                            <!-- <button type="button" class="btn btn-primary">Save</button> -->
                                            <input type="submit" name="addBtn" id="addBtn" value="ADD" class="btn btn-info btn-block">

                                            <!-- Dito na tayo sa form ang pag eedit ng status  -->
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-1">

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

            $('#selectpicker').selectpicker();

            $("#addOrder").click(function(e){
            e.preventDefault();

            var selectedOptions = [];
            $('select[name="item-dropdown[]"]').each(function() {
                var selectedOption = $(this).val();
                if (selectedOption !== "") {
                    selectedOptions.push(selectedOption);
                }
            });

            console.log("Napindot si CheckVoucherBtn")

            // Fetch product options using AJAX
            $.ajax({
                url: '../processPhp/get_productsOrder.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var optionsHtml = '<option value="">Select Item</option>';
                    data.forEach(function(product) {
                        if (!selectedOptions.includes(product.product_Id)) {
                            optionsHtml += '<option value="' + product.product_Id + '" style="text-align:center;">' + product.product_item_type_name + '</option>';
                        }
                    });
                    
                    // Append new row with the select dropdown
                    $("#divNextPurchaseOrder").append(`
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product</label>
                                <select name="item-dropdown[]" id="selectpicker" data-live-search="true" class="form-control selectpicker text-center" style="">` +
                                    optionsHtml +
                                `</select>
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
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            

            // $("#divNextPurchaseOrder").append(
            //     `
            //     <div class="row mt-3">
            //         <div class="col-md-6">
            //             <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product</label>
            //             <select name="item-dropdown[]" id="selectpicker" data-live-search="true" class="form-control selectpicker" style="">
            //                 <option value="">Select Item</option>
            //                 <?php 
            //                     $sqlp = "SELECT p.product_Id, p.item_code, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive='No'";
            //                     $resultp = $conn->query($sqlp);

            //                     if($resultp->num_rows > 0){
            //                         while($rowsp = $resultp->fetch_assoc()){
            //                             if(!in_array($rowsp['product_Id'], selectedItemOptions)){
            //                                 echo '<option value="'.$rowsp['product_Id'].'" style="text-align-center;">'.$rowsp['product_item_type_name'].'</option>';
            //                             }
                                        
            //                         }
            //                     }
            //                 ?>
                            
            //             </select>
            //         </div>

            //         <div class="col-md-4">
            //             <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>No. of Items</label>
            //             <input type="number" name="no_of_item[]" id="name" class="form-control" >
            //         </div>

            //         <div class="col-md-2">
            //             <label for="" style="font-size: 18px; font-weight: 600; visibility: hidden" ><span class="text-danger " >* </span>No. of Items</label>
            //             <input type="button" name="" id="removeOrder" class="removeOrder btn btn-danger btn-block ml-0" value="Remove" required>
            //         </div>
            //     </div>`);

            //     $('.selectpicker').selectpicker();
    
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