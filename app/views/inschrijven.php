<?php require APPROOT . '/views/includes/header_main.php'; ?>
<?php if ($data['cannot_enroll']) : ?>
<h1>Deze keuzemodule is volgeboekt, u kunt zich hiervoor niet meer inschrijven.</h1>
<?php else : ?>
    <main>
        <section class="form-section">
            <h1>Inschrijven voor keuzemodule <?php echo $data['module_title']; ?></h1>
            <form action="" enctype="multipart/form-data" method="POST">
                <label for="name">Volledige naam</label>
                <input type="text" id="name" name="name" required aria-required="true">

                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" required aria-required="true">

                <label for="phone">Telefoonnummer</label>
                <input type="tel" id="phone" name="phone" required aria-required="true">

                <label for="comments">Aanvullende opmerkingen</label>
                <textarea id="comments" name="comments"></textarea>
                <input type="hidden" name="module_id" value="<?php echo $_GET['module_id']; ?>">
                <button type="submit" class="cta-button">Inschrijven</button>
            </form>
        </section>
    </main>
<?php endif; ?>
</body>
<?php require APPROOT . '/views/includes/footer.php'; ?>