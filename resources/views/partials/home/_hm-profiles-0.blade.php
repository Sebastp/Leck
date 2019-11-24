<section class="hm-section hm-section__profiles columns__0">
  <div class="hm-sect-top">
    <h2 class="f1_sys-sect_0 hm-sect-header__big"><a href="{{asset('popular/profiles')}}">Creators You Might Like</a></h2>
  </div>

  <ul class="hm-profiles-mid">
    @foreach ($r_profiles as $rProf)
      <li class="profile-grid-itm">
        @include('partials.elem.profiles._profile-0', ['prof' => $rProf, 'show_type' => 'vert'])
      </li>
    @endforeach
  </ul>
</section>
