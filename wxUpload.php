<?php
include 'lib/wxPacker.class.php';
include 'lib/wxUpload.class.php';



$Input = "wxapp";
$Output = "wxapp.wx";
$wxPacker = new wxPacker( $Input );
$pack = $wxPacker->getPack( $Output );

$a = new wxUpload("otj3b_9i8kGqly8W_z3yhge9Pa9M1ZCaVhsBudUgqZI");
$src = $a->upload( $pack );
echo "<img src='{$src}' />";