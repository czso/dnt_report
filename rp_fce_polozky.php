<?php

 //number_format(@$RS["R9S".$s] / $RS["R28S".$s]*1, 1, ',', ' ')



function fAlign($s)
{
  switch($s)  
  {
    case "L" : $s= "left";    break;
    case "R" : $s= "right";   break;
    case "C" : $s= "center";  break;
    case "R0": $s= "right|0"; break;    //POZOR!! nezaokrouhluje pouze urezava. Desetiná císla se musi pripravit v SQL
    case "R1": $s= "right|1"; break;
    case "R2": $s= "right|2"; break;
    case "R3": $s= "right|3"; break;
    case "C0": $s= "center|0"; break;    //POZOR!! nezaokrouhluje pouze urezava. Desetiná císla se musi pripravit v SQL
    case "C1": $s= "center|1"; break;
    case "C2": $s= "center|2"; break;
    case "C3": $s= "center|3"; break;

default  : $s= "";                  // standardne jsou polozky zarovnany vlevo, neni potreba pridavat tridu
  }
  return ($s);
}

/*  priprava JK
function fAlign($s)
{
  number_format($s,0,',',' ');?>
  return ($s);
}
*/