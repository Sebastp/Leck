<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials._head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.name')}}</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i,800,800i|Raleway:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}">
  </head>
  <body>
    @include('partials.topbars._topbar', ['type' => 'user', 'start_full' => true])


@if (!Auth::check())
  <section class="hm-signbar hm-section">
    <div class="container__1 hm-signbar_inner">
      <div class="hm-signbar-top">
        <h2 class="f1_sys-sect_0__big">Introducing Inook</h2>
        <p class="f1_sys-sect_0__sl">More Than a Story</p>
      </div>

      <h2 class="f1_sys-sect_0" id="hm-signbar-cta"><a href="{{ asset('/register') }}">Sign In and Start Your Experience</a></h2>
    </div>
  </section>
@endif

@if (!empty($writings_tranding))
  <div class="container__1">
    <section class="hm-section">
      <div class="hm-sect-top">
        <h2 class="f1_sys-sect_0__big hm-sect-top__header">Tranding</h2>
      </div>

      <ul class="hm-sect-main hm-sect-grid">
        @foreach ($writings_tranding as $indx => $wrTrand)
          @if ($indx < 2)
            @include('partials.elem.writings._writing-half-0', ['writing' => $wrTrand])
          @else
            @include('partials.elem.writings._writing-0', ['writing' => $wrTrand])
          @endif
        @endforeach
      </ul>
    </section>
  </div>
@endif

@if (!empty($writings_bestIn))
  @include('partials.home._hm-bestIn', ['bestin_obj' => $writings_bestIn])
@endif



@if (!empty($usr_recent))
  @include('partials.home._hm-recent', ['usr_recent_wrs' => $usr_recent])
@endif



<div class="cont-columns__0">
  <div class="cont-incolumns__0">
    @if (!empty($recom_writings))
      @include('partials.home._hm-recomWritings', ['r_writings' => $recom_writings])
    @endif


    @if (!empty($recom_profiles))
      @include('partials.home._hm-profiles-0', ['r_profiles' => $recom_profiles])
    @endif

    <section class="hm-section hm-section__recomm columns__0">
      <ul class="hm-recomm-main ">
      </ul>
    </section>

  </div>

  @include('partials.home._hm-feed')
</div>



    @include('partials._scripts')
    @include('auth._login-modal')
  </body>
</html>
