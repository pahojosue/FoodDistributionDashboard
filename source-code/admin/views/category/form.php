

<?php 
require_once('scripts/CategoryManager.php');
$categoryManager= new CategoryManager($conn);

$msg = '';
$errMsg = '';
$id = null;

if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

/* create category  */
if(isset($_POST['create'])) {
  
  $food_type = $_POST['food_type'];
  $quantity = $_POST['quantity'];
  $create = $categoryManager->create($food_type, $quantity);

  if($create['success']) {
    $msg = "Category is saved successfully";
  }

  if($create['errMsg']) {
    $errMsg = $create['errMsg'];
  }
  
}

/* update category */
if(isset($_POST['update'])) {
  
  $id = $_GET['id'];
  $food_type = $_POST['food_type'];
  $quantity = $_POST['quantity'];

  $update = $categoryManager->updateById($id, $food_type, $quantity);

  if($update['success']) {
    $msg = "Category is updated successfully";
  }

  if($update['errMsg']) {
    $errMsg = $update['errMsg'];
  }
}

/* edit category */
if($id) {

  $getCategory = $categoryManager->getById($id);
  $getQuantity = $categoryManager->getQuantityById($id);

}
?>
<div class="row">
    <div class="col-sm-6">
     <h3 class="mb-4">Category Form</h3>
     <?php echo $msg; ?>
    </div>
    <div class="col-sm-6 text-end">
        <a href="dashboard.php?page=category-list" class="btn btn-success">Category List</a>
    </div>
</div>


<form method="post"   >
    <div class="mb-3 mt-3">
      <label for="email">Name</label>
      <input type="text" class="form-control" name="food_type" value="<?= $getCategory['food_type'] ?? ''; ?>">
       <p class="text-danger"><?php echo $errMsg; ?></p>
    </div>

    <div class="mb-3 mt-3">
      <label for="quantity">Quantity</label>
      <input type="number" class="form-control" name="quantity" value="<?= $getQuantity['quantity'] ?? ''; ?>">
       <p class="text-danger"><?php echo $errMsg; ?></p>
    </div>

    <button type="submit" class="btn btn-success" name="<?= $id ? 'update' : 'create'; ?>">Save</button>
  </form>