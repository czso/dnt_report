
<html>
  <head>
    <meta http-equiv="Content-Language" content="cs">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">                                    

    <link rel="stylesheet" type="text/css" href="rp_table.theme.silver.css">
    <link rel="stylesheet" type="text/css" href="docs.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>Historie změn</title> 
    <script>
      $(document).ready(function () {
        $('h4').click(function () {
          $(this).next().toggle(400);
        });
      });
    </script>
  </head>
  <body>
    <div id="main-frame">
      <h3>Historie změn</h3>
      <h4>27. 2. 2017</h4>
      <div class="block">
        <ul>
          <li>Změněno zobrazování chyby v SQL kódu tak, aby nezáleželo na odřádkování. Nově bude chyba zvýrazněna barvou.<br />
            <p>Před:</p>
            <div class="highlight">
              <b>Warning</b>:  oci_execute(): ORA-00904: "EQUA": neplatný identifikátor in <b>/var/www/html/devel/dnt_report/rp_content.php</b> on line <b>13</b><br>
              ORA-00904: "EQUA": neplatný identifikátor</div>
            <p>Po:</p>
            <div class="highlight">
              <b>Warning</b>:  oci_execute(): ORA-00904: "EQUA": neplatný identifikátor in <b>/var/www/html/devel/dnt_report/rp_content.php</b> on line <b>13</b><br>
              ORA-00904: "EQUA": neplatný identifikátor
              <pre>
select ikf, <mark>equa</mark> from dante.PD_501016201606 where zprac=381105</pre>
            </div>
            <div class="highlight">
              <b>Warning</b>:  oci_execute(): ORA-00904: "EQUA": neplatný identifikátor in <b>/var/www/html/devel/dnt_report/rp_content.php</b> on line <b>13</b><br>
              ORA-00904: "EQUA": neplatný identifikátor
              <pre>
select ikf,
<mark>equa</mark> from dante.PD_501016201606 where zprac=381105</pre>
            </div>
        </ul>
      </div>
      <h4>24. 2. 2017</h4>
      <div class="block">
        <ul>
          <li>Přidání upřesňující chybové hlášky o výskytu chyby v SQL kódu - SQL kód musí být do proměnné <mark>$_SESSION["sqlContent"]</mark> zapsán na jednom řádku<br />
            <p>Před:</p>
            <div class="highlight">
              <b>Warning</b>:  oci_execute(): ORA-00904: "EQUA": neplatný identifikátor in <b>/var/www/html/devel/dnt_report/rp_content.php</b> on line <b>13</b><br>
              ORA-00904: "EQUA": neplatný identifikátor</div>
            <p>Po:</p>
            <div class="highlight">
              <b>Warning</b>:  oci_execute(): ORA-00904: "EQUA": neplatný identifikátor in <b>/var/www/html/devel/dnt_report/rp_content.php</b> on line <b>13</b><br>
              ORA-00904: "EQUA": neplatný identifikátor
              <pre>select equa, ikf from dante.PD_501016201606 where zprac=381105
       ^</pre>
            </div>
            <p>V případě odřádkování selectu v proměnné <mark>$_SESSION["sqlContent"]</mark> je chyba zobrazena špatně. Šipka zobrazuje pozici výskytu chyby v řetězci, nebere v úvahu odřádkování.</p>
            <div class="highlight">
              <b>Warning</b>:  oci_execute(): ORA-00904: "EQUA": neplatný identifikátor in <b>/var/www/html/devel/dnt_report/rp_content.php</b> on line <b>13</b><br>
              ORA-00904: "EQUA": neplatný identifikátor
              <pre>select
equa, ikf from dante.PD_501016201606 where zprac=381105
       ^</pre>
            </div>
          </li>
          <li>Oprava zobrazování formátu desetinných čísel v Excelu a CSV, nyní se nastavuje dle zadaných hodnot v rp_sql</li>
          <li>Zjištěna chyba při kopírování SQL kódu, která pravděpodbně souvisí s některými verzemi Firefoxu. V takovém případě je nutné
            kód kopírovat ručně s použitím kláves Ctrl+C - text by se měl v případě výskytu chyby sám označit.</li>
        </ul>
      </div>
      <h4>27. 1. 2017</h4>
      <div class="block">
        <ul>
          <li>
            Zarovnání textu a formátování čísla<br>
            <mark>L</mark>=left, <mark>C</mark>=center, <mark>R</mark>=right,<br> 
            <mark>R0:R3</mark>(formát čísla zarovnaný vpravo), <mark>C0:C3</mark>(formát čísla zarovnaný doprostřed). Čísla určuji počet desetinných míst.<br>
            <div class="highlight">
            POZOR! <b>desetinná místa se tímto nezaokrouhlují, ale ořezávají.</b>
            Zaokrouhlení se musí se ošetřit na straně SQL. Například: <mark>round(v.O326A_R11_S1 / 26,2)</mark><br>
            Příklad : <mark>$aAlign = array('R2','R','R','R','R','R');</mark>  -  R2=zarovná číslo doprava a na 2 desetinná místa, R=zarovná doprava.</div> 
          </li>
        </ul>
      </div>
      <h4>23. 12. 2016</h4>
      <div class="block">
        <ul>
          <li>opraveno zvýrazňování syntaxe SQL dotazu - nemění velikost písmen zadanou v souboru s SQL a nepřidává odřádkování z důvodu kontroly chyb</li>
          <li>Grafické úpravy</li>
        </ul>
      </div>
      <h4>12. 12. 2016</h4>
      <div class="block">
        <ul>
          <li>Umožněno provádět sestavy na DW.<br>
            Přidaná proměnná <mark>$D_or_DW</mark>, která je povinná pouze při použití DW.<br>
            Může nabývat hodnot: <mark>D=Dante</mark> ,<mark>DW=DanteW</mark>. Když se proměnná neuvede bude deaultně nastaveno :<mark>'' = Dante</mark>.<br> 
            <div class="highlight">POZOR na!: <mark>from dante</mark> nebo <mark>from dante_web</mark>.</div></li>
        </ul>
      </div>
      <h4>8. 12. 2016</h4>
      <div class="block">
        <ul>
          <li>Přidáno kaskádové menu - položky v menu Info byly rozděleny pro lepší přehlednost.</li>
          <li>Přidány návody a historie změn.</li>
          <li>Opraveno tlačítko <mark>Reset</mark> v menu <mark>Filtr</mark> - nyní se zobrazuje pouze tehdy, pokud je filtr aktivovaný.</li>
        </ul>
      </div>
      <h4>6. 12. 2016</h4>
      <div class="block">
        <ul>
          <li>Upraven soubor <mark>!PopisNazvuSouboru.txt</mark> a <mark>!soubor_sql_vzor.php</mark> ve složce <mark>rp_sql</mark>.
            Nyní obsahuje detailnější popis pro vytváření složitějších reportů umísťovaných do složky <mark>rp_komplexni</mark></li>
        </ul> 
      </div>
      <h4>5. 12. 2016</h4>
      <div class="block">
        <ul>
          <li>Upraveno přepínání mezi filtrem a vyhledáváním v pravém horním rohu stránky. Nyní se tyto dvě
            funkce navzájem neovlivňují.</li>
        </ul> 
      </div>
      <h4>18. 11. 2016</h4>
      <div class="block">
        <ul>
          <li>Přidána možnost generování exportu do Excelu (formát .xlsx)</li>
        </ul> 
      </div>
    </div>
  </body>
</html>
