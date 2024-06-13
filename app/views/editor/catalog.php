<?php require APPROOT . '/views/includes/head.php'; ?>
<?php require APPROOT . '/views/includes/header_admin.php'; ?>
<div class="container welcome-message">
    <p>Welkom <?php echo $data['editorName']; ?>!</p>
    <div class="toolbar-wrapper">
        <div class="input-group">
            <div class="form-outline" data-mdb-input-init>
                <input type="search" id="form1" class="form-control input-search" placeholder="Zoeken" value="<?php echo (isset($_GET['search_req'])) ? $_GET['search_req'] : ''; ?>" aria-label="Search" aria-describedby="search-addon" />
            </div>
        </div>
        <form action="./insert_module">
            <input type="submit" class="btn btn-primary btn-block" value="Keuzemodule toevoegen" />
        </form>
    </div>
</div>
<!-- POPUP BAR -->
<div class="container" id="popup-bar" style="<?php echo (isset($_GET['upd_feedback']) || isset($_GET['del_feedback']) || isset($_GET['ins_feedback'])) ? 'display:block;' : 'display:none;'; ?>">
    <div class="vertical-center">
        <p id="popup-msg"><?php
                            if (isset($_GET['upd_feedback'])) echo ($_GET['upd_feedback'] == 'success') ? 'De keuzemodule is succesvol gewijzigd.' : 'Er is iets misgegaan bij het wijzigen van de keuzemodule.';
                            if (isset($_GET['del_feedback'])) echo ($_GET['del_feedback'] == 'success') ? 'De keuzemodule is succesvol verwijderd.' : 'Er is iets misgegaan bij het verwijderen van de keuzemodule.';
                            if (isset($_GET['ins_feedback'])) echo ($_GET['ins_feedback'] == 'success') ? 'De keuzemodule is succesvol aangemaakt.' : 'Er is iets misgegaan bij het toevoegen van de keuzemodule.';
                            ?></p>
    </div>
</div>
<!-- USER TABLE -->
<div class='container'>
    <div class='row'>
        <div class='col-lg-12 col-lg-offset-2'>
            <div class='col-lg-12 col-lg-offset-2'>
                <h3>Catalogus</h3>
                <div class='table-responsive'>
                    <table class="table table-user-data table-striped">
                        <thead>
                            <tr>
                                <th>Moduletitel</th>
                                <th>Auteur</th>
                                <th>Aangemaakt op</th>
                                <th>Categorie</th>
                                <th>Aantal geregistreerden</th>
                                <th>Plekken</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start_index = (max(1, $data['page']) - 1) * $data['num_results_on_page'];
                            $modules = array_slice($data['modules'], $start_index, $data['num_results_on_page']);
                            foreach ($modules as $eModule) {
                                echo '<tr>';
                                echo '<td>' . $eModule->title . '</td>';
                                echo '<td>' . $eModule->author . '</td>';
                                echo '<td>' . $eModule->creation_date . '</td>';
                                echo '<td>' . $eModule->category . '</td>';
                                echo '<td>' . $eModule->amount_registered . '</td>';
                                echo '<td>' . $eModule->registration_places . '</td>';
                                echo '<td>';
                                echo '<a href="edit_module?upd=' . $eModule->id . '" class="btn btn-sm btn-warning">Wijzig</a>';
                                echo '&nbsp;';
                                echo '<a href="catalog?del=' . $eModule->id . '" class="btn btn-sm btn-danger">Verwijder</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGINATION -->
    <div class="container pagination-wrapper">
        <?php if (ceil($data['total_pages'] / $data['num_results_on_page']) > 0) : ?>
            <ul class="pagination">
                <?php if ($data['page'] > 1) : ?>
                    <li class="prev"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 1 ?>">Vorig</a></li>
                <?php endif; ?>
    
                <?php if ($data['page'] > 3) : ?>
                    <li class="start"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=1">1</a></li>
                    <li class="dots">...</li>
                <?php endif; ?>
    
                <?php if ($data['page'] - 2 > 0) : ?><li class="page"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 2 ?>"><?php echo $data['page'] - 2 ?></a></li><?php endif; ?>
                <?php if ($data['page'] - 1 > 0) : ?><li class="page"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 1 ?>"><?php echo $data['page'] - 1 ?></a></li><?php endif; ?>
    
                <li class="currentpage"><a href="users?page=<?php echo $page ?>"><?php echo $page ?></a></li>
    
                <?php if ($data['page'] + 1 < ceil($data['total_pages'] / $data['num_results_on_page']) + 1) : ?><li class="page"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 1 ?>"><?php echo $data['page'] + 1 ?></a></li><?php endif; ?>
                <?php if ($data['page'] + 2 < ceil($data['total_pages'] / $data['num_results_on_page']) + 1) : ?><li class="page"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 2 ?>"><?php echo $data['page'] + 2 ?></a></li><?php endif; ?>
    
                <?php if ($data['page'] < ceil($data['total_pages'] / $data['num_results_on_page']) - 2) : ?>
                    <li class="dots">...</li>
                    <li class="end"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo ceil($data['total_pages'] / $data['num_results_on_page']) ?>"><?php echo ceil($data['total_pages'] / $data['num_results_on_page']) ?></a></li>
                <?php endif; ?>
    
                <?php if ($data['page'] < ceil($data['total_pages'] / $data['num_results_on_page'])) : ?>
                    <li class="next"><a href="catalog?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 1 ?>">Volgende</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
</div>
<script type="text/javascript">
    function max(num1, num2) {
        return num1 > num2 ? num1 : num2;
    }

    function updateTable(data) {
        // Delete current table contents
        let tableBody = document.querySelector('.table-user-data tbody');
        tableBody.innerHTML = '';

        const urlParams = new URLSearchParams(window.location.search);
        window.pageResultsNum = <?php echo PAGE_RESULTS_NUM; ?>;
        startIndex = (max(1, urlParams.get('page')) - 1) * window.pageResultsNum;
        const modules = data.slice(startIndex, window.pageResultsNum);
        // Add updated data to the table
        for (const eModuleId in modules) {
            if (modules.hasOwnProperty(eModuleId)) {
                const eModule = modules[eModuleId];
                let row = '<tr>';
                row += '<td>' + eModule.title + '</td>';
                row += '<td>' + eModule.author + '</td>';
                row += '<td>' + eModule.creation_date + '</td>';
                row += '<td>' + eModule.category + '</td>';
                row += '<td>' + eModule.amount_registered + '</td>';
                row += '<td>' + eModule.registration_places + '</td>';
                row += '<td>';
                row += '<a href="edit_module?upd=' + eModule.id + '" class="btn btn-sm btn-warning">Wijzig</a>';
                row += '&nbsp;';
                row += '<a href="catalog?del=' + eModule.id + '" class="btn btn-sm btn-danger">Verwijder</a>';
                row += '</td>';
                row += '</tr>';
                tableBody.innerHTML += row;
            }
        };
    }
</script>
<script type="text/javascript" src=<?php echo URLROOT . "/public/js/paginationUtils.js"; ?>></script>
<script type="text/javascript" src=<?php echo URLROOT . "/public/js/searchUtils.js"; ?>></script>
</body>