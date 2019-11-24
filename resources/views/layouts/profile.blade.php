<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials._head')
    <title>{{$user->nickname.' - Profile Page - '.config('app.name')}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}">
  </head>
  <body>
    @include('partials.topbars._topbar', ['type' => 'user'])

      <section id="prof-up">
        <div class="container__3" id="prof-innercont">
          <div class="avatar-cont">
            @if ($user->auth_profile)
              <input type="file" name="new_avatar" id="avatar_editupl">
              <div id="avatar-edit" class="avatar-round">
                <svg viewBox="0 0 54 54">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M26.115,0h2.271v54.5h-2.271V0z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M0,26.115h54.5v2.271H0V26.115z"/>
                </svg>
              </div>
            @endif
            <img src="{{ $user->avatar_path }}?s=150" alt="avatar" class="profile-avatar-main avatar-round">
          </div>

          <div id="prof-info">
            <h1 class="f1_usrname_3">{{$user->nickname}}</h1>

            <div id="prof-info-mid">
              <div id="prof-stats">
                <div class="pstats-elem">
                  <span class="f1_sys-sect_0__small pstats-num">{{$user->followers}}</span>
                  <span class="f1_sysinf-1__little pstats-desc">Followers</span>
                </div>
                <div class="pstats-elem">
                  <a href="{{ asset($user->str_id.'/following') }}">
                    <span class="f1_sys-sect_0__small pstats-num">{{$user->following}}</span>
                    <span class="f1_sysinf-1__little pstats-desc">Following</span>
                  </a>
                </div>
              </div>
            </div>

            <p class="f1_content-1" id="prof-main__bio">{{$user->bio}}</p>

          </div>
        </div>

        <nav id="prof-innernav">
          <ul class="container__3">
            <li class="prof-nav__elem @if(empty($show_sect))prof-nav__elem-active @endif"><span class="f1_content-0"><a href="/{{$user->str_id}}">Home</a></span></li>
            @if ($user->auth_profile)
              <li class="prof-nav__elem @if($show_sect == 'auth_stories')prof-nav__elem-active @endif"><span class="f1_content-0"><a href="/{{$user->str_id.'/auth_stories'}}">Stories</a></span></li>
            @endif
            <li class="prof-nav__elem @if($show_sect == 'followings')prof-nav__elem-active @endif"><span class="f1_content-0"><a href="/{{$user->str_id.'/following'}}">Followings</a></span></li>
          </ul>
        </nav>
      </section>



    <div class="container__1" id="prof-content">
      <section id="stories-cont" class="container__3 prof-sect @if (empty($show_sect))prof-currsect @endif">
        @if (!empty($u_writings))
          <ul class="prof-stories-inner prof-stories-left">
            @foreach ($u_writings as $writing)
              @include('partials.elem.writings._writing-half-0', ['writing' => $writing])
            @endforeach
          </ul>
        @else
          <div class="prof-stories-left">
            <p class="f3_sub_msg" style="margin-top: 38px">No stories were found</p>
          </div>
        @endif


        <div id="prof-content-right">
          <div class="prof-right-row prof-recom">
            <h2 class="f1_content-1__dark">Recomended</h2>
            <div class="prof-recom-inner">
              <ul class="prof-recom-list">
                @foreach ($recom_prof as $rProf)
                  <li class="prof-recom-li">
                    <a href="{{ asset($rProf->str_id) }}">
                      <img src="{{$rProf->avatar_path}}?s=35" alt="avatar" class="avatar-small prof-recom-avatar">
                      <span class="f1_usrname_1 prof-recom-name">
                        {{ $rProf->nickname}}
                      </span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </section>


    @if ($user->auth_profile)
      <section id="auth_stories-cont" class="container__3 prof-sect @if ($show_sect == 'auth_stories')prof-currsect @endif">
        <ul class="prof-innernav">
          <li class="prof-innernav__elem innernav-active" data-contid="auth_stories-public"><span class="f1_sys-sect_0__small">Public ({{count($u_writings)}})</span></li>
          <li class="prof-innernav__elem" data-contid="auth_stories-drafts"><span class="f1_sys-sect_0__small">Drafts ({{count($u_writings__drafts)}})</span></li>
          {{-- <li class="prof-innernav__elem" data-contid="auth_stories-private"><span class="f1_sys-sect_0__small">Private ({{count($u_writings__private)}})</span></li> --}}
        </ul>


        <div class="prof-stories-full auth_stories-active" id="auth_stories-public">
          @if (!empty($u_writings) && count($u_writings))
            <ul class="prof-stories-inner">
              @foreach ($u_writings as $writing)
                @include('partials.elem.writings._writing-0', ['writing' => $writing, 'wr_type' => 'authPublic'])
              @endforeach
            </ul>
          @else
            <p class="f3_sub_msg" style="margin-top: 38px">You don't have public stories</p>
          @endif
        </div>

        {{-- <div class="prof-stories-full" id="auth_stories-private">
          @if (!empty($u_writings__private) && count($u_writings__private))
            <ul class="prof-stories-inner">
              @foreach ($u_writings__private as $writing_priv)
                @include('partials.elem.writings.auth_view._writing-private', ['writing' => $writing_priv])
              @endforeach
            </ul>
          @else
            <p class="f3_sub_msg" style="margin-top: 38px">You don't have private stories</p>
          @endif
        </div> --}}


        <div class="prof-stories-full" id="auth_stories-drafts">
          @if (!empty($u_writings__drafts) && count($u_writings__drafts))
            <ul class="prof-stories-inner">
              @foreach ($u_writings__drafts as $writing_dr)
                @include('partials.elem.writings.auth_view._writing-draft', ['writing' => $writing_dr])
              @endforeach
            </ul>
          @else
            <p class="f3_sub_msg" style="margin-top: 38px">You don't have any drafts</p>
          @endif
        </div>
      </section>
    @endif



      <section id="followings-cont" class="prof-sect @if ($show_sect == 'followings')prof-currsect @endif">
        @if (!empty($usr_followings))
          <ul class="followings-inner container__3">
            @foreach ($usr_followings as $followed)
              <li class="followings-li">
                @include('partials.elem.profiles._profile-1', ['prof' => $followed])
              </li>
            @endforeach
          </ul>
        @else
          <p class="f3_sub_msg">No follows found</p>
        @endif
      </section>
    </div>

    @include('partials._scripts')
    <script type="text/javascript" src="/js/profile.js"></script>
    @include('auth._login-modal')

    <script type="text/javascript">
      var csrf_token = "{{csrf_token()}}";

      $('#avatar-edit').click(function() {
        $('#avatar_editupl').trigger('click');
      });


      $('#avatar_editupl').on('change', function() {
        if (this.files && this.files[0]) {
          formdata = new FormData();
          formdata.append('new_avatar', this.files[0]);
          formdata.append('_token', csrf_token);

          var reader = new FileReader();
          reader.onload = function (e) {
            $.ajax({
              type: "POST",
              url: "{{$user->str_id.'/upload_avatar'}}",
              dataType: 'json',
              data: formdata,
              mimeTypes: "multipart/form-data",
              contentType: false,
              cache: false,
              processData: false,
            })
            .done(function(data) {
              if (!data.success) {
                alert('error');
              }else {
                var allAvElems = $('.profile-avatar-main');
                for (var a = 0; a < allAvElems.length; a++) {
                  allAvElems[a].setAttribute('src', data.new_path);
                }
              }
            })
            .fail(function() {
              alert('error');
            });

          };
          reader.readAsDataURL(this.files[0]);
        }
      });


    </script>
  </body>
</html>
