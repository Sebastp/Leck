@if (!empty($feed))
  <section class="hm-section">
    <div class="hm-sect-divline"></div>

    <div class="hm-sect-top">
      <h2 class="f1_sys-sect_0__big hm-sect-top__header">Your Feed</h2>
      <span class="f1_sysinf-0-5 hm-sect-top__right"><a href="/feed">See more</a></span>
    </div>

    <ul class="hm-sect-main">
      @foreach ($feed as $wrFeed)
        @include('partials.elem.writings._writing-0', ['writing' => $wrFeed])
      @endforeach
    </ul>
  </section>
@endif
