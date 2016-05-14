<div class="container">
	<div class="row">
<?php

if(isset($user)) {
	if($userModel->HasAccess($user['id'], "Create Project")) {
?>
    	<div class="col-md-12">
        	<h4>Create Project</h4>
        	<form action="<?php echo $url; ?>?content=portfolio&action=save" method="POST" role="form">
            	<div class="form-group">
                	<label for="f_projectname">Name</label>
                    <input type="text" name="f_projectname" id="f_projectname" class="form-control">
                </div>
                <?php include($root."view/categories/AddCategory.php"); ?>
                <div class="form-group">
                	<label for="f_projectexcerpt">Excerpt</label>
                    <textarea class="form-control" rows="16" id="f_projectexcerpt" name="f_projectexcerpt"></textarea>
                </div>
                <div class="form-group">
                    <label for="f_projectdescription">Description</label>
                    <textarea class="form-control" rows="64" id="f_projectdescription" name="f_projectdescription"></textarea>
                </div>
                <div class="form-group">
                	<label for="f_projectpublished" class="sr-only">Published</label>
                    <select class="form-control" name="f_projectpublished">
                    	<option value="1">Published</option>
                        <option value="2">Unpublished</option>
                    </select>
                </div>
                <button class="btn btn-lg btn-primary" type="submit">Save Project</button>
                <button class="btn btn-lg btn-danger" type="reset">Reset Form</button>
            </form>
        </div>
<?php
	} else {
		// Invalid access level
	}	// end if-else
} else {
	// Not logged in.
}	// end if-else

?>
	</div>
</div>