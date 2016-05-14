<div class="container">
	<div class="row">
    		<div class="col-md-3"><img src="<?php echo $url; ?>img/titlebanner.png" alt="Optimal Dynamic" /></div>
        	<div class="col-md-9" style="padding-top: 40px;">
        		<?php
        			$value = 0;
        			if(isset($_GET['content'])) {
        				switch($_GET['content']) {
        					case 'blog':
        						$value = 1;
        						break;
        					case 'portfolio':
        						$value = 2;
        						break;
        					case 'articles':
        						$value = 3;
        						break;
        					case 'about':
        						$value = 4;
        						break;
        					default:
        						$value = 0;
        				}
        			}        						
        		?>
        		<div class="nav-bar pull-right">
            			<a class="nav-item<?php if($value == 0) { ?> active<?php } ?>" href="<?php echo $url; ?>">Home</a>
        			<a class="nav-item<?php if($value == 1) { ?> active<?php } ?>" href="<?php echo $url; ?>?content=blog">Blog</a>
            			<a class="nav-item<?php if($value == 2) { ?> active<?php } ?>" href="<?php echo $url; ?>?content=portfolio">Portfolio</a>
            			<a class="nav-item<?php if($value == 3) { ?> active<?php } ?>" href="<?php echo $url; ?>?content=articles">Articles</a>
            			<a class="nav-item<?php if($value == 4) { ?> active<?php } ?>" href="<?php echo $url; ?>?content=about">About</a>
            		</div>
       		</div>
    	</div>
</div>