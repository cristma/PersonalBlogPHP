<?php

include_once($root.'model/BlogModel.php');
include_once($root.'model/CategoryModel.php');
$blogModel = new BlogModel($sqlManager);
$categoryModel = new CategoryModel($sqlManager);

?>
<div class="container">
	<div class="row carousel minimize">
		<div class="col-md-12">
			/&nbsp;<a href="<?php echo $url; ?>">Home</a>&nbsp;
			<?php	
				if(isset($_GET['content'])) { 
					echo '/&nbsp;<a href="' . $url . '?content=' . $_GET['content'] . '">' . $_GET['content'] . '</a>&nbsp;';	
					if(isset($_GET['action'])) {
						echo '/&nbsp;<a href="' . $url . '?content=' . $_GET['content'] . '&action=' . $_GET['action'] . '">' . $_GET['action'] . ' ' . $_GET['content'] . '</a>&nbsp;';
					}
				}
			?>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<?php
				if(isset($_GET['entry'])) {
					$entry = $blogModel->GetPost($_GET['entry']);	
			?>
    			<div class="panel">
            			<div class="row content-header">
					<div class="col-md-7"><?php echo stripslashes($entry['title']); ?></div>
                    			<div class="col-md-5"><span class="pull-right"><?php echo date("M d, Y", strtotime($entry['date'])); ?></span></div>
                		</div>
                		<div class="row content-body">
					<div class="col-md-12"><?php echo stripslashes($entry['content']); ?></div>
                		</div>
                		<?php
                			@include_once($root.'model/CategoryModel.php');
                			$categoryModel = new CategoryModel($sqlManager);
                			$categories = $categoryModel->GetPostCategories($_GET['entry']);
                		?>
                		<div class="row content-footer">
                			<div class="col-md-12">
                				<?php	foreach($categories as $category) { ?>
                				<a class="badge" href="<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
                				<?php	} ?>
                			</div>
                		</div>
			</div>
		<?php
			} else {
				$posts = $blogModel->GetAllPosts();
		?>
	        	<div class="panel">
            			<div class="row archive-header">
                			<div class="col-md-6">Title</div>
                    			<div class="col-md-3">Date</div>
                    			<div class="col-md-3">Author</div>
                		</div>
            			<?php
					foreach($posts as $post) {
				?>
            			<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=blog&action=view&entry=<?php echo $post['id']; ?>'">
                			<div class="col-md-6"><?php echo stripslashes($post['title']); ?></div>
                    			<div class="col-md-3"><?php echo date("M d, Y", strtotime($post['date'])); ?></div>
                    			<div class="col-md-3"><?php echo stripslashes($post['author']); ?></div>
                		</div>
            			<?php
					}	// end foreach
				?>
			</div>
			<?php
				}	// end else
			?>
		</div>
		<div class="col-md-4">
			<div class="panel">
				<div class="row archive-header">Categories with Posts</div>
				<?php
					@include_once($root.'model/CategoryModel.php');
					$categoryModel = new CategoryModel($sqlManager);
					$categories = $categoryModel->GetCategoriesWithPosts();
					
					foreach($categories as $category) {
				?>
				<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>'">
					<div class="col-md-12"><?php echo stripslashes($category['name']); ?></div>
				</div>
				<?php	} ?>
			</div>
		</div>
	</div>
</div>