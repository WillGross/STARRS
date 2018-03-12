<?php
/*
Template Name: Blank page template (temporary)
*/

//not used
try{
	
	
	
	if($pdf->begin_document("","")==0)
		die("Error: ".$php->get_errmsg());
		
	$pdf->set_info("Creator","test.php");
	$pdf->set_info("Author","Chris Burnham");
	$pdf->set_info("Title","Hello World");
		
	$pdf->begin_page_ext(595,842,"");
		
	$font = $pdf->load_font("Helvetica-Bold","winansi","");
	$pdf->setfont($font,24.0);
		
	$pdf->set_text_pos(50,750);
		
	//$pdf->show(get_the_title());
	$pdf->setfont($font,14.0);
	$pdf->continue_text("");
	
	setup_postdata($post);
	$content = nl2br(get_the_content());
	foreach($content as $line){
		$pdf->continue_text($line);
	}
	
	
	
	
	ob_start();//used to debug
	
	
	var_dump($content);
	
	
	$result = ob_get_clean();
	$pdf->continue_text($result);
	
	
	
	$pdf->end_page_ext("");
		
	$pdf->end_document("");
		
	$buf = $pdf->get_buffer();
	$len = mb_strlen($buf, 'ASCII');

	header("Content-type: application/pdf; charset=utf-8");
	header("Content-Length: $len");
	header("Content-Disposition: attachment; filename=".get_the_title().".pdf");
		
	print $buf;
}
catch(PDFlibException $e){
	die("PDFlib eception has occured:\n"."[".$e->get_errnum()."]".$e->get_apiname().": ".$e->get_errmsg()."\n");
}
catch (Exception $e){
	die($e);
} 
$pdf = 0;