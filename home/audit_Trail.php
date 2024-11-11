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

</head>
<body>
        <?php require_once("../templates/topNav.php")?>

        <div class="main-container">

            <div class="left-container">
                <?php require_once("../templates/leftNav.php")?>
            </div>

            <div class="right-container">
                <div class="row m-0 p-0">
                    <div class="col-md-12">
                        <div class="main-title">
                            <i class="fa-solid fa-layer-group"></i><span>AUDIT TRAIL</span>
                        </div>

                        <form action="audit_trail_print_filter.php" method="POST" class="my-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="font-weight-bold">From Date</label>
                                    <input type="date" name="from" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="font-weight-bold">To Date</label>
                                    <input type="date" name="to" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <div>
                                        <label for="" class="font-weight-bold " style="visibility: hidden">To Date</label>
                                    </div>
                                    
                                    <input type="submit" name="printFilter" class="btn btn-secondary btn-block" value="Filter" >
                                </div>

                                <div class="col-md-2">
                                    <div>
                                        <label for="" class="font-weight-bold " style="visibility: hidden">To Date</label>
                                    </div>
                                    <a href="audit_trail_print.php" class="btn btn-success btn-block">Print Report</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-container">
                            <table class="table table-hover table-border table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">PRODUCT ID</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">User</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Operation</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 

                                    // Dito na tayo sa pag didisplay ng product list 
                                    
                                    $sql = "SELECT a.audit_Id, a.action, a.officials_Id, a.datetime, o.officials_Id, o.first_name, o.last_name FROM audit_trail_table a INNER JOIN officials o ON a.officials_Id = o.officials_Id ORDER BY a.audit_Id DESC";
                                    $result = $conn->query($sql);
                                    
                                    ?>

                                    <?php if($result->num_rows > 0){?>
                                        <?php while($row = $result->fetch_assoc()){?>
                                    <tr>
                                        <td class="td-product text-center d-none" style="font-size: 18px; font-weight: 700"><?php echo $row['audit_Id'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['first_name'];?> <?php echo $row['last_name'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['action'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['datetime'];?></td>
                                    </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
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

    <script>

        function confirmDelete(self){
            var id = self.getAttribute("data-id");

            document.getElementById("form-archive-product").id.value = id;
            $("#myModal").addClass("animate__fadeInDown");
            $("#myModal").modal("show");
            
        }

        $(document).ready(function(){

            // ARCHIVE REQUEST AJAX

            $("#archive_btn").click(function(e){
                    e.preventDefault();
                    console.log("napindot si a");
                    // e.preventDefault();

                    $.ajax({
                        url: "../processPhp/archive_process.php",
                        method: "POST",
                        data: $("#form-archive-product").serialize() + "&action=archivemainProduct",
                        success : function (response){

                            if(response == "successArchive"){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Successfully Archived!',
                                        showConfirmButton: false,
                                        timer: 1300  
                                    }).then(function(){
                                        window.location = "products.php";
                                    })
                            }

                            else if(response == "errorArchive"){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'There is an error, Please try again',
                                        showConfirmButton: false,
                                        timer: 1300  
                                    }).then(function(){
                                        window.location = "products.php";
                                    })
                            }
                            
                        }
                    })
            })

            $('.editBtn').on('click', function(){
                console.log("Clikced")
                $('#edit_product').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();

                $('#edit_product_Id').val(data[0]);
                $('#edit_item_code').val(data[1]);
                $('#edit_category_Id').val(data[2]);
                $('#edit_category_product_Id').val(data[3]);
                $('#edit_product_type_Id').val(data[4]);
                $('#edit_type_Id').val(data[5]);
                $('#stocks').val(data[6]);
                $('#prize').val(data[7]);

            });


            // FOR EDIT PRODUCT AJAX

            $("#update_productBtn").click(function(e){
                e.preventDefault();

                $.ajax({
                    url: "../processPhp/edit_process.php",
                    method: "POST",
                    data: $("#edit-form").serialize() + '&action=editProduct',
                    success: function (response){
                        console.log(response)
                        if(response == "editedSuccess"){

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Edited Product Successfully!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }

                        else if(response == "error"){

                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'There is an error. Please Try Again!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }
                    }
                })
            })

            $("#addBtn").on('click', function(e){
                e.preventDefault();

                

                $.ajax({
                    url: "../processPhp/add_process.php",
                    method: 'POST',
                    data: $("#add-form").serialize() + '&action=addProducts',
                    success: function(response){

                        if(response == "addedSuccess"){

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Added Category Successfully!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }

                        else if(response == "error"){

                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'There is an error. Please Try Again!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }
                        
                    }
                })
            })

            $('#category-dropdown').on('change', function(){
                var category_id = this.value;

                // console.log(category_id)
                
                $.ajax({
                    method: "POST",
                    url: "../processPhp/getProduct_process.php",
                    data: {category_ids: category_id},
                    success: function(response){
                        console.log(response);
                        $("#product-dropdown").html(response);
                    }
                })
            })

            $('#product-dropdown').on('change', function(){
                var product_id = this.value;

                // console.log(product_id)
                
                $.ajax({
                    method: "POST",
                    url: "../processPhp/getProductType_process.php",
                    data: {product_ids: product_id},
                    success: function(response){
                        console.log(response);
                        $("#product-type-dropdown").html(response);
                    }
                })
            })

            $('#product-type-dropdown').on('change', function(){
                var type_id = this.value;

                // console.log(type_id)
                
                $.ajax({
                    method: "POST",
                    url: "../processPhp/getType_process.php",
                    data: {type_ids: type_id},
                    success: function(response){
                        console.log(response);
                        $("#type-dropdown").html(response);
                    }
                })
            })


            


            // $("#addBtn").click(function(e){
            //     e.preventDefault();

            //     $.ajax({
            //         url: "../processPhp/add_process.php",
            //         method: "POST",
            //         data: $("#add-form").serialize() + '&action=addCategory',
            //         success: function (response){
            //             console.log(response)
            //             if(response == "addedSuccess"){

            //                 Swal.fire({
            //                     position: 'center',
            //                     icon: 'success',
            //                     title: 'Added Category Successfully!',
            //                     showConfirmButton: false,
            //                     timer: 1500  
            //                 }).then(function(){
            //                     window.location = "./category.php";
            //                 })

            //             }

            //             else if(response == "error"){

            //                 Swal.fire({
            //                     position: 'center',
            //                     icon: 'error',
            //                     title: 'There is an error. Please Try Again!',
            //                     showConfirmButton: false,
            //                     timer: 1500  
            //                 }).then(function(){
            //                     window.location = "./category.php";
            //                 })

            //             }
            //         }
            //     })
            // })


            


            
        
            
        })

        

    </script>

</body>
</html>