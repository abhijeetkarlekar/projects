<?php
	require_once(EXCEL_PATH.'PHPExcel.php');
	require_once(EXCEL_PATH.'PHPExcel/IOFactory.php');

	class excelIO{
		var $objPHPExcel;
		function excelIO(){
			$this->objPHPExcel = new PHPExcel();
		}
		function create_multi_sheets($index,$name){
			$this->objPHPExcel->createSheet();
			$this->objPHPExcel->setActiveSheetIndex($index);
			$this->objPHPExcel->getActiveSheet()->setTitle($name);
		}
		function send($filename,$exceltype='Excel5'){
			switch($exceltype){
				case 'Excel5':
					header('Content-Type: application/vnd.ms-excel');
					break;
				case 'Excel2007':
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					break;
				default:
					header('Content-Type: application/vnd.ms-excel');
					break;
			}
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $exceltype);
			$objWriter->save('php://output');
		}
		function setColumnName($sheetIndex,$columnname,$column){
			$this->objPHPExcel->setActiveSheetIndex($sheetIndex);
			$this->objPHPExcel->addNamedRange(new PHPExcel_NamedRange($columnname, $this->objPHPExcel->getActiveSheet(),$column));
		}
		function writeColRow($sheetIndex='0',$column,$row,$val){
			$this->objPHPExcel->setActiveSheetIndex($sheetIndex);
			$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $val, false);
		}
		function writeCell($sheetIndex,$colindex,$colval){
			$this->objPHPExcel->setActiveSheetIndex($sheetIndex);
			$this->objPHPExcel->getActiveSheet()->setCellValue($colindex, $colval);
		}
		function writeSelectBox($sheetIndex,$formula,$colindex="A",$start="2",$cnt="2"){

			if($sheetIndex == ''){ return false; }
			if(empty($formula)){ return false; }
			$this->objPHPExcel->setActiveSheetIndex($sheetIndex);
			for($row=$start;$row<=$cnt;$row++){
				$rowindex = implode("",array($colindex,$row));
				$this->writeCell($sheetIndex,$rowindex,$formula);
				/*$objValidation = $this->objPHPExcel->getActiveSheet()->getCell($rowindex)->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setOperator( PHPExcel_Cell_DataValidation::OPERATOR_EQUAL );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );

				$objValidation->setAllowBlank(false);
				$objValidation->setShowInputMessage(true);

					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');

				$objValidation->setPromptTitle('Formula');
				$objValidation->setPrompt($formula);
				$objValidation->setFormula1("$formula");	// Make sure to put the list items between " and "  !!!				$this->objPHPExcel->getActiveSheet()->getCell($rowindex)->setDataValidation($objValidation);
				*/
			}
			//exit;
		}
	}
?>