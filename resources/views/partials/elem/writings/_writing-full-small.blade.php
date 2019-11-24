<div class="writing-elem_full_small @if (empty($writing->cover->path))writing-elem__nocover @endif">
  <div class="writing-elem-inner">
    @if (!empty($writing->cover->path))
      <div class="writing-elem-cover progLoad-cont">
        <a class="writing-elem-coverimg progLoad-tmp"
            href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}"
            style="background-image: url('{{$writing->cover->path}}?s=20')" data-src="{{$writing->cover->path}}?s=200">
        </a>

        <a class="writing-elem-coverimg progLoad-base"
            href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}">
        </a>
      </div>
    @endif

    <div class="writing-elem-right">
      <a href="{{ asset($writing->authors[0]->str_id.'/'.$writing->str_id)}}" class="writing-elem-info">
        <h3 class="text-over_elip f1_sys-sect_1">
            {{$writing->title or 'Untitled'}}
        </h3>
        @if (!empty($writing->label))
          <p class="f1_sysinf-0-25 writing-elem-label">{{$writing->label}}</p>
        @endif
        <p class="f1_content-1 writing-elem-desc text-over_elip">{{$writing->description}}</p>
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
</div>
