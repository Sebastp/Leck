<section class="hm-section hm-recent container__0">
  <div class="hm-recent-top">
    <h2 class="f1_sys-sect_0 hm-sect-header__big">Recently Read</h2>
  </div>

  <div class="container__1">
    <ul class="hm-recent-mid">
      @foreach ($usr_recent_wrs as $recent_wr)
        @include('partials.elem.writings._writing-spec-12', ['writing' => $recent_wr])
      @endforeach
    </ul>
  </div>

</section>
