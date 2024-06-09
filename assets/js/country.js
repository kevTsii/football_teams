import { toggleEdit } from './common'

document.addEventListener('DOMContentLoaded', function () {
  let rows = document.querySelectorAll('.country-row');
  rows.forEach(function (row) {
    row.addEventListener('click', function () {
      window.location.href = row.dataset.href;
    });
  });

  toggleEdit($('#edit-country-submit'))
});