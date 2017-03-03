<?php
//==============================================================================  
$sql_dw_xstav="Select ZPRAC, MSBER, X_IDENT, 
Decode(X_STAV,'0','0:nevyplněný',
              '1','1:uložený',
              '2','2:odeslaný',
              '3','3:převzatý',
              '4','4:odmítnutý',
              '5','5:nevyplněný (PDF)',
              '6','6:uložený (PDF)',
              '7','7:odeslaný (PDF)',
              '8','8:převzatý (PDF)',
              '9','9:odmítnutý (PDF)'
      ) as X_STAV,
 TSVJMENO, TSVTELEFON, TSVEMAIL, TSVFAX, X_DATUM, X_DATUM_STAZENI, X_KOMENTAR, X_PREVZETI 
                 from dante_web.PD_".$_SESSION["IKF"].SUBSTR($_SESSION["ROK"],-2).$_SESSION["ROK"].$_SESSION["MESIC"]." V  
                 Where  ZPRAC=".$_SESSION["ZPRAC"]." and X_IDENT=".$_SESSION["X_IDENT"]."   
                 Order By MSBER,X_STAV,X_IDENT"; 
//echo $sql_dw_xstav;

$stid_xstav = oci_parse($ConnDW,$sql_dw_xstav);
oci_execute($stid_xstav);
$ncols = oci_num_fields($stid_xstav);
?>


<table class="table-padding-content">
  <thead>
    <?php
    echo "<tr>";
    for ($i = 1; $i <= $ncols; $i++) 
    {
      $column_name  = oci_field_name($stid_xstav, $i);
      echo "<th>".$column_name."</th>";
    }
    echo "</TR>";
    ?>
  </thead>
  <tbody>
    <?php
    while($zaznam_xstav=oci_fetch_array($stid_xstav,OCI_BOTH))
    {
      echo "<TR>";
      for ($i = 0; $i <= $ncols-1; $i++) 
      {
        $column_name  = oci_field_name($stid_xstav, $i+1);
        if($column_name=="TSVEMAIL"):
          echo "<td><a href='mailto:".@$zaznam_xstav[$i]."?Subject=generováno%20z%20Odkazy.rp_dw_xstav'%92 target='_blank'>".@$zaznam_xstav[$i]."</a></td>";
        else:
          echo"<td>".@$zaznam_xstav[$i]."</Td>";
        endif;
      } //endfor  
      echo "</TR>";
    } //end while
    ?>
  </tbody>
</table>
<br>
<?php oci_free_statement($stid_xstav);  




//==============================================================================
$sql_dw_xstav="select   
Decode(X_STAV,'0','0:nevyplněný',
              '1','1:uložený',
              '2','2:odeslaný',
              '3','3:převzatý',
              '4','4:odmítnutý',
              '5','5:nevyplněný (PDF)',
              '6','6:uložený (PDF)',
              '7','7:odeslaný (PDF)',
              '8','8:převzatý (PDF)',
              '9','9:odmítnutý (PDF)'
      ) as X_STAV,
              count(x_stav) as POCET
              from dante_web.PD_".$_SESSION["IKF"].SUBSTR($_SESSION["ROK"],-2).$_SESSION["ROK"].$_SESSION["MESIC"]."   
              Where  ZPRAC=".$_SESSION["ZPRAC"]."    
              group by ZPRAC, X_STAV
              order by x_stav"; 
//echo $sql_dw_xstav;

$stid_xstav = oci_parse($ConnDW,$sql_dw_xstav);
oci_execute($stid_xstav);
$ncols = oci_num_fields($stid_xstav);
?>


<table border=0 cellspacing=0 cellpadding=1>
<tr><td valign='top'>
<table class="table-padding-content" style="width:0%;">
  <thead>
    <?php
    echo "<tr><th colspan=2 align='center'>X_STAV <br>za zpracovatele : <br>". $_SESSION["ZPRAC"]." / ".$_SESSION["DNTKRAJ"]."</th></tr>";
    echo "<tr>";
    for ($i = 1; $i <= $ncols; $i++) 
    {
      $column_name  = oci_field_name($stid_xstav, $i);
      echo "<th align='center'>".$column_name."</th>";
    }
    echo "</TR>";
    ?>
  </thead>
  <tbody>
    <?php
    while($zaznam_xstav=oci_fetch_array($stid_xstav,OCI_BOTH))
    {
      echo "<TR>";
      for ($i = 0; $i <= $ncols-1; $i++) 
      {
        $column_name  = oci_field_name($stid_xstav, $i+1);
        switch ($column_name)  
        {
        case "POCET" :echo"<td align='right'>".@$zaznam_xstav[$i]."</Td>"; break;
        default: echo"<td align='left'>".@$zaznam_xstav[$i]."</Td>";  
        }
      } //endfor  
      echo "</TR>";
    } //end while
    ?>
  </tbody>
