$('#comm-create-area').bind('propertychange change keyup input paste', function(event) {
  if ($(this).val() === "") {
    $('#w-comment-post-btn')[0].setAttribute('disable', true);
  }else {
    document.getElementById('w-comment-post-btn').style.display = 'block';
    $('#w-comment-post-btn')[0].removeAttribute('disable', true);
  }
});



$('.comm-post-btn').click(function() {
  var txtelemId = $(this)[0].getAttribute('data-value-elem');
  var txtElem = document.querySelectorAll('[data-elem-id='+txtelemId+']')[0];
  var commVal = txtElem.value;
  if (commVal != '') {
    $.ajax({
      method: 'POST',
      url: writing_id+'/comment',
      dataType: 'json',
      data: {
        _token: csrf_token,
        val: commVal,
        reply: null
      }
    })
    .done(function(data) {
      if (!data.success) {
        console.log(data.data);
        saveProgress('error');
      }else {
        console.log(data.data);
        txtElem.value = '';
        if (typeof $('.w-down-left .w-down-empty') != 'undefined') {
          $('.w-down-left .w-down-empty').hide();
        }
        $('#w-comments-cont').prepend(data.dom);
      }
    })
    .fail(function() {
      saveProgress('error');
    })
  }
});
