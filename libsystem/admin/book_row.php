<?php
    include 'includes/session.php';


    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $sql = "SELECT *, books.id AS bookid FROM books LEFT JOIN category ON category.id=books.category_id WHERE books.id = '$id'";


/**Selects all columns (*) from the books table.


LEFT JOINs the category table, so you get the category details along with each book.


books.id AS bookid — This is an alias. Even though you're selecting *, this line ensures that bookid in the result is always coming from books.id.


Filters to only the row where books.id = $id. */


        $query = $conn->query($sql);
        $row = $query->fetch_assoc();


        echo json_encode($row);


/**Converts the PHP associative array to a JSON string.


This is what gets sent back to the browser, where JavaScript (AJAX) can process it. */
    }
?>
