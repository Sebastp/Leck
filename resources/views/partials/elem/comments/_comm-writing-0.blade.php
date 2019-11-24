<li class="w-comment-elem">
  <a href="{{ $comm->author->str_id }}">
    <img src="{{ $comm->author->avatar_path }}?s=40" alt="avatar" class="avatar-regular w-comment-author">
  </a>

  <div class="w-comment-content">
    <div class="w-comment-header">
      <a href="{{ $comm->author->str_id }}">
        <span class="f1_usrname_2">{{$comm->author->nickname}}</span>
      </a>
      <span class="f1_sysinf-0 w-comm-published">{{'· '.$comm->created_at}}</span>
    </div>
    <p class="f1_content-0 comm-text">{{$comm->value}}</p>
  </div>
</li>
