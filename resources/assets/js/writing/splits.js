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
