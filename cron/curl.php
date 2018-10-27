<?php
include_once "../db.php";

header('Content-Type: text/html; charset=utf-8');
//header('Content-Type: text/html; charset=windows-1251');

function cURL($url, $post = false) {
	if ($post) {
		$post = json_encode($post);
	}
	$url_self = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$url_self .= "s";
	}
	$url_self .= "://" . $_SERVER["SERVER_NAME"];
	if ($_SERVER["SERVER_PORT"] != "80") {
		$url_self .= ":" . $_SERVER["SERVER_PORT"];
	}

	if (substr($url_self, -1) != '/') {
		$url_self = $url_self . '/';
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_REFERER, $url_self);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	if ($post) {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$result = curl_exec($ch);
	$error = curl_error($ch);
	if ($error) {
		$result['curl_error'] = $error;
		return $result;
	}

	curl_close($ch);
	if ($result) {
		return $result;
	} else {
		return false;
	}
}

/**
 * Справочник по кодам валют
 */
function currency_library($db) {
	$currency_library = cURL("http://www.cbr.ru/scripts/XML_val.asp?d=0");
	$xml = simplexml_load_string($currency_library);

	foreach ($xml->Item as $e) {
		$query = $db->prepare("INSERT INTO `sys_library` SET
		`id`=:id,
		`name`=:name,
		`eng_name`=:eng_name,
		`nominal`=:nominal
		");
		$query->execute(array(
			'id' => (string)$e->attributes()->ID,
			'name' => (string)$e->Name,
			'eng_name' => (string)$e->EngName,
			'nominal' => (int)$e->Nominal
		));
	}
}

//currency_library($db);

/**
 * получения динамики котировок валют
 * @param $db
 */

function query_date_currency($db) {
	$query = $db->prepare("SELECT `id` FROM `sys_library` ");
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);

	foreach ($result as $e) {
		$url = trim("http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=01/01/2017&date_req2=31/12/2018&VAL_NM_RQ=" . $e->id);
		$currency_library = cURL($url);
		$xml = simplexml_load_string($currency_library);

		foreach ($xml->Record as $e) {
			$query = $db->prepare("INSERT INTO sys_dynamic_current_date SET		
									`id_library`=:id_library,
									`date`=:date,
									`value`=:value,
									`nominal`=:nominal");

			list($d, $m, $y) = explode(".", (string)$e->attributes()->Date);
			$date = $y . "-" . $m . "-" . $d;

			list($int, $float) = explode(",", (string)$e->Value);
			$value = $int + $float / pow(10, strlen($float));

			try {
				$query->execute(array(
					'id_library' => (string)$e->attributes()->Id,
					'date' => $date,
					'value' => $value,
					'nominal' => (int)$e->Nominal
				));
			} catch (PDOException $e) {
				continue;
			}
		}
	}
}

query_date_currency($db);

echo "end <br/>";