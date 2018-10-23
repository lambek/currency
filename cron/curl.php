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
	foreach ($xml as $e) {
		$query = $db->prepare("INSERT INTO library SET
		sys_id=:sys_id,
		sys_name=:sys_name,
		sys_eng_name=:sys_eng_name,
		sys_nominal=:sys_nominal
		");
		$query->execute(array(
			'sys_id' => (string)$e->ParentCode,
			'sys_name' => (string)$e->Name,
			'sys_eng_name' => (string)$e->EngName,
			'sys_nominal' => (int)$e->Nominal
		));
	}
}

//currency_library($db);

echo "end <br/>";