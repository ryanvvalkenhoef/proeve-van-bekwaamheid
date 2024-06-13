<div class="container navbar">
<?php if (isset($_SESSION['admin_id'])) : ?>
    <nav class="admin-side-nav">
        <ul>
            <li>
                <a class="nav-link" href="<?php echo URLROOT; ?>/editor/catalog">CATALOGUS</a>
            </li>
            <li>
                <a class="nav-link" href="<?php echo URLROOT; ?>/admin/reservations">RESERVERINGEN</a>
            </li>
            <li>
                <a class="nav-link" href="<?php echo URLROOT; ?>/admin/users">GEBRUIKERS</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
<?php // Check if a admin or editor session is present
if (isset($_SESSION['admin_id']) || isset($_SESSION['editor_id'])) : ?>
    <nav class="logout-nav">
        <ul>
            <li>
                <a class="nav-link" href="<?php echo URLROOT; ?><?php if (session_status() === PHP_SESSION_ACTIVE) echo (isset($_SESSION['admin_id'])) ? '/admin' : '/editor'; ?>/logout">UITLOGGEN</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
</div>