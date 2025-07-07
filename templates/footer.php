<!-- templates/footer.php -->
<footer id="appFooter" class="footer-glass text-center py-4 mt-auto">
  <div class="container">

    <!-- Top Section: Brand & Rights -->
    <div class="row">
      <div class="col-12">
        <p class="mb-0">
          &copy; <?= date('Y') ?> <strong class="gradient-text"><?= htmlspecialchars($site_title) ?></strong>. All rights reserved.
        </p>
        <small class="text-light opacity-75">Powered by <span class="fw-bold">Laikipia County Treasury ICT</span></small>
      </div>
    </div>

    <!-- Social Icons -->
    <div class="row justify-content-center my-3">
      <div class="col-auto">
        <a href="https://facebook.com" target="_blank" class="social-icon me-3" title="Facebook">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://twitter.com" target="_blank" class="social-icon me-3" title="Twitter">
          <i class="bi bi-twitter"></i>
        </a>
        <a href="https://instagram.com" target="_blank" class="social-icon me-3" title="Instagram">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" class="social-icon" title="LinkedIn">
          <i class="bi bi-linkedin"></i>
        </a>
      </div>
    </div>

    <!-- Legal & Contact -->
    <div class="row">
      <div class="col-12">
        <small class="text-light opacity-75">
          <a href="/privacy-policy" class="text-light text-decoration-none">Privacy Policy</a> | 
          <a href="/terms" class="text-light text-decoration-none">Terms & Conditions</a>
        </small>
      </div>
    </div>
  </div>
</footer>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

<!-- Global BASE_URL JS Definition -->


<!-- Page-Specific JS Modules (to be injected from page) -->
<?php if (isset($pageScripts) && is_array($pageScripts)) : ?>
  <?php foreach ($pageScripts as $script) : ?>
    <script type="module" src="<?= asset($script) ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>
