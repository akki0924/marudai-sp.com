<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TOPICS｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">
<script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/scripts.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/place_input_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<form action="<?=site_dir();?>owner/place" id="select_form" name="select_form" method="post" accept-charset="utf-8">
<nav class="navbar navbar-expand bg-white navbar-light border-bottom">
   <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= site_dir(); ?>owner/main" class="nav-link" target="_blank">
        <i class="fa fa-external-link"></i>Nagoya art news</a>
      </li>
       <button type="button" class="btn btn-block btn-default float-right nav-link menu_btn" name="01_list">美術館</button>
       <button type="button" class="btn btn-block btn-default float-right nav-link menu_btn" name="02_list">ギャラリー</button>
       <button type="button" class="btn btn-block btn-default float-right nav-link menu_btn" name="03_list">デパート</button>
       <button type="button" class="btn btn-block btn-default float-right nav-link menu_btn" name="04_list">大学</button>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-key"></i> ログインユーザー：様</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-sign-out"></i> ログアウト</a></li>
    </ul>
</nav>
<input type='hidden' id='place_id' name='place_id' value='<?= isset ( $place_id ) ? $place_id : '' ?>'>
</form>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title">あいちトリエンナーレ実行委員会</p></div>
<div class="card-body">
	<div><form action="basic_info.html"><button type="submit" class="btn btn-block btn-default col-2 float-left mr-2" name="basic_info"><i class="fa fa-file-text-o"></i> 基本情報</button></form></div>
	<div><form action="topics.html"><button type="submit" class="btn btn-block btn-default col-2 float-left mr-2" name="TOPICS" disabled><i class="fa fa-check-square-o"></i> TOPICS</button></form></div>
	<div><form action="schedule.html"><button type="submit" class="btn btn-block btn-default col-2 mb-3" name="schedule"><i class="fa fa-calendar"></i> スケジュール</button></form></div><br>

	<div class="col-8 cont_center"><p class="card-title mb-4 text-center">TOPICS一覧</p></div>
	
	<div class="col-2 cont_center mb-5">
		<form action="topics_add.html"><button type="button" class="btn btn-block btn-outline-secondary" name="add_topics"><i class="fa fa-plus-circle"></i> 新規TOPICS追加</button></form></div>
		
	<div class="col-8 cont_center">
		<table class="table">
		  <tbody>
		    <tr>
		      <td width="70%"><p>あいちトリエンナーレ2019</p></td>
		      <td width="4%"><button type="button" class="btn btn-block btn-default" name="import"><i class="fa fa-edit"></i> 編集</button></td>
			  <td width="4%"><button type="button" class="btn btn-block btn-default" name="import"><i class="fa fa-times-circle"></i> 削除</button></td>
		      <td width="22%">
				  <label class="mr-3"><input type="radio" name="display1" value="ON"> ON</label>
				  <label><input type="radio" name="display1" value="OFF"> OFF</label></td>
		    </tr>
		    <tr>
		      <td width="70%"><p>あいちトリエンナーレ2019</p></td>
		      <td width="4%"><button type="button" class="btn btn-block btn-default" name="import"><i class="fa fa-edit"></i> 編集</button></td>
			  <td width="4%"><button type="button" class="btn btn-block btn-default" name="import"><i class="fa fa-times-circle"></i> 削除</button></td>
		      <td width="22%">
				  <label class="mr-3"><input type="radio" name="display2" value="ON"> ON</label>
				  <label><input type="radio" name="display2" value="OFF"> OFF</label></td>
		    </tr>
		  </tbody>
		</table>

	</div>


</div>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>


</body>
</html>
