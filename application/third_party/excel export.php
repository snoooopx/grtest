<?php

date_default_timezone_set("Asia/Yerevan");
	//GETTING REQUESTED SALE ORDER FOR EXPORTING TO EXCEL
	$result_so = $this->m_sales->get_sales($so_id);
	$so = $result_so->result_array();

	$result_so_details = $this->m_sales->get_sale_details($so_id);
	$so_details = $result_so_details->result_array();

	$this->load->library('excel');
	// Set properties
	$this->excel->getProperties()->setCreator("Maarten Balliauw")
							 	 ->setLastModifiedBy("Maarten Balliauw")
							 	 ->setTitle("Office 2007 XLSX Test Document")
							 	 ->setSubject("Office 2007 XLSX Test Document")
							 	 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 	 ->setKeywords("office 2007 openxml php")
							 	 ->setCategory("Test result file");


	//echo date('H:i:s') . " Add some data\n";
	$this->excel->setActiveSheetIndex(0)
				->setCellValue('A1', 'SO Number')
				->setCellValue('B1', $so[0]['so_id'])
	            ->setCellValue('A2', 'Created')
	            ->setCellValue('B2', $so[0]['date'])
	            ->setCellValue('A3', 'Customer')
	            ->setCellValue('B3', $so[0]['customer'])
	            ->setCellValue('A4', 'Seller')
	            ->setCellValue('B4', $so[0]['user'])
	            ->setCellValue('A5', 'Total')
	            ->setCellValue('B5', number_format( $so[0]['total'] ,2, ".","," ))
	            ->setCellValue('A6', 'Store')
	            ->setCellValue('B6', $so[0]['warehouse'])
	            ->setCellValue('A10', 'Sale order details')
	            ->setCellValue('A12', 'Code')
				->setCellValue('B12', 'Name')
				->setCellValue('C12', 'Vendor')
				->setCellValue('D12', 'PO Number')
				->setCellValue('E12','Best Before')
				->setCellValue('F12', 'QTY')
				->setCellValue('G12', 'Unit')
				->setCellValue('H12', 'Unit price')
				->setCellValue('I12', 'Currency')
				->setCellValue('J12', 'Discount')
				->setCellValue('K12', 'Subtotal');

	 $i = 14;
	foreach ($so_details as $item) 
	{

		$subtotal_temp = floatval($item['qty']) * floatval($item['sell_price']); 
		$subtotal_final = $subtotal_temp*(1 - ($item['discount']/100));

		$this->excel->setActiveSheetIndex(0)
					->setCellValue('A' . $i, $item['code'])
					->setCellValue('B' . $i, $item['name_eng'])
					->setCellValue('C' . $i, $item['vendor'])
					->setCellValue('D' . $i, $item['po_id'])
					->setCellValue('E' . $i, $item['best_before'])
					->setCellValue('F' . $i, $item['qty'] )
					->setCellValue('G' . $i, $item['unit'] )
					->setCellValue('H' . $i, number_format( $item['sell_price'],2, ".","," ) )
					->setCellValue('I' . $i, 'Dram' )
					->setCellValue('J' . $i, $item['discount'] )
					->setCellValue('K' . $i, number_format( $subtotal_final,2, ".","," ) );
		$i = $i+1;
	}
	


	
	// Rename sheet
	$this->excel->getActiveSheet()->setTitle('gr_test');
	
	$this->excel->setActiveSheetIndex(0);
			
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="test.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	//ob_end_clean();
	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
	$objWriter->save('php://output');
	//exit;

?>