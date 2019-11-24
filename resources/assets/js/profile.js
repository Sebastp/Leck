$('#auth_stories-cont .prof-innernav__elem').click(function() {
  if (!$(this).hasClass('innernav-active')) {
    $('.innernav-active').removeClass('innernav-active');
    $(this).addClass('innernav-active');
    var contid = $(this).attr('data-contid');
    $('.auth_stories-active').removeClass('auth_stories-active');
    $('#'+contid).addClass('auth_stories-active');
  }
});
