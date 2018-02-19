<?php
/** written by Brian Martey
*/
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
include_once("adb.php");
require_once("phpmailer/class.phpmailer.php");
require 'phpmailer/PHPMailerAutoload.php';
/**
*access  class
*/
class access extends adb{
	function access(){
	}
	/**
	*login function
	*@param username username string
	*@param password password string
	*returns a boolean true if successful, else, false
	*/
	function login($username,$password){
		$strQuery="select userId, firstname, lastname, username from users where username = '$username' && password = MD5('$password')";
		$result = $this->query($strQuery);
		if ($result){
			return $this->fetch();
		}else{
			return $result;
		}
	}

	function getassetcategories(){
		$strQuery="select assetCategoryID, AssetCategory from asset_category";
		$result = $this->query($strQuery);
	}

	function getassetbycategories(){
		$strQuery="select Count(a.AssetCategory) as count,c.AssetCategory as cat from assets as a INNER JOIN asset_category as c on c.assetCategoryID = a.AssetCategory group by c.AssetCategory";
		$result = $this->query($strQuery);
	}

	function getassetbyvendors(){
		$strQuery="select Count(a.Vendor) as count,v.VendorName as name from assets as a INNER JOIN vendors as v on v.vendorID = a.Vendor group by v.VendorName";
		$result = $this->query($strQuery);
	}

	function getassetinfo($id){
		$strQuery="select * from assets where assetID = '$id' ";
		$result = $this->query($strQuery);
	}

	function getcountries(){
		$strQuery="select id, name, phonecode from countries";
		$result = $this->query($strQuery);
	}

	function getcountryphonecode($id){
		$strQuery="select phonecode from countries where id = '$id'";
		$result = $this->query($strQuery);
	}

	function getdivisions(){
		$strQuery="select divisionID, DivisionName from divisions order by DivisionName ASC";
		$result = $this->query($strQuery);
	}

	function getdivision($id){
		$strQuery="select d.DivisionName as NAME,e.Division as NUM,o.Office as OFF,e.Office as OFFNO from employees as e INNER JOIN divisions as d on d.divisionID = e.Division INNER JOIN office as o on o.officeID = e.Office where e.employeeID = '$id'";
		$result = $this->query($strQuery);
	}

	function getemployees(){
		$strQuery="select employeeID, EmployeeName, Division from employees order by EmployeeName ASC";
		$result = $this->query($strQuery);
	}

	function getemployeedetails($id){
		$strQuery="select employeeID, EmployeeName, Division, Office, MobilePhone, Extension from employees where employeeID ='$id'";
		$result = $this->query($strQuery);
	}

	function getemployeeassets($id){
		$strQuery="select a.assetID,a.AssetDescription,c.AssetCategory from assets as a INNER JOIN asset_category as c on c.assetCategoryID = a.AssetCategory where a.Employee = '$id'";
		$result = $this->query($strQuery);
	}

	function getemployeeinfo($id){
		$strQuery="select d.DivisionName as NAME,e.Division as NUM,e.Office as OFFICENO, o.Office as OFFICE from employees as e INNER JOIN divisions as d on d.divisionID = e.Division INNER JOIN office as o on e.Office = o.officeID where e.employeeID = '$id'";
		$result = $this->query($strQuery);
	}

	function getoffices(){
		$strQuery="select officeID, Office from office order by Office ASC";
		$result = $this->query($strQuery);
	}

	function getrequest($id){
		$strQuery="select r.Employee as Employee, r.Division as Division, r.Asset as Asset, r.DateRequested as DateRequested, r.Office as Office from requests as r where r.requestID = '$id'";
		$result = $this->query($strQuery);
		if ($result){
			return $this->fetch();
		}else{
			return $result;
		}
	}

	function getvendors(){
		$strQuery="select vendorID, VendorName from vendors order by VendorName ASC";
		$result = $this->query($strQuery);
	}

	function getvendorinfo($id){
		$strQuery="select vendorID, VendorName, ContactPersonnel, ContactNumber, Address, PhysicalLocation, Country, PhoneNumber, Notes from vendors where vendorID ='$id'";
		$result = $this->query($strQuery);
	}

