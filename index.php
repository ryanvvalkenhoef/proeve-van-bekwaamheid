<?php
  require_once 'includes/autoloader.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<meta name='description' content='Basic loginsystem'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<meta http-equiv='x-ua-compatible' content='ie=edge'>
	<link href='resources/css/bootstrap.min.css' rel='stylesheet'>
	<link href='resources/ccs/custom.css' rel='stylesheet'>
	<title>Basic Login System</title>
</head>
<body>
	<div id="popup-bar">
        <div class="vertical-center">
            <p id="popup-msg"></p>
        </div>
    </div>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-12 col-lg-offset-2'>
				<div class='col-lg-12 col-lg-offset-2'>
					<h3>User Data</h3>
					<hr>
					<div class='table-responsive'>
						<?php
							$authObj = new Auth(); // Initiate Auth class
							// If authorized, generate the DOM elements associated 
							// with the data, otherwhise direct to login page
							echo $authObj->checkAuthorized();
						?>
					</div>
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='col-lg-8 col-lg-offset-2'>
				<div class='col-lg-6 col-lg-offset-3'>
					<h3>Signup</h3>
					<hr/>
					<form name='signup' id='signup' action='action_completed.php' enctype="multipart/form-data" method='post'>
						<div class='form-group'>
							<label for='firstname'>Firstname</label>
							<input name='firstname' id='firstname' type='text' class='form-control' placeholder='firstname' required />
						</div>
						<div class='form-group'>
							<label for='lastname'>Lastname</label>
							<input name='lastname' id='lastname' type='text' class='form-control' placeholder='lastname' required />
						</div>
						<div class='form-group'>
							<label for='email'>E-mail</label>
							<input name='email' id='email' type='text' class='form-control' placeholder='email' required />
						</div>
						<div class='form-group'>
							<label for='username'>Username</label>
							<input name='username' id='username' type='text' class='form-control' placeholder='username' style='cursor:text; background-color:#fff;' onfocus='this.removeAttribute("readonly");' readonly required />
						</div>
						<div class='form-group'>
							<label for='password'>Password</label>
							<input name='password' id='password' type='password' class='form-control' placeholder='password' style='cursor:text; background-color:#fff;' onfocus='this.removeAttribute("readonly");' readonly required />
						</div>
						<div class='form-group'>
							<button name='submit' id='submit' class='btn btn-primary btn-block'>Sign Up</button>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</body>
</html>