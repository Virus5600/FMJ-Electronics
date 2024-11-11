<?php 

    session_start();

    require_once('../connection.php');

    $officials_Id = $_SESSION['officials_Id'];

    $action = "Logged Out";

    $sqli = "INSERT INTO audit_trail_table (action, officials_Id) VALUES ('$action', '$officials_Id')";
    $result = $conn->query($sqli);

    session_destroy();

    header("Location: ../");
    exit();

    


?>