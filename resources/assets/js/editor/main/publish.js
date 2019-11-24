var recomTagsHistory = [],
    tagLoadTimer;

$('.w-more-icon').click(function() {
  publishModal();
});

$('#more-inner-content textarea, .w-more-icon').bind('propertychange change keyup input paste click', function(){
  var mmdalTxta = $('#more-inner-content .needed-txtarea'),
      tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
      disble = false;
  for (var i = 0; i < mmdalTxta.length; i++) {
    if (mmdalTxta[i].value == '') {
      disble = true;
    }
  }

  if (tagElms.length == 0) {
    disble = true;
  }

  if (disble) {
    document.getElementById('more-publish-btn').setAttribute('disable', true);
  }else {
    document.getElementById('more-publish-btn').removeAttribute('disable');
  }
  $('#w-desc__counter').text($('#w-desc')[0].textLength);
});

$('#w-desc').blur(function() {
  updateWritingDesc($('#w-desc').val());
});


$(document).mousedown(function (e){
  if (!isSvg(e.target) && document.getElementById('w-modal-more').style.display != 'none'){
    var tagElemPar = findParentBySelector(e.target, '.modal-tag-elem');


    if(e.target.className.includes('modal-tag-elem') || tagElemPar != null) {
      if (tagElemPar == null) {
        var tagElem = e.target;
      }else {
        var tagElem = tagElemPar;
      }


      var tgsElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem');
      for (var i = 0; i < tgsElms.length; i++) {
        if (tgsElms[i] == tagElem) {
          var clickedNr = i;
        }
      }
      selectTag(clickedNr);
    }else {
      var actTgs = document.getElementsByClassName('modal-tag__active');

      for (var c = 0; c < actTgs.length; c++) {
        actTgs[c].classList.remove('modal-tag__active');
      }
    }
  }else if(isElemOrChild(e.target, '.tag-elem-close')){
    var tagElem = findParentBySelector(e.target, '.modal-tag-elem');
    tagElem.remove();
    updateWritingTags();
  }
});



