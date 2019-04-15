<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>スケジュール一覧｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">
  
  <link href="<?= site_dir(); ?>css/jquery-ui.min.css" rel="stylesheet">
  <style>
    #dialog_edit, #dialog_del, #info { display: none; }
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>
  
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
  <script>
  var schedule_ajax_sort_url = "<?= site_dir(); ?>admin/schedule/sort_part";
  var schedule_ajax_del_url = "<?= site_dir(); ?>admin/schedule/del_part";
  var schedule_ajax_status_url = "<?= site_dir(); ?>admin/schedule/status_part";
  </script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/schedule_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title">あいちトリエンナーレ実行委員会</p></div>
<div class="card-body">
    <div>
        <button type="button" class="btn btn-block btn-default col-2 float-left mr-2" onClick="location.href='<?=site_dir();?>admin/place/input'">
            <i class="fa fa-file-text-o"></i> 基本情報
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-block btn-default col-2 float-left mr-2" onClick="location.href='<?=site_dir();?>admin/topics'">
            <i class="fa fa-check-square-o"></i> TOPICS
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-block btn-default col-2 mb-3" onClick="location.href='<?=site_dir();?>admin/schedule'" disabled>
            <i class="fa fa-calendar"></i> スケジュール
        </button>
    </div>
    <br>

    <form action="<?=site_dir();?>admin/schedule/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">

	<div class="col-8 cont_center"><p class="card-title mb-4 text-center">スケジュール一覧</p></div>
	
	<div class="col-2 cont_center mb-5">
        <button type="button" class="btn btn-block btn-outline-secondary schedule_add_btn">
            <i class="fa fa-plus-circle"></i> 新規スケジュール追加</button>
        </div>
	<div class="col-8 cont_center">
<?php for ( $i = 0, $n = count ( $schedule_list ); $i < $n; $i ++ ) : ?>
    <table class="table" id="schedule_<?= $schedule_list[$i]['id'] ?>">
        <tbody>
            <tr>
                <td width="70%"><p><?= $schedule_list[$i]['title'] ?></p></td>
                <td width="4%">
                    <button type="button" class="btn btn-block btn-default schedule_edit_btn" data-id="<?= $schedule_list[$i]['id'] ?>">
                        <i class="fa fa-edit"></i> 編集
                    </button>
                </td>
                <td width="4%">
                    <button type="button" class="btn btn-block btn-default schedule_del_btn" data-id="<?= $schedule_list[$i]['id'] ?>">
                        <i class="fa fa-times-circle"></i> 削除
                    </button>
                </td>
                <td width="22%">
                    <?php foreach ( $status_list AS $status_key => $status_val ) : ?>
                    <?= form_radio( "status" . $schedule_list[$i]['id'], $status_key, ( isset ( $schedule_list[$i]['status'] ) && $schedule_list[$i]['status'] == $status_key ? true : false ), "id=status" . $schedule_list[$i]['id'] . "_" . $status_key . " class='schedule_status' data-id='" . $schedule_list[$i]['id'] . "'" ); ?>
                    <?= form_label( $status_val, "status" . $schedule_list[$i]['id'] . "_" . $status_key, "class='mr-4'" ); ?>
                    <?php endforeach; ?>
                </td>
            </tr>
        </tbody>
    </table>
<?php endfor ; ?>

	</div>

    <input type="hidden" id="schedule_id" name="schedule_id">
    <input type="hidden" id="schedule_new" name="schedule_new">
    <input type="hidden" id="status_id" name="status_id">
    </form>

</div>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>

<div id="dialog_edit" title="更新">
  <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      情報を更新します。<br>
      よろしいですか？
  </p>
</div>
<div id="dialog_del" title="削除">
  <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      情報を削除します。<br>
      よろしいですか？
  </p>
</div>
<div id="info" title="info"></div>

</body>
</html>
