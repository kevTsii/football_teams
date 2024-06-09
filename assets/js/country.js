
document.addEventListener('DOMContentLoaded', function () {
  let rows = document.querySelectorAll('.country-row');
  rows.forEach(function (row) {
    row.addEventListener('click', function () {
      window.location.href = row.dataset.href;
    });
  });

  $('.edit-button').click(function() {
    $('.form-control').attr('disabled', false)
    $('#edit-country-submit').removeClass('d-none')
  })
});