<?php

session_start();
$_SESSION['uniqid'] = uniqid('', true);
$requestID = time() - mt_rand(50000000, time()-100000000);
$_SESSION['requestNumber'] = $requestID;

require "yandex-checkout-sdk-php-master/lib/autoload.php";
use YandexCheckout\Client;
$client = new Client();
$client->setAuth('650325', 'live_vQ4vz-OLLM4pdYC7uEaRuKlszxY4UIo2eqQXbp4HbpY');
//$client->setAuth('652312', 'test_9L7pnSE4ew1mzbYNt2q7A8cl0liJc2S0-xFPfJni0jk');

$price = $_POST['postPrice'];
$action = $_POST['postAct'];
$name = $_POST['name'];
$mail = $_POST['mail'];
$phone = $_POST['phone'];
$item = $_POST['postProduct'];
$adress = $_POST['adress'];
$desc = $requestID."; ".$name."; ".$mail."; ".$phone."; ".$adress;
if($action!="qiwi"){
$response = $client->createPayment(
	array(
    "capture"=>true,
    "amount" => array(
    	"value" => $price,
    	"currency" => "RUB",
    ),
    "payment_method_data" => array(
    	"type" => $action,
    ),
    "confirmation" => array(
      "type" => "redirect",
      "return_url" => "https://pulsarhome.ru/",
    ),
    "description" => $desc,
  ),
  $_SESSION['uniqid']
);
$confirmation_url = false;

if(isset($response->status) and ($response->status != "canceled") and isset($response->confirmation->confirmation_url) and $response->confirmation->confirmation_url) {
  $confirmation_url = $response->confirmation->confirmation_url;
  $_SESSION['status'] = "sended";
	$_SESSION['uniqid'] = $response[id];
	$_SESSION['mailTo'] = $mail;
	$_SESSION['mailBody'] = "Заказ №".$requestID.";
	Товар".$item.";
	ФИО ".$name.";
	Почта ".$mail.";
	Телефон ".$phone.";
	Адрес установки ".$adress;
	if($response->status == "pending"){
		header("location: ".$response->confirmation->confirmation_url);
	}
}
}else{
	$phone = preg_replace('~\D+~','',$phone); 
$response = $client->createPayment(
	array(
    "capture"=>true,
    "amount" => array(
    	"value" => $price,
    	"currency" => "RUB",
    ),
    "payment_method_data" => array(
    	"type" => "qiwi",
    	"phone" => $phone,
    ),
    "confirmation" => array(
      "type" => "redirect",
      "return_url" => "https://pulsarhome.ru/",
    ),
    "description" => $desc,
  ),
  $_SESSION['uniqid']
);
$confirmation_url = false;

if(isset($response->status) and ($response->status != "canceled") and isset($response->confirmation->confirmation_url) and $response->confirmation->confirmation_url) {
  $confirmation_url = $response->confirmation->confirmation_url;
  $_SESSION['status'] = "sended";
	$_SESSION['uniqid'] = $response[id];
	$_SESSION['mailTo'] = $mail;
	$_SESSION['mailBody'] = "Заказ №".$requestID.";
	Товар".$item.";
	ФИО ".$name.";
	Почта ".$mail.";
	Телефон ".$phone.";
	Адрес установки ".$adress;
	if($response->status == "pending"){
		header("location: ".$response->confirmation->confirmation_url);
	}
}
}
