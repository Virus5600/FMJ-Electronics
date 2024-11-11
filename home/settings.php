<?php 

    require_once("../connection.php");

    session_start();

    if(!isset($_SESSION['officials_Id'])){
        header('Location: ../index.php');
    }

    if($_SESSION['user_type']=="Staff") {
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
                    <div class="col-md-4 box">
                        <a href="transaction_receipt.php">
                            <div class="box-content-container d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <i class="fa-solid fa-receipt" style="font-size: 58px; margin-bottom: 10px"></i>
                                <h3>TRANSACTION RECEIPT</h3>
                            </div>
                        </a>
                    </div>

                    <?php if($_SESSION['user_type']=="Admin") {?>
                    <div class="col-md-4 box">
                        <a href="accounts.php">
                            <div class="box-content-container d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <i class="fa-solid fa-user" style="font-size: 58px; margin-bottom: 10px"></i>
                                <h3>ACCOUNTS</h3>

                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    
                    <?php if($_SESSION['user_type']=="Admin") {?>
                    <div class="col-md-4 box">
                        <a href="archive.php">
                            <div class="box-content-container d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <i class="fa-solid fa-archive" style="font-size: 58px; margin-bottom: 10px"></i>
                                <h3>ARCHIVES</h3>
                            </div>
                        </a>
                        
                    </div>
                    <?php } ?>
                </div>

                <!-- <div class="row m-0 p-0">
                    <div class="col-md-12">
                        <div class="main-title">
                            <i class="fa-solid fa-layer-group"></i><span>SETTINGS</span>
                        </div>

                        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Staff" || $_SESSION['user_type']=="Cashier"){?>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                </div>

                                <div class="col-md-6 boxs">
                                    <a href="transaction_receipt.php">
                                    <div class="box-content-containerss">
                                        <i class="fa-solid fa-receipt"></i>
                                        <h3>TRANSACTION RECEIPT</h3>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        <?php } ?>

                        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin"){?>
                            <div class="row mt-3">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-5 boxs ">
                                    <a href="accounts.php">
                                        <div class="box-content-containerss">
                                            <i class="fa-solid fa-user"></i>
                                            <h3>ACCOUNT</h3>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-5 boxs">
                                    <a href="transaction_receipt.php">
                                    <div class="box-content-containerss">
                                        <i class="fa-solid fa-receipt"></i>
                                        <h3>TRANSACTION RECEIPT</h3>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                        <?php } ?>

                        
                        
                    </div>
                </div> -->
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
            

            $('#receiptBtn').on('click', function(e){
                e.preventDefault();

                var datas = {
                    carts: cart,
                    transactioNumbers: transactionNo.value,
                    totals: total.value,
                    payments: payment.value,
                    changes: change.value
                }

                console.log(datas)

                $.ajax({
                    url: "../processPhp/add_process.php",
                    method: "POST",
                    data: datas,
                    success: function(response){
                        console.log(response)

                        if(response == "addedSuccess"){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Successfully Added!',
                                    showConfirmButton: false,
                                    timer: 1300  
                                }).then(function(){
                                    window.location = "transaction.php";
                                })
                        }

                        else if(response == "error"){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'There is an error, Please try again',
                                    showConfirmButton: false,
                                    timer: 1300  
                                }).then(function(){
                                    window.location = "transaction.php";
                                })
                        }
                    }
                })
            })

            

            // ARCHIVE REQUEST AJAX

        //     $("#archive_btn").click(function(e){
        //             e.preventDefault();
        //             console.log("napindot si a");
        //             // e.preventDefault();

        //             $.ajax({
        //                 url: "../processPhp/archive_process.php",
        //                 method: "POST",
        //                 data: $("#form-archive-product").serialize() + "&action=archivemainProduct",
        //                 success : function (response){

        //                     if(response == "successArchive"){
        //                             Swal.fire({
        //                                 position: 'center',
        //                                 icon: 'success',
        //                                 title: 'Successfully Archived!',
        //                                 showConfirmButton: false,
        //                                 timer: 1300  
        //                             }).then(function(){
        //                                 window.location = "products.php";
        //                             })
        //                     }

        //                     else if(response == "errorArchive"){
        //                             Swal.fire({
        //                                 position: 'center',
        //                                 icon: 'success',
        //                                 title: 'There is an error, Please try again',
        //                                 showConfirmButton: false,
        //                                 timer: 1300  
        //                             }).then(function(){
        //                                 window.location = "products.php";
        //                             })
        //                     }
                            
        //                 }
        //             })
        //     })

        //     $('.editBtn').on('click', function(){
        //         console.log("Clikced")
        //         $('#edit_product').modal('show');

        //         $tr = $(this).closest('tr');

        //         var data = $tr.children("td").map(function(){
        //             return $(this).text();
        //         }).get();

        //         $('#edit_product_Id').val(data[0]);
        //         $('#edit_item_code').val(data[1]);
        //         $('#edit_category_Id').val(data[2]);
        //         $('#edit_category_product_Id').val(data[3]);
        //         $('#edit_product_type_Id').val(data[4]);
        //         $('#edit_type_Id').val(data[5]);
        //         $('#stocks').val(data[6]);
        //         $('#prize').val(data[7]);

        //     });


        //     // FOR EDIT PRODUCT AJAX

        //     $("#update_productBtn").click(function(e){
        //         e.preventDefault();

        //         $.ajax({
        //             url: "../processPhp/edit_process.php",
        //             method: "POST",
        //             data: $("#edit-form").serialize() + '&action=editProduct',
        //             success: function (response){
        //                 console.log(response)
        //                 if(response == "editedSuccess"){

        //                     Swal.fire({
        //                         position: 'center',
        //                         icon: 'success',
        //                         title: 'Edited Product Successfully!',
        //                         showConfirmButton: false,
        //                         timer: 1500  
        //                     }).then(function(){
        //                         window.location = "./products.php";
        //                     })

        //                 }

        //                 else if(response == "error"){

        //                     Swal.fire({
        //                         position: 'center',
        //                         icon: 'error',
        //                         title: 'There is an error. Please Try Again!',
        //                         showConfirmButton: false,
        //                         timer: 1500  
        //                     }).then(function(){
        //                         window.location = "./products.php";
        //                     })

        //                 }
        //             }
        //         })
        //     })

        //     $("#addBtn").on('click', function(e){
        //         e.preventDefault();

                

        //         $.ajax({
        //             url: "../processPhp/add_process.php",
        //             method: 'POST',
        //             data: $("#add-form").serialize() + '&action=addProducts',
        //             success: function(response){

        //                 if(response == "addedSuccess"){

        //                     Swal.fire({
        //                         position: 'center',
        //                         icon: 'success',
        //                         title: 'Added Category Successfully!',
        //                         showConfirmButton: false,
        //                         timer: 1500  
        //                     }).then(function(){
        //                         window.location = "./products.php";
        //                     })

        //                 }

        //                 else if(response == "error"){

        //                     Swal.fire({
        //                         position: 'center',
        //                         icon: 'error',
        //                         title: 'There is an error. Please Try Again!',
        //                         showConfirmButton: false,
        //                         timer: 1500  
        //                     }).then(function(){
        //                         window.location = "./products.php";
        //                     })

        //                 }
                        
        //             }
        //         })
        //     })

        //     $('#category-dropdown').on('change', function(){
        //         var category_id = this.value;

        //         // console.log(category_id)
                
        //         $.ajax({
        //             method: "POST",
        //             url: "../processPhp/getProduct_process.php",
        //             data: {category_ids: category_id},
        //             success: function(response){
        //                 console.log(response);
        //                 $("#product-dropdown").html(response);
        //             }
        //         })
        //     })

        //     $('#product-dropdown').on('change', function(){
        //         var product_id = this.value;

        //         // console.log(product_id)
                
        //         $.ajax({
        //             method: "POST",
        //             url: "../processPhp/getProductType_process.php",
        //             data: {product_ids: product_id},
        //             success: function(response){
        //                 console.log(response);
        //                 $("#product-type-dropdown").html(response);
        //             }
        //         })
        //     })

        //     $('#product-type-dropdown').on('change', function(){
        //         var type_id = this.value;

        //         // console.log(type_id)
                
        //         $.ajax({
        //             method: "POST",
        //             url: "../processPhp/getType_process.php",
        //             data: {type_ids: type_id},
        //             success: function(response){
        //                 console.log(response);
        //                 $("#type-dropdown").html(response);
        //             }
        //         })
        //     })

        // })

        })

        

    </script>

</body>
</html>