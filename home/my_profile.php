<?php 

    require_once("../connection.php");

    session_start();

    if(!isset($_SESSION['officials_Id'])){
        header('Location: ../index.php');
    }
    $official_Id = $_SESSION['officials_Id'];
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

            <div class="right-container">
                <div class="row m-0 p-0">
                    <div class="col-md-12">
                        <div class="main-title">
                            <i class="fa-solid fa-layer-group"></i><span>PROFILE</span>
                        </div>
                        <?php 
                            $sql = "SELECT * FROM officials WHERE officials_Id='$official_Id'";
                            $result = $conn->query($sql);
                        ?>

                        <?php if($result->num_rows > 0){?>
                            <?php while($row = $result->fetch_assoc()){?>
                        <form id="edit-form">
                            <div class="row mt-3 px-3">
                            <input type="hidden" name="edit_officialId" value="<?php echo $row['officials_Id']?>" id="edit_officialId" class="form-control" >
                                <div class="col-md-6">
                                    <label for="" ><span class="text-danger font-weight-bold" style="">* </span>First Name</label>
                                    <input type="text" name="edit_first_name" value="<?php echo $row['first_name']?>" id="first_name" class="form-control" >
                                </div>

                                <div class="col-md-6">
                                    <label for="" ><span class="text-danger font-weight-bold">* </span>Last Name</label>
                                    <input type="text" name="edit_last_name" value="<?php echo $row['last_name']?>" id="last_name" class="form-control" >
                                </div>
                            </div>

                            <div class="row mt-3 px-3">
                                <div class="col-md-12">
                                    <label for="" ><span class="text-danger font-weight-bold" style="">* </span>Email</label>
                                    <input type="email" value="<?php echo $row['email_address']?>" name="edit_email" id="email" class="form-control" >
                                </div>
                            </div>
                            
                            <label for="" class="mt-3 px-3 text-danger font-weight-bold">Note: If you don't want to change your password just leave it blank.</label>
                            <div class="row px-3">
                                <div class="col-md-6">
                                    <label for="" ><span class="text-danger font-weight-bold" style="">* </span>Password</label>
                                    <input type="password" value="" name="edit_password" id="password" class="form-control" >
                                </div>

                                <div class="col-md-6">
                                    <label for="" ><span class="text-danger font-weight-bold">* </span>Confirm Password</label>
                                    <input type="password" value="" name="edit_con_password" id="con_password" class="form-control" >
                                </div>
                            </div>

                            <input type="submit" class="btn btn-secondary mt-3 ml-3" id="editBtn" value="Update">
                                <?php } ?>
                            <?php } ?>
                        </form>
                        
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

            $("#editBtn").click(function(e){
                console.log("napindot");
                e.preventDefault();

                $.ajax({
                    url: "../processPhp/edit_process.php",
                    method: "POST",
                    data: $("#edit-form").serialize() + "&action=editProfile",
                    success : function (response){
                        if(response == "editedSuccess"){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Successfully Edited Profile!',
                                showConfirmButton: false,
                                timer: 1300  
                            }).then(function(){
                                window.location = "my_profile.php";
                            })
                        }

                        else if(response == "Mismatch"){
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'The password does not match the confirmation password!',
                                showConfirmButton: false,
                                timer: 1500  
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