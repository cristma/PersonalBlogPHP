<?php

include_once("/home/immuta5/www/optimaldynamic/classes/ArticleManager.php");

$l_articleManager = new ArticleManager();
$l_articles       = null;

// Determine what recent articles should be acquired.
if(isset($_GET['section']))
{
	echo '<div class="row"><div class="span8"><h2>Recent Articles (by Section)</h2></div></div>';
	$l_articles = $l_articleManager->GetArticlesInSection($_GET['section'], 4);
}	// end if
elseif(isset($_GET['category']))
{
	echo '<div class="row"><div class="span8"><h2>Recent Articles (by Category)</h2></div></div>';
}	// end elseif
else
{
	echo '<div class="row"><div class="span8"><h2>Recent Articles</h2></div></div>';
	// Acquires the recent articles.
	$l_articles = $l_articleManager->GetRecentArticles();
	
	if($l_articles)
	{
		$l_col = 0;
	
		foreach($l_articles as $l_article)
		{
			if($l_col == 0)
				echo '<div class="row">';
				
			echo '<div class="span4">';
			echo '<p class="article-heading">';
			echo '<span class="article-name">' . $l_article['name'] . '</span><br>';
			echo '<span class="article-category">' . $l_article['category'] . '</span><br>';
			echo '<span class="article-author">' . $l_article['author'] . '</span><br>';
			echo '</p>';
			echo '<p class="article-body">';
			echo preg_replace("[<img.*>]", '<a href="http://www.optimaldynamic.com/index.php?article=' . $l_article['id'] . '">[Show Image]</a>',  $l_article['content']) . '...';
			echo '</p>';
			echo '<p class="read-more"><a href="http://www.optimaldynamic.com/index.php?article=' . $l_article['id'] . '">[ Read More ]</a></p>';
			echo '</div>';
			
			if($l_col == 1)
			{
				echo '</div>';
				$l_col = 0;
			}	// end if
			else
			{
				$l_col++;
			}	// end else
		}	// end foreach
		
		if($l_col == 1)
		{
			echo '<div class="span4">&nbsp;</div></div>';
		}
	}	// end if
	else
	{
		echo "No articles to display!";
	}	// end else
}	// end else

?>