<?php
$NazTab="PD_".$_SESSION["IKF"].SUBSTR($_SESSION["ROK"],-2).$_SESSION["ROK"].$_SESSION["MESIC"];

$sql_vip="select KSU, VIP, ORS, VIPTEP from DANTE.".$NazTab."_T order by KSU, ORS";
$stid_vip = oci_parse($Conn,$sql_vip);
oci_execute($stid_vip);
$ncols = oci_num_fields($stid_vip);

?>

<table class="table-padding-content">
  <thead>
    <?php
    echo "<tr>";
    for ($i = 1; $i <= $ncols; $i++) 
    {
      $column_name  = oci_field_name($stid_vip, $i);
      echo "<th>".$column_name."</th>";
    }
    echo "</TR>";
    ?>
  </thead>
  <tbody>
    <?php
    while($zaznam_vip=oci_fetch_array($stid_vip,OCI_BOTH))
    {
      echo "<TR>";
      for ($i = 0; $i <= $ncols-1; $i++) 
      {
        $column_name  = oci_field_name($stid_vip, $i+1);
        echo"<td>".@$zaznam_vip[$i]."</Td>";
      } //endfor  
      echo "</TR>";
    } //end while
    ?>
  </tbody>
</table>

<?php oci_free_statement($stid_vip); ?> 
  


