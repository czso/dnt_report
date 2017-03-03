<?php
class Pripojit
{
const Ces = 'AL32UTF8';     //AL32UTF8     (UTF-8 - Unicode 4.0 s variabiln� d�lkou znaku) 
public $Conn;
public $SVR;
public $Uz;
public $DB;
public $PSW;

//<!-- *********************************************************************** -->
 
//-- Z -- Pripojeni k databazi -------------------------------------------------

public function getSVR() {
       return $this->SVR;
    }
public function setSVR($SVR) {
$this->SVR=$SVR;
}


public function getDB() {
       return $this->DB;
    }
public function setDB($DB) {
       $this->DB=$DB;
}

public function getUz() {
       return $this->Uz;
    }
public function setUz($Uz) {
       $this->Uz=$Uz;
}



public function getPSW() {
       return $this->PSW;
    }
public function setPSW($PSW) {
$this->PSW=$PSW;
}



public function fPripojit()  {
//--Z-- Vybira m servery -------
$server = $this->SVR; $db = $this->DB; $psw =$this->PSW; 
/*
echo '<br><br><br><br><br>Zn. sada : '.self::Ces;
echo '<br>SVR:'.$this->SVR;
echo '<br>DB:'.$this->DB;
echo '<br>PSW:'.$this->PSW;
echo '<br>PSW:'.base64_decode($this->PSW);
echo '<br>';
*/
$this->Conn = oci_connect($this->Uz, base64_decode($this->PSW), $this->SVR.'/'.$this->DB, self::Ces);
//$Krul3Conn=oci_connect('us','psw','10.12.10.11/krul3',$Ces);
//echo "Conn: ".$Conn." >>>> Melo by byt:Conn: Resource id #4  <BR>";


if(!$this->Conn) {
  $e = oci_error();
  trigger_error("Nepripojeno : ".htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
return($this->Conn);
}
//-- K -- Pripojeni k databazi -------------------------------------------------

  public function fOdpojit() {
    oci_close($this->Conn);
  }
}
?>


