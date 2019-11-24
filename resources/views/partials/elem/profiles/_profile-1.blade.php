<div class="profile_long">
  <a href="{{ asset($prof->str_id) }}" class="profile_long-inner">
    <div class="profile_long-avatarcont">
      <img src="{{asset($prof->avatar_path)}}?s=120" class="profile-avatar">
    </div>

    <div class="profile_long-mid">
      <span class="f1_usrname_2__big profile_long-name text-over_elip">
        {{ $prof->nickname}}
      </span>

      <div class="profile_long-foll">
        <span class="f1_sys-sect_0__small">{{$prof->followers}}</span>
        <span class="f1_sysinf-2 profile_long-foll-lbl">Followers</span>
      </div>

      @if (!empty($prof->bio))
        <p class="f1_sysinf-0-5 text-over_elip profile_long-desc">{{$prof->bio}}</p>
      @endif
    </div>
  </a>
  <button role="button" data-need-auth action-follow="{{$prof->id}}" class="btn-action__0 @if($prof->u_following)btn-action-half @endif w-follow-btn profile_long-btn"></button>
</div>
