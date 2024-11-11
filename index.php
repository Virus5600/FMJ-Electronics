<?php 

    require_once("connection.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMJ ELECTRONICS</title>
    
    <link rel="stylesheet" href="styles/index.css">

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

    <div class="fluid-container">
        
        <div class="container second-container d-flex justify-content-center align-items-center">
            <div class="box-container">
                <div class="row" style="height: 100%;">
                    <div class="col-md-4 pr-0">
                        <div class="left-container-image">
                            
                        </div>
                    </div>

                    <div class="col-md-8 pl-0 d-flex justify-content-center align-items-center" >
                        <div class="left-container p-4" >
                            <h1 class="login-header">LOG<span>IN</span></h1>
                            <form id="login-form" class="px-3 d-flex justify-content-center align-items-center" style="flex-direction: column">
                                <input type="email" name="email" class="input email-input" placeholder="EMAIL">
                                <input type="password" name="password" id="password" class="input password-input mt-3" placeholder="PASSWORD">

                                <div class="d-flex justify-content-start ml-4 mt-2" style="width: 100%;">
                                    <a href="forgot_password.php">FORGOT PASSWORD?</a>
                                </div>

                                <div class="d-flex justify-content-center align-items-center mt-3" style="width: 100%;">
                                    <input type="submit" id="login" class="btn-login" value="LOGIN">
                                </div>
                            </form>
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

    <script>
        $(document).ready(function(){
            $("#login-form").validate();

            $("#login").click(function(e){
                if(document.getElementById("login-form").checkValidity()){
                    e.preventDefault();

                    $.ajax({
                        url: './processPhp/login_process.php',
                        method: 'POST',
                        data: $('#login-form').serialize() + '&action=loginOfficials',
                        success: function(response){
                            if(response == "Admin"){

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Successfully Log in as Admin!',
                                    showConfirmButton: false,
                                    timer: 1500  
                                }).then(function(){
                                    window.location = "./home/dashboard.php";
                                })

                            }

                            else if(response == "Cashier"){

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Successfully Log in as Cashier!',
                                    showConfirmButton: false,
                                    timer: 1500  
                                }).then(function(){
                                    window.location = "./home/dashboard.php";
                                })

                            }

                            else if(response == "Staff"){

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Successfully Log in as Staff!',
                                    showConfirmButton: false,
                                    timer: 1500  
                                }).then(function(){
                                    window.location = "./home/dashboard.php";
                                })

                            }

                            else if(response == "loginFailed"){

                                Swal.fire({
                                    position: 'center',
                                    icon: 'warning',
                                    title: 'Login Failed Make sure the Username and Password is correct!',
                                    showConfirmButton: false,
                                    timer: 2000  
                                }).then(function(){
                                    // window.location = "dashPatient.php";
                                })

                                $('#password').val(null);

                                

                            }
                        }
                    })

                }

                else{
                    return true;
                }
            })
        })
    </script>

</body>
</html>