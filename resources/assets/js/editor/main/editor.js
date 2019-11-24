$(document).ready(function() {
  splitsRowStyling();
  progFigureLoading();
});

$(window).resize(function() {
  splitsRowStyling();
  resetPositFloatings();
});


function resetPositFloatings(){
  if (document.getElementsByClassName('w-inner-fc').length) {
    postionFrameBar(document.getElementsByClassName('w-inner-fc')[0]);
  }
  var fbarElem = $('.editor-addbar')[0];
  if (fbarElem != null && fbarElem.style.display != "" && fbarElem.style.display != 'none' && fbarElem.getAttribute('data-last-ref')) {
    var elemToRefId = fbarElem.getAttribute('data-last-ref');
    var elemToRef = document.getElementById(elemToRefId);
    postionFloatingBar(fbarElem, elemToRef);
  }
}



function unsavedState(){
  var loadT = $('#editor-logo')[0].getAttribute('data-load-turn');
  if (loadT == null) {
    var loadTC = 0;
  }else {
    var loadTC = parseInt(loadT);
  }

  if (loadTC == 0) {
    $('#editor-logo').addClass('logo-unsaved');
    $('.logoType-anachor')[0].setAttribute('title', 'Unsaved');
  }
}


function saveProgress(type, showAlert){
  var loadT = $('#editor-logo')[0].getAttribute('data-load-turn');
  if (loadT == null) {
    var loadTC = 0;
  }else {
    var loadTC = parseInt(loadT);
  }

  if (type === "start") {
    if (loadTC == 0) {
      $('#editor-logo').addClass('logoGrad-load');
      $('.logoType-anachor')[0].setAttribute('title', 'Saving');

      $('#editor-logo').removeClass('logo-unsaved');
    }
    $('#editor-logo')[0].setAttribute('data-load-turn', loadTC+1);
  }else if (type === "end") {
    $('#editor-logo')[0].setAttribute('data-load-turn', loadTC-1);
    loadTC = loadT-1;
    if (loadTC == 0) {
      $('#editor-logo').removeClass('logoGrad-load');
      $('.logoType-anachor')[0].setAttribute('title', 'Saved');
    }
    console.log('saved');
  }else {
    $('#editor-logo')[0].setAttribute('data-load-turn', loadTC-1);
    loadTC = loadT-1;
    if (loadTC == 0) {
      $('#editor-logo').removeClass('logoGrad-load');

      $('#editor-logo').addClass('logo-unsaved');
      $('.logoType-anachor')[0].setAttribute('title', 'Unsaved');
    }

    if (typeof showAlert != 'undefined') {
      if (showAlert != false) {
        alert(showAlert);
      }
    }else {
      alert('Something went Wrong, please try later');
    }
  }
}


function addIntoEditor(elemToRef, position, newElem){
  var wTopCont = findParentBySelector(elemToRef, '#w-top');
  if (wTopCont != null) {
    editor = wTopCont;
  }else {
    editor = findParentBySelector(elemToRef, '.editable');
    if (['w-cover__top', 'w-cover__mid', 'w-cover__down'].includes(newElem.id)) {
      newElem.id = randStr(6);
    }
  }

  if (editor == null || position == 'editor') {
    var editor = elemToRef.getElementsByClassName('editable')[0];
    editor.appendChild(newElem);
  }else {
    if (position == 'down') { //under
      var elmNxtSbl = elemToRef.nextElementSibling;
      if (elmNxtSbl != null) {
        editor.insertBefore(newElem, elmNxtSbl);
      }else {
        editor.appendChild(newElem);
      }
    }else {//above
      if (elemToRef.id == newElem.id) {
        elemToRef.parentNode.replaceChild(newElem, elemToRef);
      }else {
        editor.insertBefore(newElem, elemToRef);
      }
    }
  }
  if (editor.classList.contains('medium-editor-placeholder')) {
    editor.classList.remove("medium-editor-placeholder");
  }
}

function createImgDomElem(file_id, path, elem_id){
  var cont = document.createElement('figure');
  cont.setAttribute('contenteditable', false);
  if (typeof elem_id != 'undefined' && elem_id != null) {
    cont.id = elem_id;
  }else {
    cont.id = randStr(6);
  }

  var innerfCont = document.createElement('div');
  innerfCont.className += 'w-innerf-cont';
  innerfCont.setAttribute('contenteditable', false);

  var img = document.createElement('img');
  img.src = path;
  img.className += 'w-innerf';
  img.setAttribute("data-file-id", file_id);
  img.setAttribute('contenteditable', false);
  img.style.display = 'block';
  innerfCont.append(img);
  cont.append(innerfCont);
  return cont;
}


var timer;

