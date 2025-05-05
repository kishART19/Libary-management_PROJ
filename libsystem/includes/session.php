<?php
    include 'includes/conn.php'; //this conn is connected to database.
    session_start(); //Necessary for tracking users across multiple pages (like login/logout).


    if(isset($_SESSION['student'])) //check if student is logged in.
    {
        $sql = "SELECT * FROM students WHERE id = '".$_SESSION['student']."'";
        $query = $conn->query($sql); //Runs the query
        $student = $query->fetch_assoc();
    }


/*Checks if a student is logged in.
If yes, fetches their full info from the students table.
Stores that info in $student so it can be used on any page that includes this script.*/


?>
