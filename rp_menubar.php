
<?php
require_once "./rp_spravci.php";
if (array_key_exists($_SESSION["RP_PROGRAMATOR"], $seznam_programatoru)) {
  $programator = $seznam_programatoru[$_SESSION["RP_PROGRAMATOR"]];
} else {
  $programator = ["jmeno" => "", "prijmeni" => "není v seznamu", "tel" => "doplnit v rp_spravci.php", "email" => "-"];
}
$aX_ZPRAC = explode(",", $_SESSION["X_ZPRAC"]);
?>
<table class="menubar">
  <tr>
    <td class="menubar-item">
      <a href="javascript:void(0)">
        <span>Info</span>
      </a>
      <div class="sub1">
        <ul>
          <li>
            <a href="javascript:void(0)">
              <span>O reportu...</span>
            </a>
            <div class="sub2 sub-last">
              <table class="table-padding-content">
                <thead>
                  <tr><th>Proměnná</th><th>Hodnota</th></tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Období</td>
                    <td><?php echo $_SESSION["MESIC"] . "/" . $_SESSION["ROK"] ?></td>
                  </tr>
                  <tr>
                    <td>Úloha / Prefix</td>
                    <td><?php echo $_SESSION["ULOHA"] . " / " . $_SESSION["PREFIX"]; ?></td>
                  </tr>
                  <tr>
                    <th colspan="2">Správce&nbsp;reportu:</th>
                  </tr>
                  <tr>
                    <td>Jméno</td>
                    <td><?php echo $programator["jmeno"] . " " . $programator["prijmeni"] ?></td>
                  </tr>
                  <tr>
                    <td>Telefon</td>
                    <td><?php echo $programator["tel"] ?></td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td><a href="mailto:<?php echo $programator["email"] . "?Subject=Report%20" . $_SESSION["SOUB_SQL"] ?>" target="_blank"> <?php echo $programator["email"] ?></a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </li>
          <li>
            <a href="javascript:void(0)">
              <span>Předané hodnoty</span>
            </a>
            <div class="sub2 sub-last">
              <table class="table-padding-content">
                <thead>
                  <tr><th>Proměnná</th><th>Hodnota</th></tr>
                </thead>
                <tbody>
                  <tr>
                    <td>SQL&nbsp;soubor&nbsp;</td>
                    <td><?php echo $_SESSION["SOUB_SQL"] ?></td>
                  </tr>
                  <tr>
                    <td>DNTKRAJ</td>
                    <td><?php echo $_SESSION["DNTKRAJ"] ?></td>
                  </tr>
                  <tr>
                    <td>ROK</td>
                    <td><?php echo $_SESSION["ROK"] ?></td>
                  </tr>
                  <tr>
                    <td>MESIC</td>
                    <td><?php echo $_SESSION["MESIC"] ?></td>
                  </tr>
                  <tr>
                    <td>IKF</td>
                    <td><?php echo $_SESSION["IKF"] ?></td>
                  </tr>
                  <tr>
                    <td>HLAVNI_ID</td>
                    <td><?php echo $_SESSION["HLAVNI_ID"] ?></td>
                  </tr>
                  <tr>
                    <td>X_IDENT</td>
                    <td><?php echo $_SESSION["X_IDENT"] ?></td>
                  </tr>
                  <tr>
                    <td>ZPRAC</td>
                    <td><?php echo $_SESSION["ZPRAC"] ?></td>
                  </tr>
                  <tr>
                    <td>X_ZPRAC</td>
                    <td><?php echo $aX_ZPRAC[0] ?></td>
                  </tr>
                  <tr>
                    <td>PROSTREDI</td>
                    <td><?php echo $_SESSION["PROSTREDI"] ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </li>
          <li>
            <a href="javascript:void(0)">
              <span>Zpracovatel úlohy</span>
            </a>
            <div class="sub2 sub-last scrollable-x">
              <table class="table-padding-content">
                <thead>
                  <tr><th>Zpracovatel úlohy</th><th>Počet vět</th></tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($InfoUlohy as $key => $row) {     //data se nacitaji v rp_next_data
                    echo "<tr>";
                    $aInfoZprac = explode(',', $row['ZPRAC']);
                    echo "<td>";
                    foreach ($aInfoZprac as $value) {
                      if (strpos($value, "@")):
                        echo "<a href='mailto:" . $value . "?Subject=generováno%20z%20Odkazy.Info'%92 target='_blank'>" . $value . "</a>";
                      else:
                        echo $value . ", ";
                      endif;
                    }
                    echo "</td>";
                    echo "<td>" . $row['POCET'] . "</td>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </li>
        </ul>
      </div>
    </td>
    <?php
    if ($inclMenuExport):
      ?>
      <td class="menubar-item">
        <a href="javascript:void(0)">
          <span>Export</span>
        </a>
        <div class="sub1">
          <ul>
            <li>
              <a href="rp_export.php?export_type=CSV">
                <span>CSV</span>
              </a>
            </li>
            <li>
              <a href="rp_export.php?export_type=XLSX">
                <span>XLSX</span>
              </a>
            </li>
          </ul>
        </div>
      </td>

      <?php
    endif;
    if ($inclMenuSQL):
      ?>
      <td class="menubar-item">
        <a href="javascript:void(0)">
          <span>SQL</span>
        </a>
        <div class="sub1 scrollable-x sub-last">
          <button id="sql-copy">Kopíruj</button>
          <label id="copy-info"></label>
          <p id="sql-code">
            <?php require_once "rp_menu_sql.php"; ?>
          </p>
        </div>
      </td>
      <?php
    endif;
    if ($inclMenuVIP):
      ?>
      <td class="menubar-item">
        <a href="javascript:void(0)">
          <span>VIP</span>
        </a>
        <div class="sub1 scrollable-x sub-last">
          <?php require_once "rp_vip.php"; ?>
        </div>
      </td>
      <?php
    endif;
    if ($inclMenuX_STAV):
      ?>
      <td class="menubar-item">
        <a href="javascript:void(0)">
          <span>X_STAV DW</span>
        </a>
        <div class="sub1 scrollable-x-y sub-last">
          <?php require_once "rp_dw_xstav.php"; ?>
        </div>
      </td>
      <?php
    endif;
    ?>
    <td class="menubar-item">
      <a href="javascript:void(0)">
        <span>Filtr</span>
      </a>
      <div class="sub1">
        <ul>
          <li>
            <a href="javascript:void(0)">
              <span id="filter">Zapnout</span>
            </a>
          </li>
          <li id="reset-filter">
            <a href="javascript:void(0)">
              <span>Reset</span>
            </a>
          </li>
        </ul>
      </div>
    </td>
    <td class="menubar-item">
      <a href="javascript:void(0)">
        <span>Nápověda</span>
      </a>
      <div class="sub1">
        <ul>
          <li>
            <a href="rp_navod.php" target="_blank">
              <span>Návod</span>
            </a>
          </li>
          <li>
            <a href="docs/rp_odkazy_dnt.pdf" target="_blank">
              <span>Nastavení odkazů Dante</span>
            </a>
          </li>
          <li>
            <a href="rp_history.php" target="_blank">
              <span>Historie změn</span>
            </a>
          </li>
<!--          <li>
            <a href="javascript:void(0)">
              <span>Připomínky</span>
            </a>
          </li>-->
        </ul>
      </div>
    </td>
    <td class="menubar-item">
      <label>Hledat:</label>
      <input type="text" id="search" placeholder="Začněte psát...">
    </td>
    <td class="menubar-item" id="row-counter">
      <p>Záznamů: <span id="filtered"></span> z <span id="all"></span></p>
    </td>
  </tr>
</table>
