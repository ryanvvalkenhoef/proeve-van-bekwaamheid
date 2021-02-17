<?php require APPROOT . '/views/includes/head.php'; ?>
<div class="navbar">
	<?php require APPROOT . '/views/includes/navigation.php'; ?>
</div>
<div class="container-login">
	<div class="wrapper-login">
		<h2>Beheerderspaneel</h2>
		<hr />
		<form name="signin" id="signin" action="<?php echo URLROOT; ?>/admins/login" enctype="multipart/form-data" method="POST">
			<span class="login-feedback">
				<?php if (isset($data['loginFeedback'])) echo $data['loginFeedback']; ?>
			</span>
            <span class="invalid-feedback">
                <?php
                    // Check for error's and display if present
                    if (isset($data['errors'])) {
                        foreach ($data['errors'] as $error) echo '- ' . $error . '<br />';
                    } 
                ?>
            </span>
			<div class="form-group">
				<label for="email">E-mailadres</label>
				<input name="email" id="email" type="text" class="form-control" placeholder="E-mailadres *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
			</div>
			<div class='form-group'>
				<label for='password'>Wachtwoord</label>
				<input name="password" id="password" type="password" class='form-control' placeholder="Wachtwoord *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
			<div class='form-group'>
				<button name="submit" id="submit" class="btn-signin">Inloggen</button>
			</div>
		</form>
	</div>
</div>