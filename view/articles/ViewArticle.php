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
   			if(isset($_GET['article'])) {
   				@include_once($root.'model/ArticleModel.php');
   				@include_once($root.'model/CategoryModel.php');
				$articleModel = new ArticleModel($sqlManager);
				$categoryModel = new CategoryModel($sqlManager);
				$article = $articleModel->GetArticle($_GET['article']);
   		?>
    		<div class="col-md-12">
			<div class="row content-header">
				<div class="col-md-12"><?php echo stripslashes($article['title']); ?></div>
			</div>
			<div class="row content-body">
				<div class="col-md-12"><?php echo stripslashes($article['content']); ?></div>
			</div>
			<div class="row content-footer">
				<?php foreach($categoryModel->GetArticleCategories($article['id']) as $category) { ?>
				<a class="badge" href="<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
				<?php } ?>
			</div>
		</div>
            	<?php
			} else {
				@include_once($root.'model/ArticleModel.php');
				$articleModel = new ArticleModel($sqlManager);
				
				$articles = $articleModel->GetArticles();
				
		?>
		<div class="col-md-8">
		<?php	foreach($articles as $article) { ?>
			<div class="panel">
				<div class="row content-header">
					<div class="col-md-12"><?php echo stripslashes($article['title']); ?></div>
				</div>
				<div class="row content-body">
					<div class="col-md-12">
						<p class="author">Posted by <?php echo stripslashes($article['author']); ?>.</p>
						<?php echo stripslashes($article['excerpt']); ?>
						<p><a href="<?php echo $url; ?>?content=articles&action=view&article=<?php echo $article['id']; ?>">[ View Article ]</a></p>
					</div>
				</div>
				<div class="row content-footer">
					<?php 
						@include_once($root.'model/CategoryModel.php');
						$categoryModel = new CategoryModel($sqlManager);
					
						foreach($categoryModel->GetArticleCategories($article['id']) as $category) {
					?>
					<a class="badge" href="<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
					<?php	} ?>
				</div>
			</div>
        		<?php }	?>
        	</div>
        	<div class="col-md-4">
        		<div class="panel">
        			<div class="row archive-header">
        				<div class="col-md-8">Categories with Articles</div>
        				<div class="col-md-4">Count</div>
        			</div>
        			<?php foreach($categoryModel->GetCategoriesWithArticles() as $category) { ?>
        			<div class="row archive-cell" onclick="document.location='<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>'">
        				<div class="col-md-8"><?php echo stripslashes($category['name']); ?></div>
        				<div class="col-md-4"><span class="pull-right"><?php echo $category['articles']; ?></span></div>
        			</div>
        			<?php }	?>
        		</div>
        	</div>
        	<?php } ?>
	</div>
</div>