</table>
</td>
<?php oci_free_statement($stid_xstav);  



//==============================================================================  
$sql_dw_xstav="select MSBER,   
Decode(X_STAV,'0','0:nevyplněný',
              '1','1:uložený',
              '2','2:odeslaný',
              '3','3:převzatý',
              '4','4:odmítnutý',
              '5','5:nevyplněný (PDF)',
              '6','6:uložený (PDF)',
              '7','7:odeslaný (PDF)',
              '8','8:převzatý (PDF)',
              '9','9:odmítnutý (PDF)'
      ) as X_STAV,
              count(x_stav) as POCET
              from dante_web.PD_".$_SESSION["IKF"].SUBSTR($_SESSION["ROK"],-2).$_SESSION["ROK"].$_SESSION["MESIC"]."   
              group by MSBER, X_STAV
              order by MSBER, X_STAV"; 
//echo $sql_dw_xstav;

$stid_xstav = oci_parse($ConnDW,$sql_dw_xstav);
oci_execute($stid_xstav);
$ncols = oci_num_fields($stid_xstav);


$PCV=1;   //PCV = poradove cislo vety
$VytvorTabulkuAN=true;

while($zaznam_xstav=oci_fetch_array($stid_xstav,OCI_BOTH))
{
   if($PCV==1):
     $PrvniMsber=@$zaznam_xstav["MSBER"]; 
   else:
     if($PrvniMsber==@$zaznam_xstav["MSBER"]): 
       $VytvorTabulkuAN=false; 
     else: 
       echo "</td>";      // ukoncuji předchozi TD
       echo "</tbody>";   // ukoncuji předchozi body
       echo "</table>";   // ukoncuji předchozi tabulku
       $VytvorTabulkuAN=true;
       $PrvniMsber=@$zaznam_xstav["MSBER"];
     endif;
   endif;
   
   
   if($VytvorTabulkuAN==true)
   {
     echo "<td valign='top'>";
     echo "<table class='table-padding-content' style='width:0%;'>";
     echo "<thead>";
     echo "<tr><th colspan=3 align='center'>X_STAV za <br>MSBER=". $PrvniMsber.", IKF=".$_SESSION["IKF"]." <br> ".$_SESSION["ROK"].$_SESSION["MESIC"]." (".$_SESSION["PREFIX"].")</th></tr>";
     echo "<tr>";
     for ($i = 1; $i <= $ncols; $i++) 
     {
        $column_name  = oci_field_name($stid_xstav, $i);
        
        switch ($column_name)  
       {
       case "MSBER" : break;
       default: echo "<th align='center'>".$column_name."</th>";  
       }
        
        //echo "<th align='center'>".$column_name."</th>";
     }  
     echo "</TR>";
     echo "</thead>";
     echo "<tbody>";
   }
   
   
   echo "<TR>";
   for ($i = 0; $i <= $ncols-1; $i++) 
     {
       $column_name  = oci_field_name($stid_xstav, $i+1);
       switch ($column_name)  
       {
       case "MSBER" : break;
       case "POCET" :echo"<td align='right'>".@$zaznam_xstav[$i]."</Td>"; break;
       default: echo"<td align='left'>".@$zaznam_xstav[$i]."</Td>";  
       }
     } //endfor  
     echo "</TR>";
   
   
   
   if($VytvorTabulkuAN==true)
   {
   //  echo "</tbody>";
   //  echo "</table>";
   }  
 

   //echo "pcv:".$PCV;
   $PCV++;

} //end while

       echo "</td>";      // ukoncuji posledni TD
       echo "</tbody>";   // ukoncuji posledni body
       echo "</table>";   // ukoncuji posledni tabulku


?>

</td></tr>
</table>
<?php oci_free_statement($stid_xstav);  


