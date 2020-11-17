<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" type="text/css" href="css/accordion.css" />
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<?php


// CHECK IF THE INSTITUTION IS DEFINED
	if(!isset($_GET["id"]))  {
		header("Location: https://www.eqar.eu/qa-results/search/by-institution/");
		exit;
	} else {
		$institution_id=$_GET["id"];
	}

	if (!is_numeric($institution_id)){
		header("Location: https://www.eqar.eu/qa-results/search/by-institution/");
	}




//Data retrieval

$configs = include('config.php');

// Agency token
$authToken = '4025c05daa81d857542680c7b859e2ad5e30264d';

//url for retrieving reports at the institutional level
$url="https://backend.deqar.eu/webapi/v2/browse/reports/institutional/by-institution/".$institution_id."/";

//url for retrieving reports at the programme level
$url2="https://backend.deqar.eu/webapi/v1/browse/reports/programme/by-institution/".$institution_id."/?limit=1000";


//Institutional level
$ch = curl_init();
// Will return the response, if false it print the response

curl_setopt_array($ch, array(

    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array(

		'username: '.$configs['username'].'',
		'password: '.$configs['password'].'',
		'Authorization: Bearer '.$configs['authToken'],
        'Content-Type: application/json'
    )
));

// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);



//Programme level
$ch2 = curl_init();
// Will return the response, if false it print the response

curl_setopt_array($ch2, array(

    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_URL => $url2,
    CURLOPT_HTTPHEADER => array(

		'username: '.$configs['username'].'',
		'password: '.$configs['password'].'',
		'Authorization: Bearer '.$configs['authToken'],
        'Content-Type: application/json'
    )
));

// Execute
$result2=curl_exec($ch2);
// Closing
curl_close($ch2);



//echo $result;  							//will echo native JSON
//var_dump(json_decode($result));           // Object
//var_dump(json_decode($result, true));     // Associative array


//Example of usage
/*
$json       = '{"url":"stackoverflow.com","rating":"useful"}';   // in our case $json = $result=curl_exec($ch);

$jsonAsObject   = json_decode($json);
$jsonAsArray    = json_decode($json, TRUE);

echo $jsonAsObject->url;
echo $jsonAsArray['url'];
*/
$jsonAsObject= json_decode($result);

$jsonAsObject2= json_decode($result2);



$icons = include('icons.php');
?>







<body>
<div class="container">

</br>
	<a type="button" class="btn btn-default btn-sm" href='javascript:history.back(1);'>
		<span class="glyphicon glyphicon-menu-left"></span>  Return to the list of higher education institutions
	</a>

	</br></br></br>


	<div class="page-header"><h4><strong>Carried out evaluation procedure</strong></h4></div>


<!-- Institutional level reports -->

	<div class="page-header"><h4><strong>Institutional level <?php echo '('.$jsonAsObject->count.' reports)';?></strong></h4></div>
	
	<div class="container">
	
	<?php for( $i = 0; $i < $jsonAsObject->count; $i++ ) : ?>
	
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
													
					<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading<?php echo $i; ?>">
								<h4 class="panel-title">
									  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>"><?php echo $jsonAsObject->results[$i]->name;?>
															  <?php 
															  if ($jsonAsObject->results[$i]->decision=='positive') 
															  {echo '&nbsp;'; echo $icons['positive'];}
															  elseif($jsonAsObject->results[$i]->decision=='negative')
															  {echo '&nbsp;'; echo $icons['negative'];}
															  ?>
										</a>
								</h4>
							</div>
							<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
								<div class="panel-body">						
								
							<div class="container-fluid">

								<div class="row"><div class="col-xs-6 col-md-2"><p>Agency</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject->results[$i]->agency_name.' ('.$jsonAsObject->results[$i]->agency_acronym.')';?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Type</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject->results[$i]->agency_esg_activity;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Status</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject->results[$i]->status;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Formal decision</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject->results[$i]->decision;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Date</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject->results[$i]->valid_from;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Valid until</p></div><div class="col-xs-6 col-md-10"><p><?php  if($jsonAsObject->results[$i]->valid_to){echo $jsonAsObject->results[$i]->valid_to;}else{echo'not applicable';};?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Report and decision</p></div>
									<div class="col-xs-6 col-md-10">
									<?php for( $a = 0; $a < count($jsonAsObject->results[$i]->report_files); $a++ ) : ?>
										<p><a href="<?php echo $jsonAsObject->results[$i]->report_files[$a]->file;?>"><?php echo $jsonAsObject->results[$i]->report_files[$a]->file_display_name;?></a>
											<?php for( $b = 0; $b < count( $jsonAsObject->results[$i]->report_files[$a]->languages); $b++ ) : 
											 echo '('.$jsonAsObject->results[$i]->report_files[$a]->languages[$b].')';
											 endfor; ?>
										</p>																			
									<?php endfor; ?>																						
									</div>
								</div>


							</div>


								</div>
							</div>
					</div>
			</div> 							
	
	<?php endfor; ?>
	
	</div> 

