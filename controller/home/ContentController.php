<?php 

if(isset($_GET['content'])) {
	switch($_GET['content']) {
		case 'blog':
			include($root.'controller/blog/BlogController.php');
			break;
		case 'articles':
			include($root.'controller/articles/ArticleController.php');
			break;
		case 'portfolio':
			include($root.'controller/portfolio/PortfolioController.php');
			break;
		case 'about':
			include($root.'controller/about/AboutController.php');
			break;
		case 'categories':
			include($root.'controller/categories/CategoryController.php');
			break;
		case 'users':
			include($root.'controller/users/UserController.php');
			break;
		default:
			include($root.'view/home/ViewHome.php');
	}	// end switch
} else {
	include($root.'view/home/ViewHome.php');
}	// end if-else

?>