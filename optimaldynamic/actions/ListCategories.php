<?php
include_once(dirname(dirname(__FILE__)) . "/classes/CategoryManager.php");

$l_categoryManager = new CategoryManager;
$l_categories = $l_categoryManager->GetCategories();

if(count($l_categories) > 0)
{
	foreach($l_categories as $l_category)
	{
		echo '<div class="row">';
		echo '	<div class="span4">' . $l_category['name'] . '</div>';
		echo '</div>';
	}	// end foreach
}	// end if
else
{
	echo "No categories to display.";
}	// end else

?>