<li class="writing-elem_half @if (empty($writing->cover->path))writing-elem_half__nocover @endif">
  <div class="writing-elem-inner">
    @if (!empty($writing->cover->path))
      <div class="writing-elem-cover">
        <a class="writing-elem-coverimg"
        href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}"
        style="background-image: url('{{$writing->cover->path}}?s=750')">
        </a>
      </div>
      <div class="writing-elem-bottom">
        @if (!empty($writing->label))
          <p class="f1_sysinf-0-25 writing-elem-label">{{$writing->label}}</p>
        @endif
        <a href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}">
          <h3 class="text-over_elip f1_sys-sect_1 writing-elem-h">
              {{$writing->title or 'Untitled'}}
          </h3>
        </a>
    @else
      <a href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}">
        <div class="writing-elem-cover">
          <h3 class="text-over_elip f1_sys-sect_1 writing-elem-cover-title">
            {{$writing->title or 'Untitled'}}
          </h3>
        </div>
      </a>
      <div class="writing-elem-bottom">
    @endif
      <a class="writing-elem-desc" href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}">
        <p class="f1_content-1__light text-over_elip">{{$writing->description}}</p>
      </a>
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
