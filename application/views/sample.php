<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>サンプル01</title>
<link rel="stylesheet" href="<?= SiteDir(); ?>sample/css">
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>sample/js"></script>
<script>
// 読込み完了時
$(function() {
   $('.btn').click(function(){
      AjaxAction('sample/ajax');
   });
});

</script>
</head>

<body>
サンプル01ページ
<span class='red'>red</span>
<a class="btn">btn</a>
<br>
<span id="ajax"></span>
</body>
</html>
