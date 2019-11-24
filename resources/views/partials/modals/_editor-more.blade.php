<div id="w-modal-more" class="w-modal-cont" modal-cont>
  <div class="w-modal-innercont">
    <div id="more-inner-cont" class="w-cont-mid w-modal-window">
      <div id="more-inner-content">
        <div class="inpt-undrline-cont" id="w-modal-more-title">
          <textarea undrline-onfcs placeholder="Title" maxlength="120" class="neutralize-txtarea needed-txtarea txtarea-resize writing-title f3_title_0" rows="1" spellcheck="false">{{$writing->title}}</textarea>

          <div class="inpt-undrline">
            <div id="w-desc-error" class="inpt-line__unfcsed inpt-line-negative"></div>
            <div class="inpt-line__fcsed inpt-line-neutral"></div>
            <div class="inpt-line__unfcsed"></div>
          </div>
        </div>



        <div class="inpt-undrline-cont">
          <textarea class="neutralize-txtarea needed-txtarea txtarea-resize f2_content_1" id="w-desc" undrline-onfcs rows="1" maxlength="300" placeholder="Description">{{$writing->description}}</textarea>
          <div class="inpt-undrline">
            <div id="w-desc-error" class="inpt-line__unfcsed inpt-line-negative"></div>
            <div class="inpt-line__fcsed inpt-line-neutral"></div>
            <div class="inpt-line__unfcsed"></div>
          </div>
          <p class="f2_info_0 w-more-counter"><span id="w-desc__counter">0</span>/300</p>
        </div>

        <div class="inpt-undrline-cont" id="w-more-tagcont">
          <popup-box-inner id="w-more-tagbox" class="pbi__1">
            <li class="li__onhover tagbox-li li__sizing" id="cloneRecomTag">
              <div class="tagbox-li-arguments" tag-verified="0">

              </div>
              <span class="tagbox-li-title f1_sysinf-3"></span>
              <span class="tagbox-li-linked f1_sysinf-0"></span>
            </li>

            <div class="popup-loader" popupLoad-show="">
              <div id="tagbox-load" class="w-load-crcl" popupLoad-loader>
                <div class="loader">
                  <svg class="circular-loader" viewBox="25 25 50 50" >
                    <circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#b8b9bc" stroke-width="5" />
                  </svg>
                </div>
              </div>

              <p class="f3_sub_msg" popupLoad-failMsg>Sorry, we couldn't do it</p>
            </div>
            <ul id="tagbox-reommList" popupLoad-content>

            </ul>
          </popup-box-inner>


          <div id="modal-tag-clone" class="modal-tag-elem">
            <span class="f1_sysinf-0 modal-tag-elemtxt"></span>
            <div class="tag-elem-close">
              <svg class="w-x__1" viewBox="2.421 1.115 12.021 12.02">
                <path d="M13.287,13.135L2.421,2.271l1.155-1.156l10.864,10.866L13.287,13.135z"/>
                <path d="M2.421,11.981L13.285,1.116l1.156,1.155L3.577,13.135L2.421,11.981z"/>
              </svg>
            </div>
          </div>
          <div id="modal-tags-top">
            @if (!empty($writing->tags))
              @foreach ($writing->tags as $tagTitle)
                <div class="modal-tag-elem">
                  <span class="f1_sysinf-0 modal-tag-elemtxt">{{$tagTitle}}</span>
                  <div class="tag-elem-close">
                    <svg class="w-x__1" viewBox="2.421 1.115 12.021 12.02">
                      <path d="M13.287,13.135L2.421,2.271l1.155-1.156l10.864,10.866L13.287,13.135z"/>
                      <path d="M2.421,11.981L13.285,1.116l1.156,1.155L3.577,13.135L2.421,11.981z"/>
                    </svg>
                  </div>
                </div>
              @endforeach
            @endif
            <textarea class="neutralize-txtarea txtarea-resize f2_sys-is" id="w-mtags" maxlength="25" undrline-onfcs rows="1" placeholder="Add tags separated by commas"></textarea>
          </div>
          <div class="inpt-undrline">
            <div id="w-mtags-error" class="inpt-line__unfcsed inpt-line-negative"></div>
            <div class="inpt-line__fcsed inpt-line-neutral"></div>
            <div class="inpt-line__unfcsed"></div>
          </div>
          <p class="f2_info_0 w-more-counter"><span id="w-tags__counter">0</span>/6</p>
        </div>
      </div>

      <div class="w-modal-btns">
        <button type="button" class="neutralize-btn btn-empty-neutral modal-close">Close</button>
        <button type="button" class="neutralize-btn btn-empty-positiv" id="more-publish-btn" onclick="if(!document.getElementById('more-publish-btn').getAttribute('disable')) publishWriting();">Publish</button>
      </div>
    </div>
  </div>
  <div class="modal-bckground"></div>
</div>
