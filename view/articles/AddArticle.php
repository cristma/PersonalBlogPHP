<div class="container">
	<div class="row">
		<?php
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Create Article") || $userModel->HasAccess($user['id'], "Modify Article")) {
					$article = false;
					
					if(isset($_GET['article'])) {
						@include_once($root.'model/ArticleModel.php');
						$articleModel = new ArticleModel($sqlManager);
						$article = $articleModel->GetArticle($_GET['article'], true);
					}
		?>
        	<div class="col-md-12">
        		<div class="row content-header">
        			<div class="col-md-12"><?php if(isset($_GET['article'])) { ?>Edit<?php } else { ?>Create<?php } ?> Article</div>
        		</div>
        		<form action="<?php echo $url; ?>?content=articles&action=save" method="POST" role="form">
        			<?php	if($article) { ?>
        			<input type="hidden" name="f_articleid" value="<?php echo $article['id']; ?>" />
        			<?php	} ?>
            			<div class="row content-body">
            				<div class="col-md-12">
            					<label for="f_articletitle">Title</label><br />
	                    			<input type="text" class="textfield" id="f_articletitle" name="f_articletitle" />
	                    		</div>
                    		</div>
                		<div class="row content-body">
                			<div class="col-md-12">
                				<?php
                					if($article) {
                					
                					}
                				?>
                				<?php include($root.'view/categories/AddCategory.php'); ?>
                			</div>
                		</div>
				<div class="row content-body">
                			<div class="col-md-12">
                				<label for="f_articleexcerpt">Excerpt</label><br/>
						<textarea class="textfield" rows="16" id="f_articleexcerpt" name="f_articleexcerpt"></textarea>
					</div>
				</div>
				<div class="row content-body">
					<div class="col-md-12">
                				<label for="f_articlecontent">Content</label><br/>
                    				<textarea class="textfield" rows="64" id="f_articlecontent" name="f_articlecontent"></textarea>
                    			</div>
                    		</div>
                    		<div class="row content-body">
                    			<div class="col-md-12">
                    				<label for="f_articlepublished" class="sr-only">Published Status</label><br/>
                    				<select name="f_articlepublished" id="f_articlepublished" class="textfield">
                    					<option value="1">Published</option>
                        				<option value="0">Unpublished</option>
                    				</select>
                			</div>
                		</div>
                		<div class="row content-footer">
                			<div class="col-md-12">
                				<button class="button" type="reset">Reset Form</button>
	            				<button class="button" type="submit">Save Article</button>
	            			</div>
	            		</div>
	            	</form>
		</div>
<?php
	} else {
		// Not enough sec level
	}
} else {
	// Need to be logged in
}

?>
	</div>
</div>