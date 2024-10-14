<?php  if(isset($_SESSION['success']) && !empty($_SESSION['success'])){?>
  <div class="alert alert-success">
  	<?=$_SESSION['success']?>
  </div>
<?php  } $_SESSION['success'] = ''; ?>