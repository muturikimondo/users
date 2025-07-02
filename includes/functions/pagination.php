<?php

function renderPagination($currentPage, $totalPages, $baseUrl)
{
  if ($totalPages <= 1) return;

  echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

  // Previous button
  $prevDisabled = $currentPage <= 1 ? ' disabled' : '';
  $prevPage = max(1, $currentPage - 1);
  echo '<li class="page-item' . $prevDisabled . '">';
  echo '<a class="page-link" href="' . $baseUrl . 'page=' . $prevPage . '" aria-label="Previous">';
  echo '<span aria-hidden="true">&laquo;</span>';
  echo '</a></li>';

  // Pages
  for ($i = 1; $i <= $totalPages; $i++) {
    $active = $i === $currentPage ? ' active' : '';
    echo '<li class="page-item' . $active . '">';
    echo '<a class="page-link" href="' . $baseUrl . 'page=' . $i . '">' . $i . '</a>';
    echo '</li>';
  }

  // Next button
  $nextDisabled = $currentPage >= $totalPages ? ' disabled' : '';
  $nextPage = min($totalPages, $currentPage + 1);
  echo '<li class="page-item' . $nextDisabled . '">';
  echo '<a class="page-link" href="' . $baseUrl . 'page=' . $nextPage . '" aria-label="Next">';
  echo '<span aria-hidden="true">&raquo;</span>';
  echo '</a></li>';

  echo '</ul></nav>';
}
