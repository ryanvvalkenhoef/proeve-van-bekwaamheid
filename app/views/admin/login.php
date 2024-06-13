<div class="navbar">
	<?php require APPROOT . '/views/includes/header_admin.php'; ?>
</div>
<div class="container-login">
	<div class="wrapper-login">
		<h2 class="heading-panel">Beheerderspaneel</h2>
		<form name="login" id="login" action="" enctype="multipart/form-data" method="POST">
			<!-- VALIDATION HANDLING -->
			<span class="span-login-feedback">
				<?php if (isset($data['loginFeedback'])) echo $data['loginFeedback']; ?>
			</span>
			<br />
            <span class="span-invalid-feedback">
                <?php
                    // Check for error's and display if present
                    if (isset($data['errors']) && !empty($data['errors'])) {
						echo '<br />';
                        foreach ($data['errors'] as $error) echo ((count($data['errors'] ) > 1) ? '- ' : '') . $error . '<br />';
                    }
                ?>
            </span>
			<!-- START FORM -->
			<div class="input-wrapper">
				<div class="form-group">
					<label for="username_email">E-mailadres of gebruikersnaam</label>
					<input name="username_email" id="username_email" type="text" class="form-control" placeholder="E-mail of gebruikersnaam *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
				</div>
				<div class='form-group'>
					<label for='password'>Wachtwoord</label>
					<input name="password" id="password" type="password" class='form-control' placeholder="Wachtwoord *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
				</div>
			</div>
			<div class='form-group form-group-button'>
				<button name="submit" id="submit" class="btn-login">Inloggen</button>
			</div>
		</form>
	</div>
</div>