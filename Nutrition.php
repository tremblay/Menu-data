<?php

/*****************************************************
* Colin Tremblay
* Grinnell College '14
*
* This file creates .json file from the xml of all nutritional information.
*
*****************************************************/

// load input file
$file = "nutrition.xml";
$xml = simplexml_load_file($file) or die ("Unable to load XML file!");

// setup output file
$outfile = "nutrition.json";
if( ($out_handle = fopen($outfile, 'w')) == false ){
	echo('Failed to create nutrition file.');
}

// start building the JSON
$output = "{\n\t\"items\": [\n";
// iterate through each menu item
foreach ($xml->xpath('//d_itm_recipe_perportion_nutr_analysis_group1') as $item){
	// add the name of the item
	$tempName = str_replace('"','\\"',$item->srv_name);
	$output = $output."\t\t{\n\t\t\t\"".$tempName."\": {";
	// iterate to the nutrition for the item itself (not its ingredients) and add this
	foreach ($item->d_itm_nutr_analysis_nup_25_values_x->d_itm_nutr_analysis_nup_25_values_x_row as $element)
		$output = $output."\n\t\t\t\t\"$element->ls_element\":\"$element->ptn1_qty\",";
	// remove trailing coma and finalize the output
	$output = trim($output, ",");
	$output = $output."\n\t\t\t}\n\t\t},\n";
}
// remove trailing coma and finalize the output
$output = trim($output, ",\n");
$output = $output."\n\t]\n} ";
// write the file
fwrite($out_handle, $output);
fclose($out_handle);
?>