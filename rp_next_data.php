<?php
//-- sekce pro dalsi data potrebna ke zobrazeni --------------------------------

$sql_next="select kod from dante.uloha where IKF='".$_SESSION["IKF"]."'";
$stid_next = oci_parse($Conn, $sql_next);
oci_execute($stid_next);
$row = oci_fetch_row($stid_next);
$_SESSION["ULOHA"]= $row[0];
oci_free_statement($stid_next);

//==============================================================================
$sql_next="select PREFIX from dante.DNT_ULOHA_PREFIX where trim(KOD)='".$_SESSION["ULOHA"]."'";
$stid_next = oci_parse($Conn, $sql_next);
oci_execute($stid_next);
$row = oci_fetch_row($stid_next);
$_SESSION["PREFIX"]= $row[0];
oci_free_statement($stid_next);
  
//==============================================================================
$NazTab="PD_".$_SESSION["IKF"].SUBSTR($_SESSION["ROK"],-2).$_SESSION["ROK"].$_SESSION["MESIC"];

$sql_next="select ZPRAC ||', '|| X_ZPRAC as ZPRAC, count(id) as POCET from dante.".$NazTab." group by ZPRAC, X_ZPRAC order by 1";
//$sql_next="select ZPRAC, X_ZPRAC, count(id) as POCET from dante.".$NazTab." group by ZPRAC, X_ZPRAC order by 1";
$stid_next = oci_parse($Conn, $sql_next);
oci_execute($stid_next);
$ncols = oci_num_fields($stid_next);
$pocVet=0;
while($zaznam_next=oci_fetch_array($stid_next, OCI_BOTH + OCI_RETURN_NULLS))
{
  for ($i = 0; $i <= $ncols-1; $i++) 
  {
    $column_name_next  = oci_field_name($stid_next, $i+1);
    $InfoUlohy[$pocVet] [$column_name_next] = $zaznam_next[$i];
  } //endfor  
  $pocVet++;
} //end while

oci_free_statement($stid_next);




?>