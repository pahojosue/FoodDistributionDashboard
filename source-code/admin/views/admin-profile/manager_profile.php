<?php
require_once('scripts/Manager_Profile.php');
$Manager_Profile= new Manager_Profile($conn);
$Manager_profileData = $Manager_Profile->getManager_Profile();

?>
<div class="row">
    <div class="col-sm-3">

    <div class="profile-picture">
        <img src="public/images/admin-profile/<?= $Manager_profileData['profileImage']; ?>" width="200px">
     </div>
    
    </div>
    <div class="col-sm-9">
        <br>
    <h3><?php echo $Manager_profileData['firstName'] . " ". $Manager_profileData['lastName'];  ?> </h3>
    <p><?= $Manager_profileData['region']; ?></p>
    <p><?= $Manager_profileData['emailAddress']; ?></p>
    <p><?= $Manager_profileData['mobileNumber']; ?></p>

    </div>
</div>

