@if (!Auth::check())
  <link rel="stylesheet" type="text/css" href="{{ asset('css/signs.css') }}">
  <div id="signin-modal" class="w-modal-cont signs-modal" modal-cont>
    <div class="w-modal-innercont">
      <div class="signin-modal-cont w-modal-window">
        @if (count($errors) > 0)

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

        @endif

        @include('auth._login', ['from_modal' => true])
      </div>
    </div>
    <div class="modal-bckground"></div>
  </div>

  <script type="text/javascript">
    var userLogged = false;

    $('#sign-in .signs-btn-submit').click(function (e){
      if ($(this).hasClass('submit-disabled')) {
        e.preventDefault();
        var notVal = document.querySelectorAll('[data-notvalid]');
        for (var i = 0; i < notVal.length; i++) {
          signModalNegativeState(notVal[i], 1);
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
        if (txtFields[i].value.length < 2) {
          txtFields[i].setAttribute('data-notvalid', null);
          allValid = 0;
        }else {
          txtFields[i].removeAttribute('data-notvalid');
          signModalNegativeState(txtFields[i], 0);
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

    function signModalNegativeState(el, state){
      var elCont = findParentBySelector(el, '.signs-inpt-cont');
      var negLine = elCont.getElementsByClassName('inpt-line-negative')[0];
      if (state) {
        negLine.style.display = 'block';
      }else {
        negLine.style.display = 'none';
      }
    }

    window.addEventListener('click', function(e){
      if(window.isElemOrChild(e.target, '[data-need-auth]')){
        e.preventDefault();
        document.getElementById('signin-modal').style.display = 'flex';
        $('body').addClass('modalMode');
      }
    });
  </script>
@else
  <script type="text/javascript">
    var userLogged = true;
  </script>
@endif
