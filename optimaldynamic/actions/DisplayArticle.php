<?php

if(isset($_GET['article']))
{
	include_once("/home/immuta5/www/optimaldynamic/classes/ArticleManager.php");
	$l_articleManager = new ArticleManager;
	$l_article = $l_articleManager->GetArticle($_GET['article']);
}	// end if

?>
<div class="row">
	<div span="12">
		<p class="article-header">
			<span><?php echo $l_article['name']; ?></span><br>
			<span>Written by <?php echo $l_article['author']; ?> on <?php echo $l_article['date']; ?></span><br>
			<span>Sections: <- To Do</span><br>
			<span>Category: <?php echo $l_article['category']; ?></span>
		</p>
		<p class="article-content"><?php echo $l_article['content']; ?></p>
	</div>
</div>
<div class="row">
	<div span="12" style="text-align:center;">[ Write Comment ]</div>
	<div span="3">$name wrote:</div>
	<div span="9">$comment</div>
</div>