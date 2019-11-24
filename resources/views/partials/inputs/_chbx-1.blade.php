<label for="{{$name}}-inpt" class="f2_labe-top_small inpt-label__float {{ empty($value) ? '' : 'label__float-active'}}">{{ucfirst($name)}}</label>

<div class="page-detail-topline">
  <input type="text" id="{{$name}}-inpt" name="{{$name}}" prev-val="{{ $value ?: NULL }}" value="{{ $value ?: NULL}}" class="neutralize-txtarea page-det_inpt" {{ in_array($verName, $verified) ? 'readonly' : 'undrline-onfcs'}}>

  <div class="right-ver-check">
    @if (in_array($verName, $verified))
      <svg viewBox="0 286.117 301.923 241.883" class="sign-verified__1">
        <path fill="#E2E3E7" d="M0,425.178l90.176,97.949c2.818,3.139,6.661,4.872,10.625,4.872c3.967,0,7.749-1.735,10.531-4.873
        l190.591-215.506l-21.696-21.504L100.095,488.359l-79.39-85.674L0,425.178z"/>
      </svg>
    @else
      <div class="inpt-chckbx__flat">
        <input type="checkbox" name="{{$name}}" class="chckbx__flat__inpt" id="{{$name}}">
        <label for="{{$name}}">
          <div class="chckbx__checkmark"></div>
        </label>
      </div>
    @endif
  </div>
</div>

<div class="inpt-undrline">
  <div class="inpt-line__fcsed inpt-line-positiv"></div>
  <div class="inpt-line__unfcsed"></div>
</div>
