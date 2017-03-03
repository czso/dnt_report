
<html>
  <head>
    <meta http-equiv="Content-Language" content="cs">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">                                    

    <link rel="stylesheet" type="text/css" href="rp_table.theme.silver.css">
    <link rel="stylesheet" type="text/css" href="docs.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>Návod</title> 
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
      <h3>Možnosti práce s generovaným reportem</h3>
      <h4>Export</h4>
      <div class="block">
        <p>V současné době jsou na výběr dvě možnosti exportu celého reportu a to do:</p>
        <ul>
          <li>Excelu (.xlsx)</li>
          <li>CSV</li>
        </ul>
        <p>Zatím není možné exportovat pouze odfiltrované části reportu samostatně.</p>
      </div>
      <h4>Hledání</h4>
      <div class="block">
        <p>Zadáváním textu do vyhledávacího pole v záhlaví stránky dojde k zobrazení
          řádků reportu, které obsahují vyhledávaný podřetězec. Nelze tedy vyhledávat zadáváním
          více výrazů v libovolném pořadí, jako to umožňují internetové vyhledávače.
        </p>
        <p>
          Příklad vyhledávání:<br>
          Hledám všechny řádky, na kterých je podřetězec 020. Výsledkem jsou potom 
          například řádky obsahující řetezce A071_<b>020</b>, 25<b>020</b>480 atd.
        </p>
      </div>
      <h4>Filtrování</h4>
      <div class="block">        
        <p>Zapnutím této funkce dojde k deaktivaci vyhledávání. Filtrování
          nabízí na rozdíl od vyhledávání více možností. Filtr lze aplikovat na každý
          sloupec zvlášť a je možné blíže specifikovat hledané hodnoty. Pro vymazání
          všech filtrů můžete použít v menu Filtr &gt; Reset. Následující tabulka
          popisuje matematické a logické operátory, které mohou být nápomocny k
          úspěšnému odfiltrování.
        </p>
        <p>Možné operace filtru:</p>
        <table class="table-padding-content">
          <thead>
            <tr>
              <th>Priorita</th><th>Typ</th><th>Popis</th><th>Příklady</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><mark>|</mark> nebo <mark>&nbsp;OR&nbsp;</mark></td>
              <td>Logické OR. Filtruje text odpovídající jedné nebo druhé straně logického operátoru. Kolem <mark>&nbsp;or&nbsp;</mark>
                musí být mezery, kolem <mark>|</mark> nesmí.</td>
              <td>Příklad P304_012<mark>|</mark>P304_004 (P304_012<mark>&nbsp;or&nbsp;</mark>P304_004) zobrazí řádky s hodnotami P304_012 nebo P304_004.
                S využitím operátorů níže lze taky např. zobrazit všechny hodnoty vyjma P304_012 nebo P304_004
                (<mark>!=</mark>P304_012<mark>&nbsp;or&nbsp;!=</mark>P304_004)</td>
            </tr>
            <tr>
              <td>2</td>
              <td><mark>&lt;</mark> <mark>&lt;=</mark> <mark>&gt;=</mark> <mark>&gt;</mark></td>
              <td>Najde záznamy abecedně nebo numericky menší nebo větší nebo rovny. Tyto
              operátory musí být použity na začátku zápisu, jinak budou brány jako vyhledávaný text.</td>
              <td>Příklad <mark>&gt;=</mark> 10 najde záznamy s hodnotami většími nebo rovny 10.
                Naopak zápis R7S1&gt;=R3S1 bude hledat podřetězec "R7S1&gt;=R3S1"</td>
            </tr>
            <tr>
              <td>3</td>
              <td><mark>!</mark> <mark>!=</mark></td>
              <td>Operátor NOT a NOT EXACTLY zobrazí hodnoty, které neobsahují daný text.  Tyto
              operátory musí být použity na začátku zápisu, jinak budou brány jako vyhledávaný text.</td>
              <td>Příklad <mark>!</mark>A006 zobrazí záznamy neobsahující podřetězec A006.
                <mark>!=</mark>A006 zobrazí pouze výsledky, jejichž celý obsah buňky není roven A006.
                Zobrazí tak například "A006_030" ale i "Odd. A006" protože "Odd. A006" není roven A006 </td>
            </tr>
            <tr>
              <td>4</td>
              <td><mark>=</mark></td>
              <td>Přidáním znaku = na začátek a/nebo na konec hledaného výrazu zobrazíme pouze
                výsledky obsahující právě takový výraz, tedy ne podřetězec.</td>
              <td>Příklad =A006 zobrazí záznamy s hodnotou ve filtrovaném sloupci A006, nezobrazí například A006_030</td>
            </tr>
            <tr>
              <td>5</td>
              <td><mark>&nbsp;&dash;&nbsp;</mark></td>
              <td>Zobrazí rozsah hodnot od - do. Je nutné oddělit pomlčku mezerami.</td>
              <td>Příklad 1000<mark>&nbsp;&dash;&nbsp;</mark>2000 zobrazí hodnoty od 1000 do 2000.</td>
            </tr>
            <tr>
              <td>6</td>
              <td><mark>?</mark></td>
              <td>Divoká karta pro jeden znak (mimo mezeru). Za <mark>?</mark> dosadí libovolný jeden znak.</td>
              <td>Příklad A0<mark>??</mark>_00<mark>?</mark> najde např. A0<b>06</b>_00<b>8</b>, A0<b>71</b>_00<b>2</b> nebo A0<b>71</b>_00<b>5</b></td>
            </tr>
            <tr>
              <td>7</td>
              <td><mark>*</mark></td>
              <td>Divoká karta pro nula a více znaků. Za <mark>*</mark> dosadí libovolný počet znaků.</td>
              <td>Příklad A006_<mark>*</mark> najde např. A006_<b>008</b>, A006_<b>012</b> nebo A006_<b>020</b></td>
            </tr>
            <tr>
              <td>8</td>
              <td>text</td>
              <td>Libovolný textový podřetězec nalezený kdekoliv v textu.</td>
              <td>Příklad <mark>kol</mark> najde <b>kol</b>ik, o<b>kol</b>o, So<b>kol</b>ov atd.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>

