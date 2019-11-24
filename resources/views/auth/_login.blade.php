<section id="sign-in"  @if(!empty($view_type) && $view_type == "signup") style='display: none' @endif>
  <div class="signs-right">
    <div class="signs-right-header">
      @if (!empty($from_modal))
        <a href="{{asset('login')}}">
          <h2 class="f1_sys-sect_0__big">Sign in</h2>
        </a>
      @else
        <h2 class="f1_sys-sect_0__big">Sign in</h2>
      @endif
    </div>

    <form class="signs-form" role="form" novalidate method="POST" action="{{ route('login') }}">
      {{ csrf_field() }}
      <div class="inpt-undrline-cont signs-inpt-cont">
        <input undrline-onfcs spellcheck="false" data-notvalid id="spl-email" class="neutralize-input f1_sys-sect_0__small signs-inpt__text" type="email" name="email" placeholder="Email"  autofocus value="{{ old('email') }}">
        <div class="inpt-undrline">
          <div id="spl-email-error" class="inpt-line__unfcsed inpt-line-negative"></div>
          <div class="inpt-line__fcsed inpt-line-neutral"></div>
          <div class="inpt-line__unfcsed"></div>
        </div>
      </div>


      <div class="inpt-undrline-cont signs-inpt-cont">
        <input undrline-onfcs spellcheck="false" data-notvalid class="neutralize-input f1_sys-sect_0__small signs-inpt__text" type="password" name="password" placeholder="Password" >
        <div class="inpt-undrline">
          <div id="spl-psword-error" class="inpt-line__unfcsed inpt-line-negative"></div>
          <div class="inpt-line__fcsed inpt-line-neutral"></div>
          <div class="inpt-line__unfcsed"></div>
        </div>
      </div>

      <div class="inpt-chckbx__flat">
        <input type="checkbox" name="remember" class="chckbx__flat__inpt" id="signs-remember" checked>
        <label for="signs-remember" class="chckbx__lable-cont">
          <div class="chckbx__cont">
            <div class="chckbx__checkmark">
            </div>
          </div>

          <span class="f1_sysinf-0-5" id="signs-remember__text">
            Remember Me
          </span>
        </label>
      </div>

      <button type="submit" class="signs-btn-submit submit-disabled neutralize-btn f3_title_1">
        Sign in
      </button>
    </form>

    <div id="sign-in-bottom">
      <span class="f3_title_2 signs-btn-neutral" id="signs-forgot">
        <a href="{{ route('password.request') }}">
          Forgot Password ?
        </a>
      </span>


      <span class="f1_sysinf-0-5">don't have an account ?</span>

      <button type="button" id="signup-refbtn" class="neutralize-btn f3_title_2 signs-btn-neutral" onclick="changeType('signup')">Sign up</button>

    </div>
  </div>
</section>
