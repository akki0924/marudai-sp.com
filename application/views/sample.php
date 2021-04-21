<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>サンプル01</title>
<link href="<?= SiteDir(); ?>css/<?= JqueryUiCssFile() ?>" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="<?= SiteDir(); ?>sample/css">

<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script language = "JavaScript" src="<?= SiteDir(); ?>js/<?= JqueryUiJsFile() ?>"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>sample/js"></script>
<script>
// 読込み完了時
$(function() {
   $('.btn1').click(function(){
      AjaxAction('sample/ajax');
   });
   $('.btn2').click(function(){
      var mainObj = {
         title:'たいとる',
         body:'これは<br>テスト<br>です'
      };
      ShowDialog(mainObj);
   });
   $('.edit_btn').click(function() {
      $('#id').val( $(this).data('id') );
      $('#operation_form').attr( 'action', '<?= SiteDir(); ?>admin/XXX/input' );
      $('#operation_form').submit();
   });
   $('.conf_btn').click(function() {
      $('#action').val( 'conf' );
      $('#operation_form').submit();
   });
});
</script>

</head>
<body>
サンプル01ページ
<span class='red'>red</span>
<br>
<a class="btn1 form_btn">btn1</a><br>
<a class="btn2 form_btn">btn2</a><br>
<a class="edit_btn form_btn">edit_btn</a><br>
<br>
<span id="ajax"></span>
<br>
<div class="form_error">ERRROR1</div>
<br>
<span class="form_error">ERRROR2</span>

</body>
</html>
