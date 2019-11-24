@if (!empty($type) && !Auth::check() && $type != "clear")
  @php
    $type = null;
  @endphp
@elseif (empty($type) && Auth::check())
  @php
    $type = 'user';
  @endphp
@endif



<div class="topbar @if(!empty($type) && $type == "clear") topbar-cont__clear @else topbar-height @endif @if (!empty($still)) topbar_still @endif" role="banner" @if (!empty($start_full))style="transform: translateY(-100%)" @endif>

@if (empty($type))
  @php
    // if (url()->current() != route('register') and url()->current() != route('login') and url()->current() != url('/') ){
      // $prev = '?prevReg='.str_replace( url('/'), '', url()->current() );
    // }else{
      $prev = '';
    // }
  @endphp

  <div class="container container-inner topbar-cont">
    <header class="topbar-header">
      <div class="topbar-logo-cont">
        <a href="/" class="logoType-anachor">
          <svg viewBox="0 0 1991 1660" class="logoType">
            <g id="Layer_1">
              <polygon points="927,1660 0,1500 0,0 927,160 	"/>
            </g>
            <g id="Layer_2">
              <polygon points="1991,1500 1064,1660 1064,1101 1991,941 	"/>
              <polygon points="1991,805 1064,965 1064,160 1991,0 	"/>
            </g>
          </svg>
        </a>
        <h1 class="logo-text "><a href="/">Leck</a></h1>
      </div>
    </header>

    <div id="topbar-right">
      <div id="topbar-action__right-cont">

        <div class="tpbar-action__right" title="search">
          <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line>
          </svg>
        </div>

        <nav class="topbar-nav topbar-nav-sign">
          <ul class="topbar-nav-sign__cont">
            <li class="topbar-nav-sign__item f3_title_2"><a href="{{ '/register'.$prev }}">Sign Up</a></li>
            <div id="topbar-nav-sign__line"></div>
            <li class="topbar-nav-sign__item f3_title_2"><a href="{{'/login'.$prev}}">Sign In</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>




@elseif ($type == "clear")
<div class="container__1 topbar-cont topbar-cont__center">
  <div class="topbar-back2">
    <a href="/">
      <p class="f3_sub_info">back to main page</p>
    </a>
  </div>
  <header class="topbar-header">
    <div class="topbar-logo-cont">
      <a href="/" class="logoType-anachor">
        <svg viewBox="0 0 1991 1660" class="logoType_1">
          <g id="Layer_1">
            <polygon points="927,1660 0,1500 0,0 927,160 	"/>
          </g>
          <g id="Layer_2">
            <polygon points="1991,1500 1064,1660 1064,1101 1991,941 	"/>
            <polygon points="1991,805 1064,965 1064,160 1991,0 	"/>
          </g>
        </svg>
      </a>
      <h1 class="logo-text_1 "><a href="/">Leck</a></h1>
    </div>
  </header>
</div>

@elseif ($type == "user")

  <div class="container__0 topbar-cont">
    <header class="topbar-header">
      <div class="topbar-logo-cont">
        <a href="/" class="logoType-anachor">
          <svg viewBox="0 0 1991 1660" class="logoType">
            <g id="Layer_1">
              <polygon points="927,1660 0,1500 0,0 927,160 	"/>
            </g>
            <g id="Layer_2">
              <polygon points="1991,1500 1064,1660 1064,1101 1991,941 	"/>
              <polygon points="1991,805 1064,965 1064,160 1991,0 	"/>
            </g>
          </svg>
        </a>
        <h1 class="logo-text "><a href="/">Leck</a></h1>
      </div>
    </header>


    <div id="topbar-right">
      <div id="topbar-action__right-cont">
        <div class="tpbar-action__right" title="search">
          <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line>
          </svg>
        </div>

        <div class="tpbar-action__right" title="write">
          <a href="/editor/inook">
            <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
            </svg>
          </a>
        </div>
      </div>

      @include('partials.topbars._usersec')
    </div>
  </div>


