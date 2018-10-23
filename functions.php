<?php
function cURL($url,$post=false) {
	if($post) {
		$post = json_encode($post);
	}
//	echo "<pre>";
//	print_r ($post);
//	echo "</pre>";
//	die();
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
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array('company_key: 750fd2bd-78a5-4166-b761-691f90d885b1'));
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

//	echo "<pre>";
//	print_r ($result);
//	echo "</pre>";
//	die();

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

//print_r( cURL("https://ya.ru/"))
if($_POST['reg']){
	$log = $_POST['log'];
	$pas = $_POST['pas'];
//	if()

//	unset($_POST['reg']);
//	unset($_POST['log']);
//	unset($_POST['pas']);

	$_POST['data'] = array(
		"app_key"=>"22ca6325-fe62-4925-b6ed-2aab38f19640",
		"company_key"=>"750fd2bd-78a5-4166-b761-691f90d885b1",
		"device_id"=>"app_test_api",
		"is_debug"=>true,
		"locale"=>"en",
		"login"=>"test_pos_01",
//		"login"=>$log,
		"password"=>"8D969EEF6ECAD3C29A3A629280E686CF0C3F5D5A86AFF3CA12020C923ADC6C92",
		"timeout"=>300,
	);
	$_POST["method"] = "auth.login";
	$_POST["sid"] = "00000000-0000-0000-0000-000000000000";
	$_POST["ts"] = 1530694432;



	  echo cURL($_POST);
}