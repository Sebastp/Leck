$(document).mouseup(function (e)
{
    var container = $('popup-box-inner');
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0
        && !$('[data-has-popup]').is(e.target)
        && $('[data-has-popup]').has(e.target).length === 0) // ... nor a descendant of the container ||
    {
        container.hide();
    }

    if (!isSvg(e.target)) {
      if (e.target.className.includes('modal-bckground') || e.target.className.includes('modal-close')) {
        $('body').removeClass('modalMode')
        findParentBySelector(e.target, '[modal-cont]').style.display = 'none';
      }
    }

    if (e.target.hasAttribute('action-follow')) {
      var usr2followId = e.target.getAttribute('action-follow');
      followUser(e.target, usr2followId);
    }
});

/*$('.modal-bckground, .modal-close').click(function() {
  var thelem = $(this);
  if (thelem.parent('[modal-cont]').length) {
    thelem.parent('[modal-cont]').hide();
  }else {
    while (!thelem.parent('[modal-cont]').length) {
      thelem = thelem.parent();
      thelem.parent('[modal-cont]').hide();
    }
  }
});*/

$('[data-has-popup]').click(function() {
  var popChild = $(this).find('popup-box-inner');
  if (popChild.length == 0){
    var popChild = $('popup-box-inner[data-popup-id='+$(this).attr('data-popup-id')+']');
  }

  if (popChild.css('display') != 'none') {
    $('popup-box-inner').hide();
  }else {
    $('popup-box-inner').hide();
    popChild.show();
  }
});



$('[drp-down-id].drp-down__onechoice li').click(function() {
  var drpDown_id = $(this).parent('[drp-down-id]').attr('drp-down-id');
  $('#'+drpDown_id).val($(this).text()).attr('real-val', $(this).attr('drp-down-ival'));
});





window.collectionHas = function(a, b) { //helper function (see below)
  for(var i = 0, len = a.length; i < len; i ++) {
    if(a[i] == b) return true;
  }
  return false;
}

window.findParentBySelector = function(elm, selector){
    if (selector[0] == '#') {
      var all = [document.querySelector("[id='"+selector.slice( 1 )+"']")];
      if (all == null) {
        return null;
      }
    }else {
      var all = document.querySelectorAll(selector);
    }

    var cur = elm.parentNode;
    while(cur && !collectionHas(all, cur)) { //keep going up until you find a match
        cur = cur.parentNode; //go up
    }
    return cur; //will return null if not found
}


window.isElemOrChild = function(elm, selector) {
  if (selector[0] == '#') {
    if (elm.id == selector.slice( 1 )) {
      var isElem = 1;
    }else {
      var isElem = 0;
    }
  }else if(selector[0] == '.'){
    if (typeof elm.className != 'object' && elm.className.includes(selector.slice( 1 ))) {
      var isElem = 1;
    }else {
      var isElem = 0;
    }
  }else if(selector[0] == '['){
    // trimedSelector = selector.substring(0, selector.length - 2);
    trimedSelector = selector.replace(/[\[\]']+/g,'');
    if (elm.hasAttribute(trimedSelector)) {
      var isElem = 1;
    }else {
      var isElem = 0;
    }
  }else {
    if (elm.tagName == selector.toUpperCase()) {
      var isElem = 1;
    }else {
      var isElem = 0;
    }
  }

  if (isElem) {
    return 1;
  }else {
    var parRet = findParentBySelector(elm, selector);
    if (parRet != null) {
      return 1;
    }else {
      return 0;
    }
  }
}





Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

Array.prototype.unique = function() {
    var a = this.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

Array.prototype.spliceArray = function(index, n, array) {
    return this.splice.apply(this, [index, n].concat([array]));
};



window.isSvg = function(el){
  return ['svg', 'path', 'rect', 'g', 'circle', 'ellipse', 'polygon', 'polyline'].includes(el.nodeName);
};
