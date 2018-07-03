$( document ).ready(function($) {

  $("tr[id*='post-']").each(function(){
    let data = $(this).attr("id").substring(5)
    var point = $(this).find('.pumcp')
    // console.log(data.substring(5));
    $.ajax({
      type: "GET",
      url: 'https://sattvicfoods.in/wp-json/sattvic/v1/paycheck?id=' + data,
      success: function(res) {
        point.html(res.data)

      },
      error: function(err) {
        console.log(err)
      }
    });
  })

});