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

            <div class="right-container">
                <div class="row m-0 p-0">
                    <div class="col-md-12">
                        <div class="main-title">
                            <i class="fa-solid fa-layer-group"></i><span>AUDIT TRAIL REPORTS </span>
                        </div>
                    </div>
  
                </div>

                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="container mb-5">
                        <div class="card ">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0 mr-3">Print Audit Trail Reports</h5>

                                <div>

                                <a href="audit_Trail.php" class="btn btn-secondary text-light" >
                                    Back
                                </a>

                                <button class="btn btn-success btn-border btn-round" onclick="printDiv('printThis')">
                                    Print
                                </button>
                                </div>
                                
                                
                            </div>       
                        </div>

                        <div class="card-body m-5 " id="printThis">
                            <div class="d-flex flex-wrap justify-content-center pb-3 px-5" >
                                <div class="" style="width: 100%">

                                    <div class="row mt-5">
                                        <div class="col-md-4">
                                            <div style="" class="d-flex justify-content-end align-items-center">
                                                <img src="./img/LogoAdam.png" class="" alt="" style="width: 80px; height: 80px;">
                                            </div>
                                        </div>

                                        <div class="col-md-5 d-flex justify-content-center align-items-center" style="flex-direction: column">
                                            <h3 style="font-weight: 900" class="mb-0">FMJ ELECTRONICS</h3>
                                            <h5 style="font-weight: 700" class="font-italic text-center">"Sells Appliances, Lights, Electronics, and Electrical Parts"</h5>
                                        </div>

                                        <div class="col-md-3 d-flex justify-content-center " style="flex-direction: column">
                                            
                                        </div>
                                        
                                    </div>

                                    <div class="text-center ml-5 mt-3">
                                        <p class="mb-0" style="font-size: 14px;">1930 Quezon Avenue, Binangonan, 1940 Rizal Philippines</p>
                                        <p class="mb-0" style="font-size: 14px;">Mobile: (63) 919 636 9191</p>
                                        <p class="mb-0" style="font-size: 14px;">E-mail: godofredoagarap@gmail.com</p>
                                    </div>
    
                                </div>
                            </div>


                            <div class="row px-5 mt-5" style="width: 100%; margin: 0 auto;">

                            <div class="table-container" style="width: 100%">
                                <table class="table table-hover table-border table-sm" style="width: 100%">
                                    <thead style="width: 100%">
                                        <tr style="width: 100%">
                                            <th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">PRODUCT ID</th>
                                            <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">User</th>
                                            <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Operation</th>
                                            <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Date & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody style="width: 100%">
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

        function printDiv(divName) {

            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;



            window.print();


            document.body.innerHTML = originalContents;
        }

    </script> 

        

        

</body>
</html>