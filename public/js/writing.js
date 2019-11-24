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

var titleSaveTimer;
var NxtSplitTimer;
var listItems = [];


function saveSplit(listItems){
  saveProgress('start');
  splitObjArr = [];

  for (var i = 0; i < listItems.length; i++) {
    var listItem = listItems[i];

    var splitObj = new Object();
    splitObj.id = listItem.id;
    splitObj.title = listItem.getElementsByClassName('split-item__title')[0].innerHTML;
    var nodesiblings = Array.prototype.slice.call( listItem.parentElement.children );
    splitObj.position = nodesiblings.indexOf( listItem )+1;
    splitObj.next_id = listItem.getElementsByClassName('split-next_inpt')[0].getAttribute('next-id');

    splitObjArr.push(splitObj);
  }

  JSON.stringify(splitObjArr);

  $.ajax({
    method: 'POST',
    url: writing_id+'/save',
    dataType: 'json',
    data: {
        _token: csrf_token,
        split_item: splitObjArr
    }
  })
  .done(function(data) {
    if (!data.success) {
      showMsgNotif(data.msg);
      saveProgress('end');
    }else {
      saveProgress('end');
    }
  })
  .fail(function() {
    saveProgress('error');
  })
}

function modifySplitDrpdwn(exception){
  var splitLis = document.getElementsByClassName('split-li');
  if (splitLis.length != 0) {
    for (var spl = 0; spl < splitLis.length; spl++) {
      if (findParentBySelector(splitLis[spl], '#'+exception) == null) {
        var sCont = findParentBySelector(splitLis[spl], '.split-cont');
        sCont.setAttribute('data-need-drpdwn', true);
        var sectDRPDWNitm = splitLis[spl].getElementsByClassName('split-drpdwn-itm');
        for (var i = 0; i < sectDRPDWNitm.length; i++) {
          sectDRPDWNitm[i].remove();
        }
      }
    }
  }
}




function saveNxtSplitTrig(li){
  var listItem = findParentBySelector(li, '.split-item');


  if (listItem.id != "") {
    if (!listItems.includes(listItem)) {
      listItems.push(listItem);
    }
    var snxINPT = listItem.getElementsByClassName('split-next_inpt')[0];
    snxINPT.setAttribute("value", li.innerText);
    snxINPT.value = li.innerText;
    if (li.getAttribute('next-id') != snxINPT.getAttribute('next-id')) {
      snxINPT.setAttribute("next-id", li.getAttribute('next-id'));
      snxINPT.setAttribute("next-title", li.innerText);

      clearTimeout(NxtSplitTimer);
      NxtSplitTimer = setTimeout( function() {
        saveSplit(listItems, 'next');
      }, 500);
      var liParent = findParentBySelector(listItem, '.w-section-cont');
      modifySplitDrpdwn(liParent.id);
      updateCurrSectState(liParent.id);
    }
  }else {
    saveProgress('error');
  }
}


function splitDrasticQury(subject, action){
  var listItem = findParentBySelector(subject, '.split-item');

  if (action == 'next_change' && listItem.getElementsByClassName('split-next_inpt')[0].getAttribute('next-id') == subject.getAttribute('next-id')) {
    return ;
  }


  $.ajax({
    method: 'POST',
    url: writing_id+'/info',
    dataType: 'json',
    data: {
      _token: csrf_token,
      check_split: listItem.id
    }
  })
  .done(function(data) {
    var nrOfUsers = data.data;
    if (nrOfUsers) {
      if (nrOfUsers == 1) {
        var innerMsg = nrOfUsers+' user is';
      }else {
        var innerMsg = nrOfUsers+' users are';
      }
      $('#action__question-mainmsg').html('Are you sure ?</br>'+innerMsg+' on this path');
      $('#action__question-action')[0].onclick = function(){
        splitDrasticActions(subject, action);
        $('#w-modal-action__question').hide();
      }

      if (action == 'remove') {
        $('#action__question-action').text('Remove');
      }else if(action == 'next_change'){
        $('#action__question-action').text('Change');
      }

      $('#w-modal-action__question')[0].style.display = 'flex';
    }else {
      splitDrasticActions(subject, action);
    }
  })
  .fail(function() {
    saveProgress('error');
  })
}


function splitDrasticActions(subject, action){
  if (action == 'remove') {
    var listItemId = findParentBySelector(subject, '.split-cont').id;
    eval(listItemId+'.removeSplitItm(subject);');
  }else if(action == 'next_change'){
    saveNxtSplitTrig(subject);
  }
}





function splitsRowStyling(splitInnerUl){
  if (typeof splitInnerUl == 'undefined') {
    var splitulArr = document.getElementsByClassName('split-inner-list');
  }else {
    var splitulArr = [splitInnerUl];
  }


  setTimeout( function() {
    for (var s = 0; s < splitulArr.length; s++) {
      var ulWidth = splitulArr[s].offsetWidth;
      var splitlisArr = splitulArr[s].getElementsByClassName('split-item');
      var rowWidth = 0;
      for (var l = 0; l < splitlisArr.length; l++) {
        var currLiWidth = parseFloat(window.getComputedStyle(splitlisArr[l]).width);

        if (rowWidth + currLiWidth <= ulWidth) {
          var currRowWidth = rowWidth + currLiWidth;
          if (typeof splitlisArr[l+1] != 'undefined' &&
          currRowWidth + parseFloat(window.getComputedStyle(splitlisArr[l+1]).width) <= ulWidth) {
            if (!splitlisArr[l].className.includes('split-brdrdiv')) {
              splitlisArr[l].className += ' split-brdrdiv';
            }
            rowWidth = currRowWidth;
          }else {
            rowWidth = 0;
            if (splitlisArr[l].className.includes('split-brdrdiv')) {
              splitlisArr[l].classList.remove("split-brdrdiv");
            }
          }

        }else {
          rowWidth = 0;
        }
      }
    }
  }, 10);
}

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
