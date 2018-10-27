<?php
include_once "db.php";
$query = $db->prepare("SELECT * FROM `sys_library` ");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_OBJ);
$option = "";
foreach ($result as $e) {
	$option .= '<option value ="' . trim($e->id) . '">' . $e->name . '</option>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Курс валюты</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

    <link rel="stylesheet" href="/stely.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="js.js"></script>

</head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <form class="form-inline">
                        <div class="form-group">
                            <label  for="to_date">Количество записей</label>
                            <select class="form-control" id="count_record">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="100">100</option>
                                <option value="0">Все</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label  for="to_date">Начало</label>
                            <input type="date" class="form-control" id="to_date" >
                        </div>
                        <div class="form-group">
                            <label for="past_date">Конец</label>
                            <input type="date" class="form-control" id="past_date" >
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="currency_id">
                                <?php echo $option;?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default" id="btnSearch">вывести</button>
                    </form>
                </div>
                <div class="col-xs-12 col-md-8 marg_top_30">
                    <div id="myfirstchart" style="height: 250px;"></div>
                </div>
                <div class="col-xs-12 col-md-8 marg_top_30">
                    <table class="table table-hover"  id="tbl_currenty"></table>
                </div>
                <div class="col-xs-12 col-md-8 marg_top_30 jsPagination"></div>

        </div>
    </body>
</html>
