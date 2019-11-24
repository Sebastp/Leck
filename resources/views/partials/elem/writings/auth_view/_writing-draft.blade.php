<li class="writing-elem_draft-0">
  <a href="{{ asset('editor/'.$writing->str_id)}}" class="writing-elem-inner">
    @if (!empty($writing->cover))
      <div class="writing-elem-cover">
        <div class="writing-elem-coverimg"
          style="background-image: url('{{$writing->cover->path or asset(config('default.def_writing-cover'))}}')">
        </div>
      </div>
    @endif
    <div class="draft-right">
      <h3 class="text-over_elip f3_title_0-5__bold writing-elem-h">
        {{-- <a href="{{ asset('editor/'.$writing->str_id)}}"> --}}
          {{$writing->title or 'Untitled'}}
        {{-- </a> --}}
      </h3>
      <span class="draft-mid-text f1_content-1__light">{{$writing->sections_nr}} @if ($writing->sections_nr == 1)section @else sections @endif  · {{$writing->splits_nr}} @if ($writing->splits_nr == 1)split @else splits @endif</span>
      <div class="draft-bottom">
        <div class="draft-bottom-left">
          <span class="f1_sysinf-0-25">Created on {{$writing->created_at}}</span>
        </div>
      </div>
    </div>
  </a>
</li>
