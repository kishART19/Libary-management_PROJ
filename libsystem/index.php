<?php include 'includes/session.php'; ?>
<?php
	$where = '';
	if(isset($_GET['category'])){
		$catid = $_GET['category'];
		$where = 'WHERE category_id = '.$catid;
	}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-brown layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
		
	        	<div class="col-sm-8 col-sm-offset-2">
            <?php
              if (isset($_SESSION['error'])) {
                  echo "<div class='alert alert-danger'>";
                  if (is_array($_SESSION['error'])) {  // Check if it's an array
                      foreach ($_SESSION['error'] as $error) {
                          echo "<p>$error</p>";
                      }
                  } else {
                      // If it's not an array, just display the string
                      echo "<p>".$_SESSION['error']."</p>";
                  }
                  echo "</div>";
                  unset($_SESSION['error']);
              }
            ?>


	        		<div class="box">
					<!--searchbar-->
	        			<div class="box-header with-border">
	        				<div class="input-group">
				                <input type="text" class="form-control input-lg" id="searchBox" placeholder="Search for ISBN, Title or Author">
				                <span class="input-group-btn">
				                    <button type="button" class="btn btn-primary btn-flat btn-lg"><i class="fa fa-search"></i> </button>
				                </span>
				            </div>
	        			</div>
		<!--STUDENT BORROW & RETURN-->
		
    <?php if (isset($_SESSION['student'])): ?>
      <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
        <i class="fa fa-plus"></i> Borrow
      </a>
    <?php else: ?>
      <a href="login.php" class="btn btn-primary btn-sm btn-flat" >
        <i class="fa fa-plus"></i> Borrow
      </a>
    <?php endif; ?>
		<?php include 'includes/borrow_modal.php'; ?>
     

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
                    ";
                  }
                ?>
                </ul>
            </div>
          <?php
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

	        			<div class="box-body">
	        				<div class="input-group col-sm-5">
				                <span class="input-group-addon">Category:</span>
				                <select class="form-control" id="catlist">
				                	<option value=0>ALL</option>
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
<!--table-->
	        				<table class="table table-bordered table-striped" id="booklist">
			        			<thead>
			        				<th>Category</th>
                      <th></th>
			        				<th>Title</th>
			        				<th>Details</th>
                      <th>ISBN</th>
			        				<th>Status</th>
                      
			        			</thead>
			        			<tbody>
			        			<?php
                    $sql = "SELECT *, books.id AS bookid FROM books LEFT JOIN category ON category.id=books.category_id $where"; //Joins books and category tables; Uses $where — likely defined earlier for filtering (e.g. by category)

                    $query = $conn->query($sql); //Loops through all the results from the database; Each $row contains one book’s info

                    while($row = $query->fetch_assoc()){
                      if($row['status']){
                        $status = '<span class="label label-danger">unavailabe</span>';
                      }
                      else{
                        $status = '<span class="label label-success">available</span>';
                      }
                      $photo = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/profile.jpg';
//If status is true (non-zero), book is borrowed; this label is shown inside the Status column with a red or green badge
echo "
                    <tr>
                      <td>".$row['name']."</td>
                      <td><img src='".$photo."' width='80%' height='100%'></td>
                      <td>".$row['title']."</td>       
                      <td>
                        <button class='btn btn-info btn-sm view btn-flat' data-id='".$row['bookid']."'><i class='fa fa-eye'></i> View</button>
                      </td>
                      <td>".$row['isbn']."</td>
                      <td>".$status."</td>

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

</div>

<?php include 'includes/scripts.php'; ?>
<script>
  //powers the +book field. This allows users to borrow multiple books at once by entering multiple ISBNs.
$(function(){
  $(document).on('click', '#append', function(e){
    e.preventDefault();
    $('#append-div').append(
      '<div class="form-group"><label for="" class="col-sm-3 control-label">ISBN</label><div class="col-sm-9"><input type="text" class="form-control" name="isbn[]"></div></div>'
    );
  });
});
//for category
$(function(){
	$('#catlist').on('change', function(){
		if($(this).val() == 0){
			window.location = 'index.php';
		}
		else{
			window.location = 'index.php?category='+$(this).val();
		}
		
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
      $('#edit_photo').attr('src', response.photo);
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
// WORKING SEARCHBAR 
$(document).ready(function() {
  // When user types in the searchBox
  $('#searchBox').on('keyup', function() {
    var value = $(this).val().toLowerCase(); // get the typed text and lowercase it
    $("#booklist tbody tr").filter(function() {
      // each table row
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      // hide rows that don't match, show rows that match
    });
  });
});

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
      $('#view_title').text(response.title);
      $('#view_author').text(response.author);
      $('#view_isbn').text(response.isbn);
      $('#view_category').text(response.name);
      $('#view_publisher').text(response.publisher);
      $('#view_date').text(response.publish_date);
      $('#view_overview').text(response.overview);

      // Set the book photo URL dynamically
      $('#view_photo').attr('src', response.photo);

      // Show the floating box
      $('#bookDetailBox').fadeIn();
    }
  });
}


</script>

</html>