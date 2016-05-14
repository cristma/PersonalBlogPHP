<div class="container">
	<div class="row">
    	<div class="col-md-4">
        	<h4>Create User</h4>
       	  	<form action="<?php echo $url; ?>?content=users&action=save" method="POST" role="form">
            	<div class="form-group">
                	<label for="f_userusername">Username</label>
                    <input type="text" id="f_userusername" name="f_userusername" class="form-control">
                </div>
                <div class="form-group">
                	<label for="f_userpassword">Password</label>
                    <input type="password" id="f_userpassword" name="f_userpassword" class="form-control">
                    <input type="password" id="f_userpassword_repeat" class="form-control" placeholder="Again...">
                </div>
                <div class="form-group">
	           		<label for="f_useremail">Email</label>
                  	<input type="email" id="f_useremail" name="f_useremail" class="form-control">
                </div>
                <div class="form-group">
                	<label for="f_userfname">Display Name</label>
                    <input type="text" id="f_userfname" name="f_userfname" class="form-control" placeholder="First Name">
                    <input type="text" id="f_userlname" name="f_userlname" class="form-control" placeholder="Last Name">
                </div>
                <button class="btn btn-lg btn-primary" type="button">Create Account</button>
                <button class="btn btn-lg btn-danger" type="reset">Reset Form</button>
            </form>
        </div>
        <div class="col-md-8">This is an explaination of services, etc...</div>
    </div>
</div>