<?php require APPROOT . '/views/includes/header_main.php'; ?>
<main>
        <section class="module-detail">
            <?php
            $module = $data['module'];
            $imageData = base64_encode($module->image);
            echo '<h1>' . $module->title . '</h1>';
            echo '<img src="data:image/png;base64,' . $imageData . '" alt="' . $module->title . '">';
            echo '<div class="metadata-wrapper">';
            echo '<p class="grc-property">Categorie:';
            echo '<span class="grc-value">' . $module->category . '</span>';
            echo '</p>';
            echo '<p class="grc-property">Beschikbare plekken:';
            echo '<span class="grc-value">' . $module->registration_places . '</span>';
            echo '</p>';
            echo '</div>';
            echo '<p class="grc-text-content">' . $module->text_content . '</p>';
            echo '<form action="./inschrijven?module_id="' . $module->id . '>';
            echo '<input type="submit" class="grc-button grc-button-secondary" value="Nu inschrijven" />';
            echo '</form>'
            ?>
        </section>
    </main>
</body>
<?php require APPROOT . '/views/includes/footer.php'; ?>