<?php
    print "start";
    print_r($_POST);
    print "end";
    print "<br>\n"
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>test</title>

<script type="text/javascript" src="common/jquery.min.1.12.4.js"></script>
<script type="text/javascript" src="base_scripts.js"></script>
</head>

<body>
    <form action="check.php" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">
        <a class="submit_action"
            data-form_id="operation_form"
            data-action="check.php"
            data-target_id="id1"
            data-target_key="key1"
            data-target_val="val1"
        >
            test
        </a>
        <a>
            topics_exists
        <input type="hidden" id="target_id" name="target_id">
    </form>
</body>
</html>
