<?php
session_start();
include 'includes/conn.php';


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];


    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" = string type


    $stmt->execute();
    $result = $stmt->get_result(); //store it in $result


    if ($result->num_rows < 1) //if no matching username
    {
        $_SESSION['error'] = 'Cannot find account with that username';
    } else {
        $row = $result->fetch_assoc();


        if (password_verify($password, $row['password'])) //compare the entered password with the hashed password from the database.
        {
            $_SESSION['admin'] = $row['id'];
        } else {
            $_SESSION['error'] = 'Incorrect password';
        }
    }


    $stmt->close(); //closes the databas
} else {
    $_SESSION['error'] = 'Input admin credentials first';
    //If the page was accessed directly (no form submitted), sets an error.
}


header('Location: index.php'); // this will happen at index.php
 ?>
