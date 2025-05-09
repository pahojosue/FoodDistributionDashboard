

<?php 
require_once('scripts/ManagerProfile.php');
$managerProfile= new ManagerProfile($conn);

$msg = '';
$errMsg = '';
$id = null;

if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

/* create manager profile  */
if(isset($_POST['create'])) {
 
  $firstName        = $_POST['firstName'];
  $lastName         = $_POST['lastName'];
  $region           = $_POST['region'];
  $emailAddress     = $_POST['emailAddress'];
  $mobileNumber     = $_POST['mobileNumber'];
  $password         = $_POST['password'];

  $create = $managerProfile->create($firstName, $lastName, $region, $emailAddress, $mobileNumber, $password);

  if($create['success']) {
    $msg = "manager Profile is created successfully";
  }

  if($create['uploadProfileImage']) {
    $profileImageErr = $create['uploadProfileImage'];
    
  }

  if($create['errMsg']) {
   
    $firstNameErr = $create['errMsg']['firstName'];
    $lastNameErr = $create['errMsg']['lastName'];
    $regionErr = $create['errMsg']['region'];
    $emailAddressErr = $create['errMsg']['emailAddress'];
    $mobileNumberErr = $create['errMsg']['mobileNumber'];
    $passErr = $create['errMsg']['password'];
  }

}



/* update mamager profile  */
if(isset($_POST['update'])) {
 
    $firstName        = $_POST['firstName'];
    $lastName         = $_POST['lastName'];
    $region           = $_POST['region'];
    $emailAddress     = $_POST['emailAddress'];
    $mobileNumber     = $_POST['mobileNumber'];
   
  
    $update = $managerProfile->updateById($id, $firstName, $lastName, $region, $emailAddress, $mobileNumber);
  
    if($update['success']) {
      $msg = "Manager Profile is updated successfully";
    }
  
    if($update['uploadProfileImage']) {
      $profileImageErr = $update['uploadProfileImage'];
      
    }
    
  
    if($update['errMsg']) {
     
      $firstNameErr = $update['errMsg']['firstName'];
      $lastNameErr = $update['errMsg']['lastName'];
      $regionErr = $update['errMsg']['region'];
      $emailAddressErr = $update['errMsg']['emailAddress'];
      $mobileNumberErr = $update['errMsg']['mobileNumber'];
      $passwordErr = $update['errMsg']['password'];
    }
  
  }
  /* edit manager profile */
if($id) {
  $getManagerProfile = $managerProfile->getById($id);
   
}
?>
<div class="row">
    <div class="col-sm-6">
     <h3 class="mb-4">Manager Profile Form</h3>
     <?php echo $msg; ?>
    </div>
    <div class="col-sm-6 text-end">
        <a href="dashboard.php?page=manager-profile-list" class="btn btn-success">Manager list</a>
    </div>
</div>


<form method="post" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
      <label>First Name</label>
      <input type="text" class="form-control" name="firstName" value="<?= $getManagerProfile['firstName'] ?? ''; ?>">
       <p class="text-danger"><?= $firstNameErr ?? ''; ?></p>
       
       <label>Last Name</label>
      <input type="text" class="form-control" name="lastName" value="<?= $getManagerProfile['lastName'] ?? ''; ?>">
       <p class="text-danger"><?= $lastNameErr ?? ''; ?></p>
       
       <label>Region</label>
       <select id="region" name="region" required>
        <option value="">Select Region</option>
        <option value="Adamawa">Adamawa</option>
        <option value="Centre">Centre</option>
        <option value="East">East</option> 
        <option value="Far North">Far North</option>
        <option value="Littoral">Littoral</option>
        <option value="North">North</option>
        <option value="South">South</option>
        <option value="South-West">South-West</option>
        <option value="West">West</option>
        <option value="North-West">North-West</option>
      </select>
       <p class="text-danger"><?= $regionErr ?? ''; ?></p>
       
       <label>Email Address</label>
      <input type="text" class="form-control" name="emailAddress" value="<?= $getManagerProfile['emailAddress'] ?? ''; ?>">
       <p class="text-danger"><?= $emailAddressErr ?? ''; ?></p>

       <label>Mobile Number</label>
      <input type="text" class="form-control" name="mobileNumber" value="<?= $getManagerProfile['mobileNumber'] ?? ''; ?>">
       <p class="text-danger"><?= $mobileNumberErr ?? ''; ?></p>
       
       <?php
       if(!$id) {
       ?>
       <label>Password</label>
       <input type="password" class="form-control" name="password">
       <p class="text-danger"><?= $passwordErr ?? ''; ?></p>
       <?php } ?>

       <label >Profile Image</label>
        <input type="file" class="form-control" name="profileImage" >
        <?php 
         if(isset($getManagerProfile['profileImage'])){

        ?>
            <img src="public/images/admin-profile/<?=$getManagerProfile['profileImage']; ?>" width="100px">
        <?php
        }
        ?>
        
        <p class="text-danger"><?=  $profileImageErr ?? ''; ?></p>

    </div>

    <button type="submit" class="btn btn-success" name="<?= $id ? 'update' : 'create'; ?>">Save</button>
  </form>