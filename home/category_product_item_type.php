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

    
    
    if(isset($_POST['categoryProductTypeBtn'])){

        $categoryName = $_POST['categoryName'];
        $productName = $_POST['productName'];

        $categoryId = $_POST['categoryId'];
        $categoryProductId = $_POST['categoryProductId'];
        $categoryProductItemId = $_POST['categoryProductItemId'];
        $productitemName = $_POST['productitemName'];
        
    }

    // if($categoryName == null || $categoryName == ""){
    //     header("Location: category.php");
    // }

    

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
                            <i class="fa-solid fa-layer-group"></i><span>CATEGORY -><span style="font-size: 22px; color: #606FF2"><?php echo $categoryName?><span style="font-size: 22px; color: black">-></span> <?php echo $productName?><span style="font-size: 22px; color: black">-></span> <?php echo $productitemName?></span></span>
                        </div>

                        <div class="addBtn-container d-flex justify-content-between mt-5 mb-3">
                            
                            <!-- <a href="category.php" class="btn btn-secondary">BACK</a> -->

                            <form action="category_product_type.php" method="POST"> 
                                <input type="text" name="categoryId" class="d-none" value="<?php echo $categoryId;?>">
                                <input type="text" name="categoryName" class="d-none" value="<?php echo $categoryName;?>">
                                <input type="text" name="categoryProductId" class="d-none" value="<?php echo $categoryProductId;?>">
                                <input type="text" name="product_Name" class="d-none" value="<?php echo $productName;?>">
                                <input type="submit" name="categoryProductBtn" class="btn btn-secondary" value="BACK">
                            </form>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                ADD TYPE
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ADD TYPE</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="add-form">
                                                <div class="d-none">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Category ID</label>
                                                    <input type="text" name="categoryId" class=" form-control" value="<?php echo $categoryId?>" readonly>
                                                </div>

                                                <div class="d-none mt-3 ">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Category Product ID</label>
                                                    <input type="text" name="categoryProductId" class=" form-control" value="<?php echo $categoryProductId?>" readonly>
                                                </div>

                                                <div class="d-none mt-3">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Category Product Item ID</label>
                                                    <input type="text" name="categoryProductItemId" class=" form-control" value="<?php echo $categoryProductItemId?>" readonly>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Item Name</label>
                                                    <input type="text" name="categoryProductItemTypeName" class="form-control" value="" >
                                                </div>
                                                

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <!-- <button type="button" class="btn btn-primary">ADD</button> -->
                                                    <input type="submit" id="addBtn" value="ADD" class="btn btn-primary">
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Dito na tayo sa pag update ng type  -->

                        <div class="table-container">
                            <table class="table table-hover table-border table-sm">
                                <thead>
                                    <tr>
                                        <th class="d-none" scope="col">CATEGORY ID</th>
                                        <th class="d-none" scope="col">CATEGORY PRODUCT ID</th>
                                        <th class="d-none" scope="col">ITEM ID</th>
                                        <th class="d-none" scope="col">TYPE ID</th>
                                        <th scope="col">TYPES OF <span style="text-transform: uppercase"><?php echo $productitemName?></span></th>
                                        <th scope="col">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    
                                    // $sql = "SELECT c.category_Id, c.category_Name, p.category_product_Id, p.category_Id as p_category_Id, p.product_Name as p_product_Name, i.category_product_item_Id, i.category_Id, i.category_product_Id, i.product_item_name FROM category_table c INNER JOIN category_product_table p ON c.category_Id = p.category_Id INNER JOIN category_product_item_table i ON c.category_Id = i.category_Id WHERE c.category_Id='$categoryId' AND p.category_product_Id ='$categoryProductId'";
                                    $sql = "SELECT i.category_product_item_Id, i.category_Id, i.category_product_Id, i.product_item_name, t.category_product_item_type_Id, t.category_Id, t.category_product_Id, t.category_product_item_Id, t.product_item_type_name, t.archive FROM category_product_item_table i INNER JOIN category_product_item_type_table t ON i.category_product_item_Id = t.category_product_item_Id WHERE t.category_product_item_Id='$categoryProductItemId' AND t.archive='No'"; 
                                    $result = $conn->query($sql);
                                    
                                    ?>

                                    <?php if($result->num_rows > 0){?>
                                        <?php while($row = $result->fetch_assoc()){?>
                                    <tr>
                                        <td class="d-none"><?php echo $row['category_Id'];?></td>
                                        <td class="d-none"><?php echo $row['category_product_Id'];?></td>
                                        <td class="d-none"><?php echo $row['category_product_item_Id'];?></td>
                                        <td class="d-none"><?php echo $row['category_product_item_type_Id'];?>
                                        <td><?php echo $row['product_item_type_name'];?></td>
                                        <td class="d-flex justify-content-around align-items-center">

                                            <button class="editBtn btn btn-secondary btn-sm" >EDIT</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="edit_category_product_item_type" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">EDIT ITEM</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <form id="edit-form">
                                                                <input type="text" name="edit_categoryId" id="edit_categoryId" value="" class="d-none form-control" >
                                                                <input type="text" name="edit_category_productId" id="edit_category_productId" value="" class="d-none form-control" >
                                                                <input type="text" name="edit_category_product_itemId" id="edit_category_product_itemId" value="" class="d-none form-control" >
                                                                <input type="text" name="edit_category_product_itemTypeId" id="edit_category_product_itemTypeId" value="" class="d-none form-control" >
                                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Type Name</label>
                                                                <input type="text" name="edit_ItemTypeName" id="edit_ItemTypeName" value="" class="form-control" >
                                                                
                                                                <div class="modal-footer">
                                                                    
                                                                    <button type="button" id="" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <!-- <button type="button" class="btn btn-primary">ADD</button> -->
                                                                    <input type="submit" id="update_category_product_itemTypeBtn" value="UPDATE" class="btn btn-primary ">
                                                                    <!-- <input type="button" name="" id="editButton" value="EDIT" class="btn btn-primary"> -->
                                                                </div>
                                                            </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                </div>
                                            </div>
                                            </div>


                                            <button type="button" class="btn btn-danger btn-sm" data-id="<?php echo $row['category_product_item_type_Id'];?>" onclick="confirmDelete(this);">
                                                ARCHIVE
                                            </button>

                                            <div id="myModal" class="modal fade" >
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
                                                            <p class="h5">Are you sure you want to archive Type?</p>
                                                            <form action="" id="form-archive-type">
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

            document.getElementById("form-archive-type").id.value = id;
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
                        data: $("#form-archive-type").serialize() + "&action=archiveType",
                        success : function (response){

                            if(response == "successArchive"){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Successfully Archived!',
                                        showConfirmButton: false,
                                        timer: 1300  
                                    }).then(function(){
                                        location.reload();
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
                                        location.reload();
                                    })
                            }
                            
                        }
                    })
            })

            // EDIT AJAX

            $('.editBtn').on('click', function(){
                console.log("Clikced")
                $('#edit_category_product_item_type').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();

                $('#edit_categoryId').val(data[0]);
                $('#edit_category_productId').val(data[1]);
                $('#edit_category_product_itemId').val(data[2]);
                $('#edit_category_product_itemTypeId').val(data[3]);
                $('#edit_ItemTypeName').val(data[4]);
            });

            $("#update_category_product_itemTypeBtn").click(function(e){
                e.preventDefault();

                $.ajax({
                    url: "../processPhp/edit_process.php",
                    method: "POST",
                    data: $("#edit-form").serialize() + '&action=editCategoryProductItemType',
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
                                location.reload();
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
                                location.reload();
                            })

                        }
                    }
                })
            })


            // ADD AJAX

            $("#addBtn").click(function(e){
                e.preventDefault();

                $.ajax({
                    url: "../processPhp/add_process.php",
                    method: "POST",
                    data: $("#add-form").serialize() + '&action=addProductItemType',
                    success: function (response){
                        console.log(response)
                        if(response == "addedSuccess"){

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Added Item Type Successfully!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                location.reload();
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
                                location.reload();
                            })

                        }
                    }
                })
            })
        
            
        })

    </script>

</body>
</html>