<?php 
include_once("access.php");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
/** written by Brian Martey*/
/**access service*/
$level = $_REQUEST['cmd'];  //storing (get/post) request to a variable
$data = array();
$moredata = array();
$access = new access();

if (!empty($level)) {
	switch ($level) {
		case '1':
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];

			$logininfo = $access ->login($username,$password);
			if($logininfo == false){
			    echo 0;
			}else{
				$data['id']= $logininfo['userId'];
				$data['name']= $logininfo['firstname'];
				$data['lname']= $logininfo['lastname'];
				$data['uname']= $logininfo['username'];
				$json_data = $data;

				echo json_encode($json_data); //send data as json format
			}
			break;
		
		case '2':
			$assetinfo = $access ->getassetcategories();
			while($assetinfo = $access -> fetch()){
				$data['id']= $assetinfo['assetCategoryID'];
				$data['name']= $assetinfo['AssetCategory'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;

		case '3':
			$employeeinfo = $access ->getemployees();
			while($employeeinfo = $access -> fetch()){
				$data['id']= $employeeinfo['employeeID'];
				$data['name']= $employeeinfo['EmployeeName'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;
		
		case '4':
			$id = $_REQUEST['id'];
			$divisioninfo = $access ->getdivision($id);
			while($divisioninfo = $access -> fetch()){
				$data['name']= $divisioninfo['NAME'];
				$data['num']= $divisioninfo['NUM'];
				$data['off']= $divisioninfo['OFF'];
				$data['offnum']= $divisioninfo['OFFNO'];
			}
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '5':
			$emp = $_REQUEST['emp'];
			$division = $_REQUEST['div'];
			$office = $_REQUEST['off'];
			$asset = $_REQUEST['asset'];
			$date = $_REQUEST['date'];

			//date_default_timezone_set("Europe/London");
			//$date = date("Y-m-d");
			$ndate = date('Y-m-d', strtotime($date));
			
			$requestinfo = $access ->addrequest($emp, $division,$office, $asset, $ndate);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;
		
		case '6':
			$id = $_REQUEST['id'];
			$status = $_REQUEST['status'];
			$divisioninfo = $access ->updaterequest($status,$id);

			if($status == 'Approved'){
				$assetsinfo = $access ->getrequest($id);
				if($assetsinfo == false){
					echo 0;
				}else{
					date_default_timezone_set("Europe/London");
					$date = date("Y-m-d");
					$aexp = date("Y-m-d",strtotime($date.' +4 years'));

					$assetsinfo = $access ->addasset('',$assetsinfo['Asset'],$assetsinfo['Employee'],$assetsinfo['Division'],$assetsinfo['Office'],'','','','','',$date,'','',$aexp);
					$data = 'success';
				}
					
			}
			
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '7':
			$id = $_REQUEST['id'];
			$assetsinfo = $access ->getemployeeassets($id);
			while($assetsinfo = $access -> fetch()){
				$data['assetid']= $assetsinfo['assetID'];
				$data['category']= $assetsinfo['AssetCategory'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;

		case '8':
			$by = $_REQUEST['by'];
			$des = $_REQUEST['des'];
			$asset = $_REQUEST['asset'];
			$date = $_REQUEST['date'];
			$cost = $_REQUEST['cost'];

			//date_default_timezone_set("Europe/London");
			//$date = date("Y-m-d");
			$ndate = date('Y-m-d', strtotime($date));
			
			$requestinfo = $access ->addmaintenance($asset, $des, $by, $ndate,$cost);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '9':
			$divisioninfo = $access ->getdivisions();
			while($divisioninfo = $access -> fetch()){
				$data['id']= $divisioninfo['divisionID'];
				$data['name']= $divisioninfo['DivisionName'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;

		case '10':
			$officeinfo = $access ->getoffices();
			while($officeinfo = $access -> fetch()){
				$data['id']= $officeinfo['officeID'];
				$data['name']= $officeinfo['Office'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;

		case '11':
			$name = $_REQUEST['name'];
			$div = $_REQUEST['div'];
			$office = $_REQUEST['office'];
			$phone = $_REQUEST['phone'];
			$ext = $_REQUEST['ext'];
			
			$requestinfo = $access ->addemployee($name, $div, $office, $phone,$ext);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '12':
			$countryinfo = $access ->getcountries();
			while($countryinfo = $access -> fetch()){
				$data['id']= $countryinfo['id'];
				$data['name']= $countryinfo['name'];
				$data['phone']= $countryinfo['phonecode'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;
		
		case '13':
			$id = $_REQUEST['id'];
			$countryinfo = $access ->getcountryphonecode($id);
			while($countryinfo = $access -> fetch()){
				$data['num']= $countryinfo['phonecode'];
			}
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '14':
			$name = $_REQUEST['name'];
			$cname = $_REQUEST['cname'];
			$cno = $_REQUEST['cno'];
			$address = $_REQUEST['add'];
			$location = $_REQUEST['loc'];
			$country = $_REQUEST['cou'];
			$vennum = $_REQUEST['num'];
			$notes = $_REQUEST['not'];
			
			$requestinfo = $access ->addvendor($name,$cname,$cno,$address,$location,$country,$vennum,$notes);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '15':
			$id = $_REQUEST['id'];
			$divisioninfo = $access ->getemployeeinfo($id);
			while($divisioninfo = $access -> fetch()){
				$data['name']= $divisioninfo['NAME'];
				$data['num']= $divisioninfo['NUM'];
				$data['officeno']= $divisioninfo['OFFICENO'];
				$data['office']= $divisioninfo['OFFICE'];
			}
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '16':
			$vendorinfo = $access ->getvendors();
			while($vendorinfo = $access -> fetch()){
				$data['id']= $vendorinfo['vendorID'];
				$data['name']= $vendorinfo['VendorName'];
				$moredata[] = $data;
			}
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;

		case '17':
			$ades = $_REQUEST["ades"];
        	$acat = $_REQUEST["acat"];
        	$aemp = $_REQUEST["aemp"];
        	$adiv = $_REQUEST["adiv"];
        	$aoff = $_REQUEST["aoff"];
        	$aven = $_REQUEST["aven"];
        	$anum = $_REQUEST["anum"];
        	$aser = $_REQUEST["aser"];
        	$amod = $_REQUEST["amod"];
        	$adpu = $_REQUEST["adpu"];
        	$adis = $_REQUEST["adis"];
        	$apur = $_REQUEST["apur"];
        	$acur = $_REQUEST["acur"];

			if($adpu != ""){
				$ndpu = date('Y-m-d', strtotime($adpu));
			}else{
				$ndpu = date("Y-m-d");
			}

			if($adis != ""){
				$ndis = date('Y-m-d', strtotime($adis));
			}else{
				$ndis = date("Y-m-d");
			}

			$aexp = date("Y-m-d",strtotime($ndis.' +4 years'));

			$requestinfo = $access ->addasset($ades,$acat,$aemp,$adiv,$aoff,$aven,$anum,$aser,$amod,$ndpu,$ndis,$apur,$acur,$aexp);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '18':
			$id = $_REQUEST['id'];
			$assetinfo = $access ->getassetinfo($id);
			while($assetinfo = $access -> fetch()){
				$data['assid']= $assetinfo['assetID'];
				$data['description']= $assetinfo['AssetDescription'];
				$data['employee']= $assetinfo['Employee'];
				$data['category']= $assetinfo['AssetCategory'];
				$data['division']= $assetinfo['Division'];
				$data['office']= $assetinfo['Office'];
				$data['vendor']= $assetinfo['Vendor'];
				$data['serial']= $assetinfo['SerialNumber'];
				$data['asset']= $assetinfo['AssetNumber'];
				$data['model']= $assetinfo['ModelNumber'];
				$data['purchased']= $assetinfo['DatePurchased'];
				$data['issued']= $assetinfo['DateIssued'];
				$data['sold']= $assetinfo['DateSold'];
				$data['purchase']= $assetinfo['PurchasePrice'];
				$data['current']= $assetinfo['CurrentPrice'];
			}
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '19':
			$ades = $_REQUEST["ades"];
        	$acat = $_REQUEST["acat"];
        	$aemp = $_REQUEST["aemp"];
        	$adiv = $_REQUEST["adiv"];
        	$aoff = $_REQUEST["aoff"];
        	$aven = $_REQUEST["aven"];
        	$anum = $_REQUEST["anum"];
        	$aser = $_REQUEST["aser"];
        	$amod = $_REQUEST["amod"];
        	$adpu = $_REQUEST["adpu"];
        	$adis = $_REQUEST["adis"];
        	$apur = $_REQUEST["apur"];
        	$acur = $_REQUEST["acur"];
			$aaid = $_REQUEST['aid'];

			$ndpu = date('Y-m-d', strtotime($adpu));
			$ndis = date('Y-m-d', strtotime($adis));

			$requestinfo = $access ->updateasset($ades,$acat,$aemp,$adiv,$aoff,$aven,$anum,$aser,$amod,$ndpu,$ndis,$apur,$acur,$aaid);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '20':
        	$aid = $_REQUEST["aid"];

			$requestinfo = $access ->deleteasset($aid);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '21':
			$id = $_REQUEST['id'];
			$assetinfo = $access ->getvendorinfo($id);
			while($assetinfo = $access -> fetch()){
				$data['venid']= $assetinfo['vendorID'];
				$data['name']= $assetinfo['VendorName'];
				$data['cname']= $assetinfo['ContactPersonnel'];
				$data['cno']= $assetinfo['ContactNumber'];
				$data['address']= $assetinfo['Address'];
				$data['location']= $assetinfo['PhysicalLocation'];
				$data['country']= $assetinfo['Country'];
				$data['number']= $assetinfo['PhoneNumber'];
				$data['notes']= $assetinfo['Notes'];
			}
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '22':
        	$aid = $_REQUEST["id"];

			$requestinfo = $access ->deletevendor($aid);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '23':
			$name = $_REQUEST['name'];
			$cname = $_REQUEST['cname'];
			$cno = $_REQUEST['cno'];
			$address = $_REQUEST['add'];
			$location = $_REQUEST['loc'];
			$country = $_REQUEST['cou'];
			$vennum = $_REQUEST['num'];
			$notes = $_REQUEST['not'];
			$id = $_REQUEST['id'];
			
			$requestinfo = $access ->updatevendor($name,$cname,$cno,$address,$location,$country,$vennum,$notes,$id);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '24':
			$name = $_REQUEST['name'];
			$div = $_REQUEST['div'];
			$office = $_REQUEST['office'];
			$phone = $_REQUEST['phone'];
			$ext = $_REQUEST['ext'];
			$id = $_REQUEST['id'];
			
			$requestinfo = $access ->updateemployee($name, $div, $office, $phone, $ext, $id);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '25':
        	$aid = $_REQUEST["id"];

			$requestinfo = $access ->deleteemployee($aid);
			$data = 'success';

			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '26':
			$id = $_REQUEST['id'];
			$divisioninfo = $access ->getemployeedetails($id);
			while($divisioninfo = $access -> fetch()){
				$data['id']= $divisioninfo['employeeID'];
				$data['name']= $divisioninfo['EmployeeName'];
				$data['phone']= $divisioninfo['MobilePhone'];
				$data['ext']= $divisioninfo['Extension'];
				$data['div']= $divisioninfo['Division'];
				$data['off']= $divisioninfo['Office'];
			}
			$json_data = $data;

			echo json_encode($json_data); //send data as json format
			break;

		case '27':
			$id = $_REQUEST['id'];
			$reason = $_REQUEST['rea'];
			$reasoninfo = $access -> deactivateasset($id);

			$reasoninfo = $access -> addreason($id,$reason);

			$json_data = "success";

			echo json_encode($json_data); //send data as json format
			break;
		
		case '28':
			$userno = $_REQUEST['id'];
			$password = $_REQUEST['password'];

			$logininfo = $access ->updatepassword($password,$userno);
			$json_data = "success";

			echo json_encode($json_data); //send data as json format
			break;

		case 'bar':
			$list = $access->getassetbyvendors();

			while($row = $access->fetch()){
				$data['name'] = $row['name'];
				$data['y'] = $row['count'];
				$moredata[] = $data;
			}
				
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;

		case 'pie':
			$list = $access->getassetbycategories();

			while($row = $access->fetch()){
				$data['name'] = $row['cat'];
				$data['y'] = $row['count'];
				$moredata[] = $data;
			}
				
			$json_data = $moredata;

			echo json_encode($json_data); //send data as json format
			break;


		default:
			echo "error";
			break;
	}
}else{
	$data["status"] = "empty";

	echo json_encode($data);
}
?>