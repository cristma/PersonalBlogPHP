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
		<?php
			include_once($root.'model/CategoryModel.php');
			include_once($root.'model/ProjectModel.php');
			include_once($root.'model/ArticleModel.php');
			include_once($root.'model/BlogModel.php');

			$categoryModel = new CategoryModel($sqlManager);
			$blogModel     = new BlogModel($sqlManager);
			$articleModel  = new ArticleModel($sqlManager);
			$projectModel  = new ProjectModel($sqlManager);

			if(isset($_GET['category'])) {
				$posts    = $blogModel->GetPostsInCategory($_GET['category'], 50);
				$articles = $articleModel->GetArticlesInCategory($_GET['category'], 50);
				$projects = $projectModel->GetProjects($_GET['category'], 50);
				$category = $categoryModel->GetCategory($_GET['category']);
		?>
		<div class="col-md-8">
			<div class="panel">
				<div class="row content-header">
					<div class="col-md-12"><?php echo stripslashes($category['name']); ?></div>
				</div>
				<div class="row content-body">
					<div class="col-md-12"><?php echo stripslashes($category['description']); ?></div>
				</div>
			</div>
			<div class="panel">
				<div class="row archive-header">
					<div class="col-md-12">Projects</div>
				</div>
				<?php 	foreach($projects as $project) { ?>
				<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=portfolio&action=view&project=<?php echo $project['id']; ?>'">
					<div class="col-md-12"><?php echo stripslashes($project['name']); ?></div>
				</div>
				<?php	} ?>
			</div>
			<div class="panel">
				<div class="row archive-header">
					<div class="col-md-12">Articles</div>
				</div>
				<?php 	foreach($articles as $article) { ?>
				<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=articles&action=view&article=<?php echo $article['id']; ?>'">
					<div class="col-md-12"><?php echo stripslashes($article['title']); ?></div>
				</div>
				<?php } ?>
			</div>
			<div class="panel">
				<div class="row archive-header">
					<div class="col-md-12">Blog Posts</div>
				</div>
				<?php	foreach($posts as $post) { ?>
				<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=blog&action=view&entry=<?php echo $post['id']; ?>'">
					<div class="col-md-8"><?php echo stripslashes($post['title']); ?></div>
					<div class="col-md-4"><?php echo stripslashes($post['author']); ?></div>
				</div>
				<?php	} ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel">
				<div class="row archive-header">
					<div class="col-md-12">Available Categories</div>
				</div>
				<?php	foreach($categoryModel->GetCategories() as $category) { ?>
				<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>'">
					<div class="col-md-12"><?php echo stripslashes($category['name']); ?></div>
				</div>
				<?php 	} ?>
			</div>
		</div>				
		<?php
			} else {
		?>
		<div class="col-md-12">
			<div class="panel">
				<div class="row archive-header">
					<div class="col-md-12">Available Categories</div>
				</div>
				<?php 	foreach($categoryModel->GetCategories() as $category) { ?>
				<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>'">
					<div class="col-md-4"><?php echo stripslashes($category['name']); ?></div>
					<div class="col-md-8"><?php echo stripslashes($category['description']); ?></div>
				</div>
				<?php	} ?>
			</div>
		</div>
		<?php 
			}	// end if-else 
		?>
	</div>
</div>