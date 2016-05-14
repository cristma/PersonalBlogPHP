<?php
include_once("/home/immuta5/www/optimaldynamic/classes/SectionManager.php");

$l_sectionManager = new SectionManager();
$l_sections = $l_sectionManager->GetSections();

$l_span = 0;
$l_start_span = 0;

if(count($l_sections) > 0)
{
	$l_span = floor(12 / count($l_sections));
	$l_start_span = 10 - count($l_sections);
}

echo '<div class="row">';

// Start with an empty span to push the navigation to a right justification
if($l_start_span > 0)
{
	echo '<div class="span' . $l_start_span . '">&nbsp;</div>';
}	// end if

// Begin placing the navigational elements
foreach($l_sections as $l_section)
{
	echo '<div class="span1">';
	echo '<a href="http://www.optimaldynamic.com/index.php?section=' . $l_section['id'] . '">';
	echo '<img src="' .$l_section['image'] . '" alt="' . $l_section['name'] . '"><br>';
	echo '<span>' . $l_section['name'] . '</span></a>';
	echo '</div>';
}	// end foreach

echo '</div>';

?>