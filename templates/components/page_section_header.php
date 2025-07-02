<?php
if (!function_exists('renderSectionHeader')) {
  function renderSectionHeader($options = []) {
    $icon = $options['icon'] ?? 'bi-info-circle';
    $title = $options['title'] ?? 'Section Title';
    $badge = $options['badge'] ?? '';
    $subtitle = $options['subtitle'] ?? '';
    $logoUrl = BASE_URL . 'core/uploads/logo/logo.png'; // Ensure BASE_URL is defined

    ?>
    <div class="section-header-glass py-4 px-3 rounded-top-4 mb-3 shadow-sm">
      <div class="d-flex align-items-center">
        
        <!-- Logo card on the left -->
        <div class="me-4">
          <div class="card shadow rounded-4 p-2" style="width: 100px; height: 100px; background-color: #c2aa8d; overflow: hidden;">
            <img src="<?= $logoUrl ?>" alt="Logo" class="img-fluid h-100 w-100 object-fit-cover rounded-3">
          </div>
        </div>

        <!-- Header content on the right -->
        <div class="flex-grow-1">
          <?php if ($badge): ?>
            <span class="badge px-4 py-2 fs-6 fw-semibold rounded-pill mb-2 shadow-sm d-inline-block"
                  style="background: linear-gradient(135deg, #082f2e 0%, #c2aa8d 100%);
                         color: #fff;
                         letter-spacing: 0.5px;
                         text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
              <i class="bi bi-patch-check-fill me-1"></i><?= htmlspecialchars($badge) ?>
            </span>
          <?php endif; ?>

          <h3 class="fw-bold gradient-text mb-1">
            <i class="bi <?= htmlspecialchars($icon) ?> me-2"></i><?= htmlspecialchars($title) ?>
          </h3>

          <?php if ($subtitle): ?>
            <p class="text-light small fst-italic mb-0"><?= htmlspecialchars($subtitle) ?></p>
          <?php endif; ?>
        </div>

      </div>
    </div>
    <?php
  }
}
?>
