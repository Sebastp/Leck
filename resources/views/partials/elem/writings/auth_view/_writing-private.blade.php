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
        <div class="draft-likes-cont">
          <span class="f1_sysinf-0-5 draft-likes-nr">{{$writing->likes}}</span>
          <svg viewBox="0 0 1114 1027" class="draft-likes-icon">
            <path stroke-width="80" fill-rule="evenodd" clip-rule="evenodd" d="M343.1,34.9c-167.3,0-305.9,140.5-305.9,307.4
            c0,116.2,65.6,193.3,141.7,269.5c126.7,126.8,253.3,253.6,380,380.3c71.2-71.6,142.5-143.2,213.7-214.7
            c72.1-72.5,141-153.8,219-220.1c40.1-34.1,68.8-102.8,79.5-155c18.3-89.4-8.1-182.4-64.1-253.6c-109.4-139-324.2-151.8-450-26
            C502.8,66.1,420.6,34.9,343.1,34.9z"/>
          </svg>
        </div>
      </div>
    </div>
  </a>
</li>
