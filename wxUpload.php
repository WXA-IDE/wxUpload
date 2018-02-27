<?php
include 'lib/wxPacker.class.php';	//1.0.101
include 'lib/wxUpload.class.php';	//0.9.2

$Input = "wxapp";
$Output = "wxapp.wx";
$appid = "wx6fdfc32bedf1ecc8";
$newTicket = "RH5MArvxPNAmgRAPC99S9TJ-yGwjGurHoSsmzOGjTe4";
$userVersion = "1.0.2";
$userDesc = "测试";

$wxPacker = new wxPacker( $Input );
$pack = $wxPacker->getPack();
// $pack = $wxPacker->savePack( $Output );

$a = new wxUpload( $newTicket, $appid );
$src = $a->upload( $pack, $userVersion, $userDesc );
// $src = $a->perView( $pack );
echo "<img src='{$src}' />";
echo "<br>".$a->error;