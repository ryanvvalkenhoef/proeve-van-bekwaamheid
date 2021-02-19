<div class="navbar">
	<?php require APPROOT . '/views/includes/navigation.php'; ?>
</div>
<div class="container-login">
	<div class="wrapper-login">
		<h2>Inloggen</h2>
		<hr />
		<form name="signin" id="signin" action="<?php echo URLROOT; ?>/projectweek-2/login" enctype="multipart/form-data" method="POST">
			<span class="login-feedback">
				<?php if (isset($data['loginFeedback'])) echo $data['loginFeedback']; ?>
			</span>
            <span class="invalid-feedback">
                <?php
                    // Check for error's and display if present
                    if (isset($data['errors'])) {
                        if (count($data['errors']) > 1) {
							foreach ($data['errors'] as $error) echo '- ' . $error . '<br />';
						} else {
							echo $data['errors'][0] . '<br />';
						}
                    } 
                ?>
            </span>
			<div class="form-group">
				<label for="username_email">Login</label>
				<input name="username_email" id="username_email" type="text" class="form-control" placeholder="Gebruikersnaam of e-mail *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
			</div>
			<div class='form-group'>
				<label for='password'>Wachtwoord</label>
				<input name="password" id="password" type="password" class='form-control' placeholder="Wachtwoord *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
			<div class='form-group'>
				<button name="submit" id="submit" class="btn-signin">Inloggen</button>
			</div>
            <p class="options">Ben je nog niet aangemeld? <a href="<?php echo URLROOT; ?>/projectweek-2/register">Maak een account</a></p>
		</form>
	</div>
</div>