@elseif ($type == "writing")

  <div class="container container-inner topbar-cont">
    <header class="topbar-header">
      <div class="topbar-logo-cont">
        <a href="/" class="logoType-anachor">
          <svg viewBox="0 0 1991 1660" class="logoType">
            <g id="Layer_1">
              <polygon points="927,1660 0,1500 0,0 927,160 	"/>
            </g>
            <g id="Layer_2">
              <polygon points="1991,1500 1064,1660 1064,1101 1991,941 	"/>
              <polygon points="1991,805 1064,965 1064,160 1991,0 	"/>
            </g>
          </svg>
        </a>
        <h1 class="logo-text "><a href="/">Leck</a></h1>
        <div id="topbar-logo-divstr"></div>
        <p class="f3_title_2_v2 topbar-logo-subtitle writing-title topbar-w-title text-over_elip-l1">{{$writing->title}}</p>
      </div>
    </header>



    <div id="topbar-right">
      <div id="topbar-action__right-cont">
        <div class="tpbar-action__right" title="search">
          <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line>
          </svg>
        </div>

        <div class="tpbar-action__right" title="write">
          <a href="/editor/inook">
            <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
            </svg>
          </a>
        </div>
      </div>
      @include('partials.topbars._usersec')
    </div>
  </div>

@elseif ($type == "editor")

  <div class="container container-inner topbar-cont">
    <header class="topbar-header">
      <div class="topbar-logo-cont">
        <a href="/" class="logoType-anachor" title="Saved">
          <svg viewBox="0 0 1991 1660" id="editor-logo" class="logoType">
            <g id="logoEditor_saved">
              <polygon points="927,1660 0,1500 0,0 927,160"/>
              <polygon points="1991,1500 1064,1660 1064,1101 1991,941"/>
              <polygon points="1991,805 1064,965 1064,160 1991,0"/>
            </g>

            <g id="logoGrad_1">
              <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="120.043" y1="1054.4023" x2="1724.0428" y2="6.4023">
                <stop  offset="0" style="stop-color:#0879F6"/>
              	<stop  offset="1" style="stop-color:#56C1FF"/>
              </linearGradient>
              <polygon fill="url(#SVGID_1_)" points="927,1660 0,1500 0,0 927,160 "/>
              <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="653.8047" y1="1871.3438" x2="2257.8047" y2="823.3438">
                <stop  offset="0" style="stop-color:#0879F6"/>
              	<stop  offset="1" style="stop-color:#56C1FF"/>
              </linearGradient>
              <polygon fill="url(#SVGID_2_)" points="1991,1500 1064,1660 1064,1101 1991,941 "/>
              <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="279.2461" y1="1298.0674" x2="1883.2461" y2="250.0674">
                <stop  offset="0" style="stop-color:#0879F6"/>
              	<stop  offset="1" style="stop-color:#56C1FF"/>
              </linearGradient>
              <polygon fill="url(#SVGID_3_)" points="1991,805 1064,965 1064,160 1991,0 "/>
            </g>

            <g id="logoGrad_2">
              <linearGradient id="gradrev_1" gradientUnits="userSpaceOnUse" x1="120.043" y1="1054.4033" x2="1724.0375" y2="6.4054">
                <stop  offset="0" style="stop-color:#58BEFF"/>
              	<stop  offset="1" style="stop-color:#0879F6"/>
              </linearGradient>
              <polygon fill="url(#gradrev_1)" points="927,1660 0,1500 0,0 927,160 "/>
              <linearGradient id="gradrev_2" gradientUnits="userSpaceOnUse" x1="653.8052" y1="1871.3428" x2="2257.8083" y2="823.3392">
                <stop  offset="0" style="stop-color:#58BEFF"/>
              	<stop  offset="1" style="stop-color:#0879F6"/>
              </linearGradient>
              <polygon fill="url(#gradrev_2)" points="1991,1500 1064,1660 1064,1101 1991,941 "/>
              <linearGradient id="gradrev_3" gradientUnits="userSpaceOnUse" x1="279.2461" y1="1298.0684" x2="1883.2461" y2="250.0668">
                <stop  offset="0" style="stop-color:#58BEFF"/>
              	<stop  offset="1" style="stop-color:#0879F6"/>
              </linearGradient>
              <polygon fill="url(#gradrev_3)" points="1991,805 1064,965 1064,160 1991,0 "/>
            </g>


            <g id="logoEditor_gray" class="logo-col__1">
              <polygon points="927,1660 0,1500 0,0 927,160"/>
              <polygon points="1991,1500 1064,1660 1064,1101 1991,941"/>
              <polygon points="1991,805 1064,965 1064,160 1991,0"/>
            </g>

          </svg>
        </a>

        <span class="f2_1  topbar-logo-subtitle" id="topbar-title__plchlder" @if ($writing->title) style="display: none" @endif>Editor</span>
        <p class="f3_title_2_v2  topbar-logo-subtitle writing-title topbar-w-title text-over_elip-l1" id="topbar-title">{{$writing->title}}</p>
      </div>
    </header>

    <div id="topbar-right">
      <div id="topbar-action__right-cont">
        <div class="w-more-icon tpbar-action__right">
          <svg viewBox="0 0 25 5">
            <path fill="#000" fill-rule="evenodd" clip-rule="evenodd" d="M22.5,0C23.881,0,25,1.119,25,2.5C25,3.88,23.881,5,22.5,5S20,3.88,20,2.5
            C20,1.119,21.119,0,22.5,0z"/>
            <path fill="#000" fill-rule="evenodd" clip-rule="evenodd" d="M12.5,0C13.881,0,15,1.119,15,2.5C15,3.88,13.881,5,12.5,5S10,3.88,10,2.5
            C10,1.119,11.119,0,12.5,0z"/>
            <path fill="#000" fill-rule="evenodd" clip-rule="evenodd" d="M2.5,0C3.881,0,5,1.119,5,2.5C5,3.88,3.881,5,2.5,5S0,3.88,0,2.5
            C0,1.119,1.119,0,2.5,0z"/>
          </svg>
          {{-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><circle cx="20" cy="12" r="2"></circle><circle cx="4" cy="12" r="2"></circle></svg> --}}
        </div>

        <div class="w-tree-icon tpbar-action__right">
          <svg viewBox="0.992 1.992 22 20" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="19" cy="18" r="3"/>
            <circle cx="12" cy="6" r="3"/>
            <circle cx="5" cy="18" r="3"/>
            <line x1="10.49" y1="8.59" x2="6.51" y2="15.42"/>
            <line x1="17.49" y1="15.41" x2="13.51" y2="8.59"/>
          </svg>
        </div>
      </div>
      @include('partials.topbars._usersec')
    </div>
  </div>

