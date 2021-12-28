
$(function() {

  $('#btn_record').toggleClass('hidden');

  $('#works_conf2').on('click', function() {
      if ($(this).prop('checked') == true) {
        $('#btn_record').removeClass('hidden');
      } else {
        $('#btn_record').toggleClass('hidden');
      }
    });
 
 });