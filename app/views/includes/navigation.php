<nav class="side-nav">
    <ul>
        <li>
            <a class="nav-link" href="<?php echo URLROOT; ?>/projectweek-2/index">HOME</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo URLROOT; ?>/projectweek-2/events">EVENTS</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo URLROOT; ?>/projectweek-2/about">ABOUT</a>
        </li>
    </ul>
</nav>
<nav class="login-nav">
    <ul>
        <!-- Check if a session is present -->
        <?php
        if (isset($_SESSION['customer_id'])) : ?>
            <li>
                <a href="<?php echo URLROOT; ?>/projectweek-2/logout">UITLOGGEN</a>
            </li>
        <?php else : ?>
            <li>
                <a href="<?php echo URLROOT; ?>/projectweek-2/login">INLOGGEN</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>