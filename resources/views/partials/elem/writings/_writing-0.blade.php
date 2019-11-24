@php
  if (empty($wr_type)) {
    $wr_type = null;
  }
@endphp
<li class="writing-elem_1-3">
  @if (!empty($writing->cover->path))
    <div class="writing-elem-cover">
      <a class="writing-elem-coverimg"
      href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}"
      style="background-image: url('{{$writing->cover->path}}')">
    </a>
  </div>
  <h3 class="text-over_elip f2_3 writing-elem-h">
    <a href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}">
      {{$writing->title or 'Untitled'}}
    </a>
  </h3>
  @else
    <div class="writing-elem-cover">
      <a href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}" class="writing-elem-coverimg">
        <h3 class="text-over_elip f2_3 writing-elem-cover-title">
          {{$writing->title or 'Untitled'}}
        </h3>
      </a>
    </div>
  @endif
  <div class="writing-elem-footer @if(!empty($noData)) noData @endif">
    @foreach ($writing->authors as $author)
      <div class="elem-footer-left">
        <span class="elem-footer-name f1_usrname_1">
          <a href="{{ asset($author->str_id) }}">{{ $author->nickname}}</a>
        </span>

        @if (empty($noData))
          <span class="elem-footer-sub f1_sysinf-0">{{$writing->published_at_parsed}}</span>
        @endif
      </div>
    @endforeach

    @if ($wr_type == 'authPublic')
      <div class="writinge-footer-more" data-has-popup>
        <button class="neutralize-btn writinge-more-btn">
          <svg viewBox="10 -10 5 25">
            <path d="M15,12.5c0,1.381-1.119,2.5-2.5,2.5c-1.38,0-2.5-1.119-2.5-2.5s1.12-2.5,2.5-2.5C13.881,10,15,11.119,15,12.5z"/>
            <path d="M15,2.5C15,3.881,13.881,5,12.5,5C11.12,5,10,3.881,10,2.5S11.12,0,12.5,0C13.881,0,15,1.119,15,2.5z"/>
            <path d="M15-7.5C15-6.119,13.881-5,12.5-5C11.12-5,10-6.119,10-7.5s1.12-2.5,2.5-2.5C13.881-10,15-8.881,15-7.5z"/>
          </svg>
        </button>

        <leck-popup-cont>
          <popup-box-inner class="split-popup-more">
            <ul class="drp-down__onechoice">
              <li class="inner-padding li__onhover"><a class="inner-padding li__inner-a" href="{{ asset('editor/'.$writing->str_id)}}">Edit</a></li>
            </ul>
          </popup-box-inner>
        </leck-popup-cont>
      </div>
    @endif
  </div>
</li>
