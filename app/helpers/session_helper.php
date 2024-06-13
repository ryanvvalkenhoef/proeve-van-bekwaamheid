<?php
    function noAdminSession() {
        // Return boolean based on whether a admin session exists or not
        return (!isset($_SESSION['admin_id']));
    }

    function noEditorSession() {
        // Return boolean based on whether a editor session exists or not
        return (!isset($_SESSION['editor_id']));
    }