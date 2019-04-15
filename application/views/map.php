<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>東海の展覧会情報 ナゴヤアートニュース ： あいちトリエンナーレ</title>
<link href="<?= site_dir(); ?>css/reset.css" rel="stylesheet" type="text/css">
<link href="<?= site_dir(); ?>css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<header>
		<div id="navi">
			<ul class="menu">
				<li>TOP</li>
				<li>ABOUT</li>
				<li>CATEGORY</li>
				<li>CONTACT</li>
			</ul>
		</div>
	</header>
	
	<div id="title">
		<div class="titleLogo">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<th>
					<a href="index.html"><img src="<?= site_dir(); ?>images/logo.png" /></a>
				</th>
				<td>
					<p>東海の展覧会情報<span class="txt_none"><br /></span>ナゴヤアートニュース</p>
				</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div id="wrapper">
	
		<div class="container">
			<div class="con_txt">
				<table border="0" cellspacing="0" cellpadding="0">
				  <tbody>
				    <tr>
				      <td class="date">2018.10.01～2018.11.30</td>
				      <td class="number">163</td>
			        </tr>
			      </tbody>
			  </table>
					
			</div>
	
			<div class="main">
                <div class="map">
					<div class="mapTop">
	                <p>検索エリア
	                  <input type="search">
	                  （調べたい場所・現在地・駅名など）
	                  <input type="button" value="検索">
	                </p>
	                <p>例： <a href="名古屋市">名古屋市</a>　　<a href="名古屋 栄">名古屋 栄</a>　　<a href="名古屋 金山駅">名古屋 金山駅</a>　　<a href="岐阜市">岐阜市</a></p>
					</div>
                 
				  <div class="mapMiddle">
				  	<p><img src="<?= site_dir(); ?>images/map_img.jpg" alt="map" style="width: 100%;" /></p>
					<p><label><input type="radio" name="RadioGroup1" value="全て" id="RadioGroup1_0">全て</label>
					　　
					  <img src="<?= site_dir(); ?>images/map_icon00.png" align="absmiddle"/>から
					  <label><input type="radio" name="RadioGroup1" value="1㎞以内" id="RadioGroup1_1">1㎞以内</label>
					 <label><input type="radio" name="RadioGroup1" value="3㎞以内" id="RadioGroup1_2">3㎞以内</label>
					 <label><input type="radio" name="RadioGroup1" value="5㎞以内" id="RadioGroup1_3">5㎞以内</label>
					 <label><input type="radio" name="RadioGroup1" value="10㎞以内" id="RadioGroup1_4">10㎞以内</label></p>
					<p class="">検索エリア中央からの決まった距離以内の施設に限らせるには、上記のボタンを使ってください。</p>
				  	<p>または、距離に限らず、すべての施設を表示するには、「全て」をクリックしてください。</p>
				  </div>
              
             	  <div class="mapBottom">
             	  	<ul>
						<li><img src="<?= site_dir(); ?>images/map_icon00.png" align="absmiddle">検索エリア中央</li>
						<li><img src="<?= site_dir(); ?>images/map_icon01.png" align="absmiddle">美術館</li>
						<li><img src="<?= site_dir(); ?>images/map_icon02.png" align="absmiddle">ギャラリー</li>
						<li><img src="<?= site_dir(); ?>images/map_icon03.png" align="absmiddle">デパート</li>
						<li><img src="<?= site_dir(); ?>images/map_icon04.png" align="absmiddle">大学</li>
					</ul>
				  </div>

              
              </div>
					
			</div>
			
			<div class="side">
				<h4 class="sideH4">会場検索</h4>
				<div class="mapSearch">
					<h5>施設名・展示会名</h5>
						<input type="text" value="美術館・展示会名"> 
						<input type="submit" value="検索">
						<hr>
						
					<h5>会場の種類</h5>
						<p class="choice">
						  <label>
							<input type="checkbox" name="CheckboxGroup1" value="美術館" id="CheckboxGroup1_0">
							美術館</label>
						  <br>
						  <label>
							<input type="checkbox" name="CheckboxGroup1" value="ギャラリー" id="CheckboxGroup1_1">
							ギャラリー</label>
						  <br>
						  <label>
							<input type="checkbox" name="CheckboxGroup1" value="デパート" id="CheckboxGroup1_2">
							デパート</label>
						  <br>
						  <label>
							<input type="checkbox" name="CheckboxGroup1" value="大学" id="CheckboxGroup1_3">
							大学</label>
						  <br>
						</p>
						<hr>

						<h5 class="mapFilter">現在の展示会のフィルター</h5>
						<input type="checkbox"><span class="choice2">日付で検索</span>
						<p class="org">日付範囲内で開催されている展示会を探すには、上のチェックを入れてください。</p>
              			<input type="checkbox"><span class="choice2">ジャンルで検索</span>
              			<p class="org">展示会のジャンルで検索するには、上のチェックを入れてください。</p>
               
                </div>

				<h4 class="sideH4">会場情報</h4>
				
				<ul class="sideMenu">
					<li>美術館スケジュール</li>
					<li>ギャラリースケジュール</li>
					<li>デパートスケジュール</li>
					<li>大学スケジュール</li>
				</ul>
				
				<ul class="notes">
					<li>美術館・画廊へお越しの際は、公共交通機関をご利用ください。</li>
					<li>都合により、展覧会・会期等が変更になる場合があります。</li>
					<li>展覧会についてのお問い合わせは、各美術館・画廊までお願いします。</li>
				</ul>
				
			  <div class="sideTxt">
				  	<h5>著作権について</h5>
					<p>当サイトに掲載されている作品画像・作品写真などの全てのコンテンツの著作権は所蔵元、もしくはその利用を認められた権利者に帰属します。<br>
					当サイト内の各コンテンツの無断転用は固くお断りします。</p>
				</div>
				
			  <div class="sideTxt">
				  	<h5><img src="<?= site_dir(); ?>images/nafa.png" alt="nafa"></h5>
				 	<p align="center"><img src="<?= site_dir(); ?>images/jimukyoku.png"></p>
				</div>
				
				<div class="sideTxt">
					<h5>私達はNagpya Art Newsを通して東海三県の美術振興を応援しています。</h5>
				  <ul class="sideIcon">
						<li><img src="<?= site_dir(); ?>images/side_icon01.png"></li>
						<li><img src="<?= site_dir(); ?>images/side_icon02.png"></li>
						<li><img src="<?= site_dir(); ?>images/side_icon03.png"></li>
						<li><img src="<?= site_dir(); ?>images/side_icon04.png"></li>
						<li><img src="<?= site_dir(); ?>images/side_icon05.png"></li>
						<li><span class="txt_none2"><img src="<?= site_dir(); ?>images/side_icon06.png"></span></li>
					</ul>
				
				</div>				
				
			</div>
		
		</div>
	
	</div>
	
	<footer>
		<div class="f_contents">
		<p>footer</p>
		</div>
	</footer>
</body>
</html>
