<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <link rel="stylesheet" href="rp_table.theme.silver.css">
    <link rel="stylesheet" href="rp.css? <?php md5_file("rp.css"); ?>">
    <title>Statistika přístupů na jednotlivé reporty</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>  
    <!--<script type="text/javascript" src="js/script.js"></script>-->
    <script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
    <style>
      #loading, #done {
        display: inline-block;
        vertical-align: middle;
        width: 32px;
        height: 32px;
      }
      #check {
        fill: none;
        stroke: green;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-dasharray: 180;
        stroke-dashoffset: 180;
        animation: draw 4s ease forwards;
      }
      #circle {
        fill: none;
        stroke: red;
        stroke-width: 3;
        stroke-dasharray: 63;
        stroke-dashoffset: 63;
        animation: draw 1s ease infinite;
      }
      @keyframes draw {
        to {
          stroke-dashoffset: 0;
        }
      }
      section {line-height: 3;}
    </style>
  </head>
  <body>
    <script>
      $(document).ajaxStart(function () {
        $("#loading").html("<svg width=\"24\" height=\"24\"><path id=\"circle\" d=\"M2,12 a10,10 1 1,1 0,1 z\" /></svg>");
      });

      $(document).ajaxStop(function () {
        $("#loading").html("<svg width=\"24\" height=\"24\"><path id=\"check\" d=\"M2,12 6,22 22,2\" /></svg>");
      });
      $(document).ready(function () {
        $("#obdobi").change(function () {
          loadStats($(this));
        });
        $("#refresh").click(function () {
          loadStats($("#obdobi"));
        });
        function loadStats(id) {
          var obdobi = id.val();
          if (obdobi != "none") {
            var dataString = 'obdobi=' + obdobi;

            $.ajax
                    ({
                      type: "POST",
                      url: "rp_preparestats.php",
                      data: dataString,
                      cache: false,
                      success: function (html)
                      {
                        $("#result").html(html);
                      }
                    });
          }
        }
      });
    </script>
    <div style="margin: 5px;">
      <h3>Statistika přístupů na jednotlivé reporty (počítáno od 15.3.2017)</h3>
      <section>
        <label>Zvolte období: </label>
        <select id="obdobi">
          <option value="none" selected="selected">---</option>
          <option value="dnes">Dnes</option>
          <option value="vcera">Včera</option>
          <option value="tyden">Týden</option>
          <option value="mesic">Měsíc</option>
          <option value="celkem">Celkem</option>
        </select>
        <div id="loading"></div>
        <button id="refresh">Obnovit</button>
      </section>
    </div>
    <div id="result"></div>
    <a href="#" id="back-to-top"></a>  
  </body>
</html>