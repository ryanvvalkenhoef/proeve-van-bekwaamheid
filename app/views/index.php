<?php require APPROOT . '/views/includes/header_main.php'; ?>
<main>
    <section class="hero">
        <div class="hero-text">
            <h1>Wat ga jij<br /> kiezen?</h1>
            <p>Video-editen, wereldgerechten koken, bijzondere sporten beoefenen, nepnieuws leren herkennen: Rietvelduren (RVU) zijn anders dan anders! Je ontwikkelt je talenten, versterkt de band met school, leert omgaan met anderen en leert je uiten over wat er leeft en speelt in de samenleving waar je deel van uitmaakt.Kijk nu bij de keuzemodules en schrijf je in!</p>
            <a href="keuzemodule-overzicht" class="grc-button">Naar keuzemodules</a>
        </div>
        <div class="hero-image">
            <img src="<?php echo URLROOT . "/public/img/hero-image.png" ?>" alt="Kinderen die plezier hebben">
        </div>
    </section>
    <section class="modules">
        <h2>Laatste keuzemodules</h2>
        <div class="module-grid">
            <?php
            $count = 0;
            foreach ($data['modules'] as $module) {
                if ($count >= 3) break;
                $imageData = base64_encode($module->image);
                $creationDate = explode('-', $module->creation_date);
                echo '<a href="/keuzemodule/' . $creationDate[0] . '/' . $creationDate[1] . '/' . $module->title . '">';
                echo '<div class="module" style="background-image: url(\'data:image/png;base64,' . $imageData . '\');">';
                echo '<div class="grc-label"><h4>' . $module->category . '</h4></div>';
                echo '<h3>' . $module->title . '</h3>';
                echo '</div>';
                echo '</a>';
                $count++;
            }
            ?>
        </div>
        <form action="./keuzemodule-overzicht">
            <input type="submit" class="grc-button grc-button-secondary" value="Alle keuzemodules" />
        </form>
    </section>
</main>
</body>
<?php require APPROOT . '/views/includes/footer.php'; ?>