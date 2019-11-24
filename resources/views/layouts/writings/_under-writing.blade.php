<section class="w-down-cont">
  <div class="container__1 w-down-inner">
    <div class="container__1 w-down-left">
      <h2 class="f1_sys-sect_0">Comments - {{count($comments)}}</h2>
      <div class="w-comment-create w-comment-elem">
        <img src="@if(Auth::check()) {{ Auth::user()->getAvatarPath(Auth::user()->avatar).'?s=40'}} @else {{ asset(config('default.def_user-avatar')) }}@endif" alt="avatar" class="avatar-regular__big w-comment-author">
        <div class="w-comment-content inpt-undrline-cont">
          <textarea placeholder="Write a comment" data-need-auth data-elem-id="main-down" undrline-onfcs class="neutralize-txtarea txtarea-resize f1_content-0 comm-text" id="comm-create-area" rows="2" spellcheck="false"></textarea>
          <div class="inpt-undrline">
            <div class="inpt-line__fcsed inpt-line-neutral"></div>
            <div class="inpt-line__unfcsed"></div>
          </div>
          <button type="button" data-value-elem="main-down" class="neutralize-btn btn-empty-positiv comm-post-btn" id="w-comment-post-btn">Send</button>
        </div>
      </div>

      <ul id="w-comments-cont">
        @if (!empty($comments))
          @foreach ($comments as $comment)
            @include('partials.elem.comments._comm-writing-0', ['comm' => $comment])
          @endforeach
        @endif
      </ul>

      @if (empty($comments))
        <p class="f3_sub_msg__light w-down-empty">Be the first to comment</p>
      @endif

    </div>


    <div class="w-down-right">
      <h2 class="container__1 f1_sys-sect_0">Up Next</h2>

      <div class="w-recom-cont scrlbar-2">
        <ul id="recomended-list">
          @for ($i=0; $i < 4; $i++)
            {{-- @include('partials.elem.writings._writing-0', ['writing' => $writing, 'noData' => true]) --}}
          @endfor
        </ul>
      </div>
    </div>
  </div>
</section>
