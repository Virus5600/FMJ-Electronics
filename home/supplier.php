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
                            <i class="fa-solid fa-layer-group"></i><span>SUPPLIER</span>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <?php 
                                
                                    $sql = "SELECT * FROM supplier WHERE status='Active' ORDER BY supplier_Id DESC";
                                    $result = $conn->query($sql);
                                ?>

                                <button type="button" class="btn btn-info addBtn" data-toggle="modal" data-target="#addModal">ADD SUPPLIER</button>

                                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="add-form">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <label for="" ><span class="text-danger">* </span>Name</label>
                                                    <input type="text" value="" name="name" id="name" class="form-control" >
                                                </div>
                                                <div class="col-md-6">
                                                    <label for=""><span class="text-danger">* </span>Address</label>
                                                    <input type="text" value="" name="address" id="address" class="form-control" >
                                                </div>
                                            </div>


                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for=""><span class="text-danger">* </span>Contact Person</label>
                                                    <input type="text" value="" name="contact_person" id="contact_person" class="form-control" >
                                                </div>

                                                <div class="col-md-6">
                                                    <label for=""><span class="text-danger">* </span>Contact No.</label>
                                                    <input type="number" value="" name="contact_no" id="contact_no" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label for=""><span class="text-danger">* </span>Status</label>
                                                    <!-- <input type="text" value="" id="status" class="form-control" > -->
                                                    <select name="status" id="status" class="form-control" >
                                                        <option value="">Select Status:</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                    </select>
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
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">DATE CREATED </th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">SUPPLIER </th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ADDRESS </th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">CONTACT PERSON</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">CONTACT NO</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STATUS</th>
                                                <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">
                                            
                                            <?php if($result->num_rows > 0){?>
                                                <?php while($row = $result->fetch_assoc()){?>
                                            <tr>
                                                <td class="text-center d-none" style="font-size: 20px;" ><?php echo $row['supplier_Id'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo date("F j Y", strtotime($row['date_created']));?></td>
                                                <td class="text-center" style="font-size: 20px;" ><?php echo $row['name'];?></td>
                                                <td class="text-center" style="font-size: 20px;" ><?php echo $row['address'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['contact_person'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['contact_no'];?></td>
                                                <td class="text-center" style="font-size: 20px;"><?php echo $row['status'];?></td>
                                                
                                                <td class="d-flex justify-content-center align-items-center">
                                                    <button type="button" class="btn btn-secondary editBtn"> EDIT</button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="edit_supplier" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="edit-form">
                                                                        <div class="row">
                                                                            <input type="text" name="edit_supplier_Id" id="edit_supplier_Id" class="form-control d-none" >
                                                                            <div class="col-md-6">
                                                                                <label for="" ><span class="text-danger">* </span>Name</label>
                                                                                <input type="text"  name="edit_name" id="edit_name" class="form-control" >
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for=""><span class="text-danger">* </span>Address</label>
                                                                                <input type="text"  name="edit_address" id="edit_address" class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="row mt-3">
                                                                            <div class="col-md-6">
                                                                                <label for=""><span class="text-danger">* </span>Contact Person</label>
                                                                                <input type="text" name="edit_contact_person" id="edit_contact_person" class="form-control" >
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for=""><span class="text-danger">* </span>Contact No.</label>
                                                                                <input type="number" name="edit_contact_no" id="edit_contact_no" class="form-control" >
                                                                            </div>
                                                                        </div>

                                                                        <div class="row mt-3">
                                                                            <div class="col-md-12">
                                                                                <label for=""><span class="text-danger">* </span>Status</label>
                                                                                <!-- <input type="text" value="" id="status" class="form-control" > -->
                                                                                <select name="edit_status" id="edit_status" class="form-control" >
                                                                                    <option value="Active">Active</option>
                                                                                    <option value="Inactive">Inactive</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                                                            <!-- <button type="button" class="btn btn-primary">Save</button> -->
                                                                            <input type="submit" name="editBtn" id="updateBtn" value="UPDATE" class="btn btn-info">

                                                                            <!-- Dito na tayo sa form ang pag eedit ng status  -->
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                
                                                                </div>
                                                            </div>
                                                            </div>
                                                </td>

                                                

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
                $('#edit_supplier').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();


                $('#edit_supplier_Id').val(data[0]);
                $('#edit_name').val(data[2]);
                $('#edit_address').val(data[3]);
                $('#edit_contact_person').val(data[4]);
                $('#edit_contact_no').val(data[5]);
                $('#edit_status').val(data[6]);

                $('#edit_status option').filter(function() {
                    return $(this).text() === data[6];
                }).prop('selected', true);

            });

            $("#addBtn").click(function(e){
            console.log("napindot");
            e.preventDefault();

            $.ajax({
                url: "../processPhp/add_process.php",
                method: "POST",
                data: $("#add-form").serialize() + "&action=addSupplier",
                success : function (response){
                    if(response == "addedSuccess"){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully Added Supplier!',
                            showConfirmButton: false,
                            timer: 1300  
                        }).then(function(){
                            window.location = "supplier.php";
                        })
                    }

                    else if(response == "emptyFields"){
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Make sure the fields are not empty. Please try again Thankyou!',
                            showConfirmButton: false,
                            timer: 1300  
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
        $("#updateBtn").click(function(e){
            console.log("napindot");
            e.preventDefault();

            $.ajax({
                url: "../processPhp/edit_process.php",
                method: "POST",
                data: $("#edit-form").serialize() + "&action=editSupplier",
                success : function (response){
                    if(response == "editedSuccess"){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully Edited Supplier!',
                            showConfirmButton: false,
                            timer: 1300  
                        }).then(function(){
                            window.location = "supplier.php";
                        })
                    }

                    else if(response == "NotSuccess"){
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