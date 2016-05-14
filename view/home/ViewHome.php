<div class="container">
	<div class="row carousel minimize">
		<div class="col-md-12">&nbsp;</div>
	</div>
</div>
<div class="container">
<div class="row horizontal-divided-top">
    	<!-- Blog Section -->
        <?php
			include_once($root.'model/BlogModel.php');
			$blogModel = new BlogModel($sqlManager);
			$latest = $blogModel->GetLastPost();
		?>
    	<div class="col-md-6 vertical-divided-right">
        	<?php if($latest) { ?>
        	<div class="panel">
            	<div class="row content-header">
            		<div class="col-md-7"><?php echo stripslashes($latest['title']); ?></div>
                    <div class="col-md-5"><span class="pull-right"><?php echo date("M d, Y", strtotime($latest['date'])); ?></span></div>
            	</div>
                <div class="row content-body">
                	<div class="col-md-12">
                    	<p class="author"><em>Posted by <?php echo stripslashes($latest['author']); ?>.</em></p>
						<?php echo stripslashes($latest['content']); ?>
                    </div>
                </div>
                <div class="row content-footer">
                	<div class="col-md-12">
                    	<?php
							include_once($root.'model/CategoryModel.php');
							$categoryModel = new CategoryModel($sqlManager);
							$categories = $categoryModel->GetPostCategories($latest['id']);
							
							foreach($categories as $category) {
						?>
                        <a class="badge" href="<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-6 vertical-divided-left">
        	<div class="panel">
        		<?php
					@include_once($root.'model/ArticleModel.php');
					$articleModel = new ArticleModel($sqlManager);
				
					$article = $articleModel->GetLastArticle();
					
					if($article) {
				?>
        		<div class="row content-header">
                	<div class="col-md-7"><?php echo stripslashes($article['title']); ?></div>
                    <div class="col-md-5"><span class="pull-right"><?php echo date("M d, Y", strtotime($article['date'])); ?></span></div>
                </div>
                <div class="row content-body">
                	<div class="col-md-12">
                    	<p class="author">Posted by <?php echo stripslashes($article['author']); ?>.</p>
                        <?php echo stripslashes($article['excerpt']); ?>
                        <p><a href="<?php echo $url; ?>?content=articles&action=view&article=<?php echo $article['id']; ?>">View Article</a></p>
                    </div>
                </div>
                <?php
					@include_once($root.'model/CategoryModel.php');
					$categoryModel = new CategoryModel($sqlManager);
					$categories = $categoryModel->GetArticleCategories($article['id']);
				?>
                <div class="row content-footer">
                	<div class="col-md-12">
                    	<?php foreach($categories as $category) { ?>
                        <a class="badge" href="<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
                        <?php } ?>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row">No articles to display...</div>
                <?php } ?>
            </div>
            <div class="panel">
            	<?php
					@include_once($root.'model/ProjectModel.php');
					$projectModel = new ProjectModel($sqlManager);
					$project = $projectModel->GetLastProject();
					
					if($project) {
				?>
            	<div class="row content-header">
                	<div class="col-md-12"><?php echo stripslashes($project['name']); ?></div>
                </div>
                <div class="row content-body">
                	<div class="col-md-12">
                		<?php echo stripslashes($project['excerpt']); ?>
                		<p><a href="<?php echo $url; ?>?content=portfolio&action=view&project=<?php echo $project['id']; ?>">[ View Project ]</a></p>
                	</div>
                </div>
                <div class="row content-footer">
                	<div class="col-md-12">
                		<?php 
                			@include_once($root.'model/CategoryModel.php');
                			$categoryModel = new CategoryModel($sqlManager);
                		
                			foreach($categoryModel->GetProjectCategories($project['id']) as $category) {
                		?>
                		<a class="badge" href="<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
                		<?php	} ?>
                	</div>
                </div>
                <?php
					} else {
                ?>
                <div class="row">No projects to display...</div>
                <?php } ?>
            </div>
        </div>
  </div>
</div>