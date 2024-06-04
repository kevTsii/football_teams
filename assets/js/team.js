document.addEventListener('DOMContentLoaded', function () {
  let rows = document.querySelectorAll('.team-row');
  rows.forEach(function (row) {
    row.addEventListener('click', function () {
      window.location.href = row.dataset.href;
    });
  });
});