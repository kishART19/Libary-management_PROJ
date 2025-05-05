<?php
include 'includes/session.php';
include 'includes/conn.php';// if you need to connect to DB


if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);


    // Fetch the pdf_file name from the database
    $query = "SELECT pdf_file FROM books WHERE id = $book_id";
    $result = mysqli_query($conn, $query);


    if ($row = mysqli_fetch_assoc($result)) {
        $pdf_file = $row['pdf_file'];
        $file_path = 'pdfs/' . $pdf_file; // assuming PDFs are in uploads/


        if (file_exists($file_path)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
            readfile($file_path);
            exit;
        } else {
            echo "PDF file not found.";
        }
    } else {
        echo "Book not found.";
    }
} else {
    echo "No book ID specified.";
}
?>
