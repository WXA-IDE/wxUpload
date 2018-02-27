<?php
include 'lib/wxPacker.class.php';	//1.0.101
include 'lib/wxUpload.class.php';	//0.9.102

$Input = "wxapp";
$Output = "wxapp.wx";
$appid = "wxd264b75bd1c77051";
$newTicket = "7Fak-X4xaSgkkf6qYqN2BUBAIQJ8qJetocLl24xSsxs";

$wxPacker = new wxPacker( $Input );
$pack = $wxPacker->getPack();
// $pack = $wxPacker->savePack( $Output );

$a = new wxUpload( $newTicket, $appid );
$src = $a->upload( $pack );
echo "<img src='{$src}' />";
echo "<br>".$a->error;