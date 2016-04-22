<?php 
	/* 导出excel函数*/
	function php_export_excel($objPHPExcel,$filename = "Excel"){

			error_reporting(E_ALL);
			date_default_timezone_set('PRC');
			/*以下是一些设置 ，什么作者  标题啊之类的*/
			$objPHPExcel->getProperties()->setCreator("金享财行")
								   ->setLastModifiedBy("金享财行")
								   ->setTitle("数据EXCEL导出")
								   ->setSubject("数据EXCEL导出")
								   ->setDescription("备份数据")
								   ->setKeywords("excel")
								  ->setCategory("result file");
			$objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');
			$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray(array('font' => array ('bold' => true), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
			$objPHPExcel->getActiveSheet()->getStyle('A2:L2000')->applyFromArray(array('alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
			//设置行高
			$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);

			$objPHPExcel->getActiveSheet()->setTitle($filename);
			$objPHPExcel->setActiveSheetIndex(0);
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			header('Cache-Control: max-age=1');
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
	}
	
	//调用函数示例
	//导出Excel入金表
	require_once APP_ROOT_PATH."public/PHPExcel/PHPExcel.php";
	$objPHPExcel = new PHPExcel();

	$deal_load_lists = array();
	foreach($deal_load_list as $key => $value){
		$deal_load_lists[$key+1] = $value;
	}
	$deal_load_lists[0] = array();
	ksort($deal_load_lists);
	/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
	foreach($deal_load_lists as $key => $value){
		$num=$key + 1;
		$pre_income = (($value['rate'] / 100) / 360) * $value['money'] * $value['yield_ratio'] * $value['repay_time']; //预期收益
		//保留两位小数
		$pre_income = sprintf("%.2f", substr(sprintf("%.4f", $pre_income), 0, -2));
		$account_all = num_format(($value['money'] + $pre_income)); //本息总额
		//标的状态
		if ($value['deal_status'] == 2) {
			$value['deal_status'] = '满标';
		} elseif ($value['deal_status'] == 3) {
			$value['deal_status'] = '流标';
		} elseif ($value['deal_status'] == 4) {
			$value['deal_status'] = '还款中';
		} elseif ($value['deal_status'] == 5) {
			$value['deal_status'] = '已还清';
		}
		if($key == 0){
			$objPHPExcel->setActiveSheetIndex(0)
					  ->setCellValue('A'.$num, '编号')
					  ->setCellValue('B'.$num, "投资编号")
					  ->setCellValue('C'.$num, "用户名")
					  ->setCellValue('D'.$num, "标的名称")
					  ->setCellValue('E'.$num, "投资期限（天）")
					  ->setCellValue('F'.$num, "年化收益(%)")
					  ->setCellValue('G'.$num, "投资本金")
					  ->setCellValue('H'.$num, "预期收益")
					  ->setCellValue('I'.$num, "本息总额")
					  ->setCellValue('J'.$num, "投资时间")
					  ->setCellValue('K'.$num, "还款日期")
					  ->setCellValue('L'.$num, "标的状态");
		}else{
			$objPHPExcel->setActiveSheetIndex(0)
					  ->setCellValue('A'.$num, $num-1)
					  ->setCellValue('B'.$num, $value['id'])
					  ->setCellValue('C'.$num, $value['real_name'])
					  ->setCellValue('D'.$num, $value['name'])
					  ->setCellValue('E'.$num, $value['repay_time'])
					  ->setCellValue('F'.$num, $value['rate'])
					  ->setCellValue('G'.$num, $value['money'])
					  ->setCellValue('H'.$num, $pre_income)
					  ->setCellValue('I'.$num, $account_all)
					  ->setCellValue('J'.$num, $value['create_date'])
					  ->setCellValue('K'.$num, $value['jiexi_time'])
					  ->setCellValue('L'.$num, $value['deal_status']);
		}
	}
	//设置属性
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getStyle( 'A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle( 'A1:L1')->getFill()->getStartColor()->setARGB('FFFFD700');

	$filename = $deal_info['name'] . "入金表";
	php_export_excel($objPHPExcel,$filename);