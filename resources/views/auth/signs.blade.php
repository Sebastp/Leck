<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('partials/_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/signs.css') }}">
  </head>
  <body>
    @include('partials.topbars._topbar', ['type' => 'clear', 'still' => true])

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container__1 signs-cont">
      <section id="sign-up" class="sign-section" @if ($view_type == "signin") style='display: none' @endif>
        <div class="signs-left">
          <h2 class="f3_title_0-5 signs-left-header">Create Acount now</h2>
          <p class="f1_content-1__light">Leck is the platform for writers and story lovers. Our goal is to
            introduce to as many people as posible our new idea of
            telling the stories, called Inook</p>
        </div>

        <div class="signs-right">
          <div class="signs-right-header">
            <h2 class="f1_sys-sect_0__big">Sign up</h2>
            <p class="f3_title_2 signs-right-header__sub"><span class="f3_0">or</span> <a href="#" class="signs-btn-neutral">Sign up with Facebook</a></p>
          </div>
          <form class="signs-form" novalidate role="form" action="{{ route('register') }}" method="POST">
            {{ csrf_field() }}

            <div class="inpt-undrline-cont signs-inpt-cont" id="spl-up-usrnm-cont">
              <div class="inpt-undrline-top">
                <input undrline-onfcs spellcheck="false" class="neutralize-input f1_sys-sect_0__small signs-inpt__text" id="spl-up-usrnm" type="text" name="username" placeholder="User name"  value="{{ Request::old('user_name') }}">
                <div class="right-side" data-has-popup>
                  <leck-popup-cont>
                    <popup-box-inner class="error-popup1" data-popup-id="spl-up-usrnm">
                      <div class="error-popup1-inner">
                        <div class="error-popup1-left">
                          <span class="f1_sysinf-0-5">!</span>
                        </div>

                        <div class="error-popup1-right brdr f1_sysinf-0-5 error-popup1-msg-cont">
                        </div>
                      </div>
                    </popup-box-inner>
                  </leck-popup-cont>
                  <div class="error-min f1_sysinf-0-5" data-has-popup="spl-up-usrnm">
                    !
                  </div>
                </div>
              </div>
              <div class="inpt-undrline">
                <div id="spl-up-usrnm-error" class="inpt-line__unfcsed inpt-line-negative"></div>
                <div class="inpt-line__fcsed inpt-line-neutral"></div>
                <div class="inpt-line__unfcsed"></div>
              </div>
            </div>

            <div class="inpt-undrline-cont signs-inpt-cont" id="spl-up-email-cont">
              <div class="inpt-undrline-top">
                <input undrline-onfcs spellcheck="false" class="neutralize-input f1_sys-sect_0__small signs-inpt__text" id="spl-up-email" type="email" name="email" placeholder="Email"  value="{{ Request::old('email') }}">
                <div class="right-side" data-has-popup>
                  <leck-popup-cont>
                    <popup-box-inner class="error-popup1" data-popup-id="spl-up-email">
                      <div class="error-popup1-inner">
                        <div class="error-popup1-left">
                          <span class="f1_sysinf-0-5">!</span>
                        </div>

                        <div class="error-popup1-right brdr f1_sysinf-0-5 error-popup1-msg-cont">
                        </div>
                      </div>
                    </popup-box-inner>
                  </leck-popup-cont>
                  <div class="error-min f1_sysinf-0-5" data-has-popup="spl-up-email">
                    !
                  </div>
                </div>
              </div>
              <div class="inpt-undrline">
                <div id="spl-up-email-error" class="inpt-line__unfcsed inpt-line-negative"></div>
                <div class="inpt-line__fcsed inpt-line-neutral"></div>
                <div class="inpt-line__unfcsed"></div>
              </div>
            </div>


            <div class="inpt-undrline-cont signs-inpt-cont" id="spl-up-psword-cont">
              <div class="inpt-undrline-top">
                <input undrline-onfcs spellcheck="false" class="neutralize-input f1_sys-sect_0__small signs-inpt__text" id="spl-up-psword" type="password" name="password" placeholder="Password" >
                <div class="right-side" data-has-popup>
                  <leck-popup-cont>
                    <popup-box-inner class="error-popup1" data-popup-id="spl-up-psword">
                      <div class="error-popup1-inner">
                        <div class="error-popup1-left">
                          <span class="f1_sysinf-0-5">!</span>
                        </div>

                        <div class="error-popup1-right brdr f1_sysinf-0-5 error-popup1-msg-cont">
                        </div>
                      </div>
                    </popup-box-inner>
                  </leck-popup-cont>
                  <div class="error-min f1_sysinf-0-5" data-has-popup="spl-up-psword">
                    !
                  </div>
                </div>
              </div>
              <div class="inpt-undrline">
                <div id="spl-up-psword-error" class="inpt-line__unfcsed inpt-line-negative"></div>
                <div class="inpt-line__fcsed inpt-line-neutral"></div>
                <div class="inpt-line__unfcsed"></div>
              </div>
            </div>

            <p class="f1_sysinf-0-25" id="termsPPtext">by signing in I accept the <a href="#">Terms of Services</a>
                and <a href="#">Privacy Policy</a>.</p>

            <button type="submit" class="signs-btn-submit submit-disabled neutralize-btn f3_title_1">
              Sign up
            </button>

            <span class="f1_sysinf-0-5">already registered?</span>
            <button type="button" class="neutralize-btn f3_title_2 signs-btn-neutral" onclick="changeType('signin')">Sign in</button>
          </form>
        </div>
      </section>

      @include('auth._login')
    </div>
    @include('partials._scripts')
    <script type="text/javascript">
      var csrf_token = "{{csrf_token()}}";

      $('#sign-up .signs-btn-submit').click(function (e){
        if ($(this).hasClass('submit-disabled')) {
          e.preventDefault();
        }else {
          e.preventDefault();
          var notVaild = [];
          
          $.ajax({
            method: 'POST',
            url: '/register_valid',
            dataType: 'json',
            data: {
              _token: csrf_token,
              username: $('#spl-up-usrnm').val(),
              email: $('#spl-up-email').val(),
              password: $('#spl-up-psword').val(),
            }
          })
          .done(function(r) {
            if (!r.valid) {
              var errObj = JSON.parse(r.errors);
              for (var key in errObj) {
                switch (key) {
                  case 'user_name':
                    var fieldCont = $('#spl-up-usrnm-cont');
                  break;
                  case 'email':
                    var fieldCont = $('#spl-up-email-cont');
                  break;
                  case 'password':
                    var fieldCont = $('#spl-up-psword-cont');
                  break;
                }

                notVaild.push(fieldCont);
                var msgCont = fieldCont[0].getElementsByClassName('error-popup1-msg-cont')[0];

                msgCont.innerHTML = '';
                for (var k = 0; k < errObj[key].length; k++) {
                  var newErrEl = document.createElement('span');
                  newErrEl.innerText = errObj[key][k];
                  msgCont.append(newErrEl);
                }
                fieldCont.find('.error-min').fadeIn(200);
              }

              for (var n = 0; n < notVaild.length; n++) {
                if(notVaild[n].find('.inpt-line-negative').css.display != 'none'){
                  notVaild[n].find('.inpt-line-negative').show();
                }
              }
            }else {
              $('#sign-up .signs-form').submit();
            }
          })
          .fail(function() {
            alert('Sorry, something went wrong. Please try later');
          })
        }

        var txtFields = $('#sign-up').find('.signs-inpt__text'),
            emptyFields = [];
        for (var i = 0; i < txtFields.length; i++) {
          if (txtFields[i].value.length == 0) {
            emptyFields.push('err');
            continue;
          }else {
            emptyFields.push(null);
          }
        }

        for (var e = 0; e < emptyFields.length; e++) {
          var errorEl = findParentBySelector(txtFields[e], '.signs-inpt-cont').getElementsByClassName('inpt-line-negative')[0];
          if (emptyFields[e] == null) {
            continue;
          }else{
            if(emptyFields[e]=='err'){
              $(errorEl).show();
            }
          }
        }
      });


      $('.signs-inpt__text').bind('propertychange change keyup input paste', function(){
        var errorEl = findParentBySelector($(this)[0], '.signs-inpt-cont').getElementsByClassName('inpt-line-negative')[0],
            errInfoMin = findParentBySelector($(this)[0], '.inpt-undrline-top'),
            txtFields = findParentBySelector($(this)[0], '.signs-form').getElementsByClassName('signs-inpt__text'),
            submitBtn = findParentBySelector($(this)[0], '.signs-form').getElementsByClassName('signs-btn-submit')[0],
            allValid = true;

        if (errInfoMin != null) {
          errInfoMin = errInfoMin.getElementsByClassName('error-min')[0];
          $(errInfoMin).fadeOut(80);
        }
        $(errorEl).fadeOut(200);
        for (var i = 0; i < txtFields.length; i++) {
          if (txtFields[i].value.length == 0) {
            allValid = 0;
          }
        }

        if (allValid) {
          if (submitBtn.className.includes('submit-disabled')) {
            submitBtn.classList.remove('submit-disabled')
          }
        }else {
          if (!submitBtn.className.includes('submit-disabled')) {
            submitBtn.className += ' submit-disabled';
          }
        }
      });

      function changeType(toType){
        if (toType == 'signin') {
          $('#sign-up').hide();
          $('#sign-in').show();
        }else {
          $('#sign-in').hide();
          $('#sign-up').show();
        }
      }
    </script>
  </body>
</html>
