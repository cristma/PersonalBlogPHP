<?php
include_once($root.'model/CategoryModel.php');
include_once($root.'model/ProjectModel.php');
$categoryModel = new CategoryModel($sqlManager);
$projectModel = new ProjectModel($sqlManager);
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
</div>
<div class="container">
	<div class="row">
		<?php 
			if(isset($_GET['project'])) { 
				$project = $projectModel->GetProject($_GET['project']);
		?>
		<div class="col-md-12">
			<div class="panel">
            			<div class="row content-header">
            				<div class="col-md-12"><?php echo stripslashes($project['name']); ?></div>
            			</div>
                		<div class="row content-body">
                			<div class="col-md-12"><?php echo stripslashes($project['description']); ?></div>
                		</div>
                		<div class="row content-footer">
                			<div class="col-md-12">
						<?php foreach($categoryModel->GetProjectCategories($project['id']) as $category) { ?>
						<a class="badge" href="<?php echo $url; ?>index.php?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
						<?php } ?>
					</div>
				</div>
            		</div>
            	</div>
		<?php } else { ?>
		<div class="col-md-8">
			<?php foreach($projectModel->GetProjects() as $project) { ?>
			<div class="panel">
				<div class="row content-header">
            				<div class="col-md-12"><a href="<?php echo $url; ?>?content=portfolio&action=view&project=<?php echo $project['id']; ?>"><?php echo stripslashes($project['name']); ?></a></div>
            			</div>
            			<div class="row content-body">
            				<div class="col-md-12"><?php echo stripslashes($project['excerpt']); ?></div>
            			</div>
            			<div class="row content-footer">
            				<div class="col-md-12">
						<?php foreach($categoryModel->GetProjectCategories($project['id']) as $category) { ?>
						<a class="badge" href="<?php echo $url; ?>index.php?content=categories&action=view&category=<?php echo $category['id']; ?>"><?php echo stripslashes($category['name']); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php 	} ?>
               	</div>
               	<div class="col-md-4">
               		<div class="panel">
               			<div class="row archive-header">
               				<div class="col-md-8">Categories with Projects</div>
               				<div class="col-md-4"><span class="pull-right">Count</span></div>
               			</div>
               			<?php	foreach($categoryModel->GetCategoriesWithProjects() as $category) { ?>
               			<div class="row archive-cell">
               				<div class="col-md-8" onclick="document.location='<?php echo $url; ?>?content=categories&action=view&category=<?php echo $category['id']; ?>'"><?php echo stripslashes($category['name']); ?></a></div>
               				<div class="col-md-4"><span class="pull-right"><?php echo $category['projects']; ?></span></div>
               			</div>
               			<?php	} ?>
               		</div>
               	</div>
		<?php	} ?>
	</div>
</div>