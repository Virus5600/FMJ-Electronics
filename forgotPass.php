<?php 

    require_once("connection.php");

    if(isset($_GET['email']) && isset($_GET['token'])){
        $email = $_GET['email'];
        $token = $_GET['token'];

        $sql = "SELECT officials_Id from officials WHERE email_address='$email' AND token='$token' AND token<>'' AND token_expire>NOW()";
        $result = $conn->query($sql);

        if($result->num_rows>0){
            if(isset($_POST['reset'])){

                 $newpass = sha1($_POST['newpass']);
                 $cnewpass = sha1($_POST['cnewpass']);

                if($newpass == $cnewpass){
                    $sqlu = "UPDATE officials SET token='', password='$newpass' WHERE email_address='$email'";
                    $resultu= $conn->query($sqlu);
                    echo '<script>alert("Successfully Updated Password!")</script>';
                    echo '<script>window.location.href = "index.php"</script>';
                    header("Location: index.php");
                    
                }

                else{
                    echo '<script>alert("Make sure the New Password and Confirm Password is match!")</script>';
                }
            }

        }

        else{
            header("Location: forgot_password.php");
        }

    }

    else{
        header("Location: forgot_password.php");
    }
            
    // else{
    //     header("Location: index.php");
    // }

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
                            
                            <h1 class="login-header text-center" style="font-size: 48px; text-align: center">RESET<span> PASSWORD</span></h1>
                            <p class="text-center" style="font-size: 16px; text-align: center; margin-top: -20px;">To reset your password enter the email address and we will send the reset password instructions on your email.</p>
                            
                            <form id="login-form" action="" method="POST" class="px-3 d-flex justify-content-center align-items-center" style="flex-direction: column; width: 100%;">
                                <div>
                                    <input type="password" name="newpass" class="input email-input" placeholder="New Password" style="width: 100%;">
                                </div>

                                <div>
                                    <input type="password" name="cnewpass" class="input email-input" placeholder="Confirm Passwor" style="width: 100%;">
                                </div>
                                

                                <div class="d-flex justify-content-center align-items-center mt-3" style="width: 100%;">
                                    <input type="submit" name="reset" id="reset" class="btn-login" value="FORGOT">
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
        
    </script>

</body>
</html>