function InitMediumEditor(){
  var editor = new MediumEditor('.editable', {
    toolbar: {
        buttons: ['bold', 'italic', 'h2', 'quote', 'anchor'],
    },
    placeholder: {
        hideOnClick: true
    },
    spellcheck: true,
    paste: {
      forcePlainText: true,
      cleanPastedHTML: true,
      cleanAttrs: ['class', 'style', 'dir', 'id'],
      cleanTags: ['meta'],
      unwrapTags: ['pre', 'code', 'div']
    },
    autoLink: true,
    keyboardCommands: true,
    imageDragging: false,
    anchorPreview: {
      hideDelay: 300
    }
  });


  for (var eei = 0; eei < editor.elements.length; eei++) {
    var currSectId = findParentBySelector(editor.elements[eei], '.w-section-cont').getAttribute('id');
    var DOMeditChildrens = editor.elements[eei].children;
    savedDOM['"'+currSectId+'"'] = [];
    if (DOMeditChildrens.length > 0) {
      for (var i = 0; i < DOMeditChildrens.length; i++) {
        var idOfDOM = DOMeditChildrens[i].id;
        if (idOfDOM != "") {
          savedDOM['"'+currSectId+'"'].push(idOfDOM);
        }
      }
    }
  }

  var lastFcsedElem;
  editor.subscribe('focus', function(event, editable){
    if (editable.children.length == 0) {
      editable.className  += ' w-cont-mid';
    }
  });



  editor.subscribe('blur', function(event, editable){
    if (editable.children.length == 0) {
      editable.classList.remove('w-cont-mid');
    }
  });


  allSecIdsChnged = [];
  editor.subscribe('editableInput', function(event, editable){
    if (editable.children.length != 0) {
      editable.classList.remove('w-cont-mid');
    }

    /*var fcsedDOM = editor.getSelectedParentElement();
    while (BanedEditorWrapers.includes(fcsedDOM.tagName)) {
      var fcsedDOM = fcsedDOM.parentNode;
    }*/

    var fcsedDOM = getSelectedFromEditor(editable);
    if (fcsedDOM.length) {
      trigerSave(editable, fcsedDOM[0]);
    }
  });


  editor.subscribe('editablePaste', function(event, editable){
    trigerSave(editable);
  });


  editor.subscribe('editableKeyup', function(event, editable){
    if (event.key == 'Enter') {
      if (editable.childElementCount == 2) {
        var frstCh = editable.children[0];
        if (frstCh.nodeName == 'DIV') {
          var newNodeP = document.createElement('p');
          newNodeP.innerHTML = frstCh.innerHTML;
          editable.replaceChild(newNodeP, frstCh);
        }
      }
      var currAllSelected = getSelectedFromEditor(editable);
      if (currAllSelected[0].previousElementSibling != null) {
        var undtctedd = [currAllSelected[0].previousElementSibling];
      }else {
        var undtctedd = [];
      }

      trigerSave(editable, undtctedd);
    }


    var barElem = $('.editor-addbar');

    var fcsedDOM = getSelectedFromEditor(editable);
    if (fcsedDOM.length && (fcsedDOM[0].innerHTML == "" || fcsedDOM[0].innerHTML == '<br>')) {
      barElem.show();
      postionFloatingBar(barElem[0], fcsedDOM[0]);
    }else {
      barElem.hide();
      barElem.removeAttr('data-last-ref');
    }
  });




  editor.subscribe('editableClick', function(event, editable){
    var barElem = $('.editor-addbar');
    if (event.target.nodeName == 'FIGURE') {
      barElem.hide();
      handleEditorFocus(editable, null, event.target.getElementsByClassName("w-innerf")[0]);
    }else if (event.target.className.includes('w-innerf') ||
          event.target.className.includes('prog-load-elem') ||
          event.target.className.includes('prog-load')) {
      barElem.hide();
      var el = event.target;
      if (!event.target.className.includes('w-innerf')) {
        var elp = el;
        while (elp.nodeName != 'FIGURE') {
          var elp = elp.parentNode;
        }
        var el = elp.getElementsByClassName("w-innerf")[0];
      }

      handleEditorFocus(editable, null, el);
    }else {
      handleEditorFocus(editable, null);

      // var fcsedDOM = editor.getSelectedParentElement();
      var fcsedDOM = getSelectedFromEditor(editable);
      if (fcsedDOM.length && (fcsedDOM[0].innerHTML == "" || fcsedDOM[0].innerHTML == '<br>')) {
        barElem.show();
        postionFloatingBar(barElem[0], fcsedDOM[0]);
      }else {
        barElem.hide();
      }
    }
  });

  autosize.update($('.txtarea-resize'));
}



function getSelectedFromEditor(editable){
  var selection = window.getSelection();
  if (!selection.rangeCount) {
    return [];
  }
  var range = selection.getRangeAt(0);
  if (range.commonAncestorContainer.nodeName != '#text') {
    var allWithinRangeParent = range.commonAncestorContainer.getElementsByTagName("*");
    if (!allWithinRangeParent.length) {
      var allWithinRangeParent = [range.commonAncestorContainer];
    }
  }else {
    var allWithinRangeParent = [range.commonAncestorContainer.parentElement];
  }

  var allSelected = [];
  for (var i=0, el; el = allWithinRangeParent[i]; i++) {
    // The second parameter says to include the element
    // even if it's not fully selected
    if (selection.containsNode(el, true) ) {
      while (BanedEditorWrapers.includes(el.tagName)) {
        var el = el.parentNode;
      }

      if (!allSelected.includes(el) && el.parentNode == editable) {
        allSelected.push(el);
      }
    }else {
      var CurrFcsNode = selection.focusNode;
      if (!allSelected.includes(CurrFcsNode) && CurrFcsNode.parentNode == editable) {
        allSelected.push(CurrFcsNode);
      }
    }
  }
  return allSelected;
}




