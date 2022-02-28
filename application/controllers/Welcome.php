<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function  __construct()
		{
			parent::__construct();
			$this->load->library(array('form_validation','session', 'upload'));
			$this->load->helper(array('form','html', 'url'));
			
		}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// require 'vendor/autoload.php';

		// This will output the barcode as HTML output to display in the browser
		// $string = 'abcd12345';
		// $red 	= [255, 0, 0];

		// $data['angka'] = $string;
		// $generator_png = new Picqer\Barcode\BarcodeGeneratorPNG();
		// $data['barcode'] = '';//$generator_png->getBarcode($string, $generator_png::TYPE_CODE_128, 5, 100, $red);
		// file_put_contents('img/'.$string.'.png', $generator_png->getBarcode($string, $generator_png::TYPE_CODE_128, 3, 50));


		// $generator_jpg = new Picqer\Barcode\BarcodeGeneratorJPG();
		// file_put_contents('img/'.$string.'.jpg', $generator_jpg->getBarcode($string, $generator_jpg::TYPE_CODE_128, 3, 50));
		$v_content['content'] = "";
		$this->load->view('welcome_message', $v_content);

		// $data['barcode'] = $this->generate_barcode( "", 1020304050, 20, "horizontal", "code128", true, 1 );
		
	}

	function generate(){
		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		$string    = $this->input->post('p_string');
		$jml       = $this->input->post('p_angka');

		for($i=1;$i<=$jml;$i++){
			$nama      	  = $string.$i.'.png';
			file_put_contents('img/'.$nama, $generator->getBarcode($string, $generator::TYPE_CODE_128, 3, 50));
			$data[] = $nama;
		}
		$test['barcode'] = $data;
		
		$v_content['content'] = $this->load->view('hasil_generate', $test, true);
		$this->load->view('welcome_message', $v_content);
	}

	function export(){
		$barcode = $this->input->post('p_barcode');

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
		$nomor   = 1;
		$tulisan = 3;
		$urut    = 1;

		foreach($barcode as $key => $v_barcode){
			$sheet->setCellValue('A'.$nomor, $urut);
			if(file_exists('img/'.$v_barcode)){
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath('img/'.$v_barcode);
				$drawing->setHeight(35);
				$drawing->setCoordinates('B'.$nomor);
				$drawing->setWorksheet($spreadsheet->getActiveSheet());
			}
			else{
				$sheet->setCellValue('A'.$nomor, 'Kosong');
			}
			
			$sheet->setCellValue('B'.$tulisan, $v_barcode);
			
			$nomor = $nomor+4;
			$tulisan = $tulisan+4;
			$urut++;
		}
		


		$writer = new Xlsx($spreadsheet);
		$writer->save('img/Barcode.xlsx');

		redirect('welcome/index');
	}
}
