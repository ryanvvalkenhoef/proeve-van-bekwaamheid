<?php require APPROOT . '/views/includes/header_main.php'; ?>
<main>
        <section class="overview">
            <h1>Keuzemodules</h1>
            <p>Hier een overzicht van alle keuzemodules die deze periode worden aangeboden</p>
            <div class="module-grid">
            <?php
            foreach ($data['modules'] as $module) {
                $imageData = base64_encode($module->image);
                $creationDate = explode('-', $module->creation_date);
                echo '<a href="/keuzemodule/' . $creationDate[0] . '/' . $creationDate[1] . '/' . $module->title . '">';
                echo '<div class="module" style="background-image: url(\'data:image/png;base64,' . $imageData . '\');">';
                echo '<div class="grc-label"><h4>' . $module->category . '</h4></div>';
                echo '<h3>' . $module->title . '</h3>';
                echo '</div>';
                echo '</a>';
            }
            ?>
        </div>
        </section>
    </main>
</body>
<?php require APPROOT . '/views/includes/footer.php'; ?>