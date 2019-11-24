<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials._head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Feed - {{config('app.name')}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/feed.css') }}">
  </head>
  <body>
    @include('partials.topbars._topbar', ['type' => 'user'])

    <div id="feed-cont">
      <div class="container__1">
        <div class="feed-right">
          <div class="feed-right-row feed-follows">
            <h2 class="f1_content-1__dark feed-right-title"><a href="{{asset(Auth::user()->str_id.'/following')}}">Follows</a></h2>
            <div class="feed-follows-inner">
              <ul class="feed-follows-list">
                @foreach ($usr_follows as $indx => $followd)
                  @if ($indx !=  6)
                    <li class="feed-follows-li">
                      <a href="{{ asset($followd->str_id) }}">
                        <img src="{{$followd->avatar_path}}?s=40" alt="avatar" class="avatar-regular feed-follows-avatar">
                        <span class="f1_usrname_1 feed-follows-name">
                          {{ $followd->nickname}}
                        </span>
                      </a>
                    </li>
                  @endif
                @endforeach
              </ul>
              @if (count($usr_follows) != 7)
                <span class="feed-right-more f1_sysinf-0-5"><a href="{{asset(Auth::user()->str_id.'/following')}}">See more</a></span>
              @endif
            </div>
          </div>
        </div>

        <div class="feed-left">
          @if (!empty($feed_writings))
              <ul class="feed-list">
                @foreach ($feed_writings as $wrFeed)
                  @include('partials.elem.writings._writing-full-small', ['writing' => $wrFeed])
                @endforeach
              </ul>
          @else
            <div class="f1_sysinf-1" style="text-align: center; margin-top: 40px;">
              <p style="margin-bottom: 8px;">Your feed is empty</p>
              <button type="button" class="neutralize-btn f3_title_2"><a href="{{ asset('popular/profiles') }}">Browse channels</a></button>
            </div>
          @endif
        </div>
      </div>
    </div>

    @include('partials._scripts')
  </body>
</html>
