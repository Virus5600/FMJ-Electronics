<?php 

require_once("../connection.php");

if(isset($_POST['action']) && $_POST['action'] == "loginOfficials"){

     $email = $_POST['email'];
    //  $password = sha1($_POST['password']);
    $password = $_POST['password'];
    $password = sha1($password);

    $sql = "SELECT * FROM officials WHERE email_address='$email' AND password='$password' AND status='Active'";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();

    if($row != null){
        session_start();
        
        $_SESSION['officials_Id'] = $row['officials_Id'];
        $_SESSION['user_type'] = $row['user_type'];
        echo $_SESSION['user_type'] = $row['user_type'];

        $officials_Id = $_SESSION['officials_Id'];

        $action = "Logged In";

        $sqli = "INSERT INTO audit_trail_table (action, officials_Id) VALUES ('$action', '$officials_Id')";
        $result = $conn->query($sqli);

        
    }

    else{
        echo "loginFailed";
    }
}

?>