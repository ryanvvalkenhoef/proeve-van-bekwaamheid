<?php
require_once APPROOT . '/helpers/session_helper.php';
if (noAdminSession()) {
    header("Location: " . URLROOT . "/admin/login");
    exit();
}
?>
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
    </div>
</div>
<!-- POPUP BAR -->
<div class="container" id="popup-bar" style="<?php echo (isset($_GET['del_feedback'])) ? 'display:block;' : 'display:none;'; ?>">
    <div class="vertical-center">
        <p id="popup-msg"><?php
                            if (isset($_GET['del_feedback'])) echo ($_GET['del_feedback'] == 'success') ? 'De reservering is succesvol verwijderd.' : 'Er is iets misgegaan bij het verwijderen van de reservering.';
                            ?></p>
    </div>
</div>
<!-- USER TABLE -->
<div class='container'>
    <div class='row'>
        <div class='col-lg-12 col-lg-offset-2'>
            <div class='col-lg-12 col-lg-offset-2'>
                <h3>Reserveringen</h3>
                <div class='table-responsive'>
                    <table class="table table-user-data table-striped">
                        <thead>
                            <tr>
                                <th>Keuzemodule</th>
                                <th>Gereserveerd voor</th>
                                <th>Gereserveerd op</th>
                                <th>Reserveringsbewijs</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start_index = (max(1, $data['page']) - 1) * $data['num_results_on_page'];
                            $reservations = array_slice($data['modules'], $start_index, $data['num_results_on_page']);
                            foreach ($reservations as $reservation) {
                                echo '<tr>';
                                echo '<td>' . $reservation->module_name . '</td>';
                                echo '<td>' . $reservation->reserved_for. '</td>';
                                echo '<td>' . $reservation->reserved_at . '</td>';
                                echo '<td>' . $reservation->receipt . '</td>';
                                echo '<td>';
                                echo '<a href="reservations?del=' . $reservation->id . '" class="btn btn-sm btn-danger">Verwijder</a>';
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
                    <li class="prev"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 1 ?>">Vorig</a></li>
                <?php endif; ?>
    
                <?php if ($data['page'] > 3) : ?>
                    <li class="start"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=1">1</a></li>
                    <li class="dots">...</li>
                <?php endif; ?>
    
                <?php if ($data['page'] - 2 > 0) : ?><li class="page"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 2 ?>"><?php echo $data['page'] - 2 ?></a></li><?php endif; ?>
                <?php if ($data['page'] - 1 > 0) : ?><li class="page"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] - 1 ?>"><?php echo $data['page'] - 1 ?></a></li><?php endif; ?>
    
                <li class="currentpage"><a href="reservations?page=<?php echo $page ?>"><?php echo $page ?></a></li>
    
                <?php if ($data['page'] + 1 < ceil($data['total_pages'] / $data['num_results_on_page']) + 1) : ?><li class="page"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 1 ?>"><?php echo $data['page'] + 1 ?></a></li><?php endif; ?>
                <?php if ($data['page'] + 2 < ceil($data['total_pages'] / $data['num_results_on_page']) + 1) : ?><li class="page"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 2 ?>"><?php echo $data['page'] + 2 ?></a></li><?php endif; ?>
    
                <?php if ($data['page'] < ceil($data['total_pages'] / $data['num_results_on_page']) - 2) : ?>
                    <li class="dots">...</li>
                    <li class="end"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo ceil($data['total_pages'] / $data['num_results_on_page']) ?>"><?php echo ceil($data['total_pages'] / $data['num_results_on_page']) ?></a></li>
                <?php endif; ?>
    
                <?php if ($data['page'] < ceil($data['total_pages'] / $data['num_results_on_page'])) : ?>
                    <li class="next"><a href="reservations?<?php echo (isset($_GET['search_req'])) ? 'search_req=' . $_GET['search_req'] . '&' : ''; ?>page=<?php echo $data['page'] + 1 ?>">Volgende</a></li>
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
        const reservations = data.slice(startIndex, window.pageResultsNum);
        console.log(pageResultsNum);
        // Add updated data to the table
        for (const id in reservations) {
            if (reservations.hasOwnProperty(id)) {
                let reservation = reservations[id];
                let row = '<tr>';
                row += '<td>' + reservation.module_title + '</td>';
                row += '<td>' + reservation.reserved_for + '</td>';
                row += '<td>' + reservation.reserved_at + '</td>';
                row += '<td>' + reservation.receipt + '</td>';
                row += '<td>';
                row += '<a href="reservations?del=' + reservation.id + '" class="btn btn-sm btn-danger">Verwijder</a>';
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