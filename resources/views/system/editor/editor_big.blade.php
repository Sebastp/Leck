<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials._head')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/writing.css') }}">
  </head>
  <body class="editor_body">
      @include('partials.topbars._topbar', ['type' => 'editor'])

      @include('partials.modals._editor-treeview')

      @include('partials.modals._editor-more')

      @include('partials.modals._editor-actionQ')

      <div class="down-container">
        <div id="w-prev-top-cont">
          <ul id="w-prev-top" class="w-cont-mid">

          </ul>
        </div>

        <article id="w-cont-global" class="w-cont-padding" data-curr-cover="@if (!empty($writing->cover)){{$writing->cover->id}}@endif">
          <div id="w-empty-global" class="w-cont-mid">
            <div id="global-empty-main">
              <p class="f1_sys-sect_0" id="global-empty-msg">Choose section to load</p>
              <ul>
                <li id="empty-action__0" class="f1_sysinf-1__little">See more</li>
              </ul>
              <p class="f1_sysinf-1__little" id="global-empty-mid">or</p>
            </div>
            <div id="global-sect-empty">
              <p class="f3_sub_title">Sorry, we couldn't load any section</p>
            </div>
          </div>

          <div class="w-article-head" @if (!$sections[0]->isFirst)style="display: none"@endif>
            <header id="w-top">
              @if (!empty($writing->cover) && $writing->cover->position == 'top')
                <figure contenteditable="false" id="w-cover__top" class="writing-cover">
                  <div class="w-innerf-cont" contenteditable="false" data-size="{{$writing->cover->attr}}">
                    <div class="prog-load" data-size="{{$writing->cover->attr}}"><img src="{{ $writing->cover->path }}" class="prog-load-elem" contenteditable="false"></div>
                    <img class="w-innerf" data-src="{{ $writing->cover->path }}" data-file-id="{{$writing->cover->id}}" alt="cover photo">
                  </div>
                </figure>
              @endif

              <div class="w-cont-mid" id="w-author-top">
                @if (!empty($writing->authors))
                  @foreach ($writing->authors as $author)
                    <div class="w-author_single">
                      <a href="{{ asset($author->str_id) }}">
                        <img src="{{$author->avatar_path}}" alt="avatar" class="avatar-regular__big w-author-avatar">
                      </a>
                      <span class="f1_usrname_0 w-author__name">
                        <a href="{{ asset($author->str_id) }}">
                          {{ $author->nickname}}
                        </a>
                      </span>
                    </div>
                  @endforeach
                @else
                  <div class="w-author_single">
                    <img src="{{ asset('images/avatar.jpg') }}" alt="avatar" class="avatar-small">
                    <span class="f1_usrname_0 w-author__name">Unknown</span>
                  </div>
                @endif
              </div>

              @if (!empty($writing->cover) && $writing->cover->position == 'mid')
                <figure contenteditable="false" id="w-cover__mid" class="writing-cover">
                  <div class="w-innerf-cont" contenteditable="false" data-size="{{$writing->cover->attr}}">
                    <div class="prog-load" data-size="{{$writing->cover->attr}}"><img src="{{ $writing->cover->path }}" class="prog-load-elem" contenteditable="false"></div>
                    <img class="w-innerf" data-src="{{ $writing->cover->path }}" data-file-id="{{$writing->cover->id}}" alt="cover photo">
                  </div>
                </figure>
              @endif

              <div id="Vtitle_inpt" class="w-cont-mid">
                <textarea placeholder="Title" maxlength="120" class="neutralize-txtarea txtarea-resize writing-title f3_title_0" id="w-title" rows="1" spellcheck="false">{{$writing->title}}</textarea>
              </div>

              @if (!empty($writing->cover) && $writing->cover->position == 'down')
                <figure contenteditable="false" id="w-cover__down" class="writing-cover">
                  <div class="w-innerf-cont" contenteditable="false" data-size="{{$writing->cover->attr}}">
                    <div class="prog-load" data-size="{{$writing->cover->attr}}"><img src="{{ $writing->cover->path }}" class="prog-load-elem" contenteditable="false"></div>
                    <img class="w-innerf" data-src="{{ $writing->cover->path }}" data-file-id="{{$writing->cover->id}}" alt="cover photo">
                  </div>
                </figure>
              @endif
            </header>
          </div>




          <div id="w-content-cont">
            @include('layouts.writings._inook-editor-section', ['sections' => $sections])

            <div id="vapp_nwsec" class="w-cont-mid w-add_section">
              <div @click="createSection()" class="w-add_section_inner">
                <svg viewBox="0 0 54 54">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M26.115,0h2.271v54.5h-2.271V0z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M0,26.115h54.5v2.271H0V26.115z"/>
                </svg>
                <span class="f3_sub_title">Add Section</span>
              </div>
            </div>
          </div>

          <div id="temporary-sections"></div>
        </article>


      <div class="styled-content f_content_main" id="offscrean-clone"></div>

      <div class="editor-addbar">
        <button class="neutralize-btn addbar-itm" id="addbar-split" title="Create split">
          <svg viewBox="0.992 1.992 22 20" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="19" cy="18" r="3"/>
            <circle cx="12" cy="6" r="3"/>
            <circle cx="5" cy="18" r="3"/>
            <line x1="10.49" y1="8.59" x2="6.51" y2="15.42"/>
            <line x1="17.49" y1="15.41" x2="13.51" y2="8.59"/>
          </svg>
        </button>
        <button class="neutralize-btn addbar-itm" id="addbar-img" title="Add na image">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21">
            </polyline>
          </svg>
        </button>
        {{-- <button class="neutralize-btn addbar-itm" id="addbar-link" title="Link">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
          </svg>
        </button> --}}
      </div>

      <div id="editor-addimg__line"></div>

      <div id="editor-framebar">
        <button class="neutralize-btn framebar-itm" action-type="s1" id="frmbs_1">
          <svg viewBox="0 0 25 26">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,23h25v3H0V23z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M3,6h19v14H3V6z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,0h25v3H0V0z"/>
          </svg>
        </button>
        <button class="neutralize-btn framebar-itm" action-type="s2" id="frmbs_2">
          <svg viewBox="0 0 25 26">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,23h25v3H0V23z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,6h25v14H0V6z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,0h25v3H0V0z"/>
          </svg>
        </button>
        <button class="neutralize-btn framebar-itm" action-type="s3" id="frmbs_3">
          <svg viewBox="0 0 31 26">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M3,23h25v3H3V23z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,6h31v14H0V6z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M3,0h25v3H3V0z"/>
          </svg>
        </button>
        <button class="neutralize-btn framebar-itm" action-type="s4" id="frmbs_4">
          <svg viewBox="0 0 31 26">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M0,0h31v26H0V0z"/>
          </svg>
        </button>
      </div>
    </div>
    <input type="file" name="content-file" id="content-file_uplinpt">

    @include('partials._scripts')
    <script type="text/javascript" src="/js/purify.min.js"></script>
    <script type="text/javascript" src="/js/editor.js"></script>
    <script type="text/javascript" src="/js/writing.js"></script>

    {{-- <script src="https://d3js.org/d3.v3.min.js"></script> --}}
    {{-- <script type="text/javascript" src="/js/d3graph.js"></script> --}}


    <script type="text/javascript">
    var writing_id = "{{$writing->id}}";
    var csrf_token = "{{csrf_token()}}";

    InitMediumEditor();
    $(document).mouseup(function (e){
        var addbar = $('.editor-addbar');
        if (!addbar.is(e.target) // if the target of the click isn't the container...
            && addbar.has(e.target).length === 0
            && !$('.editable').is(e.target)
            && $('.editable').has(e.target).length === 0) // ... nor a descendant of the container
        {
            addbar.hide();
        }
        var arrActiveArr = document.getElementById('w-content-cont').getElementsByClassName('split-arrow-active');

        if (arrActiveArr.length != 0 &&
            findParentBySelector(e.target, '.popup__drpdwn-inner[style="display: block;"]') == null &&
            findParentBySelector(e.target, '.split-bottom-arrow') == null &&
            !e.target.className.includes('split-bottom-arrow') &&
            !e.target.className.includes('split-next_inpt')
          ) {
          var ppuInnerArr = document.getElementById('w-content-cont').getElementsByClassName('popup__drpdwn-inner');
          if (ppuInnerArr.length != 0) {
            for (var a = 0; a < arrActiveArr.length; a++) {
              arrActiveArr[a].classList.remove('split-arrow-active');
            }
          }
        }
    });



    $(document).mousedown(function (e){
      if (findParentBySelector(e.target, '.editable') == null &&
        findParentBySelector(e.target, '.writing-cover') == null) {
        var lastFcsedElem = document.getElementsByClassName('w-inner-fc')[0];
        if (typeof lastFcsedElem != 'undefined') {
          if (findParentBySelector(e.target, '#editor-framebar') == null && e.target != document.getElementById('editor-framebar')) {
            lastFcsedElem.classList.remove('w-inner-fc');
            $('#editor-framebar').hide();
          }else {
            FrameBarAction(e);
          }
        }
      }

      if (!isSvg(e.target) && (findParentBySelector(e.target, '.writing-cover') != null ||
          e.target.className.includes('writing-cover'))) {
        if (e.target.className.includes('writing-cover')) {
          var el = e.target.querySelector('[alt="cover photo"]');
        }else {
          var el = e.target;
        }

        var lastFcsedElem = document.getElementsByClassName('w-inner-fc')[0];
        if (typeof lastFcsedElem != 'undefined' && lastFcsedElem != el) {
          lastFcsedElem.classList.remove('w-inner-fc');
        }

        if (!el.className.includes('w-inner-fc')) {
          if (el.className.includes('w-innerf-cont')) {
            el = el.children[0];
          }
          postionFrameBar(el);
          removeTxtSelection();
          el.className += ' w-inner-fc';
        }
      }
    });


    window.addEventListener('resize', function(){
      var prevTopCont = document.getElementById('w-prev-top-cont');
      var prevTop = document.getElementById('w-prev-top');
      if (prevTopCont.clientHeight != prevTop.clientHeight) {
        prevTopCont.style.height = prevTop.clientHeight+'px';
      }
    });




    window.onmousewheel = function(e){
      if (e.deltaY < 0 && $(window).scrollTop() == 0) {
        var ltstSec = document.getElementsByClassName('w-section-cont')[0];
        var prevTopElem = document.getElementById('w-prev-top');
        if (ltstSec.getAttribute('isfrst') == 0 &&
            $('#w-empty-global')[0].style.display != 'block' &&
            !prevTopElem.className.includes('prev-visible')) {
          var prevContElem = ltstSec.getElementsByClassName('w-prev-sections-cont')[0];

          for (var c = 0; c < prevContElem.children.length; c++) {
            document.getElementById('w-prev-top').append(prevContElem.children[c].cloneNode(true));
          }

          prevTopElem.className += ' prev-visible';
          hideAllFloatingBars();

          $('#w-prev-top').animate({marginTop: '0px'}, {queue: false, duration: 400});
          $('#w-prev-top-cont').animate({height: prevTopElem.offsetHeight+"px", marginTop: '16px'}, {queue: false, duration: 400});
        }
      }
    }



    window.addEventListener("keydown",function(e){
      lastFcsedElem = document.getElementsByClassName('w-inner-fc')[0];
      if (e.keyCode == 40 || e.keyCode == 38) {
        carrPos = getCaretPos(true);
      }else {
        carrPos = getCaretPos();
      }


      var fxsedEditors = document.querySelectorAll('[data-medium-focused="true"]'),
          toolFromACT = document.getElementsByClassName('toolbar-form-active');

      if (typeof lastFcsedElem != 'undefined' && lastFcsedElem.hasAttribute('disable-lstfsced')) {
        lastFcsedElem = undefined;
      }

      if (typeof lastFcsedElem != 'undefined' &&
        findParentBySelector(lastFcsedElem, '.w-article-head') != null &&
        (e.keyCode == 8 || e.keyCode == 46)) {
          findParentBySelector(lastFcsedElem, '.writing-cover').remove();
          updateCoverImg(null, null, null, true);//delete
          e.preventDefault();
          hideAllFloatingBars();
      }

      if ((typeof lastFcsedElem != 'undefined' || document.querySelectorAll('[w-fcsed-near]').length) && carrPos != null) {
        if ((e.keyCode == 40 && document.querySelectorAll('[w-fcsed-near="1"]').length && carrPos.bottom) ||
            (e.keyCode == 38 && document.querySelectorAll('[w-fcsed-near="-1"]').length && carrPos.top) ||
            (e.keyCode == 46 && carrPos.right) || (e.keyCode == 8 && carrPos.left)){
          e.preventDefault();
          hideAllFloatingBars();
        }
      }



      if (fxsedEditors.length == 1) {
        if (toolFromACT.length != 0) {
          return 0;
        }
        if (typeof lastFcsedElem != 'undefined') {
          handleEditorFocus(fxsedEditors[0], e.keyCode, lastFcsedElem, carrPos);
        }else {
          handleEditorFocus(fxsedEditors[0], e.keyCode, null, carrPos);
        }
        return 0;
      }

      var selection
      if (window.getSelection){
        selection = window.getSelection();
      }else if (document.selection && document.selection.type != "Control"){
        selection = document.selection;
      }

      if (selection.anchorNode != null) {
        var range = selection.getRangeAt(0);
        var caretText = range.startOffset;
        var currTextNode = selection.anchorNode;
      }

      if (e.target.className.includes('writing-title')) {
        var currNode = e.target;
        var caretText = currNode.selectionStart;


        if (e.keyCode === 13) {
          e.preventDefault();
        }else if (e.keyCode === 8 || e.keyCode === 46) {
          var prev2Character = currNode.value[caretText-2];
          var next2Character = currNode.value[caretText+1];

          var prevCharacter = currNode.value[caretText-1];
          var nextCharacter = currNode.value[caretText];

          if (e.keyCode === 8 && typeof prev2Character != 'undefined' && prev2Character.trim() == '' && nextCharacter.trim() == '') {
            currNode.setSelectionRange(caretText-2, caretText);
          }

          if (e.keyCode === 46 && typeof next2Character != 'undefined' && next2Character.trim() == '' && prevCharacter.trim() == '') {
            currNode.setSelectionRange(caretText, caretText+2);
          }
        }else if (e.keyCode === 32) {
          var prevCharacter = currNode.value[caretText-1];
          var nextCharacter = currNode.value[caretText];

          if ((typeof prevCharacter == 'undefined' || prevCharacter.trim() == '') ||
              (typeof nextCharacter != 'undefined' && nextCharacter.trim() == '')) {
            e.preventDefault();
          }

          if (typeof nextCharacter != 'undefined' && nextCharacter.trim() == '') {
            currNode.setSelectionRange(caretText+1, caretText+1);
          }
        }
      }

      if (e.target.className.includes('split-item__title')) {
        if (e.key == '{' || e.key == '}') {
          e.preventDefault();
          return;
        }
        var brTags = e.target.querySelectorAll('br');
        if (e.keyCode !== 8 && e.keyCode !== 46) {
          if (e.target.innerText.length >= 100) {
            e.preventDefault();
            return;
          }
        }
        prevDoubleSpace(e, currTextNode);

        if (e.keyCode === 13) {
          e.preventDefault();
          if (brTags.length < 1) {
            if (carrPos.right || e.target.innerText.length <= carrPos.position) {
              document.execCommand('insertHTML', false, '<br><br>');
            }else {
              document.execCommand('insertHTML', false, '<br>');
            }
          }
        }else if (e.keyCode === 8 || e.keyCode === 46) {
          if (e.target.innerText.length <= carrPos.position) {
            for (var b = 0; b < brTags.length; b++) {
              brTags[b].remove();
              e.preventDefault();
            }
          }
        }
        splitsRowStyling(findParentBySelector(e.target, '.split-inner-list'));
      }

      if (e.target.className.includes('section-title')) {
        if (e.keyCode === 13) {
          e.preventDefault();
        }

        if (e.keyCode !== 8 && e.keyCode !== 46) {
          if (e.target.innerText.length >= 50) {
            e.preventDefault();
            return;
          }
        }

        prevDoubleSpace(e, currTextNode);
        saveSectTitle(e.target);
      }


      if (e.target.id == 'w-desc') {
        if (e.keyCode === 13) {
          e.preventDefault();
        }

        prevDoubleSpace(e, e.target);
      }
    },false);



    window.addEventListener("paste",function(e){
      if (e.target.className.includes('split-item__title') ||
          e.target.className.includes('writing-title') ||
          e.target.className.includes('section-title') ||
          e.target.id == 'w-desc') {
        e.preventDefault();
        var toInsert = DOMPurify.sanitize(e.clipboardData.getData('text/plain'));
        document.execCommand("insertHTML", false, toInsert.replace(/\s+/g, ' '));
      }

      if (e.target.id == 'w-mtags') {
        e.preventDefault();
      }
    },false);


    window.addEventListener("drag", function(e){
      e = e || event;
      e.preventDefault();
      e.stopPropagation();

      var addIMGlineElem = document.getElementById('editor-addimg__line');
      var currDragInner = e.target;
      if (currDragInner.className.includes('prog-load-elem')) {
        currDragInner = findParentBySelector(currDragInner, 'figure').getElementsByClassName("w-innerf")[0];
      }
      var currDrag = e.target;
      while (currDrag.nodeName != 'FIGURE' && currDrag.nodeName != 'BODY') {
        currDrag = currDrag.parentNode;
      }

      if (currDrag.nodeName != 'BODY') {
        hideAllFloatingBars();
        var winnFcsArr = document.getElementsByClassName('w-inner-fc');
        if (winnFcsArr.length && winnFcsArr[0] != currDragInner){
          winnFcsArr[0].classList.remove('w-inner-fc');
        }

        if (!currDragInner.className.includes('w-inner-fc')) {
          currDragInner.className += ' w-inner-fc';
        }

        addIMGlineElem.setAttribute("data-curr-drag", currDrag.id);
      }
    },true);



    var dropTimerVar;
    window.addEventListener("dragover", function(e){
      e = e || event;
      e.preventDefault();
      e.stopPropagation();

      var addIMGlineElem = document.getElementById('editor-addimg__line');
      var dt = e.dataTransfer;

      if ((dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') != -1 : dt.types.contains('Files'))) ||
          (addIMGlineElem.getAttribute("data-curr-drag") != null) ) {
        var elemToRef = e.target;
        if (findParentBySelector(elemToRef, '.editable') != null) {
          while (BanedEditorWrapersWimg.includes(elemToRef.tagName)) {
            var elemToRef = elemToRef.parentNode;
          }

          if (e.pageY < getOffsetTop(elemToRef, false) + elemToRef.clientHeight/2) { //above
            addBarPosition(elemToRef);
          }else {//under
            var elmNxtSbl = elemToRef.nextElementSibling;
            if (elmNxtSbl != null) {
              addIMGlineElem.style.top = getOffsetTop(elmNxtSbl)+'px'; //position addimg bar
              addIMGlineElem.setAttribute("last-pos", elemToRef.id+' down');
            }else {
              addBarPosition(elemToRef, 'down');
            }
          }
        }else {
          var allEditorsElem = document.getElementsByClassName('w-section-cont');
          for (var i=0; allEditorsElem.length > i; i++) {
            if (getOffsetTop(allEditorsElem[i]) < e.pageY) {
              var domElem = allEditorsElem[i];
            }
          }
          if (typeof domElem == 'undefined') {
            var domElem = allEditorsElem[0];
          }

          var editorElem = domElem.getElementsByClassName('editable')[0];

          if (e.pageY < getOffsetTop(editorElem, false) || e.pageY > getOffsetTop(editorElem, false) + editorElem.offsetHeight) {
            if (domElem.getAttribute('isfrst') && e.pageY < getOffsetTop(domElem, false)) {
              var titinpt = document.getElementById('Vtitle_inpt');
              var autTop = document.getElementById('w-author-top');
              if (e.pageY < (getOffsetTop(autTop, false)+ (autTop.offsetHeight/2))) {
                var cov_pos = 'top';
              }else if (e.pageY < (getOffsetTop(titinpt, false) + (titinpt.offsetHeight/2))) {
                var cov_pos = 'mid';
              }else{
                var cov_pos = 'down';
              }
              addBarPosition('cover', cov_pos);
            }else {
              if (editorElem.children.length) {
                if (e.pageY > getOffsetTop(editorElem, false) + editorElem.clientHeight) {
                  var elemToRef = editorElem.children[editorElem.children.length-1];
                  addBarPosition(elemToRef, 'down');
                  removeBarAnimPad();
                }else {
                  var elemToRef = editorElem.children[0];
                  addBarPosition(elemToRef);
                }
              }else {
                addIMGlineElem.style.top = getOffsetTop(editorElem)+'px'; //position addimg bar
                addIMGlineElem.setAttribute("last-pos", domElem.id+' editor');
              }
            }
          }
        }



        if (addIMGlineElem.getAttribute("data-curr-drag") != null) {
          var lposAtrr = addIMGlineElem.getAttribute("last-pos");
          var currDid = addIMGlineElem.getAttribute("data-curr-drag");
          if (lposAtrr != null) {
            var elemToRefFbar = document.getElementById(lposAtrr.split(' ')[0]);
            if (lposAtrr.split(' ').length == 2) {
              var lstPos = lposAtrr.split(' ')[1];
            }else {
              var lstPos = null;
            }
          }

          if (lposAtrr == null || lposAtrr == '' || currDid == elemToRefFbar.id ||
              (lstPos == 'down' && elemToRefFbar.nextElementSibling && elemToRefFbar.nextElementSibling.id == currDid) ||
              (lstPos == null && elemToRefFbar.previousElementSibling && elemToRefFbar.previousElementSibling.id == currDid)) {
            hideAddBar();
          }else {
            showAddBar();
          }
        }else {
          showAddBar();
        }
      }

      clearTimeout(dropTimerVar);
      dropTimerVar = setTimeout( function() {
        hideAddBar();
      }, 400);

    },true);




    window.addEventListener("drop",function(e){
      e.preventDefault();
      $('#editor-addimg__line').hide();
      var dt = e.dataTransfer;

      var addImgBar = document.getElementById('editor-addimg__line');
      var lstposId = addImgBar.getAttribute("last-pos");
      if (lstposId != null) {
        elemToRef = document.getElementById(lstposId.split(' ')[0]);
        lstPos1 = lstposId.split(' ')[1];
      }else {
        elemToRef = null;
        lstPos1 = null;
      }


      if (dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') != -1 : dt.types.contains('Files')) && elemToRef != null) {
        uploadImageFile(dt, elemToRef, lstPos1);
      }else if (addImgBar.getAttribute("data-curr-drag") != null && elemToRef != null &&
       addImgBar.getAttribute("data-curr-drag")) {
        var currDragId = addImgBar.getAttribute("data-curr-drag"),
            currDragElem = document.getElementById(currDragId),
            currInnerFc = document.getElementsByClassName('w-inner-fc')[0],
            editable = findParentBySelector(elemToRef, '.editable'),
            ifCover = findParentBySelector(elemToRef, '#w-top') != null,
            wasCover = findParentBySelector(currDragElem, '#w-top') != null;
        if (ifCover) {
          if (currInnerFc.naturalWidth < 400 || currInnerFc.naturalHeight < 350 ) {
            alert('This image is too small for cover');
            return false;
          }
        }


        var unddArray = [];
        if (currDragElem.nextElementSibling) {
          unddArray.push(currDragElem.nextElementSibling);
        }
        if (editable == null) {
          editable = findParentBySelector(currDragElem, '.editable');
        }

        addIntoEditor(elemToRef, lstPos1, currDragElem);
        postionFrameBar(currDragElem.getElementsByClassName('w-inner-fc')[0]);
        if (editable == null) {
          editable = findParentBySelector(currDragElem, '.editable');
        }


        if (!ifCover) {
          if (currDragElem.nextElementSibling) {
            unddArray.push(currDragElem.nextElementSibling);
          }
          unddArray.push(currDragElem);
          if (wasCover) {
            updateCoverImg(null, null, null, false);//delete
            currInnerFc.className += ' w-innerf-changed w-innerf-wascover';
          }
          trigerSave(editable, unddArray);
        }else {
          var covSize = findParentBySelector(currInnerFc, '.w-innerf-cont').getAttribute('data-size');

          if (elemToRef.id == 'w-author-top') {
            var position = 'top';
            currDragElem.id = 'w-cover__top';
          }else if (elemToRef.id == 'Vtitle_inpt' && lstPos1 != 'down') {
            var position = 'mid';
            currDragElem.id = 'w-cover__mid';
          }else {
            var position = 'down';
            currDragElem.id = 'w-cover__down';
          }

          updateCoverImg(currInnerFc.getAttribute('data-file-id'), covSize, position, true);
          if (!wasCover) {
            unddArray.push(currDragId);
            trigerSave(editable, unddArray);
            if (editable.children.length == 0) {
              var newNodeP = document.createElement('p');
              newNodeP.id = randStr(6);
              newNodeP.innerHTML = "<br>";
              editable.append(newNodeP);
            }
          }
        }
      }
      addImgBar.removeAttribute("data-curr-drag");

      var ibAnim = document.getElementsByClassName('img-bar__animation');
      if (ibAnim.length != 0) {
        ibAnim[0].classList.remove('img-bar__animation');
      }
    },true);





      $('.editor-addbar button').click(function() {
        switch ($(this)[0].id) {
          case 'addbar-split':
            $('.editable').each(function(index, el) {
              $(el).removeAttr('style');
            });
            var lastEditable = $('.editor-addbar').attr('data-lasteditable');
            var lstSectionElem = findParentBySelector(document.querySelectorAll('[medium-editor-index="'+lastEditable+'"]')[0], '.w-section-cont');
            lstSectionElem.getElementsByClassName('split-cont')[0].style.display = 'block';
            break;
          case 'addbar-img':
            $('#content-file_uplinpt').trigger('click');
            break;
          case 'addbar-link':

            break;
        }

        $('.editor-addbar').hide();
      });


      $('#content-file_uplinpt').on('change', function(e) {
        var etoRef = document.getElementById($('.editor-addbar')[0].getAttribute('data-last-ref'));
        uploadImageFile($(this)[0], etoRef, null, etoRef.id);
      });




      var nxtDrpTimer;
      //vue
      var mixin_split = {
        methods: {
          addSplitSingle: function (letter) {
            this.SplitRange = this.$el.getElementsByClassName("split-inner-list")[0].children.length -1;
            splitsRowStyling(this.$el.getElementsByClassName("split-inner-list")[0]);

            if (this.SplitRange < splitLabels.length) {
              var spllbl = splitLabels[this.SplitRange];
              this.splitLabels.push(spllbl);
              this.SplitRange += 1;


              var newsplitObj = new Object();
              newsplitObj.position = this.SplitRange;
              newsplitObj.section_id = findParentBySelector(this.$el, '.w-section-cont').getAttribute('id');

              $.ajax({
                method: 'POST',
                url: writing_id+'/save',
                dataType: 'json',
                data: {
                    _token: csrf_token,
                    newsplit_item: newsplitObj
                }
              })
              .done(function(data) {
                if (!data.success) {
                  saveProgress('error');
                }else {
                  $('.split-inner-list').children().each(function(index, el) {
                    if (!$(el).hasClass('split-empty') && typeof $(el).attr("id") == 'undefined') {
                      el.id = data.new_id;
                      return false;
                    }
                  });
                  saveProgress('end');
                }
              })
              .fail(function() {
                saveProgress('error');
              });
            }

            if (this.$el.getElementsByClassName('split-item').length > 5) {
              findParentBySelector(this.$el, '.w-section-cont').getElementsByClassName("split-empty")[0].style.display = 'none';
            }
          },
          saveSplitTrig: function(txtelem){
            var listItem = findParentBySelector(txtelem.srcElement, '.split-item');
            var listItemTitle = listItem.getElementsByClassName('split-item__title')[0];
            var valSucc = true;
            if (listItem.id != "") {
              if (listItemTitle.textContent == "") {
                if (!listItemTitle.className.includes('item__title-plc-empty')) {
                  listItemTitle.className += ' item__title-plc-empty';
                }
              }else {
                if (!listItems.includes(listItem)) {
                  listItems.push(listItem);
                }
                clearTimeout(titleSaveTimer);
                titleSaveTimer = setTimeout( function() {
                  saveSplit(listItems);
                  listItems = [];
                }, 2000);
              }
            }else {
              saveProgress('error');
            }
          },
          removeSplitItmTrig: function(e){
            splitDrasticQury(e.srcElement, 'remove');
          },
          removeSplitItm: function(el){
            var listItem = findParentBySelector(el, '.split-item');
            saveProgress('start');
            splitsRowStyling(findParentBySelector(el, '.split-inner-list'));

            var vuelsbels = this.splitLabels;
            var vueprstLbls = this.presentLabels;

            $.ajax({
              method: 'POST',
              url: writing_id+'/save',
              dataType: 'json',
              data: {
                  _token: csrf_token,
                  remove_split: listItem.id
              }
            })
            .done(function(data) {
              if (!data.success) {
                saveProgress('error');
              }else {
                var litms = findParentBySelector(el, '.split-inner-list').children;

                for (var rmspi = 0; rmspi < litms.length-1; rmspi++) {
                  if (litms[rmspi].id == listItem.id) {
                    if (rmspi <= vueprstLbls.length && this.presentLabels.includes(this.splitLabels[rmspi])) {
                      this.presentLabels = splitLabels.slice(0, vueprstLbls.length-1);
                    }
                    break;
                  }
                }

                modifySplitDrpdwn(findParentBySelector(listItem, '.w-section-cont').id);
                this.splitLabels.splice(-1,1);

                listItem.remove();
                if (litms.length == 1) {
                  this.$el.style.display = 'none';
                }

                for (var spltrlbl = 0; spltrlbl < litms.length -1; spltrlbl++) {
                  litms[spltrlbl].getElementsByClassName('split-item__label')[0].textContent = splitLabels[spltrlbl];
                }
                document.getElementsByClassName('split-empty')[0].style.display = 'flex';
                saveProgress('end');
              }
            }.bind(this))
            .fail(function() {
              saveProgress('error');
            })
          },
          splitNextDrpdwnArrow: function(inptelem){
            var inptDOM = inptelem.srcElement;
            var spCont = findParentBySelector(inptDOM, '.split-item');
            var arrowCont = findParentBySelector(inptDOM, '.split-bottom-arrow');
            if (arrowCont != null || inptDOM.className.includes('split-bottom-arrow')) {
              if (arrowCont != null) {
                var arrowElem = arrowCont;
              }else {
                var arrowElem = inptDOM;
              }

              if (arrowElem.className.includes('split-arrow-active')) {
                this.splitNextinptBlur(inptelem);
              }else {
                this.splitNextDrpdwn(inptelem);
              }
            }
          },
          splitNextDrpdwn: function(inptelem){
            var inptDOM = inptelem.srcElement,
                spCont = findParentBySelector(inptDOM, '.split-item'),
                spBottom = spCont.getElementsByClassName('split-bottom')[0],
                spCont = findParentBySelector(inptDOM, '.split-item'),
                splArrow = spBottom.getElementsByClassName('split-bottom-arrow')[0],
                sectCont = findParentBySelector(inptDOM, '.w-section-cont'),
                liulArr = sectCont.getElementsByClassName('drp-down__onechoice');

            for (var ulf = 0; ulf < liulArr.length; ulf++) {
              liulArr[ulf].innerHTML = '';
            }


            if (!splArrow.className.includes('split-arrow-active')) {
              splArrow.className += ' split-arrow-active';
            }

            // if (spCont.getAttribute('data-need-drpdwn')) {
              $.ajax({
                method: 'POST',
                url: writing_id+'/info',
                dataType: 'json',
                data: {
                  _token: csrf_token,
                  get_drpdwn: sectCont.id
                }
              })
              .done(function(data) {
                if (!data.success) {
                  saveProgress('error');
                }else {
                  var htmlArr = '';
                  for (var h = 0; h <  data.data.length; h++) {
                    htmlArr += data.data[h];
                  }

                  for (var ul = 0; ul < liulArr.length; ul++) {
                    lisArr = document.createElement("div");
                    lisArr.innerHTML = htmlArr;
                    while (lisArr.firstChild) {
                      liulArr[ul].append(lisArr.firstChild);
                    }
                  }
                }
              }.bind(this))
              .fail(function() {
                saveProgress('error');
              })
              // spCont.removeAttribute('data-need-drpdwn');
            // }

            var listItem = findParentBySelector(inptDOM, '.split-item');
            var lpb = spBottom.getElementsByTagName("popup-box-inner")[0];

            var everyLPB = document.getElementsByTagName('popup-box-inner');
            for (var lpbi = 0; lpbi < everyLPB.length; lpbi++) {
              if (everyLPB[lpbi] != lpb) {
                everyLPB[lpbi].style.display = "none";
              }
            }

            var splitdrpdwlis =  listItem.getElementsByTagName('li');
            for (var sid = 0; sid < splitdrpdwlis.length; sid++) {
              splitdrpdwlis[sid].style.display = "";
            }
            lpb.style.display = 'block';
          },
          splitShowNextDom: function(inptelem){
            var splitItmToRef= findParentBySelector(inptelem.srcElement, '.split-item'),
                nextWantedId = splitItmToRef.getElementsByClassName("split-next_inpt")[0].getAttribute('next-id');
            if (nextWantedId != null) {
              var sectContToRef = findParentBySelector(splitItmToRef, '.w-section-cont');
              if (sectContToRef.id != 'vapp_nwsec' && sectContToRef.id != nextWantedId && !getVisibleSectsIds().includes(nextWantedId)) {
                var presentSecDOM = document.getElementById('w-content-cont').getElementsByClassName('w-section-cont'),
                    afterToRef = false;
                for (var p = 0; p < presentSecDOM.length; p++) {
                  if (afterToRef) {
                    section2temp([presentSecDOM[p]]);
                  }
                  if (presentSecDOM[p] == sectContToRef) {
                    afterToRef = true;
                  }
                }
                changeSectionsDOM(nextWantedId, 'append');
              }
            }
          },
          showMore:function(el){
            var elem = findParentBySelector(el.srcElement, '.split-item');

            var ppup= elem.getElementsByClassName('split-popup-more')[0];
            if (window.getComputedStyle(ppup, null).getPropertyValue("display") != 'none') {
              ppup.style.display = 'none';
            }else {
              ppup.style.display = 'block';
            }
          },
          splitSearchDRPDWN: function(inptelem){
            var inptDOM = inptelem.srcElement;
            var listItem = findParentBySelector(inptDOM, '.split-item');
            var splitdrpdwlis = listItem.getElementsByTagName('li');
            sortDrpdwnList(inptDOM.value, splitdrpdwlis);
          },
          splitNextinptBlur: function(inpt){
            if (inpt.srcElement.getAttribute('next-id') == null) {
              inpt.srcElement.value = null;
            }else {
              inpt.srcElement.value = inpt.srcElement.getAttribute('next-title');
            }


            var spCont = findParentBySelector(inpt.srcElement, '.split-item');
            var splArrow = spCont.getElementsByClassName('split-bottom-arrow')[0];
            var splDrpdwnin = spCont.getElementsByClassName('split-bottom')[0].getElementsByClassName('popup__drpdwn-inner')[0];
            setTimeout( function() {
              if (splArrow.className.includes('split-arrow-active') && splDrpdwnin.style.display == 'none') {
                  splArrow.classList.remove('split-arrow-active');
              }
            }, 150);
          }
        },
        mounted: function() {
          this.SplitRange = this.$el.getElementsByClassName("split-inner-list")[0].children.length -1;

          for (var sprl = 0; sprl < this.SplitRange; sprl++) {
            this.presentLabels.push(splitLabels[sprl]);
            this.splitLabels.push(splitLabels[sprl]);
          }
        },
        updated: function () {
          autosize($('.txtarea-resize'));
        }
      }

      const splitLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
      @foreach ($sections as $section)
        let {{'vapp_split'.$section->id}} = new Vue({
          data: {
            splitLabels: [],
            presentLabels: [],
            SplitRange: 0
          },
          mixins: [mixin_split],
          el: '{{'#vapp_split'.$section->id}}'
        })
      @endforeach


      let vapp_nwsec = new Vue({
        el: '#vapp_nwsec',
        methods: {
          createSection: function(nwtitle){
            nwtitle = (typeof nwtitle === 'undefined') ? 'Section' : nwtitle;

            saveProgress('start');
            $.ajax({
              method: 'POST',
              url: writing_id+'/save',
              dataType: 'json',
              data: {
                  _token: csrf_token,
                  new_section: nwtitle
              }
            })
            .done(function(data) {
              if (!data.success) {
                saveProgress('error');
              }else {
                saveProgress('end');
                hideEmptyView();

                var newNode = document.createElement("div");
                newNode.innerHTML = data.sectionTemplate;
                while(newNode.firstChild) {
                    document.getElementById('vapp_nwsec').parentNode.insertBefore(newNode.firstChild, document.getElementById('vapp_nwsec'));
                }
                InitMediumEditor();

                new Vue({
                  data: {
                    splitLabels: [],
                    presentLabels: [],
                    SplitRange: 0
                  },
                  mixins: [mixin_split],
                  el: '#vapp_split'+data.section_id
                })
                modifySplitDrpdwn(0);
              }
            }.bind(this))
            .fail(function() {
              saveProgress('error');
            });
          }
        }
      });
    </script>
  </body>
</html>
