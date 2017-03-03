<script>
  $(document).ready(function () {
    $('.table-sorter').tablesorter({
      sortList: [[0, 0]],
      headers: {
        3: {sorter: false},
        6: {sorter: false},
        7: {sorter: false}
      }
    });
  });
</script>

<?php
/*
 * Jelikoz je hodnota Nestaž. D-W v databazi dante_web ve sloupci x_stav in (2, 7), je potreba
 * vytvorit spojeni se dvemi db a vysledky nasledne seskupit do jedne tabulky, z dante_web
 * je nacten sloupec "zprac" a pocet vyskytu pro x_stav=7 do pole a toto pole je nasledne
 * prochazeno a podle "zprac" jsou prirazovany chybejici hodnoty
 */

$rok = $_SESSION["ROK"];
$mesic = $_SESSION["MESIC"];
$ikf = $_SESSION["IKF"];

require_once 'report_souhrnny_sql_dotaz.php';

$conn1 = $Conn;
$conn2 = $ConnDW;
?>

<div> 
  <table id="table-main" class="table-padding-content table-sorter">
    <thead>
      <tr>
        <th rowspan="2"><span>č.&nbsp;zprac.</span></th>
        <th rowspan="2"><span>Zpracovatel</span></th>
        <th rowspan="2"><span>VS</span></th>
        <th class="netrid" colspan="4">Pořízeno</th>
        <th rowspan="2"><span>%&nbsp;VS</span></th>
        <th rowspan="2"><span>Nestaž. D-W</span></th>
        <th class="netrid" colspan="5">EQA</th>
        <th class="netrid" colspan="17">AKT</th>
      </tr>
      <tr>
        <th><span>WEB</span></th>
        <th><span>PDF</span></th>
        <th><span>Ostatní</span></th>
        <th><span>Celkem</span></th>
        <th><span>3</span></th>
        <th><span>4</span></th>
        <th><span>5</span></th>
        <th><span>6</span></th>
        <th><span>%&gt;3</span></th>
        <th><span>00</span></th>
        <th><span>11</span></th>
        <th><span>12</span></th>
        <th><span>13</span></th>
        <th><span>21</span></th>
        <th><span>22</span></th>
        <th><span>24</span></th>
        <th><span>25</span></th>
        <th><span>26</span></th>
        <th><span>27</span></th>
        <th><span>31</span></th>
        <th><span>32</span></th>
        <th><span>33</span></th>
        <th><span>36</span></th>
        <th><span>37</span></th>
        <th><span>38</span></th>
        <th><span>%&gt;21</span></th>
      </tr>
    </thead>
    <?php
    $sql = "SELECT uloha_id
            FROM dante.uloha
            WHERE ikf = :ikf";
    $stid1 = oci_parse($conn1, $sql);
    oci_bind_by_name($stid1, ':ikf', $ikf);
    oci_execute($stid1);
    $radek = oci_fetch_array($stid1, OCI_ASSOC + OCI_RETURN_NULLS);
    $uloha_id = $radek['ULOHA_ID'];
    $jmeno_tab = "PD_" . $ikf . substr($rok, 2, 2) . $rok . $mesic;

    //$jmeno_tab = 'PD_164015201512'; pouze pro testovaci ucely
    //$uloha_id = 878;     // TODO

    $sql = "SELECT DISTINCT ident 
            FROM dante.zpracovani
            WHERE uloha_uloha_id = " . $uloha_id;
    $stid1 = oci_parse($conn1, $sql);
    oci_execute($stid1);
    // nactu pole z DB, typicky pujde o IDENT => "ICO" nebo IDENT => "STAVURAD, STAKODSO,PAGINAST7" atd.
    $temp = oci_fetch_array($stid1, OCI_ASSOC + OCI_RETURN_NULLS);
    //var_dump($temp);
    //echo "<br />";
    // z nacteneho retezce v poli pod klicem IDENT odeberu vsechny mezery
    $identFormat = str_replace(' ', '', $temp['IDENT']);
    $ident = (string) str_replace(',', " || ',' || ", $identFormat);
    $ident .= ' as IDENT';
    /* var_dump($ident);
      echo "<br />";
      var_dump($identFormat);
      echo "<br />"; */

    $res = array(); // pole pro vysledky nestaz D-W

    $sql = "SELECT *
          FROM all_objects
          WHERE object_type IN ('TABLE','VIEW')
          AND object_name = '" . $jmeno_tab . "'";
    $stid2 = oci_parse($conn2, $sql);
    oci_execute($stid2);
    if (oci_fetch_array($stid2, OCI_ASSOC + OCI_RETURN_NULLS)) {
      $sql = "SELECT " . $ident . ", count(*) nestaz
              FROM dante_web." . $jmeno_tab . "
              WHERE x_stav IN (2, 7)
              GROUP BY " . $identFormat;
      $stid2 = oci_parse($conn2, $sql);
      oci_execute($stid2);
      oci_fetch_all($stid2, $temp);
      //var_dump($temp);
      //echo "<br />";
      oci_free_statement($stid2);
      oci_close($conn2);

      /* var_dump($ident);
        echo "<br />"; */
      $sql = "SELECT " . $ident . ", zprac
              FROM dante." . $jmeno_tab;
      $stid1 = oci_parse($conn1, $sql);
      oci_execute($stid1);
      $pocetPrvku = count($temp['NESTAZ']);
      while ($radek = oci_fetch_array($stid1, OCI_ASSOC + OCI_RETURN_NULLS)) {
        //var_dump($radek);
        for ($i = 0; $i < $pocetPrvku; $i++) {
          if ($radek['IDENT'] == $temp['IDENT'][$i]) {
            if (array_key_exists($radek['ZPRAC'], $res)) {
              $res[$radek['ZPRAC']] += $temp['NESTAZ'][$i];
            } else {
              $res[$radek['ZPRAC']] = $temp['NESTAZ'][$i];
            }
          }
        }
      }
    } //end if oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS) 
    else {
      echo '<p class="upozorneni">Upozornění: Neexistují záznamy o Nestažení z Dante Webu: tabulka ' . $jmeno_tab . ' není v databázi VST1.</p>';
    }

    $stid1 = oci_parse($conn1, sqlSouhrnnyReport($jmeno_tab));
    oci_execute($stid1);

    $SumVet = 0;
    echo "  <tbody>\n";
    while ($radek = oci_fetch_array($stid1, OCI_ASSOC + OCI_RETURN_NULLS)) {
      $SumVet++;
      echo "  <tr>\n";
      foreach ($radek as $polozka) {
        $polozka !== NULL ? htmlentities($polozka, ENT_QUOTES) : "&nbsp;";
        if ($polozka == 'doplnit') {
          if (array_key_exists($radek['ZPRAC'], $res)) {
            $polozka = $res[$radek['ZPRAC']];
          } else
            $polozka = "&nbsp;";
        }
        echo "    <td>" . $polozka . "</td>\n";
      }
      echo "  </tr>\n";
    }
    echo "  </tbody>\n";
    echo "  <tfoot>\n";
    $stid1 = oci_parse($conn1, sqlSouhrRepCelkem($jmeno_tab));
    oci_execute($stid1);
    while ($radek = oci_fetch_array($stid1, OCI_ASSOC + OCI_RETURN_NULLS)) {
      echo "  <tr>\n";
      echo "    <th colspan=2 style=\"text-align: left\">CELKEM</th>\n";
      foreach ($radek as $polozka) {
        $polozka !== NULL ? htmlentities($polozka, ENT_QUOTES) : "&nbsp;";
        $suma = 0;
        if ($polozka == 'doplnit') {
          foreach ($res as $pocet) {
            $suma += $pocet;
          }
          $polozka = $suma;
        }
        echo "    <th>" . $polozka . "</th>\n";
      }
      echo "  </tr>\n";
    }

    oci_free_statement($stid1);
    oci_close($conn1);
    ?>
  </table>
  <script>
    var sumVet = <?php echo $SumVet ?>;
    $("#row-counter #filtered, #row-counter #all").html(sumVet);
  </script>
</div>
</body>
</html>