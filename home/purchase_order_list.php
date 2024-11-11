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
                            <i class="fa-solid fa-layer-group"></i><span>PURCHASE ORDER LIST</span>
                        </div>

                        <form action="purchase_order_print_filter.php" method="POST" class="mt-3">
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
                                    <a href="purchase_order_print.php" class="btn btn-success btn-block">Print Report</a>
                                </div>
                            </div>
                        </form>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                

                                <div class="d-flex justify-content-end align-items-center " style="flex-direction: row">
                                    <a href="purchase_order.php" class="btn btn-primary addBtn" >ADD ORDER</a>
                                </div>

                                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Order</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="add-form">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Supplier Name</label>
                                                    <select name="supplier_Id" class="form-control" style="">
                                                        <option value="">Select Supplier</option>
                                                        <?php 
                                                            $sqlp = "SELECT * FROM supplier";
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
                                                <div class="col-md-12">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Product</label>
                                                    <select name="item-dropdown" id="selectpicker" data-live-search="true" class="form-control" style="">
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
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>No. of Items</label>
                                                    <input type="number" name="no_of_item" id="name" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                                <!-- <button type="button" class="btn btn-primary">Save</button> -->
                                                <input type="submit" name="addBtn" id="addBtn" value="Submit" class="btn btn-info">

                                                <!-- Dito na tayo sa form ang pag eedit ng status  -->
                                            </div>
                                        </form>
                                    </div>
                                    
                                    </div>
                                </div>
                                </div>
                                



                                <div class="table-container mt-3">
                                    <table class="table table-hover table-border table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">ID</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">BATCH #</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">DATE CREATED</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">SUPPLIER</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STATUS</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartTable">

                                            <?php 
                                                // $sql = "SELECT o.order_Id, o.supplier_Id, o.item, o.no_of_item, o.status, o.date_created, s.supplier_Id, s.name, p.product_Id, p.type_Id, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM order_purchase o INNER JOIN supplier s ON o.supplier_Id = s.supplier_Id INNER JOIN products p ON o.item = p.product_Id INNER JOIN category_product_item_type_table cpit ON cpit.category_product_item_type_Id = p.type_Id ORDER BY order_Id DESC";
                                                $sql = "SELECT o.order_Id, o.supplier_Id, o.status, o.date_created, s.supplier_Id, s.name FROM order_purchase o INNER JOIN supplier s ON o.supplier_Id = s.supplier_Id ORDER BY order_Id DESC";
                                                $result = $conn->query($sql);
                                            ?>
                                            
                                            <?php if($result->num_rows > 0){?>
                                                <?php 
                                                
                                                $x = 1;
                                                
                                                while($row = $result->fetch_assoc()){
                                                    
                                                    
                                                ?>
                                            <tr>
                                                <td class="text-center d-none" style="font-size: 20px;" ><?php echo $row['order_Id'];?></td>
                                                <td class="text-center" style="font-size: 20px;" ><?php echo $x;?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo date("F j Y", strtotime($row['date_created']));?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['name'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['status'];?></td>
                                                
                                                <td class="d-flex justify-content-center align-items-center">
                                                    <button class="btn btn-secondary editBtn">EDIT</button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="edit_status" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">EDIT PURCHASE ORDER</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <form id="edit-form">
                                                                        <input type="text" name="edit_purchaseOrderId" id="edit_purchaseOrderId" value="" class="d-none form-control" >

                                                                        <div class="row mt-3">
                                                                            <div class="col-md-6">
                                                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Batch #</label>
                                                                                <input type="text" name="batch_no" id="batch_no" value="" class="form-control" readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Date Created</label>
                                                                                <input type="text" name="no_of_item" id="date" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="row mt-3">
                                                                            <div class="col-md-12">
                                                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Supplier</label>
                                                                                <input type="text" name="supplier" id="supplier" value="" class="form-control" readonly>
                                                                            </div>
                                                                        </div>


                                                                        <div class="row mt-3">
                                                                            <div class="col-md-12">
                                                                                <label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger" >* </span>Select Status</label>
                                                                                <select name="status_batch" id="status" class="form-control">
                                                                                    <option value="">Select Status</option>
                                                                                    <option value="Pending">Pending</option>
                                                                                    <option value="On-Going">On-Going</option>
                                                                                    <option value="Successed">Successed</option>
                                                                                    <option value="Returned">Reject</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        

                                                                        <div class="modal-footer">
                                                                            
                                                                            <button type="button" id="" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <!-- <button type="button" class="btn btn-primary">ADD</button> -->
                                                                            <input type="submit" id="update_statusBtn" value="UPDATE" class="btn btn-primary">
                                                                            <!-- <input type="button" name="" id="editButton" value="EDIT" class="btn btn-primary"> -->
                                                                        </div>
                                                                    </form>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <a href="purchase_order_view_list.php?order_Id=<?php echo $row['order_Id'];?>&batch=<?php echo $x; ?>" class="btn btn-info ml-1"> VIEW</button>
                                                </td>
                                            </tr>
                                                <?php 

                                                $x++;
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
                $('#batch_no').val(data[1]);
                $('#date').val(data[2]);
                $('#supplier').val(data[3]);
                $('#status').val(data[4]);

                
                $('#status option').filter(function() {
                    return $(this).text() === data[4];
                }).prop('selected', true);
            });




            $('#selectpicker').selectpicker();

            $("#addBtn").click(function(e){
            console.log("napindot");
            e.preventDefault();

            $.ajax({
                url: "../processPhp/add_process.php",
                method: "POST",
                data: $("#add-form").serialize() + "&action=addOrder",
                success : function (response){
                    if(response == "editedSuccess"){
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

            // console.log("Hello World")
        $("#update_statusBtn").click(function(e){
            console.log("napindot");
            e.preventDefault();

            $.ajax({
                url: "../processPhp/edit_process.php",
                method: "POST",
                data: $("#edit-form").serialize() + "&action=editPurchaseOrder",
                success : function (response){
                    if(response == "editedSuccess"){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully Edited Purchase Order!',
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