function handleEditorFocus(editable, key, element, prevCaretPos){
  setTimeout( function() {
    lastFcsedElem = document.getElementsByClassName('w-inner-fc')[0];
    if (typeof lastFcsedElem != 'undefined') {
      if (lastFcsedElem.hasAttribute('disable-lstfsced')) {
        lastFcsedElem.removeAttribute('disable-lstfsced');
        lastFcsedElem = undefined;
      }else {
        lastFcsedElemPar = findParentBySelector(lastFcsedElem, 'figure');
      }
    }

      caretPos = getCaretPos();

    if (typeof element == 'undefined' || element == null) {
      if (caretPos == null) {
        editable.focus();
      }
      var currAllSelected = getSelectedFromEditor(editable);
      if (currAllSelected.length == 0 && editable.getElementsByClassName('w-innerf').length == 1) {
        var currAllSelected = editable.getElementsByClassName('w-innerf');
      }
    }else {
      currAllSelected = [element];
      positEditorCursor(element.parentElement);
      editable.blur();
    }

    if (currAllSelected.length == 1) {
      var currChild = currAllSelected[0];
      if (document.querySelectorAll('[w-fcsed-near]').length) {
        for (var i = 0; i < document.querySelectorAll('[w-fcsed-near]').length; i++) {
          var FcsedNVal = document.querySelectorAll('[w-fcsed-near]')[i].getAttribute('w-fcsed-near');
          if ((key == 40 && FcsedNVal == 1 && prevCaretPos.bottom) || (key == 38 && FcsedNVal == -1 && prevCaretPos.top)) {
            editable.blur();
            currChild = document.querySelectorAll('[w-fcsed-near]')[i].getElementsByClassName("w-innerf")[0];
            currChild.className += ' w-inner-fc';
            postionFrameBar(currChild);
            removeTxtSelection();
          }
        }
      }



      var newNodeP = document.createElement('p');
      newNodeP.innerHTML = '<br>';
      if (typeof lastFcsedElem != 'undefined' && !lastFcsedElemPar.hasAttribute("w-fcsed-near")) {
        switch (key) {
          case 40:
            var nxtParSib = lastFcsedElemPar.nextElementSibling;
            if (nxtParSib == null) {
              editable.appendChild(newNodeP);
              positEditorCursor(newNodeP);
              newCurrFcsed = newNodeP;
              currChild = newNodeP;
            }else {
              positEditorCursor(nxtParSib, false);
              newCurrFcsed = nxtParSib;
              currChild = nxtParSib;
            }
            break;
          case 38:
            var prevParSib = lastFcsedElemPar.previousElementSibling;
            if (prevParSib != null) {
              positEditorCursor(prevParSib, true);
              newCurrFcsed = prevParSib;
              currChild = prevParSib;
            }else {
              editable.insertBefore(newNodeP, lastFcsedElemPar);
              positEditorCursor(newNodeP, false);
              newCurrFcsed = newNodeP;
              currChild = newNodeP;
            }
            break;
          case 8:
          case 46:
            if (lastFcsedElemPar.nextElementSibling != null) {
              positEditorCursor(lastFcsedElemPar.nextElementSibling, false);
              newCurrFcsed = lastFcsedElemPar.nextElementSibling;
            }else if(lastFcsedElemPar.previousElementSibling != null) {
              positEditorCursor(lastFcsedElemPar.previousElementSibling, true);
              newCurrFcsed = lastFcsedElemPar.previousElementSibling;
            } else {
              editable.appendChild(newNodeP);
              newCurrFcsed = newNodeP;
            }

            saveDOMChanges([], [lastFcsedElemPar.id]);
            lastFcsedElemPar.remove();
            $('#editor-framebar').hide();
            break;
          case 13:
            editable.insertBefore(newNodeP, lastFcsedElemPar);
            positEditorCursor(newNodeP, false);
            currChild = newNodeP;
            editable.focus();
            break;
          default:
        }

        if ((typeof newCurrFcsed == 'undefined' || newCurrFcsed.tagName != 'FIGURE') && [40,38,8,46].includes(key)) {
          editable.focus();
        }
      }


      if (typeof lastFcsedElem != 'undefined' && lastFcsedElem != currChild &&
          (key == 40 || key == 38 || key == 13 || key == null)) {
        lastFcsedElem.classList.remove('w-inner-fc');
        $('#editor-framebar').hide();
      }

      // console.log(lastFcsedElem);
      if (lastFcsedElem != currChild) {
        if (currChild.className.includes('w-innerf')) {
          currChild.className += ' w-inner-fc';
          postionFrameBar(currChild);
        }else if (currChild.nodeName == "FIGURE") {
          var innerImgElem = currChild.getElementsByClassName("w-innerf")[0];
          innerImgElem.className += ' w-inner-fc';
          postionFrameBar(innerImgElem);
        }
      }


      if (document.querySelectorAll('[w-fcsed-near]').length) {
        for (var fcn = 0; fcn <= document.querySelectorAll('[w-fcsed-near]').length; fcn++) {
          document.querySelectorAll('[w-fcsed-near]')[0].removeAttribute("w-fcsed-near");
        }
      }

      if (typeof document.getElementsByClassName('w-inner-fc')[0] == 'undefined') {
        if (currChild.nextElementSibling != null && currChild.nextElementSibling.nodeName == "FIGURE") {
          currChild.nextElementSibling.setAttribute('w-fcsed-near', 1);
        }
        if (currChild.previousElementSibling != null && currChild.previousElementSibling.nodeName == "FIGURE") {
          currChild.previousElementSibling.setAttribute('w-fcsed-near', -1);
        }
      }
    }
  }, 1);
}





function trigerSave(editable, undetected){
    var editbleChildrens = $(editable).children();
    var currAllSelected = getSelectedFromEditor(editable);
    if (typeof undetected != 'undefined') {
      currAllSelected = currAllSelected.concat(undetected);
    }

    var currSectId = findParentBySelector(editable, '.w-section-cont').getAttribute('id');
    if (!allSecIdsChnged.includes(currSectId)) {
      allSecIdsChnged.push(currSectId);
    }

    if (editbleChildrens.length > 0) {
      for (var i = 0; i < editbleChildrens.length; i++) {
        if (editbleChildrens[i].id == "") {
          editbleChildrens[i].id = randStr(6);
        }else if (editbleChildrens[i-1] != undefined) {
          if (editbleChildrens[i].id == editbleChildrens[i-1].id) {
            editbleChildrens[i].id = randStr(6);
          }
        }
      }
    }

    for (var i = 0; i < currAllSelected.length; i++) {
      if (typeof currAllSelected[i] == 'string') {
        var currSelId = currAllSelected[i];
      }else {
        var currSelId = currAllSelected[i].id;
      }
      if (!changedDOM.includes(currSelId) && currSelId != "") {
        changedDOM.push(currSelId);
      }
    }

    unsavedState();
    clearTimeout(timer);
    timer = setTimeout( function() {
      console.log('save trigged');
      DOMChildrens = [];

      allSecIdsChnged.forEach(function(currSectId, index) {
          var currEditbleInner = document.getElementById(currSectId).getElementsByClassName('editable')[0].childNodes;
          if (currEditbleInner.length > 0) {
            for (var crredch = 0; crredch < currEditbleInner.length; crredch++) {
              var currChild = currEditbleInner[crredch];
              if (currChild.id != "" && currChild.id != undefined) {
                if (DOMChildrens['"'+currSectId+'"'] == undefined) {
                  DOMChildrens['"'+currSectId+'"'] = [];
                }
                DOMChildrens['"'+currSectId+'"'].push(currChild.id);
              }
            }
          }
      });

      var removed = [];
      var added = [];
      for (var keyDm in DOMChildrens) {
        if (keyDm === 'length' || !DOMChildrens.hasOwnProperty(keyDm)) continue;
        if (savedDOM[keyDm] == undefined) {
          savedDOM[keyDm] = [];
        }
        removed.push(Object.values(savedDOM[keyDm]).diff(Object.values(DOMChildrens[keyDm])));
        added.push(Object.values(DOMChildrens[keyDm]).diff(Object.values(savedDOM[keyDm])));
      }
      removed = removed[0];


      var notNeeded = [];
      changedDOM = changedDOM.diff(removed); //where not in removed
      changedDOM = changedDOM.diff(added); //where not in added
      for (var g = 0; g < changedDOM.length; g++) {
        var domElem = document.getElementById(changedDOM[g]);
        if (domElem == null) {
          notNeeded.push(changedDOM[g]);
        }
      }

      changedNadded = changedDOM.concat(added[0]).unique();


      for (var i = 0; i < notNeeded.length; i++) {
        var index = changedNadded.indexOf(notNeeded[i]);
        if (index != -1) {
            changedNadded.splice(index, 1);
        }
      }


      if (changedNadded.length > 0 || removed.length > 0) {
        // console.log('add '+added);
        // console.log('rem '+removed);
        console.log('chnged '+changedNadded);
        if (changedNadded.length > 500 || removed.length > 500) {
          if (changedNadded.length > removed.length) {
            var bigestLen = changedNadded.length;
          }else {
            var bigestLen = removed.length;
          }

          for (var spl = 0; spl < bigestLen; spl+=500) {
            saveDOMChanges(changedNadded.slice(spl, spl+500), removed.slice(spl, spl+500));
          }
        }else {
          saveDOMChanges(changedNadded, removed);
        }

        for (var keyDm in DOMChildrens) {
          if (savedDOM[keyDm] == undefined) {
            savedDOM[keyDm] = [];
          }
          savedDOM[keyDm] = DOMChildrens[keyDm];
        }
        changedDOM.length = 0;
        changedNadded.length = 0;
        removed.length = 0;
        added.length = 0;
        notNeeded.length = 0;
      }
      allSecIdsChnged.length = 0;
  }, 1000);
}


