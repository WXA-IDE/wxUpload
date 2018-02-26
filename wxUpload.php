<?php
include 'lib/wxPacker.class.php';
include 'lib/wxUpload.class.php';



$Input = "wxapp";
$Output = "wxapp.wx";
$wxPacker = new wxPacker( $Input );
$pack = $wxPacker->getPack( $Output );

$a = new wxUpload("PyLkhRNMtlgsmLFyumOyFcp_DbXRfNStniB8zinAABI");
$src = $a->upload( $pack );
echo "<img src='{$src}' />";