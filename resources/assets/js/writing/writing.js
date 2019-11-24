$(document).ready(function() {
  closeNotifBox();
  progFigureLoading();
});

function progFigureLoading(){
  var imgDomElms = document.getElementsByClassName('w-innerf'),
  newImgs = [];
  for (var h = 0; h < imgDomElms.length; h++) {
    var image = imgDomElms[h];
    newImgs[h] = new Image();
    newImgs[h].onload = function(){
      var image = document.querySelectorAll('[data-file-id="'+this.getAttribute('data-file-id')+'"]')[0],
          progLCont = findParentBySelector(image, 'figure').getElementsByClassName('prog-load')[0],
          imgCont = findParentBySelector(image, '.w-innerf-cont');

      if (imgCont.getAttribute('data-size') < 750 || imgCont.getAttribute('data-size') == null) {
        imgCont.style.width = 'auto';
      }
      image.src = this.src;
      image.style.paddingBottom = '0';
      progLCont.className += ' prog-load__fadeout'
      setTimeout( function() {
        progLCont.remove();
      }, 1200);
      progLCont.style.position = 'absolute';
    };
    newImgs[h].src = image.getAttribute('data-src');
    image.style.display = 'block';
    newImgs[h].setAttribute('data-file-id', image.getAttribute('data-file-id'));
  }
}

function closeNotifBox(el){
  findParentBySelector(el, '.notif-box').remove();
}

function showMsgNotif(msg, type){
  alert(msg);
  // closeNotifBox();
}

function hoverNotifBox(){
  clearTimeout(fadeNotifTimer);
  clearTimeout(hideNotifTimer);
  document.getElementsByClassName('notif-box')[0].classList.remove('fade-notif-box');
}


var hideNotifTimer,
    fadeNotifTimer;

function closeNotifBox(time){
  allNotifBoxes = document.getElementsByClassName('notif-box');
  if (typeof time == 'undefined') {
    var time = 5000;
  }

  if (allNotifBoxes.length != 0) {
    clearTimeout(hideNotifTimer);
    clearTimeout(fadeNotifTimer);
    hideNotifTimer = setTimeout( function() {
      allNotifBoxes[0].className += ' fade-notif-box';

      fadeNotifTimer = setTimeout( function() {
        allNotifBoxes[0].remove();
        if (allNotifBoxes.length > 0) {
          closeNotifBox(1500);
        }
      }, 800);
    }, time);
  }
}


var toloadprev = 1;
function loadPrevSections(sec_id){
  if (toloadprev) {
    $('#w-cont-load').css('opacity', 0)
      .slideDown('200')
      .animate(
        { opacity: 1 },
        { queue: false, duration: '200' }
      );
    toloadprev = 0;
    $.ajax({
      method: 'POST',
      url: writing_id+'/getsec',
      dataType: 'json',
      data: {
          _token: csrf_token,
          ltst_section: sec_id,
          type: 'loadNew'
      }
    })
    .done(function(data) {
      if (!data.success) {
        $('#w-cont-load').animate({opacity: 0}, 200, function() {
          $('#w-cont-fail').css('opacity', 0)
          .slideDown('200')
          .animate(
            { opacity: 1 },
            { queue: false, duration: '200' }
          );
          $(this).css('display', 'none');
        });
      }else {
        if(data.data == null){
          $('#w-cont-load').css('display', 'none');
          alert('Something went Wrong, please try later');
        }else {
          var newNode = document.createElement("div");
          newNode.innerHTML = data.data;
          var firstMsg = newNode.firstChild;
          firstMsg.getElementsByClassName('w-section-secdiv')[0].style.display = 'block';

          var curOffset = $(document).scrollTop();
          while(newNode.firstChild) {
              document.getElementById('w-content-cont').insertBefore(newNode.firstChild, document.getElementsByClassName('w-section-cont')[0]);
          }

          var ahead = 0;
          if(firstMsg.getAttribute('isfrst')==1){
            var wahArr = document.getElementsByClassName('w-article-head');
            for (var i = 0; i < wahArr.length; i++) {
              wahArr[i].style.display = 'block';
              ahead += wahArr[i].offsetHeight;
            }
          }
          // Offset to previous first message minus original offset/scroll
          var styles = window.getComputedStyle(firstMsg);
          var margin = parseFloat(styles['marginTop']) +
                       parseFloat(styles['marginBottom']);
          $(document).scrollTop(firstMsg.offsetHeight + margin - curOffset + ahead);
          $('#w-cont-load').css('display', 'none');
          toloadprev = 1;
          splitsRowStyling();
          progFigureLoading();
        }
      }
    })
    .fail(function() {
      $('#w-cont-load').css('display', 'none');
      alert('Something went Wrong, please try later');
    })
  }
}