const BanedEditorWrapers = ['B', 'I', 'U', 'A', 'BR'],
      BanedEditorWrapersWspan = ['B', 'I', 'U', 'A', 'BR', 'SPAN'],
      BanedEditorWrapersWimg = ['B', 'I', 'U', 'A', 'BR', 'IMG'];


const allowedInnerEditorElms = ['B', 'I', 'U', 'A', 'BR', 'FIGURE', 'IMG', 'P', 'H2', 'BLOCKQUOTE'],
      InnerEditorElmsTEXT = ['EM', 'STRONG', 'B', 'I', 'U', 'A', 'P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'BLOCKQUOTE'];

trytosaveDomI = 0;
savedDOM = [];
changedDOM = [];
removed = [];
added = [];


function saveDOMChanges(changed, removed){
  saveProgress('start');

  var allChangesnArr = [];
  for (var i = 0; i < changed.length; i++) {
    if (changed[i].length == 6) {
      var currChild = document.getElementById(changed[i]);
      if (currChild == null) {
        if (trytosaveDomI < 3) {
          trytosaveDomI++;
          setTimeout( function() {
            return saveDOMChanges(changed, removed);
          }, 3000);
        }else {
          saveProgress('error');
        }
      }

      var obj = new Object();
      obj.id  = currChild.id;
      obj.type = currChild.localName;
      if (obj.type == 'figure') {
        obj.type = 'img';
        obj.atribute = null;

        var innerElemArr = currChild.getElementsByClassName("w-innerf-changed");
        if (innerElemArr.length == 0) {
          obj.content = null;
        }else {
          obj.content = [];
          for (var ie = 0; ie < innerElemArr.length; ie++) {

            if (innerElemArr[ie].getAttribute('data-file-id') == null) {
              var errLoop = 0;
              while (innerElemArr[ie].getAttribute('data-file-id') == null) {
                setTimeout( function() {
                  if (errLoop == 5) {
                    saveProgress('error');
                    return;
                  }
                  errLoop++
                }, 3000);
              }
              ie--;
            }else {
              var inCont = findParentBySelector(innerElemArr[ie], '.w-innerf-cont');
              var inFrmObj = new Object();
              inFrmObj.file_id = innerElemArr[ie].getAttribute('data-file-id');
              inFrmObj.position_after = null;

              /*inFrmObj.position_after = innerElemArr[ie].previousElementSibling;
              while(inFrmObj.position_after != null && inFrmObj.position_after.className.includes('prog-load')){
                inFrmObj.position_after = inFrmObj.position_after.previousElementSibling;
              }
              if (inFrmObj.position_after != null) {
                inFrmObj.position_after = inFrmObj.position_after.id;
              }*/

              inFrmObj.atribute = inCont.getAttribute('data-size');

              obj.content.push(inFrmObj);
              if (innerElemArr[ie].className.includes('w-innerf-wascover')) {
                obj.excover = true;
                innerElemArr[ie].classList.remove('w-innerf-wascover');
              }
              innerElemArr[ie].classList.remove('w-innerf-changed');
            }
          }
        }
      }else {

        var obAnchs = currChild.querySelectorAll('a');
        if (obAnchs.length != 0) {
          for (var g = 0; g < obAnchs.length; g++) {
            obAnchs[g].setAttribute('target', "_blank");
          }
        }

        obj.content = DOMPurify.sanitize(currChild.innerHTML, {ADD_ATTR: ['target']});
        obj.atribute = null;
      }

      if (currChild.previousElementSibling != null) {
        obj.position = currChild.previousElementSibling.id;
      }else {
        obj.position = null;
      }


      var Currsection_id = findParentBySelector(currChild, '.w-section-cont').getAttribute('id');
      var section_inarr = null;

      if (allChangesnArr.length) {
        for (var c = 0; c < allChangesnArr.length; c++){
          if (allChangesnArr[c].id == Currsection_id) {
            section_inarr = allChangesnArr[c];
          }
        }
      }

      if (section_inarr != null) {
        section_inarr.innerElmts.push(obj);
      }else {
        var ElementObj = new Object();
        ElementObj.id = Currsection_id;
        ElementObj.innerElmts = [];

        ElementObj.innerElmts.push(obj);
        allChangesnArr.push(ElementObj);
      }

    }
  }
  trytosaveDomI = 0;



  if (removed.length || allChangesnArr.length) {
    JSON.stringify(allChangesnArr);
    JSON.stringify(removed);
    console.log(allChangesnArr);
    console.log(removed);

    $.ajax({
      method: 'POST',
      url: writing_id+'/save',
      dataType: 'json',
      data: {
        _token: csrf_token,
        editor_content: allChangesnArr,
        editor_removed: removed
      }
    })
    .done(function(data) {
      if (!data.success) {
        if (data.msg) {
          console.log(data.msg);
        }else {
          saveProgress('error');
        }
      }else {
        saveProgress('end');
      }
    })
    .fail(function() {
      saveProgress('error');
    });
  }
}




var coverUodateTime;
function updateCoverImg(newFileId, atribute, position, delf){
  // if 3x null then delete
  if (typeof position == 'undefined') {
    var position = null;
  }

  if (typeof delf == 'undefined') {
    var delf = true;
  }
  var data = new Object();
  data.file_id = newFileId;
  data.attr = atribute;
  data.pos = position;
  data.delf = delf;

  unsavedState();
  clearTimeout(coverUodateTime);
  coverUodateTime = setTimeout( function() {
    saveProgress('start');
    $.ajax({
      method: 'POST',
      url: writing_id+'/save',
      dataType: 'json',
      data: {
        _token: csrf_token,
        writing_cover: data
      }
    })
    .done(function(data) {
      if (!data.success) {
        saveProgress('error');
      }else {
        $('#w-cont-global')[0].setAttribute('data-curr-cover', newFileId);
        saveProgress('end');
      }
    })
    .fail(function() {
      saveProgress('error');
    })
  }, 800);
}



var sectTitleSaveTimer;
function saveSectTitle(el){
  setTimeout( function() {
    var ttVal = el.innerText,
        sect_cont = findParentBySelector(el, '.w-section-cont'),
        section_id = sect_cont.id;
    if (ttVal != '') {
      sect_cont.getElementsByClassName('section-title__plchlder')[0].style.display = 'none';
      // sect_cont.getElementsByClassName('section-title')[0].style.display = 'inline-block';
    }else {
      sect_cont.getElementsByClassName('section-title__plchlder')[0].style.display = 'inline-block';
      // sect_cont.getElementsByClassName('section-title')[0].style.display = 'none';
    }

    var nextEl = document.querySelectorAll('[next-id="'+section_id+'"]'),
        prevEl = document.querySelectorAll('[sect-id="'+section_id+'"]'),
        treeEl = document.querySelectorAll('[tree-section-id="'+section_id+'"]'),
        elToChnge = Array.from(nextEl).concat(Array.from(treeEl)).concat(Array.from(prevEl));
    if (elToChnge.length != 0) {
      for (var i = 0; i < elToChnge.length; i++) {
        elToChnge[i].innerText = el.innerText;
        elToChnge[i].value = el.innerText;
      }
    }

    unsavedState();
    clearTimeout(sectTitleSaveTimer);
    sectTitleSaveTimer = setTimeout( function() {
      saveProgress('start');

      $.ajax({
        method: 'POST',
        url: writing_id+'/save',
        dataType: 'json',
        data: {
          _token: csrf_token,
          section_title: ttVal,
          section_id: section_id
        }
      })
      .done(function(data) {
        if (!data.success) {
          saveProgress('error');
        }else {
          saveProgress('end');
        }
      })
      .fail(function() {
        saveProgress('error');
      });
    }, 2000);
  }, 1);
}

var titleSaveTimer;
$('.writing-title').bind('propertychange change keyup input paste', function(){
  var ttVal = $(this).val();

  $('.writing-title').each(function(index, el) {
    if ($(this).val() != ttVal) {
      $(el).text(ttVal);
      autosize.update($('.txtarea-resize'));
    }
  });


  if (ttVal != '') {
    $('#topbar-title__plchlder').hide();
    $('#topbar-title').show();
  }else {
    $('#topbar-title__plchlder').show();
    $('#topbar-title').hide();
  }

  unsavedState();
  clearTimeout(titleSaveTimer);
  titleSaveTimer = setTimeout( function() {
    saveProgress('start');

    $.ajax({
      method: 'POST',
      url: writing_id+'/save',
      dataType: 'json',
      data: {
          _token: csrf_token,
          title: ttVal
      }
    })
    .done(function(data) {
      if (!data.success) {
        alert(data.msg);
        saveProgress('error');
      }else {
        saveProgress('end');
      }
    })
    .fail(function() {
      saveProgress('error');
    });
  }, 2000);
});






function getVisibleSectsIds(){
  var sectsElms = $('#w-content-cont')[0].getElementsByClassName('w-section-cont'),
      sectsIds = [];
  for (var i = 0; i < sectsElms.length; i++) {
    sectsIds.push(sectsElms[i].id);
  }
  return sectsIds;
}


function getVisibleSects(){
  var currIds = getVisibleSectsIds(),
      currElms = [];
  for (var i = 0; i < currIds.length; i++) {
    currElms.push(document.getElementById(currIds[i]));
  }
  return currElms;
}





function hideAllFloatingBars(){
  $('#editor-framebar')[0].style.display = 'none';
  $('.editor-addbar')[0].style.display = 'none';
  $('.editor-addbar').removeAttr('data-last-ref');
}


function postionFloatingBar(elemToPosition, elemToRef){
  while (BanedEditorWrapers.includes(elemToRef.tagName)) {
    var elemToRef = elemToRef.parentNode;
  }
  if (elemToRef.nodeName != "IMG" &&
      elemToRef.parentNode.nodeName != 'BLOCKQUOTE' &&
      elemToRef.nodeName != 'BLOCKQUOTE') {
    var offY = getOffsetTop(elemToRef)+2;
    var centerX = elemToRef.clientWidth/2 - elemToPosition.clientWidth/2;
    var offX = $( elemToRef ).offset().left + centerX;
    // offY = elemToRef.offsetTop;
    elemToPosition.style.top = offY + 'px';
    elemToPosition.style.left = offX + 'px';
    elemToPosition.setAttribute('data-lastEditable', findParentBySelector(elemToRef, '.editable').getAttribute('medium-editor-index'));
    elemToPosition.setAttribute('data-last-ref', elemToRef.id);
  }
}


function postionFrameBar(elemToRef){
  var barElem = $('#editor-framebar')[0],
      imgCont = findParentBySelector(elemToRef, 'figure');
  if (elemToRef.nodeName == "IMG") {
    FrameBarStates(elemToRef);

    var EdToolBar = $('.medium-editor-toolbar')[0];
    if (window.getComputedStyle(EdToolBar, null).getPropertyValue("display") != 'none') {
      if (EdToolBar.className.includes('medium-editor-toolbar-active')) {
        EdToolBar.setAttribute('data-disable-visib', 1);
        EdToolBar.classList.remove('medium-editor-toolbar-active');
      }
    }


    barElem.style.display = 'block';
    var topOff = barElem.clientHeight+11;

    if (imgCont.id == 'w-cover__top') {
      var offY = getOffsetTop(elemToRef) + elemToRef.clientHeight/2 - barElem.clientHeight/2;
    }else {
      var offY = getOffsetTop(elemToRef)-topOff;
    }

    var centerX = elemToRef.clientWidth/2 - barElem.clientWidth/2;
    var offX = $( elemToRef ).offset().left + centerX;

    barElem.style.top = offY + 'px';
    barElem.style.left = offX + 'px';
  }
}


function FrameBarAction(e){
  if (e.target.nodeName != 'BUTTON') {
    if (findParentBySelector(e.target, '.neutralize-btn') != null) {
      var actionBtn = findParentBySelector(e.target, '.framebar-itm');
    }else {
      return false;
    }
  }else {
    var actionBtn = e.target;
  }
  var FcsedInner = document.getElementsByClassName('w-inner-fc')[0];
  var fcsedParFigure = findParentBySelector(FcsedInner, 'figure');
  var fcsedCont = fcsedParFigure.getElementsByClassName('w-innerf-cont')[0];
  var lastAtrVal = fcsedCont.getAttribute('data-size');


  switch (actionBtn.getAttribute('action-type')) {
    case 's1':
      var dataSize = null;
      break;
    case 's2':
      var dataSize = '750';
      break;
    case 's3':
      var dataSize = '1005';
      break;
    case 's4':
      var dataSize = '1';
      break;
  }


  fcsedCont.setAttribute('data-size', dataSize);

  if (lastAtrVal != dataSize) {
    var frmitsArr = document.getElementsByClassName('frm-itm__active');
    if (frmitsArr.length && frmitsArr[0] != actionBtn) {
      frmitsArr[0].classList.remove('frm-itm__active');
    }
    if (!actionBtn.className.includes('frm-itm__active')) {
      actionBtn.className += ' frm-itm__active';
    }


    if (findParentBySelector(FcsedInner, '.writing-cover') == null) {
      FcsedInner.className += " w-innerf-changed";
      saveDOMChanges([findParentBySelector(FcsedInner, 'figure').id], []);
    }else {
      updateCoverImg(FcsedInner.getAttribute('data-file-id'), dataSize);
    }
    postionFrameBar(FcsedInner);
  }
}

function FrameBarStates(elemToRef){
  var FcsedInner = elemToRef;
  var dSizVal = findParentBySelector(FcsedInner, '.w-innerf-cont').getAttribute('data-size');
  var imgFullW = FcsedInner.naturalWidth;
  var frmbsBtn_4 = document.getElementById('frmbs_4');
  var frmbsBtn_3 = document.getElementById('frmbs_3');
  var frmbsBtn_2 = document.getElementById('frmbs_2');
  var frmbsBtn_1 = document.getElementById('frmbs_1');

  if (imgFullW > 1020) {
    frmbsBtn_4.style.display = 'inline-block';
  }else {
    frmbsBtn_4.style.display = 'none';
  }

  if (imgFullW > 1004) {
    frmbsBtn_3.style.display = 'inline-block';
  }else {
    frmbsBtn_3.style.display = 'none';
  }

  if (imgFullW > 749) {
    frmbsBtn_2.style.display = 'inline-block';
  }else {
    frmbsBtn_2.style.display = 'none';
  }

  if (imgFullW < 750) {
    frmbsBtn_1.style.display = 'inline-block';
  }else {
    frmbsBtn_1.style.display = 'none';
  }

  if (dSizVal == 'null' || dSizVal == null ||dSizVal == '') {
    if (imgFullW > 749) {
      var frmbsBtn_act = frmbsBtn_2;
    }else {
      var frmbsBtn_act = frmbsBtn_1;
    }
  }else if (dSizVal == 750) {
    var frmbsBtn_act = frmbsBtn_2;
  }else if (dSizVal == 1005) {
    var frmbsBtn_act = frmbsBtn_3;
  }else if (dSizVal == 1) {
    var frmbsBtn_act = frmbsBtn_4;
  }

  var frmitsArr = document.getElementsByClassName('frm-itm__active');
  if (frmitsArr.length && frmitsArr[0] != frmbsBtn_act){
    frmitsArr[0].classList.remove('frm-itm__active');
  }

  if (!frmbsBtn_act.className.includes('frm-itm__active')) {
    frmbsBtn_act.className += ' frm-itm__active';
  }
}


function hideAddBar(){
  removeBarAnimPad();
  $('#editor-addimg__line').hide();
}

function removeBarAnimPad(){
  var ibAnim = document.getElementsByClassName('img-bar__animation');
  var currAnimated = ibAnim[0];
  if (ibAnim.length && currAnimated.className.includes('img-bar__animation')) {
    currAnimated.classList.remove('img-bar__animation');
    if (!currAnimated.className.includes('img-bar__animationRem')) {
      currAnimated.className += ' img-bar__animationRem';
    }

    setTimeout( function() {
      currAnimated.classList.remove('img-bar__animationRem');
    }, 150);
  }
}


function showAddBar(){
  var addIMGlineElem = document.getElementById('editor-addimg__line');
  var lposAtrr = addIMGlineElem.getAttribute("last-pos");

  if (lposAtrr != null) {
    var elemToRefFbar = document.getElementById(lposAtrr.split(' ')[0]);

    if (lposAtrr.split(' ').length == 2) {
      var lstPos = lposAtrr.split(' ')[1];
    }else {
      var lstPos = null;
    }

    if (lstPos != 'down') {
      var elemToRun = elemToRefFbar;
    }else {
      if (elemToRefFbar != null && elemToRefFbar.nextElementSibling) {
        var elemToRun = elemToRefFbar.nextElementSibling;
      }else {
        var elemToRun = null;
      }
    }

    if (elemToRun != null) {
      if (!elemToRun.className.includes('img-bar__animation')) {
        removeBarAnimPad();
        elemToRun.className += ' img-bar__animation';
      }
    }else if(lstPos == 'down'){
      removeBarAnimPad();
    }


    if (addIMGlineElem.style.display != 'block') {
      $('#editor-addimg__line').show();
    }
  }
}


function addBarPosition(elem, pos){
  var plusPadding = 10;
  var additionOpt = 0;

  var addIMGlineElem = document.getElementById('editor-addimg__line');

  if (elem == 'cover') {
    switch (pos) {
      case 'top':
        var elem = document.getElementById('w-author-top');
        addIMGlineElem.setAttribute("last-pos", 'w-author-top');
        addIMGlineElem.style.top = getOffsetTop(elem) - 5 +'px';
        break;
      case 'mid':
        var elem = document.getElementById('Vtitle_inpt');
        addIMGlineElem.setAttribute("last-pos", 'Vtitle_inpt');
        addIMGlineElem.style.top = getOffsetTop(elem) +'px';
        break;
      case 'down':
        var elem = document.getElementById('Vtitle_inpt');
        addIMGlineElem.setAttribute("last-pos", 'Vtitle_inpt down');
        addIMGlineElem.style.top = getOffsetTop(elem) + elem.offsetHeight +'px';
        break;
    }

  }else {
    if (typeof pos != 'undefined') {
      if (!elem.nextElementSibling) {
        additionOpt += 16;
      }

      addIMGlineElem.style.top = getOffsetTop(elem) + elem.offsetHeight- (plusPadding/2) + additionOpt +'px'; //under
      addIMGlineElem.setAttribute("last-pos", elem.id+' down');
    }else {
      addIMGlineElem.style.top = getOffsetTop(elem) +'px'; //above
      addIMGlineElem.setAttribute("last-pos", elem.id);
    }
  }
}


function uploadImageFile(dt, elemToRef, lstPos1, newElemId){
  var files = dt.files; // Array of all files
  for (var i=0, file; file=files[i]; i++) {
    file._token = csrf_token;
    if (file.type.match(/image.*/) && ['image/jpeg', 'image/png'].includes(file.type)) {
      var ifCover = findParentBySelector(elemToRef, '#w-top') != null,
          reader = new FileReader();
      formdata = new FormData();
      formdata.append('inimage', file);
      formdata.append('_token', csrf_token);
      reader.onload = function(e2) {
          var image = new Image();
          image.src = e2.target.result;
          image.onload = function() {
            if (typeof newElemId != 'undefined') {
              imgDOM = createImgDomElem(null, e2.target.result, newElemId);
            }else {
              imgDOM = createImgDomElem(null, e2.target.result);
            }

            addIntoEditor(elemToRef, lstPos1, imgDOM);
            var imgOrgSizeW = this.width;
            innerImg = imgDOM.querySelectorAll("img[src='"+e2.target.result+"']")[0];
            innerfCont = imgDOM.getElementsByClassName('w-innerf-cont')[0];
            if (imgOrgSizeW < 50 || this.height < 20) {
              alert('Image is too small');
              return false;
            }

            if (ifCover) {
              if (this.width < 400 || this.height < 350 ) {
                alert('This image is too small for cover');
                return false;
              }
            }

            if (imgOrgSizeW > 1020) {
              formdata.append('attr', 1);
              innerfCont.setAttribute('data-size', 1);
            }else if (imgOrgSizeW > 1004) {
              formdata.append('attr', 1005);
              innerfCont.setAttribute('data-size', 1005);
            }else if (imgOrgSizeW < 1005 && imgOrgSizeW >+ 750) {
              formdata.append('attr', 750);
              innerfCont.setAttribute('data-size', 750);
            }else {
              formdata.append('attr', null);
              innerImg.setAttribute('data-size', null);
            }

            formdata.append('position', null);

            if (ifCover) {
              formdata.append('paragraph_id', 'cover');
            }else {
              formdata.append('paragraph_id', imgDOM.id);
            }

            $.ajax({
              type: "POST",
              url: writing_id+'/uploadf',
              dataType : 'json',
              data: formdata,
              mimeTypes:"multipart/form-data",
              contentType: false,
              cache: false,
              processData: false,
            })
            .done(function(data) {
              if (!data.success) {
                imgDOM.remove();
                saveProgress('error');
              }else {
                innerImg.src = data.new_path;
                innerImg.setAttribute("data-file-id", data.new_img_id);

                if (!ifCover) {
                  editable = findParentBySelector(elemToRef, '.editable');
                  if (editable == null || lstPos1 == 'editor') {
                    editable = elemToRef.getElementsByClassName('editable')[0];
                  }
                  trigerSave(editable);
                }else {
                  if (elemToRef.id == 'w-author-top') {
                    var position = 'top';
                    imgDOM.id = 'w-cover__top';
                  }else if (elemToRef.id == 'Vtitle_inpt' && lstPos1 != 'down') {
                    var position = 'mid';
                    imgDOM.id = 'w-cover__mid';
                  }else {
                    var position = 'down';
                    imgDOM.id = 'w-cover__down';
                  }
                  imgDOM.className += ' writing-cover';
                  updateCoverImg(data.new_img_id, innerImg.getAttribute('data-size'), position);
                }
              }
            })
            .fail(function(e) {
              imgDOM.remove();
              saveProgress('error');
            });
          };
      }
      reader.readAsDataURL(file); // start reading the file data.*/
    }
  }
}







function closeSection(section_id){
  var sect2close = document.getElementById(section_id),
      prevTopElem = document.getElementById('w-prev-top'),
      sect2closePrevSect = sect2close.previousElementSibling;

  if (sect2close.parentNode.children[0] == sect2close) {
    neutralizePrvBar();
    prevTopElem.setAttribute('data-need-update', true);
  }

  section2temp([sect2close]);
  var sectMinL = document.getElementById('global-empty-main').getElementsByClassName('w-top-sect-min').length;

  if(document.getElementById('w-content-cont').getElementsByClassName('w-section-cont').length == 0){
    if (sectMinL < 4) {
      generateEmptyView();
    }else {
      $('#w-cont-global').addClass('w-cont-center');
      $('#w-empty-global').show();
    }
  }
  if (sect2closePrevSect) {
    updateCurrSectState(sect2closePrevSect.id);
  }
}



function renderEmptyLi(data){
  newNode = document.createElement("li");
  newNode.className = 'w-top-sect-min btn-action__2';
  newNode.setAttribute('sect-id', data.id);
  newNode.onclick = function(e){
    changeSectionsDOM(data.id);
  }
  newNode.innerText = data.title;
  return newNode;
}


function generateEmptyView(){
  $('#global-sect-empty').hide();
  $('#global-empty-main').hide();
  $('#w-cont-global').addClass('w-cont-center');

  $.ajax({
    method: 'POST',
    url: writing_id+'/info',
    dataType: 'json',
    data: {
        _token: csrf_token,
        sect_min: 4
    }
  })
  .done(function(data) {
    if (data.success && data.data) {
      var secminEl = document.getElementById('global-empty-main').getElementsByClassName('w-top-sect-min');
      while (secminEl[0]) {
        secminEl[0].parentNode.removeChild(secminEl[0]);
      }

      data.data.reverse();
      for (var p = 0; p < data.data.length; p++) {
        var elem = renderEmptyLi(data.data[p]);
        $('#global-empty-main ul').prepend(elem);
      }

      $('#w-empty-global #global-empty-main').show();
    }else {
      $('#w-empty-global #global-sect-empty').show();
    }
  })
  .fail(function() {
    $('#w-empty-global #global-sect-empty').show();
  });

  $('#w-empty-global').show();
}


function section2temp(sections){
  var tpCont = document.getElementById('temporary-sections');
  var aHead = document.getElementsByClassName('w-article-head')[0];
  neutralizePrvBar();
  for (var k = 0; k < sections.length; k++) {
    if (sections[k].getAttribute('isfrst') == 1) {
      aHead.style.display = 'none';
    }
    tpCont.append(sections[k]);
  }
}

function hideEmptyView(){
  $('#w-cont-global').removeClass('w-cont-center');
  $('#w-empty-global').hide();
}


function changeSectionsDOM(wanted_sect_id, insertType){
  hideEmptyView();

  var presentSecDOM = document.getElementsByClassName('w-section-cont');
  var needDownload = true;
  var presentSecIds = [];
  var activeSects = getVisibleSects(); //visible

  for (var i = 0; i < presentSecDOM.length; i++) {
    presentSecIds.push(presentSecDOM[i].id);


    if (presentSecDOM[i].id == wanted_sect_id) {
      insertSectDOM(presentSecDOM[i], insertType, activeSects);
      needDownload = false;
    }
  }


  if (needDownload) {
    $.ajax({
      method: 'POST',
      url: writing_id+'/info',
      dataType: 'json',
      data: {
        _token: csrf_token,
        get_section: wanted_sect_id,
        present: presentSecIds
      }
    })
    .done(function(data) {
      if (data.success) {
        newSectNode = document.createElement("div");
        newSectNode.innerHTML = data.data;
        newSectNode = newSectNode.firstChild;
        insertSectDOM(newSectNode, insertType, activeSects);
        console.log('end');
      }else {
        downloadSucc = false;
        console.log('error');
      }
    })
    .fail(function() {
      downloadSucc = false;
      console.log('error');
    });
  }
}


function insertSectDOM(newSectNode, insertType, activeSects){
  var aHead = document.getElementsByClassName('w-article-head')[0];
  if (findParentBySelector(newSectNode, '#temporary-sections') == null) {
    var needInit = true;
  }else {
    var needInit = false;
  }
  if (typeof insertType == 'undefined') {
    if (activeSects.length > 0) {
      if (activeSects[0] == newSectNode) {
        section2temp(activeSects.slice(1));
      }else {
        if (newSectNode.getAttribute('isfrst') == 1) {
          aHead.style.display = 'block';
        }else {
          aHead.style.display = 'none';
        }
        section2temp(activeSects);
      }
    }
    if (activeSects[0] != newSectNode) {
      if (newSectNode.getAttribute('isfrst') == 1) {
        aHead.style.display = 'block';
      }else {
        aHead.style.display = 'none';
      }
      document.getElementById('vapp_nwsec').parentNode.insertBefore(newSectNode, document.getElementById('vapp_nwsec'));
      neutralizePrvBar();
    }
  }else if(insertType == 'append'){
    if (newSectNode.getAttribute('isfrst') != 1) {
      if (activeSects.length > 0 && activeSects[activeSects.length -1] != newSectNode) {
        document.getElementById('vapp_nwsec').parentNode.insertBefore(newSectNode, document.getElementById('vapp_nwsec'));
        neutralizePrvBar();
      }
    }
    updateCurrSectState(newSectNode.previousElementSibling.id);
  }else if(insertType == 'prepend'){
    neutralizePrvBar();

    if (newSectNode.getAttribute('isfrst') == 1) {
      aHead.style.display = 'block';
    }else {
      aHead.style.display = 'none';
    }
    activeSects[0].parentNode.insertBefore(newSectNode, activeSects[0]);
    updateCurrSectState(newSectNode.nextElementSibling.id);
  }
  splitsRowStyling();
  progFigureLoading();
  hideAllFloatingBars();
  if (needInit) {
    InitMediumEditor();
    var objName = "vapp_split"+newSectNode.id;
    var f = new Vue({
      data: {
        splitLabels: [],
        presentLabels: [],
        SplitRange: 0
      },
      mixins: [mixin_split],
      el: '#vapp_split'+newSectNode.id
    })
    window[objName] = f;
  }
  autosize($('.txtarea-resize'));
  autosize.update($('.txtarea-resize'));
}


function updateCurrSectState(sectToUpdateId){
  var sectToUpdate = document.getElementById(sectToUpdateId),
      currVisSpltsArr = sectToUpdate.getElementsByClassName('split-visible');
  if (sectToUpdate.nextElementSibling) {
    if (currVisSpltsArr.length) {
      for (var v = 0; v < currVisSpltsArr.length;) {
        var currVisibleId = currVisSpltsArr[v].getElementsByClassName('split-next_inpt')[0].getAttribute('next-id');
        if (currVisibleId != sectToUpdate.nextElementSibling.id) {
          currVisSpltsArr[v].classList.remove('split-visible');
        }
      }
    }


    var newActiveArr = sectToUpdate.querySelectorAll('.split-next_inpt[next-id="'+sectToUpdate.nextElementSibling.id+'"]');

    for (var a = 0; a < newActiveArr.length; a++) {
      var newActSplit = findParentBySelector(newActiveArr[a], '.split-item');
      if (!newActSplit.className.includes('split-visible')) {
        newActSplit.className += ' split-visible';
      }
    }
  }else if (currVisSpltsArr.length){
    for (var v = 0; v < currVisSpltsArr.length; v++) {
      currVisSpltsArr[v].classList.remove('split-visible');
    }
  }
}


function neutralizePrvBar(){
  $('#w-prev-top')[0].innerHTML = "";
  $('#w-prev-top')[0].removeAttribute('style');
  $('#w-prev-top')[0].classList.remove('prev-visible');
  $('#w-prev-top-cont')[0].removeAttribute('style');
}
