<div class="navbar">
    <?php require APPROOT . '/views/includes/navigation.php'; ?>
</div>
<div class="container-register">
    <div class="wrapper-register">
        <h2>Registreren</h2>
        <hr />
        <form name="signup" id="signup" action="<?php echo URLROOT; ?>/projectweek-2/login" enctype="multipart/form-data" method="POST">
            <span class="register-feedback">
				<?php if (isset($data['registerFeedback'])) echo $data['registerFeedback']; ?>
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
                <label for="email">E-mailadres</label>
                <input name="email" id="email" type="text" class="form-control" placeholder="E-mailadres *" required />
            </div>
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input name="username" id="username" type="text" class="form-control" placeholder="Gebruikersnaam *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class='form-group'>
                <label for='password'>Wachtwoord</label>
                <input name="password" id="password" type="password" class='form-control' placeholder="Wachtwoord *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class='form-group'>
				<label for='confirm-password'>Wachtwoord bevestigen</label>
				<input name="confirm-password" id="confirm-password" type="password" class='form-control' placeholder="Wachtwoord bevestigen *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class='form-group'>
                <button name="submit" id="submit" class="btn-signup">Registreren</button>
            </div>
            <p class="options">Heb je al een account? <a href="<?php echo URLROOT; ?>/projectweek-2/login">Nu inloggen</a></p>
        </form>
    </div>
</div>