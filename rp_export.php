<?php
//------------------------------------------------------------------------------
  require_once "./class/class.pripojit.php";
  
  $Sql            = $_SESSION["sqlContent"];
  $UdajePripojeni = $_SESSION["UdajePripojeni"];
  $objPripoj = new Pripojit();
  $aUdajePripojeni=explode("|",$UdajePripojeni);
  $objPripoj->setSVR($aUdajePripojeni[2]); 
  $objPripoj->setDB ($aUdajePripojeni[3]);
  $objPripoj->setUz ($aUdajePripojeni[4]);
  $objPripoj->setPSW($aUdajePripojeni[5]);
  $objPripoj->fPripojit();
  $Conn = $objPripoj->Conn;
  //echo "<br>".$objPripoj->Conn;
  //echo "<br>".$Conn;
  //  echo $Sql."<br>";


$stid = oci_parse($Conn,$Sql);
oci_execute($stid);
$ncols = oci_num_fields($stid);

IF (!empty($_GET)) {
  $export_type = strtolower($_GET['export_type']);
  switch($export_type) {
    case "csv":
      require_once 'rp_export_csv.php'; break;
    case "xlsx":
      require_once 'rp_export_xlsx.php'; break;
  }  
}

//oci_free_statement($Conn);
oci_close($Conn);