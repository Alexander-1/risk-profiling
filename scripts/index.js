function change_page(page){
  $('#filter input[name="page"]').val(page);
  $('#filter').submit();
}

function add_phone(){
  var i = 0;
  $('input[name^="name"]').each(function(){
    i++;
  });
  $('#contacts').append('<tr><td><input type=text name="name['+i+']" value="" /></td><td><input type=text name="phone['+i+']" value="" /></td><td></td></tr>');
}

function check_cd(id){
  if ($('input[name="cd['+id+']"]') != undefined){
    if (($('input[name="count_little_photos['+id+']"]').val() == '' || $('input[name="count_little_photos['+id+']"]').val() == '0') && ($('input[name="count_big_photos['+id+']"]').val() == '' || $('input[name="count_big_photos['+id+']"]').val() == '0')){
      $('input[name="cd['+id+']"]').removeAttr('checked');
    }
    else{
      $('input[name="cd['+id+']"]').attr('checked','checked');
    }
  }
}            