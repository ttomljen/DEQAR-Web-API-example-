<!DOCTYPE html>
<html>
<head>
	
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">

	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.dataTables.min.css">

	<style type="text/css" class="init">
	
	</style>

	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
	

	<!-- Export buttons
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
	-->

</head>

<?php
$configs = include('config.php');

//Data retrieval
//$url='https://backend.deqar.eu/webapi/v1/browse/institutions/?agency='.$configs['agency_id'].'&limit=1000000&offset=0';
$url='https://backend.deqar.eu/webapi/v2/browse/institutions/?agency='.$configs['agency_acronym'].'&limit=1000000&offset=0';

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


//EXAMPLES OF USAGE

/*
echo $result;  							   //will echo native JSON
var_dump(json_decode($result));           // Object
var_dump(json_decode($result, true));     // Associative array

$json = '{"url":"stackoverflow.com","rating":"useful"}';   // in our case $json = $result=curl_exec($ch);

$jsonAsObject   = json_decode($json);
$jsonAsArray    = json_decode($json, TRUE);

echo $jsonAsObject->url;
echo $jsonAsArray['url'];
*/

$jsonAsObject= json_decode($result);

?>


<body>

 	<div class="container">   


<!-- HEADER  (It can be removed if for example you want to use an iframe on your site)  -->
      <div class="text-center">
        <h1 class="h2"><?php echo $configs['agency_name'] ?></h2>
      </div>

		<blockquote class="blockquote">
		<h3 class="mb-0">Evaluation of Higher Education Institutions</h3>
		<footer class="blockquote-footer">Evaluation outcomes by Higher Education Institutions</footer>
		</blockquote>
<!-- HEADER  -->

<!--
This part of the script is related to displaying data in a table.
DataTables are used, more can be found at https://datatables.net/
-->
<script class="init" type="text/javascript" language="javascript">
	 
jQuery.noConflict();
(function( $ ) {

		 $(document).ready(function() {
			var table = $('#example').DataTable( {
			
			responsive: true,

		"dom": '<"pull-left"fB><"pull-right"l><"clearfix"><"wrapper"t><"text-center"i><"clearfix"<"text-center"p>>',	

	/*
        buttons: [
            'copyHtml5',
			{extend: 'excelHtml5',
             title: 'Institutions'},
			{extend: 'pdfHtml5',
             title: 'Institutions'},

        ] ,
	*/

	/* Example of using another language
		
		"language": {
						"url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Croatian.json"
					} ,
	*/
				
		"order": [[0, 'asc']]

			} );
		new $.fn.dataTable.FixedHeader( table );	 

		} );
		
		
})(jQuery);</script>



		<table id="example" class="table table-striped table-bordered  table-responsive " style="width: 100%;">
		
			<thead>
				<tr>
					<th>Institutions</th>
					<th>Location</th>
				</tr>
			</thead>

				<tbody>

						<?php for( $i = 0; $i < $jsonAsObject->count; $i++ ) : ?>
								<tr>

									<td>
																																								
										<h4><a href="https://baza.azvo.hr/addons/download/example/institution.php?&id=<?php echo $jsonAsObject->results[$i]->id; ?>"><?php echo $jsonAsObject->results[$i]->name_official_display; ?></a></h4>	
										<h4 style="color:#808080;"><i><?php echo $jsonAsObject->results[$i]->name_primary; ?></i></h4>										
										<p> <?php echo $jsonAsObject->results[$i]->website_link; ?></p>
									<!--<p> DEQAR ID: <a href="https://www.eqar.eu/qa-results/search/by-institution/institution/?id=<?php echo $jsonAsObject->results[$i]->id;?>" target="_blank"><?php echo $jsonAsObject->results[$i]->id; ?> </a></p>-->
										
									</td>
									<td>  
									<?php 
										foreach($jsonAsObject->results[$i]->place as $place) {echo '<p>' . $place->city . ', ' . $place->country . '</p>';}
									?>										
									</td>

								</tr>
						<?php endfor; ?>	

				</tbody>

			<tfoot>
				<tr>

					<th>Institutions</th>
					<th>Location</th>
				</tr>
			</tfoot>
		</table>


	</div>		

</body>
