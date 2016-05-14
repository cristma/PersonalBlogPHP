<div class="container">
   	<div class="row footer">
       	<div class="col-md-4">
        <?php if(!isset($user)) { ?>
        	<h4>System Login</h4>
          	<form role="form" action="<?php echo $url; ?>index.php" method="POST">
            	<div class="form-group">
                	<label for="f_username" class="sr-only">Username</label>
                    <input type="text" placeholder="Username" id="f_username" name="f_username" class="textfield">
                </div>
                <div class="form-group">
                	<label for="f_password" class="sr-only">Password</label>
                    <input type="password" placeholder="Password" name="f_password" id="f_password" class="textfield">
                </div>
                <input type="hidden" name="action" value="login">
                <button type="submit" class="button">Login</button>
            </form>
		<?php } else { ?>
           	<ul class="nav nav-list">
           		<li class="nav-header">Site Submissions</li>
               	<li><a href="<?php echo $url; ?>index.php?content=blog&action=add">Submit Blog</a></li>
                <li><a href="<?php echo $url; ?>index.php?content=articles&action=add">Submit Article</a></li>
                <li><a href="<?php echo $url; ?>index.php?content=portfolio&action=add">Submit Project</a></li>
               	<li><a href="<?php echo $url; ?>index.php?action=logout">Log Out</a></li>
            </ul>
        <?php } ?>
        </div>
        <div class="col-md-4">
           	<p class="text-info">Copyright &copy; 2013 Immutable Productions.</p>
           	<p><a href="http://www.w3.org/html/logo/"><img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-semantics.png" width="165" height="64" alt="HTML5 Powered with CSS3 / Styling, and Semantics" title="HTML5 Powered with CSS3 / Styling, and Semantics"></a></p>
           	<p>Built with <a href="http://www.jquery.com/" target="_new">JQuery</a> and <a href="http://twitter.github.io/bootstrap/" target="_new">Twitter Bootstrap CSS</a>.</p>
            <p>Icons by <a href="http://www.famfamfam.com">famfamfam.com</a>.</p>
        </div>
        <div class="col-md-2">
           	<ul class="nav nav-list">
           		<li class="nav-header">Social</li>
          		<li><a href="https://twitter.com/immutable_1" target="_new">Twitter</a></li>
           		<li><a href="https://www.facebook.com/mcrist" target="_new">Facebook</a></li>
           		<li><a href="http://immutable.deviantart.com/" target="_new">DeviantArt</a></li>
           		<li><a href="http://www.linkedin.com/profile/view?id=126438833" target="_new">LinkedIn</a></li>
           	</ul>
        </div>
        <div class="col-md-2">
          	<ul class="nav nav-list">
           		<li class="nav-header">Contributions</li>
           		<li><a href="http://www.imagine-code.com" target="_new">Imagine-Code</a></li>
           		<li><a href="http://www.optimaldynamic.com" target="_new">Optimal Dynamic</a></li>
           		<li><a href="http://www.image-fight.com" target="_new">Image-Fight</a></li>
           	</ul>
        </div>
    </div>
</div>