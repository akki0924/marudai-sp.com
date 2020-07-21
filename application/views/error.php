<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title><?= $title ?></title>
<link rel="stylesheet" href="<?= site_dir(); ?>/css/style.css">
</head>

<body>

<form class="form-inline" action="<?= site_dir(); ?><?= $action ?>" role="form" method="post">

<h2><?= $title ?></h2>

<table>
<tr>
    <th>
        <?= $body ?>
    </th>
</tr>
</table>
<center>
    <input type="submit" name="submit_btn" value="<?= $button_str ?>">
</center>

</form>

</body>
</html>
