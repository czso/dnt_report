<?php

require_once 'PHPExcel_1.8.0/Classes/PHPExcel.php';
require_once "rp_fce_polozky.php";
require_once './rp_sql/' . $_SESSION["SOUB_SQL"];

function clearFileName($fileName) {
  $fileName = str_replace([" ", ","], "_", $fileName);
  $fileName = str_replace([".", "!", "\"", "'"], "", $fileName);
  $replace_table = [
      'á' => 'a', 'Á' => 'A',
      'č' => 'c', 'Č' => 'C',
      'ď' => 'd', 'Ď' => 'D',
      'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E',
      'í' => 'i', 'Í' => 'I',
      'ň' => 'n', 'Ň' => 'N',
      'ó' => 'o', 'Ó' => 'O',
      'ř' => 'r', 'Ř' => 'R',
      'š' => 's', 'Š' => 'S',
      'ť' => 't', 'Ť' => 'T',
      'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U',
      'ý' => 'y', 'Ý' => 'Y',
      'ž' => 'z', 'Ž' => 'Z',];
  $fileName = strtr($fileName, $replace_table);
  return $fileName;
}

$objPHPExcel = new PHPExcel();
$locale = 'cs';
$validLocale = PHPExcel_Settings::setLocale($locale);

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, "Report");
$objPHPExcel->addSheet($myWorkSheet, 0);
$objPHPExcel->removeSheetByIndex(1);
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setShowGridLines(false);
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(85);
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

$styleInfo = ['font' => ['bold' => true,
        'size' => 12],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,],
    'borders' => [
        'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => ['rgb' => 'A4A4A4'],],
    ],
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => 'f5f5f5'],
    ],
];

$styleHeader = ['font' => ['bold' => true,],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,],
    'borders' => [
        'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => ['rgb' => 'A4A4A4'],],
    ],
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => 'd4d4d4'],
    ],
];

$styleBody = ['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,],
    'borders' => [
        'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => ['rgb' => 'A4A4A4'],],
    ],
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => 'f5f5f5'],
    ],
];

try {
// souradnice, kde se ma zacit vypisovat vysledek
  $colStart = 1;
  /* POZOR toto je napevno nastavena hodnota sirky pro prvni sloupec, nutno zmenit
   * pri zmene colStart, viz vyse
   */
  $objPHPExcel->getActiveSheet()->getColumnDimension('A')
          ->setWidth(4);
  $rowStart = 2;
  $rowNr = $rowStart;

  /* prevede souradnicove ciselne hodnoty na Pismena, excel pouziva pro sloupce
   * pismena velke abecedy. 
   * 0 -> A
   * 1 -> B
   * 2 -> C  atd.
   */
  $colStartStringVal = PHPExcel_Cell::stringFromColumnIndex($colStart);
  $colEndStringVal = PHPExcel_Cell::stringFromColumnIndex($colStart + $ncols - 1);

  for ($columnID = $colStartStringVal; $columnID <= $colEndStringVal; $columnID++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
  }

//informace v zahlavi
  $content = [$_SESSION["RP_NAZEV"] . " (" . $_SESSION["RP_POPIS"] . ")",
      "Období: " . $_SESSION["MESIC"] . "/" . $_SESSION["ROK"],
      "Úloha / Prefix: " . $_SESSION["ULOHA"] . "/" . $_SESSION["PREFIX"],];
  foreach ($content as $key => $value) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colStart, $rowNr, $value);
    $range = $colStartStringVal . $rowNr . ':' . $colEndStringVal . $rowNr;
    $objPHPExcel->getActiveSheet()->mergeCells($range);
    $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleInfo);
    $rowNr++;
  }
  $rowNr++;

  /* for ($i = 1; $i <= $ncols; $i++) {
    $column_type  = oci_field_type($stid, $i);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i + $colStart - 1, $rowNr, $column_type);
    }

    // vytvorim rozsah, napr. A1:F30, na ten potom aplikuji styly
    $range = $colStartStringVal . $rowNr . ':' . $colEndStringVal . $rowNr;
    echo "1. radek ".$range. "<br />";
    $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleHeader);

    $rowNr++;
   */

// hlavicka
  for ($i = 1; $i <= $ncols; $i++) {
    $column_name = oci_field_name($stid, $i);
    $objPHPExcel->getActiveSheet()
            ->setCellValueExplicitByColumnAndRow($i + $colStart - 1, $rowNr, $column_name, PHPExcel_Cell_DataType::TYPE_STRING);
  }

  $range = $colStartStringVal . $rowNr . ':' . $colEndStringVal . $rowNr;
  $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleHeader);

// pro telo tabulky se meni pouze index radku, ziskam tedy pocatecni
  $range = $colStartStringVal . ($rowNr + 1) . ':' . $colEndStringVal;

// obsah
  while ($record = oci_fetch_array($stid, OCI_NUM + OCI_RETURN_NULLS)) {
    $colNr = $colStart;
    $rowNr++;
    for ($i = 0; $i < $ncols; $i++) {
      if (isset($aAlign)) {
        $aFormat = explode("|", fAlign($aAlign[$i]));
        if (count($aFormat) === 2) {
          $record[$i] !== null ? $record[$i]  = number_format($record[$i], $aFormat[1], ',', ' ') : null;
        } else {
          $record[$i] = stripslashes(trim($record[$i]));
        }
      }
      $objPHPExcel->getActiveSheet()
              ->setCellValueExplicitByColumnAndRow($colNr++, $rowNr, $record[$i], PHPExcel_Cell_DataType::TYPE_STRING);
    }
  }
// a pripojim koncovy index radku
  $range .= $rowNr;
  $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleBody);

  $objPHPExcel->getActiveSheet()->calculateColumnWidths();
  for ($colID = $colStartStringVal; $colID <= $colEndStringVal; $colID++) {
    $width = $objPHPExcel->getActiveSheet()->getColumnDimension($colID)->getWidth();
    if ($width > 100) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($colID)->setAutoSize(false);
      $objPHPExcel->getActiveSheet()->getColumnDimension($colID)->setWidth(100);
      $range = $colID . $colStart . ":" . $colID . $rowNr;
      $objPHPExcel->getActiveSheet()->getStyle($range)
              ->getAlignment()
              ->setWrapText(true);
    }
//$objPHPExcel->getActiveSheet()->setCellValueExplicit(, PHPExcel_Cell_DataType::TYPE_STRING);   	
  }
  
} catch (Exception $e) {
  echo $e->getMessage();
}
/*
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('vysledek.xlsx');*/

$fileName = $_SESSION["RP_NAZEV"];
$fileName .= "(" . $_SESSION["ULOHA"] . "_";
$fileName .= $_SESSION["MESIC"] . "-" . $_SESSION["ROK"] . ")";
$fileName = clearFileName($fileName);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
$objWriter->save('php://output');
?>