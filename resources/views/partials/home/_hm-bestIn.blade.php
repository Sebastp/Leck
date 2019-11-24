<section class="hm-section hm-bestin container__0">
  <div class="hm-bestin-top">
    <p class="f1_usrname_2">Best In <span class="hm-bestin-name f1_sys-sect_1">{{$bestin_obj->title}}</span></p>
  </div>
{{-- {{$bestin_obj->title}} --}}
  <div class="container__1">
    <ul class="hm-sect-grid hm-bestin-mid">
      @foreach ($bestin_obj->writings as $wr_index => $b_wr)
        @include('partials.elem.writings._writing-spec-13', ['writing' => $b_wr, 'index' => $wr_index+1])
      @endforeach
    </ul>
  </div>

  <div class="hm-bestin-bck"></div>
  {{-- <div class="hm-bestin-bck" style="background-image: url('{{asset('beach.jpg')}}')"></div> --}}
</section>
