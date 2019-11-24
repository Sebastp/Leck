@if (empty($ppupId))
  @php
    $ppupIdStr = 'tpbav1';
  @endphp
@else
  @php
    $ppupIdStr = 'tpbav'.$ppupId;
  @endphp
@endif
<div class="topbar-usersec">
  <img src="{{ Auth::user()->getAvatarPath(Auth::user()->avatar) }}?s=40" alt="avatar" class="@if(!empty($avatar_size)){{$avatar_size}}@else avatar-small__big @endif" id="topbar-usersec__avatar" data-has-popup data-popup-id="{{$ppupIdStr}}">

  <leck-popup-cont class="topbar-usr__drpdwn-cont">
    <popup-box-inner data-popup-id="{{$ppupIdStr}}" class="pbi__1">
      <ul>
        <li class="topbar-usersec-li topbar-usersec-top inner-padding">
          <a href="{{ asset(Auth::user()->str_id) }}" id="topbar-usersec-top-inner">
            <img src="{{ Auth::user()->getAvatarPath(Auth::user()->avatar) }}?s=40" alt="avatar" class="avatar-small">

            <span id="topbar-usersec__name" class="f1_usrname_0">
                {{ Auth::user()->nickname }}
            </span>
          </a>
        </li>

        <li class="topbar-usersec-li li__onhover inner-padding">
          <a href="{{ asset(Auth::user()->str_id) }}" class="inner-padding li__inner-a">
            <span onclick="document.getElementById('logout-form').submit();">
              My profile
            </span>
          </a>
        </li>

        <li class="topbar-usersec-li li__onhover inner-padding">
          <a href="{{ asset('feed') }}" class="inner-padding li__inner-a">
            <span id="topbar-usersec__name">
              My feed
            </span>
          </a>
        </li>

        <div class="topbar-usersec-divline"></div>

        <li class="topbar-usersec-li li__onhover">
          <span onclick="document.getElementById('logout-form').submit();">
              Logout
          </span>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
        </li>

      </ul>
    </popup-box-inner>
  </leck-popup-cont>
</div>
