<?php
include 'includes/session.php';


if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $overview = $_POST['overview'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $pub_date = $_POST['pub_date'];


    $filename = '';


    // Handle PDF upload
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $pdf_name = $_FILES['pdf_file']['name'];
        $pdf_tmp = $_FILES['pdf_file']['tmp_name'];
        $ext = pathinfo($pdf_name, PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;


        // Move the file to the pdfs folder
        if (move_uploaded_file($pdf_tmp, 'pdfs/' . $filename)) {
            // Update with nezw PDF file
            $stmt = $conn->prepare("UPDATE books SET isbn = ?, title = ?, overview = ?, category_id = ?, author = ?, publisher = ?, publish_date = ?, pdf_file = ? WHERE id = ?");
            $stmt->bind_param("ssssssssi", $isbn, $title, $overview, $category, $author, $publisher, $pub_date, $filename, $id);
        } else {
            $_SESSION['error'] = 'Failed to upload PDF file';
            header('location: book.php');
            exit();
        }
    } else {
        // Update without changing the PDF file
        $stmt = $conn->prepare("UPDATE books SET isbn = ?, title = ?, overview = ?, category_id = ?, author = ?, publisher = ?, publish_date = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $isbn, $title, $overview, $category, $author, $publisher, $pub_date, $id);
    }


    if ($stmt->execute()) {
        $_SESSION['success'] = 'Book updated successfully';
    } else {
        $_SESSION['error'] = $stmt->error;
    }


    $stmt->close();
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}


header('location: book.php');