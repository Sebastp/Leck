@if (empty($sections))
  <section class="w-section-cont f_content_main">
      <p class="f1_sysinf-1 w-sect-emptycont">There was a problem loading this story</p>
  </section>
@else

  @foreach ($sections as $indx => $section)
    <section class="w-section-cont f_content_main" sec-id="{{$section->id}}" isfrst="{{$section->isFirst}}">
      @if (!$section->content->contentEmpty)
        <div class="w-content">
          @foreach ($section->content->innerHtml as $htmlElement)
            {!!$htmlElement!!}
          @endforeach
        </div>

        @if (!empty($section->splits) && count($section->splits))
          <div class="split-cont w-cont-mid">
            <ul class="w-split-inner-list">
              @foreach ($section->splits as $split)
                <li data-need-auth class="w-split-item split-li split-center @if($section->active_split == $split->id) w-split-item__pos @elseif($section->active_split != NULL) w-split-item__neg @endif" split-id="{{$split->id}}" @if($section->active_split == NULL) onclick="splitAction({{$split->id}})"@endif>
                  <span class="f2_sys-is split-item__label">{{$split->label}}</span>
                  @if (!empty($split->title))<p class="f3_title_0-5 split-item__title">{!!$split->title!!}</p>@endif
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      @else
        <p class="f1_sysinf-1 w-sect-emptycont">This section is empty</p>
      @endif

      <div class="w-section-secdiv">
        <svg viewBox="-6 0 35 6">
          <path d="M11.5,0C9.856,0,8.519,1.346,8.519,3c0,1.654,1.337,3,2.981,3c1.645,0,2.98-1.346,2.98-3C14.48,1.346,13.145,0,11.5,0z
             M26.02,0c-1.645,0-2.981,1.346-2.981,3c0,1.654,1.338,3,2.981,3C27.662,6,29,4.654,29,3C29,1.346,27.662,0,26.02,0z M-3.019,0
            C-4.663,0-6,1.346-6,3c0,1.654,1.337,3,2.981,3s2.981-1.346,2.981-3C-0.038,1.346-1.375,0-3.019,0z"/>
        </svg>
      </div>
    </section>
  @endforeach


@endif
