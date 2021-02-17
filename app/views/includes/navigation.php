<nav class="side-nav">
    <ul>
        <li>
            <a href="<?php echo URLROOT; ?>/pages/index">Home</a>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/pages/events">Events</a>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/pages/about">About</a>
        </li>
        <li class="btn-login">
            <!-- Check if a session is present -->
            <?php
            if (isset($_SESSION['customer_id'])) : ?>
                <a href="<?php echo URLROOT; ?>/customers/logout">Uitloggen</a>
            <?php else : ?>
                <a href="<?php echo URLROOT; ?>/customers/login">Inloggen</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>