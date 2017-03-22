<?php
require_once './rp_sql/' . $_SESSION["SOUB_SQL"];
require_once "rp_fce_polozky.php";
?>                                   

<table class="table-padding-content table-sorter" id="table-main">
  <thead>
    <tr>
      <?php
      $sql = $_SESSION["sqlContent"];
      $stid = oci_parse($Conn, $sql); // $Conn- rp_pripojeni.php nebo rp_pripojeni_tp.php

      $r = oci_execute($stid);
      if (!$r) {
        $e = oci_error($stid);  // For oci_execute errors pass the statement handle
        $errStart = $e['offset'];
        $errLength = strpos($e['sqltext'], ' ', $errStart) - $errStart;
        $errVal = substr($e['sqltext'], $errStart, $errLength);
        $highlighted = substr_replace($e['sqltext'], "<mark>" . $errVal . "</mark>", $errStart, $errLength);
         
        echo htmlentities($e['message']);
        echo "\n<div class=\"highlight\">\n<pre>";
        echo $highlighted;
        echo "</pre>\n</div>\n";
      }
      $ncols = oci_num_fields($stid);
      for ($i = 1; $i <= $ncols; $i++) {
        echo "<th title='".@$aTitle[$i]."'>" . oci_field_name($stid, $i) . "<span></span></th>\n";
      } //endfor  
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $SumVet = 0;
    while ($record = oci_fetch_array($stid, OCI_BOTH + OCI_RETURN_NULLS)) {
      $SumVet += 1;
      echo "<tr>\n";

      for ($i = 0; $i < $ncols; $i++) {
        if (isset($aAlign)) {
          $aFormat = explode("|", fAlign($aAlign[$i]));
          switch (count($aFormat)) {
            case 1 : echo "<td class=\"" . $aFormat[0] . "\">" . stripslashes(trim($record[$i])) . "</td>\n";
              break;
            case 2 : echo "<td class=\"" . $aFormat[0] . "\">" . ($record[$i] !== null ? number_format($record[$i], $aFormat[1], ',', ' ') : null ) . "</td>\n";
              break;
            default: echo "<td class='left'>" . stripslashes(trim($record[$i])) . "</td>\n";
          }
        } else {
          echo "<td class='left'>" . stripslashes(trim($record[$i])) . "</td>\n";
        }
      } //endfor  

      echo "</tr>\n";
    } //end while
    oci_free_statement($stid);
    ?>
  </tbody>
</table>