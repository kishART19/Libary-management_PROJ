<?php
    include 'includes/session.php';


    if(isset($_GET['return'])){
        $return = $_GET['return'];
    }
    else{
        $return = 'home.php';
    }


    if(isset($_POST['save'])){
        $curr_password = $_POST['curr_password'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $photo = $_FILES['photo']['name'];
        if(password_verify($curr_password, $user['password'])){


            if(!empty($photo)){
                move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);
                $filename = $photo;
            }
            else{
                $filename = $user['photo'];
            } //If a new photo is uploaded, it’s moved to ../images/.If not, the existing photo is kept.


            if($password == $user['password']){
                $password = $user['password'];
            }
            else{
                $password = password_hash($password, PASSWORD_DEFAULT);
            } //If the new password matches the current one (somewhat insecure check), it’s kept as is.Otherwise, the new password is hashed.


            $sql = "UPDATE admin SET username = '$username', password = '$password', firstname = '$firstname', lastname = '$lastname', photo = '$filename' WHERE id = '".$user['id']."'"; //Updates the admin’s profile info in the admin table Uses values from the form.


            if($conn->query($sql)){
                $_SESSION['success'] = 'Admin profile updated successfully';
            }
            else{
                if($return == 'borrow.php' OR $return == 'return.php'){
                    if(!isset($_SESSION['error'])){
                        $_SESSION['error'] = array();
                    }
                    $_SESSION['error'][] = $conn->error;
                }
                else{
                    $_SESSION['error'] = $conn->error;
                }
               
            }
           
        }
        else{
            if($return == 'borrow.php' OR $return == 'return.php'){
                if(!isset($_SESSION['error'])){
                    $_SESSION['error'] = array();
                }
                $_SESSION['error'][] = 'Incorrect password';
            }
            else{
                $_SESSION['error'] = 'Incorrect password';
            }


        }
    }
    else{
        if($return == 'borrow.php' OR $return == 'return.php'){
            if(!isset($_SESSION['error'])){
                $_SESSION['error'] = array();
            }
            $_SESSION['error'][] = 'Fill up required details first';
        }
        else{
            $_SESSION['error'] = 'Fill up required details first';
        }
       
    }


    header('location:'.$return);


?>
