<div class="container">
	<div class="row">
<?php

// See if userModel is initialized (should be from header)
if(!isset($userModel)) {
	include_once($root.'model/UserModel.php');
	$userModel = new UserModel($sqlManager);
}	// end if

if(isset($user)) {
	if($userModel->HasAccess($user['id'], "Create Post")) {
?>
		<div class="col-md-12">
        	<h4>Create Blog Entry</h4>
        	<form action="<?php echo $url; ?>index.php?content=blog&action=save" method="POST" role="form">
	        	<div class="form-group">
                	<label for="f_blogtitle">Title</label>
                    <input class="form-control" type="text" name="f_blogtitle" id="f_blogtitle" placeholder="Enter blog title">
	            </div>
                <?php include($root."view/categories/AddCategory.php"); ?>
                <div class="form-group">
                	<label for="f_blogcontent">Content</label>
                    <textarea class="form-control" rows="40" name="f_blogcontent" id="f_blogcontent"></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Save Post</button>
                <button class="btn btn-danger" type="reset">Clear</button>
            </form>
        </div>
<?php } else { ?>
		<div class="col-md-12">
        	<div class="panel panel-danger">
            	<div class="panel-heading">Insufficient Access!</div>
                <div class="panel-body">You do not have sufficient privledges to access this resource.  Please contact an administrator regarding access.</div>
            </div>
        </div>
<?php } ?>
<?php } else { ?>
		<div class="col-md-12">
        	<div class="panel panel-danger">
        		<div class="panel-heading">User Error!</div>
                <div class="panel-body">You must log in to view this resource!</div>
                <div class="panel-footer"><a href="<?php echo $url; ?>?content=user&action=login">Log in to service.</a></div>
            </div>
        </div>
<?php } ?>
	</div>
</div>