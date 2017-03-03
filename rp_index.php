<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <link rel="stylesheet" href="rp_table.theme.silver.css">
    <link rel="stylesheet" href="rp.css? <?php echo time(); ?>">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>  
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
    <?php
//==============================================================================
    $_SESSION["DNTKRAJ"] = strtolower(filter_input(INPUT_GET, "DNTKRAJ", FILTER_SANITIZE_STRING));
    $_SESSION["ROK"] = filter_input(INPUT_GET, "ROK", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["MESIC"] = filter_input(INPUT_GET, "MESIC", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["IKF"] = filter_input(INPUT_GET, "IKF", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["HLAVNI_ID"] = filter_input(INPUT_GET, "HLAVNI_ID", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["X_IDENT"] = filter_input(INPUT_GET, "X_IDENT", FILTER_SANITIZE_STRING);
    $_SESSION["ZPRAC"] = filter_input(INPUT_GET, "ZPRAC", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["X_ZPRAC"] = filter_input(INPUT_GET, "X_ZPRAC", FILTER_SANITIZE_STRING);
    $_SESSION["PC"] = filter_input(INPUT_GET, "PC", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["RP_NAZEV"] = "";
    $_SESSION["PROSTREDI"] = filter_input(INPUT_GET, "PROSTREDI", FILTER_SANITIZE_STRING);
    $_SESSION["TYP"] = filter_input(INPUT_GET, "TYP", FILTER_SANITIZE_NUMBER_INT);

    if ($_SESSION["PC"] == null) {
      $_SESSION["ODKAZNAZEV"] = filter_input(INPUT_GET, "ODKAZNAZEV", FILTER_SANITIZE_STRING);
      if ($_SESSION["ODKAZNAZEV"] == null) {
        echo "Není zadán název souboru s sql dotazem";
        exit;
      }
      $_SESSION["SOUB_SQL"] = $_SESSION["ODKAZNAZEV"] . ".php";
    } else {
      $_SESSION["SOUB_SQL"] = strtolower("rp_" . $_SESSION["IKF"] . "_" .
                      $_SESSION["DNTKRAJ"]) . "_" . $_SESSION["ROK"] . "_mm_" . $_SESSION["PC"] . ".php";
    }


//==============================================================================
    require_once "./class/class.pripojit.php";
    require_once 'default_config.php';
    

    $prostredi = strtoupper(trim($_SESSION["PROSTREDI"]));

    if ($prostredi == null or $prostredi == "PP") {
      $_SESSION["PROSTREDI"] = "PP";
      require_once 'rp_pripojeni.php';              //--- blok pro pripojeni kraju --
    } elseif ($prostredi == "TP") {
      $_SESSION["PROSTREDI"] = $prostredi;
      require_once 'rp_pripojeni_tp.php';             //--- blok pro pripojeni kraju na TP --
    } else {
      echo "Chybně zadaný parametr PROSTREDI";
      exit;
    }

    if ($_SESSION["TYP"] == null or $_SESSION["TYP"] === "0") {
      require_once './rp_sql/' . $_SESSION["SOUB_SQL"];
    } elseif ($_SESSION["TYP"] === "1") {
      require_once './rp_komplexni/' . $_SESSION["ODKAZNAZEV"] . '/config.php';
    }

    require_once "rp_next_data.php";    //další pomocná data z dtb
    require_once './rp_statistics.php'; // statistika pristupu na stranky
    ?>

    <title><?php echo $_SESSION["RP_NAZEV"] ?></title>

  </head>
  <body>
    <div id="header" <?php echo ($prostredi === "TP" ? "class=\"tp-env\"" : "class=\"pp-env\"")?> > 
     
      <div>
        <p id="report_name">
          <?php
          echo (((@$D_or_DW === null) or (@$D_or_DW ==='')) ? "D : " : $D_or_DW.' : ')."  <b>" . ($_SESSION["RP_NAZEV"] === '' ? 'Název reportu chybí' : $_SESSION["RP_NAZEV"]) . "</b>";
          echo $_SESSION["RP_POPIS"] === '' ? "" : " (" . $_SESSION["RP_POPIS"] . ")";
          ?>
        </p>
      </div>  
      <div id="menu">
        <?php
        require_once 'rp_menubar.php';
        ?>    
      </div>
      <span id="tp-info"></span>
    </div>
    
    
    <?php 
    //-- Z -- jestli bude D nebo DW --------------------------------------------
    //$D_or_DW="DW";

    if (@$D_or_DW == null or @$D_or_DW === "D") 
    {
      echo "<div id='content'>";
      if ($_SESSION["TYP"] == null or $_SESSION["TYP"] === "0") {
        require_once 'rp_content.php';
      } elseif ($_SESSION["TYP"] === "1") {
        require_once './rp_komplexni/' . $_SESSION["ODKAZNAZEV"] . '/' . $_SESSION["SOUB_SQL"];
      }
      echo "</div>";
    }
    elseif (@$D_or_DW === "DW") 
    {
      echo "<div id='content'>";
      if ($_SESSION["TYP"] == null or $_SESSION["TYP"] === "0") {
        require_once 'rp_content_dw.php';
      } elseif ($_SESSION["TYP"] === "1") {
        require_once './rp_komplexni/' . $_SESSION["ODKAZNAZEV"] . '/' . $_SESSION["SOUB_SQL"];
      }
      echo "</div>";
    }
    else  
    {
      echo "<div id='content' <table><tr><td>";
      echo "CHYBA! Chybně definovaná proměnná D_or_DW v souboru : <b>".$_SESSION["SOUB_SQL"]."<b>.";
      echo "</td></tr></table></div>";
    } 
    //-- K -- jestli bude D nebo DW --------------------------------------------
    ?>
    <a href="#" id="back-to-top"></a>  
  </body>
</html>
<?php
if ($Conn != null) {
  oci_close($Conn);
}