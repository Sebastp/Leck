$('.w-tree-icon, #empty-action__0').click(function() {
  $('#w-modal-treeview').css('display', 'flex');
  $('body').addClass('modalMode');

  // if (!$('#treeview-inner-tree')[0].children.length) {
    getTreeView();
  // }
});


$('#treeview-top-view').click(function() {
  var currAct = $('#treeview-inner-tree')[0].getElementsByClassName('treeview-itm__active')[0];
  changeSectionsDOM(currAct.getAttribute('tree-section-id'));
  findParentBySelector($(this)[0], '[modal-cont]').style.display = 'none';
  $('body').removeClass('modalMode')
  $('#treeview-inner-cont .loader').hide();
});


$('#treeview-top-del').click(function() {
  var currAct = $('#treeview-inner-tree')[0].getElementsByClassName('treeview-itm__active')[0];

  if (currAct.getAttribute('sect-first') != '1') {
    $('#action__question-mainmsg').html('Are you sure ?</br>Readers with progress after this section will be returned</br>to the section behind');

    $('#action__question-action')[0].onclick = function(){
      var selectedSectId = $('#treeview-inner-tree')[0].getElementsByClassName('treeview-itm__active')[0].getAttribute('tree-section-id');
      deleteSection(selectedSectId);
      $('#w-modal-action__question').hide();
    }

    $('#action__question-action').text('Delete');
    $('#w-modal-action__question')[0].style.display = 'flex';
  }else {
    $('#action__question-mainmsg').html("You can't delete base section");

    $('#action__question-cancel')[0].onclick = function(){
      $('#action__question-sub-mainmsg').text('This action is irreversible');
    }

    $('#action__question-sub-mainmsg').text('');
    $('#action__question-action').text('');
    $('#w-modal-action__question')[0].style.display = 'flex';
  }
});


function deleteSection(sectId){
  $.ajax({
    method: 'POST',
    url: writing_id+'/save',
    dataType: 'json',
    data: {
      _token: csrf_token,
      delete_sect: sectId
    }
  })
  .done(function(data) {
    var nrOfUsers = data.data;
    if (nrOfUsers) {
      if (nrOfUsers == 1) {
        var innerMsg = nrOfUsers+' user is';
      }else {
        var innerMsg = nrOfUsers+' users are';
      }
      $('#action__question-mainmsg').html('Are you sure ?</br>'+innerMsg+' on this path');
      $('#action__question-action')[0].onclick = function(){
        splitDrasticActions(subject, action);
        $('#w-modal-action__question').hide();
      }

      if (action == 'remove') {
        $('#action__question-action').text('Remove');
      }else if(action == 'next_change'){
        $('#action__question-action').text('Change');
      }

      $('#w-modal-action__question')[0].style.display = 'flex';
    }else {
      splitDrasticActions(subject, action);
    }
  })
  .fail(function() {
    saveProgress('error');
  });
}


function getTreeView(){
  //del curr
  var currNodes = Array.from($('#treeview-inner-tree')[0].children);
  for (var i = 0; i < currNodes.length; i++) {
    currNodes[i].remove();
  }
  $.ajax({
    method: 'POST',
    url: writing_id+'/info',
    dataType: 'json',
    data: {
        _token: csrf_token,
        tree_view: writing_id
    }
  })
  .done(function(data) {
    if (data.success) {
      $('#treeview-inner-cont .loader').hide();
      if (data.data) {
        var currVisibleSects = document.getElementById('w-content-cont').getElementsByClassName('w-section-cont'),
            currVisibleIds = [];
        for (var i = 0; i < currVisibleSects.length; i++) {
          currVisibleIds.push(parseInt(currVisibleSects[i].id));
        }

        for (var tdi = 0; tdi < data.data.length; tdi++) {
          $('#treeview-inner-tree').append(data.data[tdi].html);
          var currAddedElem = document.querySelectorAll('[tree-section-id="'+data.data[tdi].id+'"]')[0];
          if (currVisibleIds.includes(data.data[tdi].id)) {
            currAddedElem.className += ' tree-itm-curr'; //select current elem
          }

          currAddedElem.addEventListener("click", function(){setTreeItmActive(parseInt(this.getAttribute('tree-section-id')))});
        }
      }else {
        $('#treeview-empty').show();
      }
    }else {
      console.log('error');
    }
  })
  .fail(function() {
    console.log('error');
  });
}



function setTreeItmActive(sectId){
  var wantedNode = $('#treeview-inner-tree')[0].querySelector('[tree-section-id="'+sectId+'"]'),
      activeNode = $('#treeview-inner-tree')[0].getElementsByClassName('treeview-itm__active');

  for (var i = 0; i < activeNode.length; i++) {
    activeNode[i].classList.remove("treeview-itm__active");
  }

  if (!wantedNode.className.includes('treeview-itm__active')) {
    wantedNode.className += ' treeview-itm__active';
  }
}