	function addasset($ades,$acat,$aemp,$adiv,$aoff,$aven,$anum,$aser,$amod,$adpu,$adis,$apur,$acur,$aexp){
		$strQuery="insert into assets set AssetDescription='$ades',AssetCategory='$acat',Employee='$aemp',Division='$adiv',Office='$aoff',Vendor='$aven',AssetNumber='$anum',SerialNumber='$aser',ModelNumber='$amod',DatePurchased='$adpu',DateIssued='$adis',PurchasePrice='$apur',CurrentPrice='$acur',ExpiryDate='$aexp', Status='1'";
		$result = $this->query($strQuery);
	}

	function addemployee($name,$div,$office,$phone,$ext){
		$strQuery="insert into employees set EmployeeName='$name', Division='$div', Office='$office',MobilePhone='$phone',Extension='$ext' ";
		$result = $this->query($strQuery);
	}

	function addmaintenance($assetid,$description,$by,$datemaintained,$cost){
		$strQuery="insert into maintenance set AssetID='$assetid', MaintenanceDescription='$description', MaintenanceBy='$by',MaintenanceDate='$datemaintained',MaintenanceCost='$cost' ";
		$result = $this->query($strQuery);
	}

	function addrequest($employeeid,$divisionid,$officeid,$assetid,$daterequested){
		$strQuery="insert into requests set Employee='$employeeid', Division='$divisionid', Asset='$assetid',DateRequested='$daterequested',Office='$officeid' ";
		$result = $this->query($strQuery);
	}

	function addreason($assetid,$reason){
		$strQuery="insert into reasons set AssetID='$assetid',Reason ='$reason' ";
		$result = $this->query($strQuery);
	}

	function addvendor($name,$cname,$cno,$address,$location,$country,$vennum,$notes){
		$strQuery="insert into vendors set VendorName='$name', ContactPersonnel='$cname', ContactNumber='$cno',Address='$address',PhysicalLocation='$location',Country='$country',PhoneNumber='$vennum',Notes='$notes' ";
		$result = $this->query($strQuery);
	}

	function deactivateasset($aid){
		$strQuery="update assets set Status='2' where assetID='$aid'";
		$result = $this->query($strQuery);
	}

	function updateasset($ades,$acat,$aemp,$adiv,$aoff,$aven,$anum,$aser,$amod,$adpu,$adis,$apur,$acur,$aid){
		$strQuery="update assets set AssetDescription='$ades',AssetCategory='$acat',Employee='$aemp',Division='$adiv',Office='$aoff',Vendor='$aven',AssetNumber='$anum',SerialNumber='$aser',ModelNumber='$amod',DatePurchased='$adpu',DateIssued='$adis',PurchasePrice='$apur',CurrentPrice='$acur', Status='1' where assetID='$aid'";
		$result = $this->query($strQuery);
	}

	function updateemployee($name,$div,$office,$phone,$ext,$id){
		$strQuery="update employees set EmployeeName='$name', Division='$div', Office='$office',MobilePhone='$phone',Extension='$ext' where employeeID ='$id' ";
		$result = $this->query($strQuery);
	}

	function updatepassword($pass,$id){
		$strQuery="update users set password=MD5('$pass') where userId='$id'";
		$result = $this->query($strQuery);
	}

	function updaterequest($status,$id){
		$strQuery="update requests set Status='$status' where requestID='$id'";
		$result = $this->query($strQuery);
	}

	function updatevendor($name,$cname,$cno,$address,$location,$country,$vennum,$notes,$id){
		$strQuery="update vendors set VendorName='$name', ContactPersonnel='$cname', ContactNumber='$cno',Address='$address',PhysicalLocation='$location',Country='$country',PhoneNumber='$vennum',Notes='$notes'  where vendorID='$id'";
		$result = $this->query($strQuery);
	}

	function deleteasset($aid){
		$strQuery="delete from assets where assetID='$aid'";
		$result = $this->query($strQuery);
	}

	function deleteemployee($id){
		$strQuery="delete from employees where employeeID ='$id' ";
		$result = $this->query($strQuery);
	}

	function deletevendor($id){
		$strQuery="delete from vendors where vendorID='$id'";
		$result = $this->query($strQuery);
	}
			
}
?>