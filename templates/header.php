<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../includes/config.php';
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <!-- Meta Information -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="theme-color" content="#00632d" />

  <!-- Dynamic Title -->
  <title><?= $site_title ?? 'Records Portal' ?><?= isset($page_title) ? " | $page_title" : '' ?></title>

  <!-- Favicon -->
  <link rel="icon" href="<?= asset('core/favicon/favicon.ico') ?>" type="image/x-icon" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet" />

  <!-- Select2 CSS (Searchable Select Boxes) -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <!-- Custom Styles -->
  <link href="<?= asset('styles/styles.css') ?>" rel="stylesheet" />

  <!-- SEO Defaults -->
  <meta name="robots" content="noindex, nofollow" />
</head>

<body>
  <!-- jQuery (already required by Bootstrap and Select2) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Global Base URL for JS -->
  <script>
    const BASE_URL = "<?= BASE_URL ?>";
  </script>
</body>
</html>
