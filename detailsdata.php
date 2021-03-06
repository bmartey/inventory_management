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
	1 => 'OFFICE', 
	2 => 'ASSET',
	3 => 'DESCR',
	4 => 'SERIAL',
	5 => 'ASSETNO',
	6 => 'DATE',
	7 => 'ID'
);

// getting total number records without any search
$sql = "select a.assetID as ID, a.AssetNumber as ASSETNO, c.AssetCategory as ASSET, o.Office as OFFICE, a.DateIssued as DATE,e.EmployeeName as NAME,a.SerialNumber as SERIAL, a.AssetDescription as DESCR FROM assets as a INNER JOIN employees as e on e.employeeID = a.Employee INNER JOIN office as o on o.officeID = a.Office INNER JOIN asset_category as c on c.assetCategoryID = a.assetCategory where Status='1'";
$query=mysqli_query($conn, $sql) or die("detailsdata.php: get information");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "select a.assetID as ID, a.AssetNumber as ASSETNO, c.AssetCategory as ASSET, o.Office as OFFICE, a.DateIssued as DATE,e.EmployeeName as NAME,a.SerialNumber as SERIAL, a.AssetDescription as DESCR FROM assets as a INNER JOIN employees as e on e.employeeID = a.Employee INNER JOIN office as o on o.officeID = a.Office INNER JOIN asset_category as c on c.assetCategoryID = a.assetCategory where Status='1'";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( e.EmployeeName LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR a.DateIssued LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR o.Office LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR c.AssetCategory LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("detailsdata.php: get information");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
if ($requestData['length'] == -1){
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
}else{
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
//$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains column index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("detailsdata.php: get information");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array();

	$nestedData[] = $row['NAME'];
	$nestedData[] = $row['OFFICE'];
	$nestedData[] = $row['ASSET'];
	$nestedData[] = $row['DESCR'];
	$nestedData[] = $row['SERIAL'];
	$nestedData[] = $row['ASSETNO'];
	$nestedData[] = $row['DATE'];
	$nestedData[] = "<button class='btn btn-neutral btn-round btn-info' type='button' data-toggle='modal' data-target='#assetdetails' onclick='dets({$row['ID']})'><i class='fa fa-pencil'></i></button>";

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