function loadNextSections(sec_id){
  $('#w-cont-load').css('opacity', 0)
    .slideDown('200')
    .animate(
      { opacity: 1 },
      { queue: false, duration: '200' }
    );
  toloadprev = 0;
  $.ajax({
    method: 'POST',
    url: writing_id+'/getsec',
    dataType: 'json',
    data: {
        _token: csrf_token,
        ltst_section: sec_id,
        type: 'loadNew'
    }
  })
  .done(function(data) {
    if (!data.success) {
      $('#w-cont-load').animate({opacity: 0}, 200, function() {
        $('#w-cont-fail').css('opacity', 0)
        .slideDown('200')
        .animate(
          { opacity: 1 },
          { queue: false, duration: '200' }
        );
        $(this).css('display', 'none');
      });
    }else {
      if(data.data == null){
        $('#w-cont-load').css('display', 'none');
        alert('Something went Wrong, please try later');
      }else {
        var newNode = document.createElement("div");
        newNode.innerHTML = data.data;

        var firstMsg = newNode.firstChild;
        var curOffset = $(document).scrollTop();
        while(newNode.firstChild) {
            document.getElementById('w-content-cont').insertBefore(newNode.firstChild, document.getElementsByClassName('w-section-cont')[0]);
        }

        var ahead = 0;
        if(firstMsg.getAttribute('isfrst')==1){
          var wahArr = document.getElementsByClassName('w-article-head');
          for (var i = 0; i < wahArr.length; i++) {
            wahArr[i].style.display = 'block';
            ahead += wahArr[i].offsetHeight;
          }
        }
        // Offset to previous first message minus original offset/scroll
        var styles = window.getComputedStyle(firstMsg);
        var margin = parseFloat(styles['marginTop']) +
                     parseFloat(styles['marginBottom']);
        $(document).scrollTop(firstMsg.offsetHeight + margin - curOffset + ahead);
        $('#w-cont-load').css('display', 'none');
        toloadprev = 1;
        splitsRowStyling();
      }
    }
  })
  .fail(function() {
    $('#w-cont-load').css('display', 'none');
    alert('Something went Wrong, please try later');
  })
}




function splitAction(split_id){
  if (!userLogged) {
    return;
  }
  var splitObj = new Object();
  splitObj.split_id = split_id;
  var clickedSplitEl = document.querySelector('.w-split-item[split-id="'+split_id+'"]');
  var sectSplitNodes = findParentBySelector(clickedSplitEl, '.w-split-inner-list').children;

  for (var s = 0; s < sectSplitNodes.length; s++) {
    if (sectSplitNodes[s] == clickedSplitEl) {
      if (!sectSplitNodes[s].className.includes('w-split-item__pos')) {
        sectSplitNodes[s].className += ' w-split-item__pos';
      }
    }else {
      if (!sectSplitNodes[s].className.includes('w-split-item__neg')) {
        sectSplitNodes[s].className += ' w-split-item__neg';
      }
    }
  }


  JSON.stringify(splitObj);
  $.ajax({
    method: 'POST',
    url: writing_id+'/progress',
    dataType: 'json',
    data: {
        _token: csrf_token,
        prog: splitObj
    }
  })
  .done(function(r) {
    if (!r.success) {
      showMsgNotif(r.msg);
      console.log('error');
    }else {
      var newNode = document.createElement("div");
      newNode.innerHTML = r.data;
      secdivElems = document.getElementsByClassName('w-section-secdiv');
      secdivElems[secdivElems.length-1].style.display = 'block';

      document.getElementById('w-content-cont').append(newNode.firstChild);
    }
  })
  .fail(function() {
    showMsgNotif('Something went Wrong, please try later');
  });
}
