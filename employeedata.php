<?php
//customized by Brian Martey 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
/** written by Brian Martey*/
/** messages service*/

/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "it_asset_management";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name	
	0 => 'NAME',
	1 => 'EXT', 
	2 => 'PHONE',
	3 => 'ID'
);

// getting total number records without any search
$sql = "select employeeID as ID, EmployeeName as NAME, Extension as EXT, MobilePhone as PHONE FROM employees";
$query=mysqli_query($conn, $sql) or die("employeedata.php: get information");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "select employeeID as ID, EmployeeName as NAME, Extension as EXT, MobilePhone as PHONE FROM employees";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" WHERE ( EmployeeName LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR MobilePhone LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR Extension LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("employeedata.php: get information");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
if ($requestData['length'] == -1){
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
}else{
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
//$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains column index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("employeedata.php: get information");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array();

	$nestedData[] = $row['NAME'];
	$nestedData[] = $row['EXT'];
	$nestedData[] = $row['PHONE'];
	$nestedData[] = "<button class='btn btn-neutral btn-round btn-info' type='button'data-toggle='modal' data-target='#empdetails' onclick='empdets({$row['ID']})'><i class='fa fa-pencil'></i></button>";

	$data[] = $nestedData;
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
