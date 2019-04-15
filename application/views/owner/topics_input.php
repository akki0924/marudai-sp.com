<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>新規TOPICS追加｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">
</head>
<body>

<!-- ヘッダー-->
<nav class="navbar navbar-expand bg-white navbar-light border-bottom">
   <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="http://artnews.jp/" class="nav-link" target="_blank">
		<i class="fa fa-external-link"></i>Nagoya art news</a>
      </li>
       <form action="#.html"><button type="submit" class="btn btn-block btn-default float-right nav-link" name="01_list">美術館</button></form>
       <form action="#.html"><button type="submit" class="btn btn-block btn-default float-right nav-link" name="02_list">ギャラリー</button></form>
       <form action="#.html"><button type="submit" class="btn btn-block btn-default float-right nav-link" name="03_list">デパート</button></form>
       <form action="#.html"><button type="submit" class="btn btn-block btn-default float-right nav-link" name="04_list">大学</button></form>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-key"></i> ログインユーザー：平林様</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-sign-out"></i> ログアウト</a></li>
    </ul>
  </nav>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title">あいちトリエンナーレ実行委員会</p></div>
<div class="card-body">
	<div class="col-6 cont_center">
		<p class="card-title mb-4 text-center">新規TOPICS追加</p>
		タイプ<label class="ml-5 mr-4"><input type="radio" name="type" value="1"> 1</label><label class="mr-4"><input type="radio" name="type" value="2"> 2</label><label><input type="radio" name="type" value="3"> 3</label><br><br>
		タイトル<input type="input" class="form-control" placeholder="" name="title"><br>
		サブタイトル<input type="input" class="form-control" placeholder="" name="sub_title"><br>
		開始日
		<div>
			<select name="start_date" class="form-control col-2 float-left mr-2">
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
				<option value="2023">2023</option>
				<option value="2024">2024</option>
			</select>
		</div>
		<div>
			<select name="start_date" class="form-control col-3 float-left mr-2">
				<option value="January">January</option>
				<option value="February">February</option>
				<option value="March">March</option>
				<option value="April">April</option>
				<option value="May">May</option>
				<option value="June">June</option>
				<option value="July">July</option>
				<option value="August">August</option>
				<option value="September">September</option>
				<option value="October">October</option>
				<option value="November">November</option>
				<option value="December">December</option>
			</select>
		</div>
		<div>
			<select name="start_date" class="form-control col-1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
		</div><br>
		終了日
		<div>
			<select name="end_date" class="form-control col-2 float-left mr-2">
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
				<option value="2023">2023</option>
				<option value="2024">2024</option>
			</select>
		</div>
		<div>
			<select name="end_date" class="form-control col-3 float-left mr-2">
				<option value="January">January</option>
				<option value="February">February</option>
				<option value="March">March</option>
				<option value="April">April</option>
				<option value="May">May</option>
				<option value="June">June</option>
				<option value="July">July</option>
				<option value="August">August</option>
				<option value="September">September</option>
				<option value="October">October</option>
				<option value="November">November</option>
				<option value="December">December</option>
			</select>
		</div>
		<div>
			<select name="end_date" class="form-control col-1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
		</div><br>
		休館日<textarea class="form-control" name="closing_day" rows="5"></textarea><br>
		展覧会内容<textarea class="form-control" name="exhibiiton_cont" rows="8"></textarea><br>
		次回開催情報<textarea class="form-control" name="next_time" rows="8"></textarea><br>
		備考<textarea class="form-control" name="note" rows="5"></textarea><br>
		画像<div><input type="file" name="import-order" size="30"></div><br>
		画像キャプション<input type="input" class="form-control mb-5" placeholder="" name="caption"><br>
		
		<button type="submit" class="cont_center btn btn-block btn-info col-3 mb-2" name="decision">決定</button>
	</div>



</div>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>


</body>
</html>
