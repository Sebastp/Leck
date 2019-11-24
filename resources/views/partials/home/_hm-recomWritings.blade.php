<section class="hm-section hm-section__recomm columns__0">
  <div class="hm-sect-top">
    <h2 class="f1_sys-sect_0__big hm-sect-top__header">Recommended</h2>
  </div>

  <ul class="hm-recomm-main">
    @foreach ($r_writings as $indx => $rWrit)
      @if ($indx == 0)
        <li class="hm-recomm-item">
          @include('partials.elem.writings._writing-full-0', ['writing' => $rWrit])
        </li>
      @else
        <li class="hm-recomm-item">
          @include('partials.elem.writings._writing-full-small', ['writing' => $rWrit])
        </li>
      @endif
    @endforeach
  </ul>
</section>
