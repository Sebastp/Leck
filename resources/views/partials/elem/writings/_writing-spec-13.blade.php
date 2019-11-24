<li class="writing-elem_1-3">
  <div class="writing-elem-inner">
    @if (!empty($writing->cover->path))
      <div class="writing-elem-cover">
        <svg viewBox="0 0 322 22.7" class="writing-cover-shape">
        <polygon fill="#FFFFFF" points="0,22.7 322,22.7 0,0 "/>
        </svg>
        <a class="writing-elem-coverimg"
        href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}"
        style="background-image: url('{{$writing->cover->path}}')">
      </a>
    </div>
    <div class="writing-elem-bottom">
      <p class="f1_anach_sml writing-label__anch">#{{$index}} {{$bestin_obj->title}}</p>
      <h3 class="text-over_elip f2_3 writing-elem-title">
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
      <div class="writing-elem-bottom">
        <p class="f1_anach_sml writing-label__anch">#{{$index}} {{$bestin_obj->title}}</p>
      @endif
      <p class="writing-elem-desc text-over_elip">{{$writing->description}}</p>

      <div class="writing-elem-footer">
        <div class="elem-footer-left">
          @foreach ($writing->authors as $author)
            <span class="elem-footer-name f1_usrname_1">
              <a href="{{ asset($author->str_id) }}">{{ $author->nickname}}</a>
            </span>
          @endforeach
          <span class="elem-footer-sub f1_sysinf-0">{{$writing->published_at_parsed}}</span>
        </div>
      </div>
    </div>
  </div>
</li>
