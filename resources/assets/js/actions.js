window.followUser = function(follBtn, f_id){
  if (!userLogged) {
    return;
  }

  if (!follBtn.className.includes('btn-action-half')) {
    follBtn.className += ' btn-action-half';
  }else {
    follBtn.classList.remove('btn-action-half');
  }

  $.ajax({
    method: 'POST',
    url: '/follow_usr',
    dataType: 'json',
    data: {
      _token: window.Laravel.csrfToken,
      followed: f_id
    }
  })
  .done(function(data) {
    if (!data.success) {
      if (!follBtn.className.includes('btn-action-half')) {
        follBtn.className += ' btn-action-half';
      }else {
        follBtn.classList.remove('btn-action-half');
      }

      alert('error');
    }
  })
  .fail(function() {
    if (!follBtn.className.includes('btn-action-half')) {
      follBtn.className += ' btn-action-half';
    }else {
      follBtn.classList.remove('btn-action-half');
    }

    alert('error');
  })
}





window.likeAction = function(e){
  if (!userLogged) {
    return;
  }
  if (typeof likeTimer != 'undefined') {
    clearInterval(likeTimer);
  }

  var el = e.target,
      likeCont = findParentBySelector(el, '.like-container'),
      likesNr = parseInt(likeCont.getAttribute('data-likesnr')),
      newNr = likesNr+1,
      chngNr = 1,
      timmerMS = 500;


  if (e.button == 2) {
    newNr = likesNr-1,
    chngNr = -1,
    timmerMS = 300;
  }

  changeLike(likeCont, newNr);
  likeTimer = setInterval(function(){
    likeTimmerFnc(el, chngNr);
  }, timmerMS);
}

window.likeTimmerFnc = function(el, chngNr){
  var likeCont = findParentBySelector(el, '.like-container'),
  likesNr = parseInt(likeCont.getAttribute('data-likesnr'));
  changeLike(likeCont, likesNr+chngNr);
}

window.changeLike = function(el, newNr){
  if (newNr<11 && newNr >= 0) {
    var usrNrNode = el.getElementsByClassName('like-usr__likes')[0],
        posRateBar = el.getElementsByClassName('like-ratebar-positive')[0],
        counterId = el.getAttribute('data-counter-id');

    if (counterId != null) {
      var counterEl = document.getElementById(counterId);
      if (counterEl != null) {
        counterEl.innerText = parseInt(counterEl.getAttribute('data-base-likes'))+newNr;
      }
    }
    el.setAttribute('data-likesnr', newNr);
    usrNrNode.innerText = newNr;
    posRateBar.style.height = newNr*10+'%';
    saveLikes(newNr);
  }
}


window.saveLikes = function(likesNr){

  $.ajax({
    method: 'POST',
    url: writing_id+'/react',
    dataType: 'json',
    data: {
      _token: window.Laravel.csrfToken,
      likes: likesNr
    }
  })
  .done(function(data) {
    if (!data.success) {

      alert('error');
    }
  })
  .fail(function() {
    alert('error');
  })
}
