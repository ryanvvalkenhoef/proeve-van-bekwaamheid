<?php
	require_once 'includes/autoloader.php';

	// Hier komt de code te staan die ervoor zorgt dat er een database-connectie is
	$crudObj = new CRUD();
	
	// Hier komt de code te staan die de gebruiker ophaalt uit de database die geupdate gaat worden
	if (isset($_GET['upd'])) $user = $crudObj->read(true);
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<meta name='description' content='Basic loginsystem'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<meta http-equiv='x-ua-compatible' content='ie=edge'>
	<link href='resources/css/bootstrap.min.css' rel='stylesheet'>
	<link href='css/bootstrap.min.css' rel='stylesheet'>
	<title>Basic Login System</title>
</head>
<body>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-12'>
				<div class='col-lg-4 col-lg-offset-4'>
					<h3>Update Data</h3>
					<hr/>
					<form name='update' id='update' action='action_completed.php?id=<?php echo $user['id'] ?>' enctype="multipart/form-data" method='post'>
						<div class='form-group'>
							<label for='firstname'>Firstname</label>
							<input value='<?php echo $user['firstname'] ?>' name='firstname' id='firstname' type='text' class='form-control' placeholder='firstname' />
						</div>
						<div class='form-group'>
							<label for='lastname'>Lastname</label>
							<input value='<?php echo $user['lastname'] ?>' name='lastname' id='lastname' type='text' class='form-control' placeholder='lastname' />
						</div>
						<div class='form-group'>
							<label for='email'>E-mail</label>
							<input value='<?php echo $user['email'] ?>' name='email' id='email' type='text' class='form-control' placeholder='email' />
						</div>
						<div class='form-group'>
							<label for='username'>Username</label>
							<input value='<?php echo $user['username'] ?>' name='username' id='username' type='text' class='form-control' placeholder='username' />
						</div>
						<div class='form-group'>
							<label for='password'>New Password</label>
							<input name='password' id='password' type='password' class='form-control' placeholder='Enter new password' />
						</div>
						<div class='form-group'>
							<button name='btnupdate' id='update' class='btn btn-primary btn-block'>Update</button>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</body>
</html>