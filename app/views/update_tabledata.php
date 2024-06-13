<?php
require_once APPROOT . '/helpers/session_helper.php';
if (session_status() == PHP_SESSION_NONE) {
    header("Location: " . URLROOT . "/admin/login");
    exit();
}

$crudModel = $this->model('CRUD');

$table = ($_POST['slug'] != 'catalog') ? $_POST['slug'] : 'elective_modules';

if (isset($_POST['search_req']) && $_POST['search_req'] != '') {
    // Use crudModel to fetch data from the database based on the search request
    $data = $crudModel->read($table, null, $_POST['search_req']);
} else {
    $data = $crudModel->read($table);
}

if ($table == 'elective_modules') {
    // Extract only textual data to avoid JSON encoding issues
    $filteredData = [];
    foreach ($data as $row) {
        // Assuming 'image' is a key in your database result that contains image data
        // Exclude 'image' key from $row to avoid JSON encoding issues
        unset($row->image);
        $filteredData[] = $row;
    }
    $data = $filteredData; // Assign filtered data back to $data
}

// Set proper header
header('Content-Type: application/json');

// Return the results as JSON
echo json_encode($data);