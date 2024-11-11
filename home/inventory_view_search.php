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

    if(isset($_POST["searchSubmit"]) && $_POST['search'] != ""){
        $getSearch = $_POST['search'];

        $sql = "SELECT p.product_Id, p.item_code, p.barcode, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.item_code LIKE '%$getSearch%' || p.barcode LIKE '%$getSearch%' || cpit.product_item_type_name LIKE '%$getSearch%' || c.category_Name LIKE '%$getSearch%' || cp.product_Name LIKE '%$getSearch%' || cpi.product_item_name LIKE '%$getSearch%' AND p.archive='No'";
        $result = $conn->query($sql);
    }

    else{
        header("Location: inventory_view.php");
    }

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
                            <i class="fa-solid fa-layer-group"></i><span>INVENTORY</span>
                        </div>

                        <div class="addBtn-container d-flex justify-content-between mb-3">
                            <form action="inventory_view_search.php" method="post" class="d-flex mr-5 mt-3" >
                                <input type="text" name="search" id="search" class="form-control mr-1" placeholder="Search" >
                                <input type="submit" value="Search" id="searchSubmit" class="btn btn-dark ml-1" >
                            </form>
                        </div>

                        <!-- <button type="button" id="editButton" class="btn btn-secondary" >Close</button> -->
                        <!-- <input type="submit" id="editButton" value="EDIT" class="btn btn-primary"> -->
                        

                        <div class="table-container">
                            <table class="table table-hover table-border table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">PRODUCT ID</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM CODE</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">BARCODE</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">CATEGORY</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRODUCT</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRODUCT TYPES</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TYPES</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STOCKS</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRIZE</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">

                                    <?php if($result->num_rows > 0){?>
                                        <?php while($row = $result->fetch_assoc()){?>
                                    <tr>
                                        <td class="td-product text-center d-none" style="font-size: 18px; font-weight: 700"><?php echo $row['product_Id'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['item_code'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['barcode'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['category_Name'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['product_Name'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['product_item_name'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['product_item_type_name'];?>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['stocks'];?></td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['prize'];?></td>
                                        <td class="d-flex justify-content-around align-items-center">

                                            <!-- Button trigger modal -->
                                            <!-- <input type="submit" class="btn btn-secondary editBtn" value="EDIT"> -->
                                            <button class="btn btn-secondary editBtn btn-sm">EDIT</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="edit_product" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">EDIT PRODUCTS</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <form id="edit-form">

                                                                <input type="text" id="edit_product_Id" name="edit_product_Id" class="d-none form-control" >

                                                                <div class="">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Item Code</label>
                                                                    <input type="text" id="edit_item_code" name="edit_item_code" class="form-control" readonly>
                                                                </div>

                                                                <div class="">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Bar Code</label>
                                                                    <input type="text" id="edit_bar_code" name="edit_bar_code" class="form-control" >
                                                                </div>

                                                                <div class="mt-1">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Category</label>
                                                                    <input type="text" id="edit_category_Id" name="edit_category_Id" class="form-control" readonly >
                                                                </div>

                                                                <div class="mt-1">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product</label>
                                                                    <input type="text" id="edit_category_product_Id" name="edit_category_product_Id" class="form-control" readonly>
                                                                </div>
                                                                
                                                                <div class="mt-1">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product Type</label>
                                                                    <input type="text" id="edit_product_type_Id" name="edit_product_type_Id" class="form-control" readonly>
                                                                </div>
                                                                
                                                                <div class="mt-1">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Type</label>
                                                                    <input type="text" id="edit_type_Id" name="edit_type_Id" class="form-control" readonly>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Stocks</label>
                                                                        <input type="text" id="stocks" name="stocks" class="form-control"  >
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Prize</label>
                                                                        <input type="text" id="prize" name="prize" class="form-control" >
                                                                    </div>
                                                                </div>

                                                                <!-- <div class="mt-1">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Stocks</label>
                                                                    <input type="text" id="stocks" name="stocks" class="form-control"  >
                                                                </div>

                                                                <div class="mt-1">
                                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Prize</label>
                                                                    <input type="text" id="prize" name="prize" class="form-control" >
                                                                </div> -->

                                                                <div class="modal-footer">
                                                                    
                                                                    <button type="button" id="" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <!-- <button type="button" class="btn btn-primary">ADD</button> -->
                                                                    <input type="submit" id="update_productBtn" value="UPDATE" class="btn btn-primary">
                                                                    <!-- <input type="button" name="" id="editButton" value="EDIT" class="btn btn-primary"> -->
                                                                </div>
                                                            </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                </div>
                                            </div>
                                            </div>

                                            <!-- <form action="category_product.php" method="POST">
                                                <input type="text" name="categoryId" class="d-none" value="<?php echo $row['category_Id'];?>">
                                                <input type="text" name="categoryName" class="d-none" value="<?php echo $row['category_Name'];?>">
                                                <input type="submit" name="categoryBtn" class="btn btn-info btn-sm" value="VIEW">
                                            </form> -->

                                            <button type="button" class="btn btn-danger btn-sm ml-2" data-id="<?php echo $row['product_Id'];?>" onclick="confirmDelete(this);">
                                                ARCHIVE
                                            </button>

                                            <div id="myModal" class="modal fade" >
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
                                                            <p class="h5">Are you sure you want to archive Product?</p>
                                                            <form action="" id="form-archive-product">
                                                                <input type="text" name="id" class="d-none">
                                                            </form>

                                                            <div class="d-flex justify-content-center align-items-center mt-3 px-5" style="flow-direction: column; width: 100%;" >
                                                                <button type="button" style="width: 49%;" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
                                                                <button type="submit" style="width: 49%;" form="form-delete-user" class="btn btn-danger ml-1" id="archive_btn" data-dismiss="modal">Archive</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <?php } ?>
                                    <?php 
                                    }
                                    ?>
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
                $('#edit_bar_code').val(data[2]);
                $('#edit_category_Id').val(data[3]);
                $('#edit_category_product_Id').val(data[4]);
                $('#edit_product_type_Id').val(data[5]);
                $('#edit_type_Id').val(data[6]);
                $('#stocks').val(data[7]);
                $('#prize').val(data[8]);

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

                        else if(response == "productExist"){

                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'The product already exist in the table!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){

                            })

                        }

                        else if(response == "fieldRequired"){
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'All fields are required. Please try again thankyou!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){

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