window.addEventListener("keydown",function(e){
  if (e.target.id == 'w-mtags') {
    currTextNode = e.target;
    caretText = currTextNode.selectionStart;

    var tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
        prevmTagVal = $('#w-mtags').val().toLowerCase();

    if (e.keyCode === 32) {
      var prevCharacter = currTextNode.value[caretText-1],
          nextCharacter = currTextNode.value[caretText],
          spaceCount = (currTextNode.value.split(" ").length - 1);

      if ((typeof prevCharacter == 'undefined' || prevCharacter.trim() == '') ||
          (typeof nextCharacter != 'undefined' && nextCharacter.trim() == '')) {
        e.preventDefault();
        if (typeof prevCharacter != 'undefined' && prevCharacter.trim() == '') {
          pubModalAddTag();
        }
      }
      if (spaceCount >= 1) {
        pubModalAddTag();
      }

      if (typeof nextCharacter != 'undefined' && nextCharacter.trim() == '') {
        currTextNode.setSelectionRange(caretText+1, caretText+1);
      }
    }else if ([37, 8].includes(e.keyCode) && caretText == 0) {
      selectTag(tagElms.length-1);
      currTextNode.blur();
      e.preventDefault();
    }else if ([188, 13].includes(e.keyCode)) {
      pubModalAddTag();
      e.preventDefault();
    }else if(e.keyCode == 188){
      e.preventDefault();
    }
    prevDoubleSpace(e, e.target);

    var tagsNr = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem').length;
    if (tagsNr >= 6) {
      e.preventDefault();
    }

    setTimeout( function() {
      var currSearch = $('#w-mtags').val().toLowerCase().trim();
      if (prevmTagVal != currSearch && currSearch.length > 1) {
        downloadRecomTags(currSearch);
      }else {
        $('#w-more-tagbox').hide();
      }
    }, 1);

    $('#w-tags__counter').text(tagsNr);
  }else {
    var actTgs = document.getElementsByClassName('modal-tag__active');
    if (actTgs.length != 0) {
      var tagTxtArNode = document.getElementById('w-mtags'),
      tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
      delAct = 0,
      actNr = null;

      for (var i = 0; i < tagElms.length; i++) {
        if (tagElms[i].className.includes('modal-tag__active')) {
          actNr = i;
        }
      }

      if(e.keyCode == 39 && actNr == tagElms.length-1){
        tagTxtArNode.focus();
        delAct = 1;
      }else if ([8, 46].includes(e.keyCode)) {
        actTgs[0].remove();
        if (actNr > tagElms.length-1) {
          if (!actNr-1 > tagElms.length-1 && actNr-1 >= 0) {
            selectTag(actNr-1);
          }else {
            tagTxtArNode.focus();
          }
        }else {
          selectTag(actNr);
        }
      }else if (e.keyCode == 39) {
        selectTag(actNr+1);
      }else if (e.keyCode == 37 && actNr != 0) {
        selectTag(actNr-1);
      }

      if (delAct) {
        for (var c = 0; c < actTgs.length; c++) {
          actTgs[c].classList.remove('modal-tag__active');
        }
        updateWritingTags();
      }
      e.preventDefault();
      $('#w-tags__counter').text(document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem').length);
    }
  }
},false);




function publishModal(){
  $('#w-modal-more').css('display', 'flex');
  $('body').addClass('modalMode');

  $('#w-tags__counter').text(document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem').length);

  autosize($('.txtarea-resize'));
  autosize.update($('.txtarea-resize'));

}


function publishWriting(){
  var wtitle = $('#w-title').val(),
      wdesc = $('#w-desc').val(),
      tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
      activeCover = $('#w-cont-global')[0].getAttribute('data-curr-cover'),
      sucs = true;

  if (wtitle.length == 0) {
    alert('Writing needs title');
    sucs = false;
  }

  if (wdesc.length == 0) {
    $('#w-desc-error').show();
    sucs = false;
  }

  if (tagElms.length == 0) {
    $('#w-mtags-error').show();
    sucs = false;
  }else {
    var tagsContent = [];
    for (var t = 0; t < tagElms.length; t++) {
      tagsContent.push(tagElms[t].innerText.trim().toLowerCase());
    }
  }

  if(sucs){
    $.ajax({
      method: 'POST',
      url: writing_id+'/save',
      dataType: 'json',
      data: {
        _token: csrf_token,
        publish: true,
        title: wtitle,
        desc: wdesc,
        tags: tagsContent
      }
    })
    .done(function(data) {
      if (data.success) {
        $('#w-modal-more .inpt-line-negative').hide();
        saveProgress('end');
        // window.location.href = data.url;
        // console.log(data.url);
      }else {
        if (data.error_type) {
          if (data.error_type == 'no_split') {
            console.log('Your first section need to have at least 1 split');
          }
        }else {
          saveProgress('error');
        }
      }
    })
    .fail(function() {
      saveProgress('error');
    })
  }
}

function getCurrentTags(){
  var tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
      tagsContent = [];
  for (var t = 0; t < tagElms.length; t++) {
    tagsContent.push(tagElms[t].innerText.trim().toLowerCase());
  }
  return tagsContent;
}

function updateWritingTags(){
  var tagsContent = getCurrentTags();

  $.ajax({
    method: 'POST',
    url: writing_id+'/save',
    dataType: 'json',
    data: {
      _token: csrf_token,
      tags: tagsContent
    }
  })
  .done(function(data) {
    if (!data.success) {
      saveProgress('error');
    }
  })
  .fail(function() {
    saveProgress('error');
  })
}


function updateWritingDesc(desc_content){
  $.ajax({
    method: 'POST',
    url: writing_id+'/save',
    dataType: 'json',
    data: {
      _token: csrf_token,
      desc: desc_content
    }
  })
  .done(function(data) {
    saveProgress('end');
  })
}


function pubModalAddTag(tagName){
  var txtArea = document.getElementById('w-mtags'),
      txtAreaVal = txtArea.value.trim(),
      tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
      currTags = getCurrentTags();

  if (typeof tagName != 'undefined') {
    txtAreaVal = tagName;
  }

  if (txtAreaVal.length < 2 || tagElms.length >= 6) {
    return 0;
  }


  if (currTags.includes(txtAreaVal.toLowerCase())) {
    $('#w-more-tagbox').hide();
    txtArea.value = '';
    return 0;
  }


  var tagEl = document.getElementById('modal-tag-clone').cloneNode(true);
  tagEl.removeAttribute('id');
  tagEl.getElementsByClassName('modal-tag-elemtxt')[0].innerText = txtAreaVal;
  txtArea.parentNode.insertBefore(tagEl, txtArea);

  $('#w-more-tagbox').hide();
  txtArea.value = '';
  updateWritingTags();
}



function selectTag(nr){
  var txtArea = document.getElementById('w-mtags'),
      tagElms = document.getElementById('modal-tags-top').getElementsByClassName('modal-tag-elem'),
      actTgs = document.getElementsByClassName('modal-tag__active');
  $('#w-more-tagbox').hide();

  for (var c = 0; c < actTgs.length; c++) {
    if (actTgs[c] != tagElms[nr]) {
      actTgs[c].classList.remove('modal-tag__active');
    }
  }

  if (!tagElms[nr].className.includes('modal-tag__active')) {
    tagElms[nr].className += ' modal-tag__active';
  }
}



function downloadRecomTags(searchVal){
  clearTimeout(tagLoadTimer);

  if (typeof recomTagsHistory[searchVal] == 'undefined' || searchVal.length < 2) {
    boxLoading($('#w-more-tagbox')[0]);
    tagLoadTimer = setTimeout( function() {
      $.ajax({
        method: 'POST',
        url: '/tagInfo',
        dataType: 'json',
        data: {
          _token: csrf_token,
          searchTag: searchVal,
          recom: true
        }
      })
      .done(function(data) {
        if (data.success) {
          recomTagsHistory[data.forTag] = data.resultArray;
          updatedRecomTags(searchVal);
          boxStopLoading($('#w-more-tagbox')[0]);
        }else {
          boxLoadingFail($('#w-more-tagbox')[0]);
        }
      })
      .fail(function() {
        boxLoadingFail($('#w-more-tagbox')[0]);
      })
    }, 400);
  }else {
    boxStopLoading($('#w-more-tagbox')[0]);
    updatedRecomTags(searchVal);
  }
}


function updatedRecomTags(searchVal){
  if ($('#w-mtags').text() == searchVal) {
    return;
  }



  var listData = recomTagsHistory[searchVal];

  if (typeof listData != 'undefined') {
    var currTags = getCurrentTags();
    $('#tagbox-reommList')[0].innerHTML = '';
    for (var t = 0; t < listData.length; t++) {
      var tagTitle = listData[t]['title'],
      tagVarif = listData[t]['ver'],
      tagLinkedNr = listData[t]['linked'];

      if (currTags.includes(tagTitle.toLowerCase())) {
        continue;
      }

      var tagLiEl = $('#w-more-tagbox #cloneRecomTag').clone()[0];


      tagLiEl.removeAttribute('id');
      tagLiEl.getElementsByClassName('tagbox-li-arguments')[0].setAttribute('tag-verified', tagVarif);
      tagLiEl.getElementsByClassName('tagbox-li-title')[0].innerText = tagTitle;
      tagLiEl.getElementsByClassName('tagbox-li-linked')[0].innerText = tagLinkedNr;
      tagLiEl.onclick =  function(){
        pubModalAddTag(tagTitle);
      }

      $('#tagbox-reommList').append(tagLiEl);
    }


    if (listData.length > 0) {
      var boxTopOffset = $('#w-mtags')[0].scrollHeight+$('#w-mtags')[0].offsetTop+6,
          boxLeftOffset = $('#w-mtags')[0].offsetLeft;

      $('#w-more-tagbox').css({top: boxTopOffset+'px', left: boxLeftOffset+'px'});
      $('#w-more-tagbox').show();
    }
  }else {
    $('#w-more-tagbox').hide();
  }
}


function boxLoading(popupEl){
  var contentEl = popupEl.querySelector('[popupLoad-content]'),
      popupLoadEl = popupEl.querySelector('[popupLoad-show]');
  popupLoadEl.style.display = 'block';
  popupLoadEl.setAttribute('popupLoad-show', 'loader');
  contentEl.innerHTML = '';
};

function boxStopLoading(popupEl){
  var popupLoadEl = popupEl.querySelector('[popupLoad-show]');
  if (popupLoadEl.getAttribute('popupLoad-show') == 'loader') {
    popupLoadEl.setAttribute('popupLoad-show', '');
    popupLoadEl.style.display = 'none';
  }
};


function boxLoadingFail(popupEl){
  var popupLoadEl = popupEl.querySelector('[popupLoad-show]');
  popupLoadEl.style.display = 'block';
  popupLoadEl.setAttribute('popupLoad-show', 'failMsg');
}
