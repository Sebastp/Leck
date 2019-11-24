<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials._head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/popular.css') }}">
  </head>
  <body>
    @include('partials.topbars._topbar', ['type' => 'user', 'start_full' => true])


    <div class="container__1">
    @if (!empty($prof_popular))
      <section class="pop-sect">
        <div class="pop-sect-top">
          <h2 class="f1_sys-sect_0__big pop-sect-name">Most Popular</h2>

          {{-- <span class="f1_sysinf-1 pop-sect-more">See more</span> --}}
        </div>

        <ul class="pop-list">
          @foreach ($prof_popular as $user_obj)
            <li class="pop-li__profile">
              @include('partials.elem.profiles._profile-0', ['prof' => $user_obj])
            </li>
          @endforeach
        </ul>
      </section>
    @endif
    </div>


    @include('partials._scripts')
    @include('auth._login-modal')
  </body>
</html>
