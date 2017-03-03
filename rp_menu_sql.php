
<?php
        // provede pridani tagu pro tucny text a odradkovani pred kazdym klicovym SQL slovem 
          $sqlString = $_SESSION["sqlContent"];
          $sqlString = trim($sqlString);
          $patterns = ["/(\)|\(|\s)(from|where|order by|group by|on|join|left|right|in|is|not|and|or|between|union|inner|top|asc|decs|like|as|having)(\(|\)|\s)/i",
                      "/(\)|\(|\s?)(select)(\(|\)|\s)/i"];
          $replacements = '${1}<b>${2}</b>${3}';
          $sqlString = preg_replace($patterns, $replacements, $sqlString);
          echo $sqlString;

