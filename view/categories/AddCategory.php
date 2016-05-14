<?php
if(isset($user)) {
	// If you are logged in, you should be able to see the selectable item box.
?>
<div class="form-group">
	<label for="f_categories">Categories</label>
	<select class="form-control" multiple="multiple" name="f_categories[]" id="f_categories">
<?php
	include_once($root.'model/CategoryModel.php');
	$categoryModel = new CategoryModel($sqlManager);
						
	foreach($categoryModel->GetCategories() as $category) {
?>
		<option value="<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></option>
<?php } ?>
	</select>
</div>
<?php
	if($userModel->HasAccess($user['id'], "Create Category")) {
?>
<a data-toggle="modal" href="#manageCategoryModal" class="btn btn-primary">Add Categories</a>
<div class="modal fade" id="manageCategoryModal" tabindex="-1" role="dialog" aria-labelledby="manageCategoryLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Categories</h4>
            </div>
            <div class="modal-body">
            	<div class="form-group">
                  	<label for="f_categoryname">Name</label>
                    <input type="text" class="form-control" id="f_categoryname">
                </div>
                <div class="form-group">
                  	<label for="f_categorydescription">Description</label>
                    <textarea class="form-control" rows="5" id="f_categorydescription"></textarea>
                </div>
            </div>
           	<div class="modal-footer">
            	<button type="button" class="btn btn-primary" data-loading-text="Saving..." id="f_savecategorybutton">Save Category</button>
            </div>
        </div>
   	</div>
</div>

<script>
	$("#f_savecategorybutton").click(function() {
		$.post("<?php echo $url; ?>ajax.php?content=categories&action=save", {
			f_categoryname:$("#f_categoryname").val(), 
			f_categorydescription:tinymce.get('f_categorydescription').getContent()
		}, 
        
		function(data, status) {			
			if(status=="success") {
				var response = eval("(" + data + ")");
				
				if(response.status=="success") {
					$("#manageCategoryModal").modal('hide');
					$("#f_categories").append('<option value="' + response.category.id + '">' + response.category.name + '</option>');
					$("#f_categoryname").val('');
					tinymce.get('f_categorydescription').setContent('');
				} else {
					alert(response.message);
				}	// end if-else
			} else {
				// Script communication error.
				alert("No response from the server...");
			}	// end if-else
		});
	});
</script>
<?php
	}
} else {
	// No access to this resource.
	echo "You must be logged in to access this resource.";
}

?>