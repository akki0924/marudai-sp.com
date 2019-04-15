<script type="text/javascript" src="<?= site_dir(); ?>js/admin/admin_func.js"></script>
<nav class="navbar navbar-expand bg-white navbar-light border-bottom">
   <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= site_dir(); ?>admin/main" class="nav-link">
        <i class="fa fa-home"></i>HOME</a>
      </li>
      <form action="<?=site_dir();?>admin/place" id="select_form" name="select_form" method="post" accept-charset="utf-8">
<?php
foreach ( $type_list AS $type_key => $type_val ) :
?>
<!--       <button type="button" data-id="<?= $type_key ?>" class="btn btn-block btn-default float-right nav-link header_btn"><?= $type_val ?></button> -->
        <input type="button" data-id="<?= $type_key ?>" value="<?= $type_val ?>" class="btn btn-block btn-default float-right nav-link header_btn">
<?php
endforeach;
?>
      <input type='hidden' id='type_id' name='type_id'>
      </form>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><i class="fa fa-key"></i> ログインユーザー：<?= $name ?>様</li>
      <li class="nav-item"><a class="nav-link" href="<?=site_dir();?>admin/index/logout"><i class="fa fa-sign-out"></i> ログアウト</a></li>
    </ul>
</nav>
