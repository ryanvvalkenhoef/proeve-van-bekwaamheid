<?php
require_once APPROOT . '/helpers/session_helper.php';
// If there's no editor session, redirect to login page
if (noEditorSession()) {
    header("Location: " . URLROOT . "/editor/login.php");
    exit();
}
?>
<?php require APPROOT . '/views/includes/header_admin.php'; ?>
<div class="container-register">
    <div class="wrapper-register">
        <h2>Keuzemodule toevoegen</h2>
        <form name="insert-module" id="insert-module" action="" enctype="multipart/form-data" method="POST">
            <!-- START FORM -->
            <div class="form-group">
                <label for="title">Titel</label>
                <input name="title" id="title" type="text" class="form-control" placeholder="Titel *" required />
            </div>
            <div class="form-group">
                <label for="creation_date">Aanmaakdatum</label>
                <input name="creation_date" id="creation_date" type="text" class="form-control" placeholder="Aanmaakdatum *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class="form-group">
                <label for="imageUpload">Upload een afbeelding</label><br />
                <input type="file" name="image" id="imageUpload" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="category">Categorie</label>
                <input name="category" id="category" type="text" class="form-control" placeholder="Categorie *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class="form-group">
                <label for="txt_content">Tekstinhoud</label>
                <textarea id="txt_content" name="txt_content" class="form-control" placeholder="Tekstinhoud *" value="" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required></textarea>
            </div>
            <div class="form-group">
                <label for="registration_places">Registratieplekken</label>
                <input name="registration_places" id="registration_places" type="number" class="form-control" placeholder="Categorie *" style="cursor:text; background-color:#fff;" onfocus="this.removeAttribute('readonly');" readonly required />
            </div>
            <div class="form-group">
                <button name="submit" id="submit" class="btn btn-primary btn-block">Keuzemodule toevoegen</button>
            </div>
        </form>
    </div>
</div>