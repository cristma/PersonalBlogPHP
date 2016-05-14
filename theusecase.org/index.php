<!DOCTYPE html>
<html lang="en">
<head>
<title>The Use Case</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="author" content="Matthew Crist" />
<meta name="keywords" content="use, case, programming, solutions" />
<meta name="description" content="The Use Case is a site dedicated to resolving solutions for application development." />
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="css/global.css" type="text/css" />
<script src="scripts/jquery-2.0.3.min.js"></script>
<script src="scripts/custom.js"></script>
</head>
<body>
<div class="container">
	<div class="row top-banner">
		<div class="col-md-3"><img src="img/Logo.png" alt="The Use Case" /></div>
		<div class="col-md-9">
			<form action="index.php?content=search&action=list" method="POST" class="pull-right form-inline" role="form">
				<div class="form-group">
					<label for="f_search" class="sr-only">Search</label>
					<input type="text" maxlength="255" name="f_search" id="f_search" class="form-control" placeholder="Search..." />
				</div>
				<button class="btn btn-default">Search</button>
			</form>
		</div>
	</div>
	<div class="row content">
		<div class="col-md-3">
			<h5>Navigation:</h5>
			<ul class="nav nav-pills nav-stacked">
				<li><a href="#">Category 1</a></li>
				<li><a href="#">Category 2</a></li>
				<li><a href="#">Category 3</a></li>
			</ul>
		</div>
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-12">
					<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
						<li><a href="#">Content</a><li>
						<li class="active">Action</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>