@endif

</div>


@if (empty($still))
  @if (empty($start_full))
    <div class="topbar-placeholder topbar-height"></div>
  @else
    <div class="container__1 topbar-cont topbar-start_full">
      <header class="topbar-header">
        <div class="topbar-logo-cont">
          <a href="/" class="logoType-anachor">
            <svg viewBox="0 0 1991 1660" class="logoType_1">
              <g id="Layer_1">
                <polygon points="927,1660 0,1500 0,0 927,160 	"/>
              </g>
              <g id="Layer_2">
                <polygon points="1991,1500 1064,1660 1064,1101 1991,941 	"/>
                <polygon points="1991,805 1064,965 1064,160 1991,0 	"/>
              </g>
            </svg>
          </a>
          <h1 class="logo-text "><a href="/">Leck</a></h1>
        </div>
      </header>


      <div id="topbar-right">
        <div id="topbar-action__right-cont">
          <div class="tpbar-action__right" title="search">
            <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line>
            </svg>
          </div>

          @if ($type == "user")
            <div class="tpbar-action__right" title="write">
              <a href="{{asset('editor/inook')}}">
                <svg class="icon__1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                </svg>
              </a>
            </div>
          @else
            <nav class="topbar-nav topbar-nav-sign">
              <ul class="topbar-nav-sign__cont">
                <li class="topbar-nav-sign__item f3_title_2"><a href="{{ asset('/register'.$prev) }}">Sign Up</a></li>
                <div id="topbar-nav-sign__line"></div>
                <li class="topbar-nav-sign__item f3_title_2"><a href="{{ asset('/login'.$prev) }}">Sign In</a></li>
              </ul>
            </nav>
          @endif
        </div>
        @if ($type == "user")
          @include('partials.topbars._usersec', ['ppupId' => '2', 'avatar_size' => 'avatar-regular__big'])
        @endif
      </div>
    </div>
  @endif
@endif
