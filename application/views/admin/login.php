<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ログイン</title>
  <style src="<?= site_dir(); ?>css/jquery.min.1.12.4.js"></style>
  <link rel="stylesheet" href="<?= site_dir(); ?>css/forms.css">
</head>
<body>
<form id="operation_form" name="operation_form" class="form-inline" action="<?= site_dir(); ?>admin" role="form" method="post">
ID：<br>
<input type="text" id="account" name="account" value="<?= var_disp( $form['account'] ) ?>" class="<?= ( ( isset ( $error_account ) && $error_account ) ? 'formerr_color' : '' ) ?>" ><br>
<?= ( ( isset ( $error_account ) && $error_account ) ? '<span class="formerr_msg">' . $error_account . '</span>' : '' ) ?>
<br>
PASS：<br>
<input type="password" id="password" name="password" value="<?= var_disp( $form['password'] ) ?>"  class="<?= ( ( isset ( $error_password ) && $error_password ) ? 'formerr_color' : '' ) ?>" ><br>
<?= ( ( isset ( $error_password ) && $error_password ) ? '<span class="formerr_msg">' . $error_password . '</span>' : '' ) ?>
<br>
<input type="submit" id="submit_btn" name="submit_btn" value="ログイン">

<?= validation_errors () ?>

</form>
</body>
</html>
