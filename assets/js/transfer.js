
export function refreshPlayerList(url)
{
  $.ajax({
    url: url,
    success: function (data) {
      console.log(data);
    }
  })
}
