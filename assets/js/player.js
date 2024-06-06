$(document).ready(function() {
  $('.edit-button').click(function() {
    $('.form-control').attr('disabled', false)
    $('#edit-player-submit').removeClass('d-none')
  })
})