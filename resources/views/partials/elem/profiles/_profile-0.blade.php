<div class="profile_big-cont @if (!empty($show_type)) profile_big-cont-vert @endif">
  <div class="profile_big-avatarcont">
    <a href="{{ asset($prof->str_id) }}">
      <img src="{{asset($prof->avatar_path)}}?s=70" class="profile-avatar">
    </a>
  </div>

  <div class="profile_big-downcont">
    <span class="f1_usrname_2 profile_big-name">
      <a href="{{ asset($prof->str_id) }}">{{ $prof->nickname}}</a>
    </span>

    <div class="profile_big-foll">
      <span class="f1_sysinf-2 profile_big-foll-lbl">{{$prof->followers}} Followers</span>
    </div>

    <button role="button" data-need-auth action-follow="{{$prof->id}}" class="btn-action__1 @if($prof->u_following)btn-action-half @endif w-follow-btn"></button>
  </div>
</div>
