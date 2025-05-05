<?php include 'includes/session.php'; ?>
<?php
  $catid = 0;
  $where = '';
  if(isset($_GET['category'])){
    $catid = $_GET['category'];
    $where = 'WHERE books.category_id = '.$catid;
  }


?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-brown sidebar-mini">
<div class="wrapper">


  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Book List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Books</li>
        <li class="active">Book List</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
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
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> ADD NEW book</a>
              <div class="box-tools pull-right">
                <form class="form-inline">
                  <div class="form-group">
                    <label>Category: </label>
                    <select class="form-control input-sm" id="select_category">
                      <option value="0">ALL</option>
                      <?php
                        $sql = "SELECT * FROM category";
                        $query = $conn->query($sql);
                        while($catrow = $query->fetch_assoc()){
                          $selected = ($catid == $catrow['id']) ? " selected" : "";
                          echo "
                            <option value='".$catrow['id']."' ".$selected.">".$catrow['name']."</option>
                          ";
                        }
                      ?>
                    </select>
                  </div>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                <th>Category</th>
                  <th>Title</th>
                  <th>photo</th>
                  <th>Details</th>
                  <!--<th>ISBN</th>
                  <th>Author</th>
                  <th>Publisher</th>-->
                  <th>Status</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT *, books.id AS bookid FROM books LEFT JOIN category ON category.id=books.category_id $where"; //Joins books and category tables; Uses $where — likely defined earlier for filtering (e.g. by category)


                    $query = $conn->query($sql); //Loops through all the results from the database; Each $row contains one book’s info


                    while($row = $query->fetch_assoc()){
                      if($row['status']){
                        $status = '<span class="label label-danger">borrowed</span>';
                      }
                      else{
                        $status = '<span class="label label-success">available</span>';
                      }
//If status is true (non-zero), book is borrowed; this label is shown inside the Status column with a red or green badge
                        echo "
                        <tr>
                          <td>".$row['name']."</td>
                         
                          <td>".$row['title']."</td>
                          <td>
                          <img src='../images/".$row['photo']."' width='30px' height='30px'>


                          <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['bookid']."'><span class='fa fa-edit'></span></a>
                        </td>


                          <td>
                            <button class='btn btn-info btn-sm view btn-flat' data-id='".$row['bookid']."'><i class='fa fa-eye'></i> View</button>
                            </td>
                          <td>".$status."</td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['bookid']."'><i class='fa fa-edit'></i> Edit</button>


                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['bookid']."'><i class='fa fa-trash'></i> Delete</button>
                          </td>
                        </tr>
                        ";
//Each book is printed as a new <tr> (table row); data-id='".$row['bookid']."': sets the book’s ID on each button, so JS (like getRow(id)) can later use it for AJAX
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
   
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/book_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $('#select_category').change(function(){
    var value = $(this).val();
    if(value == 0){
      window.location = 'book.php';
    }
    else{
      window.location = 'book.php?category='+value;
    }
  });


  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });


  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });
});


function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'book_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.bookid').val(response.bookid);
      $('#edit_isbn').val(response.isbn);
      $('#edit_photo').val(response.photo);
      $('#edit_title').val(response.title);
      $('#edit_overview').val(response.overview);
      $('#catselect').val(response.category_id).html(response.name);
      $('#edit_author').val(response.author);
      $('#edit_publisher').val(response.publisher);
      $('#datepicker_edit').val(response.publish_date);
      $('#del_book').html(response.title);
    }
  });
}
</script>
</body>


<div id="bookDetailBox" class="floating-box">
  <div class="floating-box-content">
    <span class="close-btn">&times;</span>
    <h4>📖 Book Details</h4>
    <p><strong>cover photo:</strong> <span id="view_photo"></span></p>
    <p><strong>Title:</strong> <span id="view_title"></span></p>
    <p><strong>Author:</strong> <span id="view_author"></span></p>
    <p><strong>ISBN:</strong> <span id="view_isbn"></span></p>
    <p><strong>Category:</strong> <span id="view_category"></span></p>
    <p><strong>Publisher:</strong> <span id="view_publisher"></span></p>
    <p><strong>Publish Date:</strong> <span id="view_date"></span></p>
    <p><strong>Overview:</strong> <span id="view_overview"></span></p>
  </div>
</div>


<style>
.floating-box {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  width: 400px;
  transform: translate(-50%, -50%);
  background-color: #fff;
  border: 2px solid #007BFF;
  border-radius: 10px;
  z-index: 1050;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  padding: 20px;
  font-family: sans-serif;
}


.floating-box-content {
  position: relative;
}


.floating-box .close-btn {
  position: absolute;
  top: 5px;
  right: 15px;
  font-size: 20px;
  font-weight: bold;
  cursor: pointer;
  color: #aaa;
}


.floating-box .close-btn:hover {
  color: red;
}
</style>


<script>
    $(document).on('click', '.view', function(e){
  e.preventDefault();
  var id = $(this).data('id');
  getRow(id);
});


// Close floating box when clicking close
$(document).on('click', '.close-btn', function(){
  $('#bookDetailBox').fadeOut();
});


function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'book_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#view_photo').text(response.photo);
      $('#view_title').text(response.title);
      $('#view_author').text(response.author);
      $('#view_isbn').text(response.isbn);
      $('#view_category').text(response.name);
      $('#view_publisher').text(response.publisher);
      $('#view_date').text(response.publish_date);
      $('#view_overview').text(response.overview);
     
      // Show floating box
      $('#bookDetailBox').fadeIn();
    }
  });
}


</script>


</html>
