<?php 

require_once("../connection.php");

if(isset($_POST['action']) && $_POST['action'] == "forgotOfficials"){

     $email = $_POST['email'];

     $sql = "SELECT * FROM officials WHERE email_address='$email'";
     $result = $conn->query($sql);

     if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        $token = "qwertyuiopasdfghjklzxcvbnm1234567890";
        $token = str_shuffle($token);
        $token = substr($token, 0,10);

        $sqlu = "UPDATE officials SET token='$token', token_expire=DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email_address='$email'";
        $resultu = $conn->query($sqlu);

        if($resultu){

            // require("Phpmailer/PHPMailer.php");
            // require("Phpmailer/SMTP.php");

            require("../Phpmailer/PHPMailer.php");
            require("../Phpmailer/SMTP.php");

            $mail = new PHPMailer\PHPMailer\PHPMailer();


            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";

            $mail->Username = "electronicsfmj@gmail.com";
            $mail->Password = "ympvhdvavuqdgdis";

            $mail->AddAddress($email);
            $mail->setFrom('electronicsfmj@gmail.com', 'FMJ ELECTRONICS');
            $mail->Subject = 'Reset Password';
            $mail->isHTML(true);

            $mail->Body = "<h3>Click the Link Below to reset your password.</h3>
                           <br>
                           <a href='http://localhost/fmj-electronics/forgotPass.php?email=$email&token=$token'>http://localhost/fmj-electronics/forgotPass.php?email=$email&token=$token</a>
                           <br>
                           <h3>Regards:</h3>
                           <h3>FMJ ELECTRONICS</h3>";

            if($mail->send()){
                echo "SuccessedSent";
                // <a href='http://localhost/DCMSMID/forgotPass.php?email=$email&token=$token'>http://localhost/DCMSMID/forgotPass.php?email=$email&token=$token</a>
            }

            else{
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' .$mail->ErrorInfo;
            }
        }
     }

     else{
        echo "EmptyEmail";
     }
}



if(isset($_POST['action']) && $_POST['action'] == "resetOfficials"){

    $email = $_POST['email'];
    $token = $_POST['token'];

    $sql = "SELECT officials_Id from officials WHERE email_address='$email' AND token='$token' AND token<>'' AND token_expire>NOW()";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $newpass = sha1($_POST['newpass']);
        $cnewpass = sha1($_POST['cnewpass']);

        if($newpass == $cnewpass){
            
            $sqlu = "UPDATE officials SET token='', password='$newpass' WHERE email_address='$email'";
            $resultu= $conn->query($resultu);

            echo '<script>window.location.href = "index.php"</script>';
            
        }

        else{
            echo '<script>window.location.href = "index.php"</script>';
        }
    }
}

?>