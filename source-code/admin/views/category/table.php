

<?php 

  require_once('scripts/CategoryManager.php');

  $categoryManager= new CategoryManager($conn);
  $category = $categoryManager->get();

 
?>
<div class="row">
    <div class="col-sm-6">
     <h3 class="mb-4">Food Categories</h3>
    </div>
    <div class="col-sm-6 text-end">
        <a href="dashboard.php?page=category-form" class="btn btn-success">Add New</a>
    </div>
</div>

<div class="table-responsive-sm">
<table class="table table-hover">
    <thead>
      <tr>
      <th>Id</th>
      <th>Category Name</th>
      <th>Quantity</th>
      <th colspan="2" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($category)) {
           
        $sn = 1;
       foreach($category as $data){
        ?>
      <tr>
      <td><?= $sn; ?></td>
        <td><?= $data['food_type']; ?></td>
        <td><?= $data['quantity']; ?></td>
        <td class="text-center">
            <a href="dashboard.php?page=category-form&id=<?= $data['id']; ?>" class="text-success">
                <i class="fa fa-edit"></i>
            </a>
        </td>
        <td  class="text-center">
            <a href="javascript:void(0)" onclick="confirmDeleteCategory(<?=$data['id']; ?>)" class="text-danger">
              <i class="fa fa-trash-o"></i>
            </a>
        </td>
       
      </tr>
       <?php 
        $sn++; }
        } else {
       ?>
     <tr>
        <td colspan="3">No categories Found</td>
       
      </tr>
       <?php } ?>
      
      
    </tbody>
  </table>
</div>

<script src="public/js/ajax/delete-category.js"></script>