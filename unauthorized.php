<?php
// unauthorized.php — Central unauthorized access handler

require_once __DIR__ . '/templates/header.php'; // Reusable header

// Ensure session is active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<style>
.unauthorized-page {
    margin-top: 100px;
    text-align: center;
}

.unauthorized-page h1 {
    font-size: 3rem;
    color: #dc3545; /* Bootstrap danger */
}

.unauthorized-page p {
    font-size: 1.2rem;
}
</style>

<div class="container unauthorized-page">
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Unauthorized access.
        </div>
    <?php endif; ?>

    <h1 class="display-3">Access Denied</h1>
    <p class="lead">Sorry, you do not have permission to access this page.</p>
    <p>If you believe this is an error, please contact your administrator.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">← Back to Home</a>
</div>

<?php
require_once __DIR__ . '/templates/footer.php'; // Reusable footer
?>