<!-- Programme level reports -->

	<div class="page-header"><h4><strong>Programme level <?php echo '('.$jsonAsObject2->count.' reports)';?></strong></h4></div>
	



	<div class="container">
	
	<?php for( $i2 = 0; $i2 <$jsonAsObject2->count; $i2++ ) : ?>
	
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
													
					<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading<?php echo $i+$i2; ?>">
								<h4 class="panel-title">
									  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i+$i2; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i+$i2; ?>"><?php echo $jsonAsObject2->results[$i2]->programme_names[0]->name; echo ', '.$jsonAsObject2->results[$i2]->qf_ehea_level;?>
															  <?php 
																if ($jsonAsObject2->results[$i2]->report->decision=='positive') 
																{echo '&nbsp;'; echo $icons['positive'];}
																elseif($jsonAsObject2->results[$i2]->report->decision=='negative')
																{echo '&nbsp;'; echo $icons['negative'];}
															  ?>
										</a>
								</h4>
							</div>
							<div id="collapse<?php echo $i+$i2; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i+$i2; ?>">
								<div class="panel-body">						
								
							<div class="container-fluid">

								
								<div class="row"><div class="col-xs-6 col-md-2"><p>Qualification</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->programme_names[0]->qualification;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2"><p>Level</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->qf_ehea_level.' (NQF '; echo $jsonAsObject2->results[$i2]->nqf_level.')';?></p></div></div>
								
								<div class="row"><div class="col-xs-6 col-md-2"><p>Agency</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->report->agency_name.' ('.$jsonAsObject2->results[$i2]->agency_acronym.')';?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Type</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->report->name.' ('; echo $jsonAsObject2->results[$i2]->report->agency_esg_activity.')';?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Status</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->report->status;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Formal decision</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->report->decision;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Date</p></div><div class="col-xs-6 col-md-10"><p><?php echo $jsonAsObject2->results[$i2]->report->valid_from;?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Valid until</p></div><div class="col-xs-6 col-md-10"><p><?php  if($jsonAsObject2->results[$i2]->report->valid_to){echo $jsonAsObject2->results[$i2]->report->valid_to;}else{echo'not applicable';};?></p></div></div>
								<div class="row"><div class="col-xs-6 col-md-2">Report and decision</p></div>
									<div class="col-xs-6 col-md-10">
									<?php for( $a = 0; $a < count($jsonAsObject2->results[$i2]->report->report_files); $a++ ) : ?>
										<p><a href="<?php echo $jsonAsObject2->results[$i2]->report->report_files[$a]->file;?>"><?php echo $jsonAsObject2->results[$i2]->report->report_files[$a]->file_display_name;?></a>
											<?php for( $b = 0; $b < count( $jsonAsObject2->results[$i2]->report->report_files[$a]->languages); $b++ ) : 
											 echo '('.$jsonAsObject2->results[$i2]->report->report_files[$a]->languages[$b].')';
											 endfor; ?>
										</p>																			
									<?php endfor; ?>																						
									</div>
								</div>


							</div>


								</div>
							</div>
					</div>
			</div> 							
	
	<?php endfor; ?>
	
	</div> 






</div>
</body>
</html>