<?php
    include 'includes/session.php';


    if(isset($_POST['upload'])){
        $id = $_POST['id'];
        $photo = $_FILES['photo']['name'];
        if(!empty($photo)){
            move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);  
        }
       
        $sql = "UPDATE books SET photo = '$photo' WHERE id = '$id'";
        if($conn->query($sql)){
            $_SESSION['success'] = 'Book photo updated successfully';
        }
        else{
            $_SESSION['error'] = $conn->error;
        }


    }
    else{
        $_SESSION['error'] = 'Select Book to update photo first';
    }


    header('location: book.php');
?>
