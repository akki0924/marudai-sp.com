<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ログイン|Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">

<div class="login-box">

<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Nagoya art news ログイン</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="<?= site_dir(); ?>admin/index" method="post">
                <div class="card-body">

                <label for="inputPassword3" class="col-sm-6 control-label">ユーザーID</label>
		        <div class="form-group has-feedback">
						<div class="input-group mb-3">
		                  <div class="input-group-prepend">
		                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                          </div>
                          <input type="text" class="form-control" placeholder="" name="account" value="<?=$form['account'];?>">
                          <?php if (form_error('account')) : ?>
                          <br>
                          <span class="errors"><?= form_error('account'); ?></span><br>
                          <br>
                          <?php endif; ?>
		                </div>
		        </div>
                  
                <label for="inputPassword3" class="col-sm-6 control-label">パスワード</label>
		        <div class="form-group has-feedback">
						<div class="input-group mb-3">
		                  <div class="input-group-prepend">
		                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
		                  </div>
                          <input type="password" class="form-control" placeholder="" name="password" value="<?=$form['password'];?>">
                          <?php if (form_error('password')) : ?>
                          <br>
                          <span class="errors"><?= form_error('password'); ?></span><br>
                          <br>
                          <?php endif; ?>
		                </div>
		        </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <input type="submit" name="submit_btn" value="ログイン" class="btn btn-info">
                  <?php if (form_error('login')) : ?>
                  <p><span class="errors"><?= form_error('login'); ?></span></p>
                  <?php endif; ?>

                  <p style="padding-top: 20px; font-size: 12px;">copyright &copy; Nagoya art news</p>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
</div>
<!-- /.login-box -->

</body>
</html>
