<?php
    session_start();

    function isLoggedIn() {
        // Return boolean based on whether a customer-session exists or not
        return (isset($_SESSION['user_id']));
    }