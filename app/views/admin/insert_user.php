<?php
require_once APPROOT . '/helpers/session_helper.php';
if (noAdminSession()) {
    header("Location: " . URLROOT . "/admin/login");
    exit();
}
?>
<?php require APPROOT . '/views/includes/header_admin.php'; ?>
<div class="container-register">
    <div class="wrapper-register">
        <h2>Account toevoegen</h2>
        <form name="insert-user" id="insert-user" action="" enctype="multipart/form-data" method="POST">
            <!-- VALIDATION HANDLING -->
            <span class="span-register-feedback">
				<?php if (isset($data['registerFeedback'])) echo $data['registerFeedback']; ?>
			</span>
            <br />
            <span class="span-invalid-feedback">
                <?php
                    // Check for error's and display if present
                    if (isset($data['errors']) && !empty($data['errors'])) {
                        echo '<br />';
                        foreach ($data['errors'] as $error) echo ((count($data['errors']) > 1) ? '- ' : '') . $error . '<br />';
                    }
                ?>
            </span>
            <!-- START FORM -->
            <div class="form-group">
                <label for="role">Rol</label>
                <select name="role" id="role">
                    <option value="" disabled selected>Selecteer een rol</option>
                    <option value="admin">Administrator</option>
                    <option value="editor">Redacteur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Volledige naam</label>
                <input name="name" id="name" type="text" class="form-control" placeholder="Naam *" required />
            </div>
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
				<label for='confirmPassword'>Wachtwoord bevestigen</label>
				<input name="confirmPassword" id="confirmPassword" type="password" class='form-control' placeholder="Wachtwoord bevestigen *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class='form-group'>
                <button name="submit" id="submit" class="btn btn-primary btn-block">Account toevoegen</button>
            </div>
        </form>
    </div>
</div>