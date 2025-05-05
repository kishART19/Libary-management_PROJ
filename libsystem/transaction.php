<?php include 'includes/session.php'; ?>
<?php
    if(!isset($_SESSION['student']) || trim($_SESSION['student']) == ''){
        header('Location: index.php');
        exit;


    }


    $stuid = $student['id']; //Gets the current student's ID
    $sql = "SELECT * FROM borrow LEFT JOIN books ON books.id=borrow.book_id WHERE student_id = '$stuid' ORDER BY date_borrow DESC"; //Selects all borrowed books for this student. It joins the borrow table with the books table so you get book details. Orders the result by latest borrow date first.
    $action = '';
    if(isset($_GET['action'])){
        $sql = "SELECT * FROM returns LEFT JOIN books ON books.id=returns.book_id WHERE student_id = '$stuid' ORDER BY date_return DESC";
        $action = $_GET['action'];
    }


?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-brown layout-top-nav">
<div class="wrapper">


    <?php include 'includes/navbar.php'; ?>
     
      <div class="content-wrapper">
        <div class="container">


          <!-- Return content -->
          <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-warning"></i> Error!</h4>
                <ul>
                <?php
                  foreach($_SESSION['error'] as $error){
                    echo "
                      <li>".$error."</li>
                    "; /**Loops through all error messages (it's assumed $_SESSION['error'] is an array).


                    Displays each error inside a <li> (list item), which appears in a bullet-point list. */
                  }
                ?>
                </ul>
            </div>
          <?php
          unset($_SESSION['error']); //Deletes the error messages from the session after displaying them, so they don’t keep showing up after a page reload.




        }


        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
          <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Return Book</a>
            </div> <!--connected to the return_modal-->
       
    <!--TRANSACTION TABLE-->
          <section class="content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">TRANSACTIONS</h3>
    <!--This dropdown lets users choose between Borrow and Return modes.


PHP checks the $action variable to preserve the user's selection when the page reloads.-->
                            <div class="pull-right">
                                <select class="form-control input-sm" id="transelect">
                                    <option value="borrow" <?php echo ($action == '') ? 'selected' : ''; ?>>Borrow</option>
                                    <option value="return" <?php echo ($action == 'return') ? 'selected' : ''; ?>>Return</option>
                                </select>
<!--The selected logic uses the $action variable to remember which view the user is on:
If no action in the URL → it shows "Borrow".
If ?action=return is in the URL → it shows "Return".-->
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead>
                                    <th class="hidden"></th>
                                    <th>Date</th>
                                    <th>ISBN</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>PDF</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = $conn->query($sql);
                                    while($row = $query->fetch_assoc()){
                                        $date = (isset($_GET['action'])) ? 'date_return' : 'date_borrow';


                                        // Check if the book has a PDF
                                        $pdfLink = 'No PDF';
                                        if ($action == '' && !empty($row['pdf_file'])) {
                                            $pdfLink = "<a href='view_pdf.php?book_id=".$row['book_id']."' class='btn btn-sm btn-info' target='_blank'>Read Book</a>";
                                        }




                                        echo "
                                        <tr>
                                            <td class='hidden'></td>
                                            <td>".date('M d, Y', strtotime($row[$date]))."</td>
                                            <td>".$row['isbn']."</td>
                                            <td>".$row['title']."</td>
                                            <td>".$row['author']."</td>
                                            <td>".$pdfLink."</td>
                                        </tr>
                                        ";
                                    }
                                    ?>
                                    </tbody>


                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </section>
         
        </div>
      </div>
 
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/return_modal.php'; ?>
</div>


<?php include 'includes/scripts.php'; ?>
<script>
    $(function(){
        $(document).on('click', '#append', function(e){
            e.preventDefault();
            $('#append-div').append(
            '<div class="form-group"><label for="" class="col-sm-3 control-label">ISBN</label><div class="col-sm-9"><input type="text" class="form-control" name="isbn[]"></div></div>'
            );
        });
        });
    // to show borrow and return list
    $('#transelect').on('change', function(){
        var action = $(this).val();
        if(action == 'borrow'){
            window.location = 'transaction.php';
        }
        else{
            window.location = 'transaction.php?action='+action;
        }
    });
</script>
</body>
</html>
