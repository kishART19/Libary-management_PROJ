<?php
    session_start();
    include 'includes/conn.php';


    if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
        header('location: index.php');
    }
/*checks if the admin is not login
Or empty after removing whitespace (trim(...) == '')


If either is true, the script redirects the user to index.php, probably a login page — meaning this section is protected and only accessible to logged-in admins.*/


    $sql = "SELECT * FROM admin WHERE id = '".$_SESSION['admin']."'";
    $query = $conn->query($sql);
    $user = $query->fetch_assoc();
   
/**Uses the admin's ID (stored in the session) to look up their full info in the admin table.


Runs the SQL query.


Stores the result in $user
 * If logged in, it fetches their details from the admin table for use in the page */
?>
