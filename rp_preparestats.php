<?php
require_once './class/class.pripojit.php';
if (isset($_POST["obdobi"])) :
  $sort = "ALTER SESSION SET nls_sort=ascii7"; //nastaveni trideni (podtrzitko musi mit nizsi prioritu nez pismena a cislice)
  switch ($_POST["obdobi"]) {
    case "dnes": $sql = "SELECT soub_sql as jméno_souboru, dntkraj as kraj, pristupy as počet_načtení "
              . "FROM bartos_dnt_report "
              . "WHERE cas = (to_char(CURRENT_TIMESTAMP, 'dd.mm.yy')) "
              . "ORDER BY jméno_souboru asc, dntkraj asc";
      break;
    case "vcera": $sql = "SELECT soub_sql as jméno_souboru, dntkraj as kraj, pristupy as počet_načtení "
              . "FROM bartos_dnt_report "
              . "WHERE cas = (to_char(CURRENT_TIMESTAMP - interval '1' day, 'dd.mm.yy')) "
              . "ORDER BY jméno_souboru asc, dntkraj asc";
      break;
    case "tyden": $sql = "SELECT soub_sql as jméno_souboru, dntkraj as kraj, sum(pristupy) as počet_načtení "
              . "FROM bartos_dnt_report "
              . "WHERE cas > (to_char(CURRENT_TIMESTAMP - interval '7' day, 'dd.mm.yy')) "
              . "GROUP BY soub_sql, dntkraj "
              . "ORDER BY jméno_souboru asc, dntkraj asc";
      break;
    case "mesic": $sql = "SELECT soub_sql as jméno_souboru, dntkraj as kraj, sum(pristupy) as počet_načtení "
              . "FROM bartos_dnt_report "
              . "WHERE cas > (to_char(CURRENT_TIMESTAMP - interval '1' month, 'dd.mm.yy')) "
              . "GROUP BY soub_sql, dntkraj "
              . "ORDER BY jméno_souboru asc, dntkraj asc";
      break;
    case "celkem": $sql = "SELECT soub_sql as jméno_souboru, dntkraj as kraj, sum(pristupy) as počet_načtení "
              . "FROM bartos_dnt_report "
              . "GROUP BY soub_sql, dntkraj "
              . "ORDER BY jméno_souboru asc, dntkraj asc";
      break;
  }
  ?>
  <table class="table-padding-content table-sorter" id="table-main">
    <thead>
      <tr>
        <?php
        $UdajePripojeni = "10.15.10.45|KROV3TST.csu|dante|c3RhcnQxMjM=";
        $stats = new Pripojit();
        $aUdajePripojeni = explode("|", $UdajePripojeni);
        $stats->setSVR($aUdajePripojeni[0]);
        $stats->setDB($aUdajePripojeni[1]);
        $stats->setUz($aUdajePripojeni[2]);
        $stats->setPSW($aUdajePripojeni[3]);
        $stats->fPripojit();
        $connStat = $stats->Conn;
        
        $stid = oci_parse($connStat, $sort); // nastaveni trideni
        oci_execute($stid);

        $stid = oci_parse($connStat, $sql);

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
          echo "<th>" . oci_field_name($stid, $i) . "<span></span></th>\n";
        } //endfor  
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $suma = 0;
      while ($record = oci_fetch_array($stid, OCI_BOTH + OCI_RETURN_NULLS)) {
        echo "<tr>\n";
        for ($i = 0; $i < $ncols; $i++) {
          echo "<td>" . stripslashes(trim($record[$i])) . "</td>\n";
          if ($i === 2) {
            $suma += $record[$i];
          }
        } //endfor  

        echo "</tr>\n";
      } //end while
      oci_free_statement($stid);
      ?>
    </tbody>
    <tfoot>
      <tr><th colspan="2">SUMA</th><th><?php echo $suma; ?></th></tr>
    </tfoot>
  </table>
<script>
  $(".table-sorter").tablesorter({
          widthFixed: true,
          //showProcessing: true,
          //headerTemplate : '{content} {icon}', // Add icon for various themes

          widgets: ['zebra', 'stickyHeaders', 'filter'],

          widgetOptions: {

            // extra class name added to the sticky header row
            stickyHeaders: '',
            // number or jquery selector targeting the position:fixed element
            stickyHeaders_offset: 0,
            // added to table ID, if it exists
            stickyHeaders_cloneId: '-sticky',
            // trigger "resize" event on headers
            stickyHeaders_addResizeEvent: true,
            // if false and a caption exist, it won't be included in the sticky header
            stickyHeaders_includeCaption: false,
            // The zIndex of the stickyHeaders, allows the user to adjust this to their needs
            stickyHeaders_zIndex: 1,
            // jQuery selector or object to phycially attach the sticky headers
            stickyHeaders_appendTo: false,
            // jQuery selector or object to attach scroll listener to (overridden by xScroll & yScroll settings)
            stickyHeaders_attachTo: null,
            // jQuery selector or object to monitor horizontal scroll position (defaults: xScroll > attachTo > window)
            stickyHeaders_xScroll: null,
            // jQuery selector or object to monitor vertical scroll position (defaults: yScroll > attachTo > window)
            stickyHeaders_yScroll: null,
            // scroll table top into view after filtering
            stickyHeaders_filteredToTop: true,

            // *** REMOVED jQuery UI theme due to adding an accordion on this demo page ***
            // adding zebra striping, using content and default styles - the ui css removes the background from default
            // even and odd class names included for this demo to allow switching themes
            // , zebra   : ["ui-widget-content even", "ui-state-default odd"]
            // use uitheme widget to apply defauly jquery ui (jui) class names
            // see the uitheme demo for more details on how to change the class names
            // , uitheme : 'jui'
          }
        });
</script>
  <?php
  $stats->fOdpojit();
  endif;