window.onmousewheel = function(e){
  var downBarScroll = $(window).scrollTop()+window.innerHeight,
      tillDown = document.body.scrollHeight - downBarScroll;
  if (tillDown < 550) {
    downloadScrollData();
  }
}

function appendNewElems(htmlArr){
  var recContsArr = document.getElementsByClassName('hm-recomm-main'),
      lastRecCont = recContsArr[recContsArr.length -1];
  for (var h = 0; h < htmlArr.length; h++) {
    newNode = document.createElement("li");
    newNode.className =  'hm-recomm-item';
    newNode.innerHTML = htmlArr[h];
    lastRecCont.appendChild(newNode);
  }
}



function downloadScrollData(){
  var itmContsArr = document.getElementsByClassName('hm-recomm-main'),
      visibleNr = 0;
  for (var c = 0; c < itmContsArr.length; c++) {
    visibleNr += itmContsArr[c].children.length;
  }


  $.ajax({
    method: 'POST',
    url: '/hm_scrollLoad',
    dataType: 'json',
    data: {
      _token: window.Laravel.csrfToken,
      visibleNr: visibleNr
    }
  })
  .done(function(data) {
    if (data.success) {
      console.log(data);
      appendNewElems(data.appendData);
    }
  })
}



function progWrLoading(){
  var imgDomElms = document.getElementsByClassName('progLoad-tmp'),
  newImgs = [];
  for (var h = 0; h < imgDomElms.length; h++) {
    var image = imgDomElms[h];

    newImgs[h] = new Image();
    newImgs[h].onload = function(){
      var image = findParentBySelector(this, '.progLoad-cont').getElementsByClassName('prog-base')[0];

      image.src = this.src;
      this.className += ' prog-load__fadeout'
      setTimeout( function() {
        this.remove();
      }, 1200);
      this.style.position = 'absolute';
    };
    newImgs[h].src = image.getAttribute('data-src');
    image.style.display = 'block';
  }
}
