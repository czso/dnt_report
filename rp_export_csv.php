<?php

require_once "rp_fce_polozky.php";
require_once './rp_sql/' . $_SESSION["SOUB_SQL"];

$name = "rp_Uloha_obdobi_atd.csv";
header('Content-type: txt/csv');
header("Content-Disposition: attachment; filename=" . $name . "");
header("Pragma: no-cache");
header("Expires: 0");
setlocale(LC_CTYPE, 'cs_CZ');
//==============================================================================
try {
  for ($i = 1; $i <= $ncols; $i++) {
    $column_name = oci_field_name($stid, $i);
    echo "\"" . $column_name . "\";";
  }

  while ($record = oci_fetch_array($stid, OCI_BOTH)) {
    echo "\r\n";
    for ($i = 0; $i <= $ncols - 1; $i++) {
      if (isset($aAlign)) {
        $aFormat = explode("|", fAlign($aAlign[$i]));
        if (count($aFormat) === 2) {
          $record[$i] !== null ? $record[$i] = number_format($record[$i], $aFormat[1], ',', ' ') : null;
        } else {
          $record[$i] = stripslashes(trim($record[$i]));
        }
      }
      echo "\"" . iconv('utf-8', 'windows-1250', $record[$i]) . "\";";
    } //endfor  
  } //end while
} catch (Exception $e) {
  echo $e->getMessage();
}