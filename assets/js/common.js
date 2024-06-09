
export function toggleEdit(submitBtn){
  $('.edit-button').click(function() {
    if(submitBtn.hasClass('d-none')){
      $('.form-control').attr('disabled', false)
      submitBtn.removeClass('d-none')
    }else{
      $('.form-control').attr('disabled', true)
      submitBtn.addClass('d-none')
    }
  })
}