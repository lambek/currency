<?php
include_once "db.php";
$query = $db->prepare("SELECT * FROM `library` ");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_OBJ);
//echo '<pre>';
//print_r($result);
//echo '</pre>';
//die();
$option ="";
foreach ($result as $e){
	$option .='<option id="'.trim($e->sys_id).'">'.$e->sys_name.'</option>';
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

    <link rel="stylesheet" href="/stely.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="js.js"></script>

</head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <form class="form-inline">
                        <div class="form-group">
                            <label  for="to_date">Начало</label>
                            <input type="date" class="form-control" id="to_date" >
                        </div>
                        <div class="form-group">
                            <label for="past_date">Конец</label>
                            <input type="date" class="form-control" id="past_date" >
                        </div>
                        <select class="form-control">
                            <?php echo $option;?>
                        </select>
                        <button type="submit" class="btn btn-default">вывести</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>