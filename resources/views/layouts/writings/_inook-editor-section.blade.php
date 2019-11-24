@foreach ($sections as $section)
  <section class="w-section-cont" id="{{$section->id}}" isfrst="{{$section->isFirst}}">
    @if (!$section->isFirst)
      <ul class="w-prev-sections-cont">
        @if ($section->prev_paths)
          @foreach ($section->prev_paths as $prev_sect)
            <li class="w-top-sect-min btn-action__2" sect-id="{{$prev_sect->id}}" onclick="changeSectionsDOM({{$prev_sect->id}}, 'prepend')">{{$prev_sect->title}}</li>
          @endforeach
        @else
          <p class="f3_sub_title prev-sect-empty">This section doesn't have path</p>
        @endif
      </ul>
    @endif
    <div class="w-section-header w-cont-mid">
      <div class="w-section-header__top">
        <p class="f2_heading-info_dark section-title" contenteditable="true" spellcheck="false">{{$section->title or $section->id}}</p>
        <p class="f2_heading-info_dark section-title__plchlder">Section nr {{$section->id}}</p>
        <div class="sect-edit-cont">
          <div class="sect-close" onclick="closeSection({{$section->id}})">
            <svg class="w-x__0" viewBox="6.712 8.781 10.611 10.612">
              <path d="M16.659,8.781l-4.643,4.643L7.375,8.781L6.712,9.443l4.643,4.643l-4.643,4.643l0.663,0.662l4.643-4.642l4.643,4.644
              l0.662-0.664l-4.643-4.643l4.644-4.643"/>
            </svg>
          </div>
        </div>
      </div>
      <div class="w-divline"></div>
    </div>

    <div class="editable f_content_main w-content" spellcheck="true" role="textbox" data-placeholder="Tell the story" @if (empty($section->splits)) style="min-height: calc(70vh - 262px)" @endif>
      @if (!empty($section->content->innerHtml))
        @foreach ($section->content->innerHtml as $htmlElement)
          {!!$htmlElement!!}
        @endforeach
      @endif
    </div>


    <div class="split-cont w-cont-mid" id="{{'vapp_split'.$section->id}}" @if (empty($section->splits) || !count($section->splits))style="display: none"@endif>
      <ul class="split-inner-list">
        @if (!empty($section->splits))
          @foreach ($section->splits as $indx => $split)
              <li class="split-item split-li" id="{{$split->id}}">
                <div class="split-rightup">
                  <button class="neutralize-btn split-rightup-btn" @click="showMore">
                    <svg viewBox="10 -10 5 25">
                      <path d="M15,12.5c0,1.381-1.119,2.5-2.5,2.5c-1.38,0-2.5-1.119-2.5-2.5s1.12-2.5,2.5-2.5C13.881,10,15,11.119,15,12.5z"/>
                      <path d="M15,2.5C15,3.881,13.881,5,12.5,5C11.12,5,10,3.881,10,2.5S11.12,0,12.5,0C13.881,0,15,1.119,15,2.5z"/>
                      <path d="M15-7.5C15-6.119,13.881-5,12.5-5C11.12-5,10-6.119,10-7.5s1.12-2.5,2.5-2.5C13.881-10,15-8.881,15-7.5z"/>
                    </svg>
                  </button>

                  <leck-popup-cont>
                    <popup-box-inner class="split-popup-more">
                      <ul>
                        <li @click="removeSplitItmTrig">Delete</li>
                        {{-- <li class="inner-padding">
                          <label for="chck1" class="chckbx__lable-cont inner-padding">
                            <div class="inpt-chckbx__flat">
                              <input id="chck1" type="checkbox" class="chckbx__flat__inpt">
                              <label class="chckbx__cont" for="chck1">
                                <div class="chckbx__checkmark"></div>
                              </label>
                            </div>
                            <span class="chckbx__desc">
                              Returnable
                            </span>
                          </label>
                        </li> --}}
                      </ul>
                    </popup-box-inner>
                  </leck-popup-cont>
                </div>
                <div class="split-center">
                  <span class="f2_sys-is split-item__label">{{$split->label}}</span>
                  <p @keyup="saveSplitTrig" contenteditable="true" data-placeholder="Title" class="f3_title_0-5 split-item__title ce-placeholder" spellcheck="false">{!!$split->title!!}</p>
                </div>

                <div class="split-bottom f3_sub_title">
                  <div class="split-bottom-dot" @click="splitShowNextDom"></div>
                  <input @focus="splitNextDrpdwn" @blur="splitNextinptBlur" @keyup="splitSearchDRPDWN" placeholder="Add next section" type="text" @if (!empty($split->next_id)) value="{{ $split->next_title ?: $split->next_id}}" next-id="{{ $split->next_id ?: NULL}}" next-title="{{$split->next_title ?: $split->next_id}}"@endif class="neutralize-txtarea split-next_inpt f2_content_2" data-has-popup>
                  <div class="split-bottom-arrow" @click="splitNextDrpdwnArrow">
                    <svg viewBox="6.404 10.323 15.094 8.488">
                      <path d="M21.498,11.264"/>
                      <path d="M13.95,18.812h0.002l7.546-7.548l-0.946-0.94l-6.603,6.604l-6.602-6.604l-0.943,0.94L13.95,18.812z"/>
                    </svg>
                  </div>
                  <leck-popup-cont class="popup__drpdwn-cont">
                    <popup-box-inner class="scrlbar-1 popup__drpdwn-inner">
                      <ul class="popup__drpdwn-items drp-down__onechoice">
                        @foreach ($section->posible_nexts as $next)
                          @include('partials.elem._w-split-drpdwn-item', ['next' => $next])
                        @endforeach
                      </ul>
                    </popup-box-inner>
                  </leck-popup-cont>
                </div>
              </li>
          @endforeach
        @endif


        <li class="split-item split-li" v-for="label in splitLabels" v-if="!presentLabels.includes(label)">
          <div class="split-rightup" data-has-popup @click="showMore">
            <button class="neutralize-btn split-rightup-btn">
              <svg viewBox="10 -10 5 25">
                <path d="M15,12.5c0,1.381-1.119,2.5-2.5,2.5c-1.38,0-2.5-1.119-2.5-2.5s1.12-2.5,2.5-2.5C13.881,10,15,11.119,15,12.5z"/>
                <path d="M15,2.5C15,3.881,13.881,5,12.5,5C11.12,5,10,3.881,10,2.5S11.12,0,12.5,0C13.881,0,15,1.119,15,2.5z"/>
                <path d="M15-7.5C15-6.119,13.881-5,12.5-5C11.12-5,10-6.119,10-7.5s1.12-2.5,2.5-2.5C13.881-10,15-8.881,15-7.5z"/>
              </svg>
            </button>

            <leck-popup-cont>
              <popup-box-inner class="split-popup-more">
                <ul class="drp-down__onechoice">
                  <li @click="removeSplitItmTrig">Delete</li>
                  {{-- <li><input type="checkbox">Returnable</li> --}}
                </ul>
              </popup-box-inner>
            </leck-popup-cont>
          </div>
          <div class="split-center">
            <span class="f2_sys-is split-item__label">@{{ label }}</span>
            <p @keyup="saveSplitTrig" contenteditable="true" data-placeholder="Title" class="f3_title_0-5 split-item__title ce-placeholder" spellcheck="false"></p>
          </div>

          <div class="split-bottom f3_sub_title">
            <div class="split-bottom-dot" @click="splitShowNextDom"></div>
            <input @focus="splitNextDrpdwn" @blur="splitNextinptBlur" @keyup="splitSearchDRPDWN" placeholder="Add next section" type="text" class="neutralize-txtarea split-next_inpt f2_content_2" data-has-popup>
            <div class="split-bottom-arrow" @click="splitNextDrpdwnArrow">
              <svg viewBox="6.404 10.323 15.094 8.488">
                <path d="M21.498,11.264"/>
                <path d="M13.95,18.812h0.002l7.546-7.548l-0.946-0.94l-6.603,6.604l-6.602-6.604l-0.943,0.94L13.95,18.812z"/>
              </svg>
            </div>
            <leck-popup-cont class="popup__drpdwn-cont">
              <popup-box-inner class="scrlbar-1 popup__drpdwn-inner">
                <ul class="popup__drpdwn-items drp-down__onechoice">
                  @if (!empty($section->posible_nexts))
                    @foreach ($section->posible_nexts as $next)
                      @include('partials.elem._w-split-drpdwn-item', ['next' => $next])
                    @endforeach
                  @endif
                </ul>
              </popup-box-inner>
            </leck-popup-cont>
          </div>
        </li>



        <li class="split-empty split-item split-li" @click="addSplitSingle" @if(count($section->splits)==6)style="display: none"@endif>
          <svg viewBox="0 0 54 54">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M26.115,0h2.271v54.5h-2.271V0z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0,26.115h54.5v2.271H0V26.115z"/>
          </svg>
          <span class="f3_sub_title">Add Split</span>
        </li>
      </ul>
    </div>
  </section>
@endforeach
