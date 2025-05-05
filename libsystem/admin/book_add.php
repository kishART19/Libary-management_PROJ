<?php
    include 'includes/session.php';


    if (isset($_POST['add'])) {
        $isbn = $_POST['isbn'];


        // Handle photo upload
        $photo = $_FILES['photo']['name'];
        if (!empty($photo)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $photo);
        }


        // Handle PDF upload
        $pdf_file = $_FILES['pdf_file']['name'];
        if (!empty($pdf_file)) {
            move_uploaded_file($_FILES['pdf_file']['tmp_name'], '../pdfs/' . $pdf_file);
        }


        // Escape the data to prevent SQL injection and errors
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $overview = mysqli_real_escape_string($conn, $_POST['overview']);
        $category = $_POST['category'];
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
        $pub_date = $_POST['pub_date'];


        // Add pdf_file column in your INSERT query
        $sql = "INSERT INTO books (isbn, photo, pdf_file, category_id, title, overview, author, publisher, publish_date)
                VALUES ('$isbn', '$photo', '$pdf_file', '$category', '$title', '$overview', '$author', '$publisher', '$pub_date')";


        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Book added successfully';
        } else {
            $_SESSION['error'] = $conn->error;
        }
    } else {
        $_SESSION['error'] = 'Fill up add form first';
    }


    header('location: book.php');
?>
