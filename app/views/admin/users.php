<?php require APPROOT . '/views/includes/header_admin.php'; ?>
<!-- TOOLBAR CONTENT -->
<div class="container welcome-message">
    <p>Welkom <?php echo $data['adminName']; ?>!</p>
    <div class="toolbar-wrapper">
        <div class="input-group">
            <div class="form-outline" data-mdb-input-init>
                <input type="search" id="form1" class="form-control input-search" placeholder="Zoeken" value="<?php echo (isset($_GET['search_req'])) ? $_GET['search_req'] : ''; ?>" aria-label="Search" aria-describedby="search-addon" />
            </div>
        </div>
        <form action="./insert_user">
            <input type="submit" class="btn btn-primary btn-block" value="Gebruiker toevoegen" />
        </form>
    </div>
</div>
<!-- POPUP BAR -->
<div class="container" id="popup-bar" style="<?php echo (isset($_GET['upd_feedback']) || isset($_GET['del_feedback']) || isset($_GET['ins_feedback'])) ? 'display:block;' : 'display:none;'; ?>">
    <div class="vertical-center">
        <p id="popup-msg"><?php
                            if (isset($_GET['upd_feedback'])) echo ($_GET['upd_feedback'] == 'success') ? 'Het gebruikersaccount is succesvol gewijzigd.' : 'Er is iets misgegaan bij het wijzigen van het gebruikersaccount.';
                            if (isset($_GET['del_feedback'])) echo ($_GET['del_feedback'] == 'success') ? 'Het gebruikersaccount is succesvol verwijderd.' : 'Er is iets misgegaan bij het verwijderen van het gebruikersaccount.';
                            if (isset($_GET['ins_feedback'])) echo ($_GET['ins_feedback'] == 'success') ? 'Het gebruikersaccount is succesvol aangemaakt.' : 'Er is iets misgegaan bij het toevoegen van het gebruikersaccount.';
                            ?></p>
    </div>
</div>
<!-- USER TABLE -->
<div class='container'>
    <div class='row'>
        <div class='col-lg-12 col-lg-offset-2'>
            <div class='col-lg-12 col-lg-offset-2'>
                <h3>Gebruikers</h3>
                <div class='table-responsive'>
                    <table class="table table-user-data table-striped">
                        <thead>
                            <tr>
                                <th>Volledige naam</th>
                                <th>Rol</th>
                                <th>E-mailadres</th>
                                <th>Gebruikersnaam</th>
                                <th>Wachtwoord</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start_index = (max(1, $data['page']) - 1) * $data['num_results_on_page'];
                            $users = array_slice($data['users'], $start_index, $data['num_results_on_page']);
                            foreach ($users as $user) {
                                echo '<tr>';
                                echo '<td>' . $user->name . '</td>';
                                echo '<td>' . (($user->role == 'admin') ? 'Administrator' : 'Redacteur') . '</td>';
                                echo '<td>' . $user->email . '</td>';
                                echo '<td>' . $user->username . '</td>';
                                echo '<td>' . str_repeat("*", strlen($user->password)) . '</td>';
                                echo '<td>';
                                if ($user->name != 'Hoofdaccount') {
                                    echo '<a href="edit_user?upd=' . $user->id . '" class="btn btn-sm btn-warning">Wijzig</a>';
                                    echo '&nbsp;';
                                    echo '<a href="users?del=' . $user->id . '" class="btn btn-sm btn-danger">Verwijder</a>';
                                }
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
                    <li class="prev"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 1 ?>">Vorig</a></li>
                <?php endif; ?>
    
                <?php if ($data['page'] > 3) : ?>
                    <li class="start"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=1">1</a></li>
                    <li class="dots">...</li>
                <?php endif; ?>
    
                <?php if ($data['page'] - 2 > 0) : ?><li class="page"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 2 ?>"><?php echo $data['page'] - 2 ?></a></li><?php endif; ?>
                <?php if ($data['page'] - 1 > 0) : ?><li class="page"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 1 ?>"><?php echo $data['page'] - 1 ?></a></li><?php endif; ?>
    
                <li class="currentpage"><a href="users?page=<?php echo $page ?>"><?php echo $page ?></a></li>
    
                <?php if ($data['page'] + 1 < ceil($data['total_pages'] / $data['num_results_on_page']) + 1) : ?><li class="page"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 1 ?>"><?php echo $data['page'] + 1 ?></a></li><?php endif; ?>
                <?php if ($data['page'] + 2 < ceil($data['total_pages'] / $data['num_results_on_page']) + 1) : ?><li class="page"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 2 ?>"><?php echo $data['page'] + 2 ?></a></li><?php endif; ?>
    
                <?php if ($data['page'] < ceil($data['total_pages'] / $data['num_results_on_page']) - 2) : ?>
                    <li class="dots">...</li>
                    <li class="end"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo ceil($data['total_pages'] / $data['num_results_on_page']) ?>"><?php echo ceil($data['total_pages'] / $data['num_results_on_page']) ?></a></li>
                <?php endif; ?>
    
                <?php if ($data['page'] < ceil($data['total_pages'] / $data['num_results_on_page'])) : ?>
                    <li class="next"><a href="users?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 1 ?>">Volgende</a></li>
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
        const users = data.slice(startIndex, window.pageResultsNum);
        // Add updated data to the table
        for (const userId in users) {
            if (users.hasOwnProperty(userId)) {
                const user = users[userId];
                let row = '<tr>';
                row += '<td>' + user.name + '</td>';
                row += '<td>' + ((user.role == 'admin') ? 'Administrator' : 'Redacteur') + '</td>';
                row += '<td>' + user.email + '</td>';
                row += '<td>' + user.username + '</td>';
                row += '<td>' + "*".repeat(user.password.length) + '</td>';
                row += '<td>';
                if (user.name != 'Hoofdaccount') {
                    row += '<a href="edit_user?upd=' + user.id + '" class="btn btn-sm btn-warning">Wijzig</a>';
                    row += '&nbsp;';
                    row += '<a href="users?del=' + user.id + '" class="btn btn-sm btn-danger">Verwijder</a>';
                }
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