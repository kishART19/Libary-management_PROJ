<?php
include 'includes/session.php';
include 'includes/conn.php';


if (!isset($_SESSION['student'])) {
  die("Access denied.");
}


$student_id = $_SESSION['student'];
$book_id = $_GET['book_id'] ?? 0;


// Check if student has borrowed this book
$sql = "SELECT * FROM returns WHERE student_id = ? AND book_id = ? AND date_return IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 1) {
  die("You are not authorized to view this PDF.");
}


// Get the file name
$sql = "SELECT pdf_file FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->bind_result($pdf_file);
$stmt->fetch();
$stmt->close();


$file_path = 'admin/pdfs/' . $pdf_file;




if (file_exists($file_path)) {
  header('Content-type: application/pdf');
  header('Content-Disposition: inline; filename="'.$pdf_file.'"');
  readfile($file_path);
} else {
  echo "PDF file not found.";
}


?>