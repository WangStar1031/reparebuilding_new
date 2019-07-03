<?php
	require_once __DIR__ . '/library/Mysql.php';
	
	$db = new Mysql();
	$db->exec("set names utf8");
	function getApartmentInfo_DB($_aptName){

	}
	function getAllUsers(){
		global $db;
		$sql = "SELECT * FROM user";
		return $db->select($sql);
	}
	function getAllProjects(){
		global $db;
		$sql = "SELECT * FROM projectinfo";
		return $db->select($sql);
	}
	function getProjectInfo4User($_userId){
		global $db;
		$sql = "SELECT * FROM user_project WHERE UserId = '$_userId'";
		return $db->select($sql);
	}
	function saveProjectName($_projectPath, $_projectName){
		global $db;
		$sql = "SELECT * FROM projectinfo WHERE ProjectPath = '$_projectPath'";
		$result = $db->select($sql);
		if( $result == false){
			$sql = "INSERT INTO projectinfo(ProjectPath, ProjectName) VALUES(?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_projectPath, $_projectName]);
		} else{
			$sql = "UPDATE projectinfo SET ProjectName = ? WHERE ProjectPath = ?";
			$stmt= $db->prepare($sql);
			$stmt->execute([$_projectName, $_projectPath]);
		}
		echo "OK";
	}
	function getArrDefects($_projectPath, $_apartNo){
		$projectInfo = getProjectInfo($_projectPath);
		if( $projectInfo == false){
			return [];
		}
		return getAllDefects($projectInfo[0]['Id'], $_apartNo);
	}
	function getArrReparation($_projectPath, $_apartNo){
		$projectInfo = getProjectInfo($_projectPath);
		if( $projectInfo == false){
			return [];
		}
		return getAllReparations($projectInfo[0]['Id'], $_apartNo);
	}
	function getArrNotes($_projectPath, $_apartNo){
		$projectInfo = getProjectInfo($_projectPath);
		if( $projectInfo == false){
			return [];
		}
		return getAllNotes($projectInfo[0]['Id'], $_apartNo);
	}
	function getProjectInfo( $_projectPath){
		global $db;
		$sql = "SELECT * FROM projectinfo WHERE ProjectPath='$_projectPath'";
		$result = $db->select($sql);
		return $result;
	}
	function getApartments($_projectId){///adsfdsfasdf
		// return false;
		global $db;
		$sql = "SELECT * FROM apartmentinfo WHERE ProjectId='$_projectId' ORDER BY Id ASC";
		$result = $db->select($sql);
		if( $result == false){
			return false;
		}
		$arrRetVal = [];
		foreach ($result as $value) {
			$objApart = new \stdClass;
			$objApart->Id = $value['Id'];
			$objApart->ApartmentName = $value['ApartmentName'];
			$objApart->SectionCount = $value['SectionCount'];
			$objApart->PictureCount = $value['PictureCount'];
			$objApart->arrPartInfos = [];
			$objApart->arrSectionInfos = [];
		}
		return $result;
	}
	function getParts($_projectId){
		global $db;
		$sql = "SELECT * FROM parts WHERE ProjectId='$_projectId'";
		$result = $db->select($sql);
		return $result;
	}
	function saveParts($_projectId, $_arrParts){
		global $db;
		$result = getParts($_projectId);
		$arrCurParts = [];
		if( $result){
			foreach ($result as $value) {
				$arrCurParts[] = $value['PartName'];
			}
		}
		foreach ($arrCurParts as $value) {
			if( !in_array($value, $_arrParts)){
				$sql = "DELETE FROM parts WHERE ProjectId='$_projectId' AND PartName='$value'";
				$db->__exec__($sql);
			}
		}
		foreach ($_arrParts as $value) {
			if( !in_array($value, $arrCurParts)){
				$sql = "INSERT INTO parts(ProjectId, PartName) VALUES(?,?)";
				$stmt = $db->prepare($sql);
				$stmt->execute([$_projectId, $value]);
			}
		}
	}
	function getApratmentInfo( $_projectId){
		global $db;
		$sql = "SELECT * FROM apartmentinfo WHERE ProjectId='$_projectId' ORDER BY Id ASC";
		$result = $db->select($sql);
		return $result;
	}
	function saveApartmentInfo($_projectId, $_arrApartments){
		global $db;
		$result = getApratmentInfo($_projectId);
		$arrCurApartmentIDs = [];
		if( $result){
			foreach ($result as $value) {
				$arrCurApartmentIDs[] = $value['Id'];
			}
		}
		$sql = "DELETE FROM apartmentinfo WHERE ProjectId='$_projectId'";
		$db->__exec__($sql);
		if( count($arrCurApartmentIDs)){
			$strWhere = implode(",", $arrCurApartmentIDs);
			$sql = "DELETE FROM partinfo WHERE ApartmentId in ('$strWhere')";
			$db->__exec__($sql);
			$sql = "DELETE FROM sectioninfo WHERE ApartmentId in ('$strWhere')";
			$db->__exec__($sql);
		}
		foreach ($_arrApartments as $value) {
			$aptName = $value->ApartmentName;
			$secCount = $value->SectionCount;
			$picCount = $value->PictureCount;
			$PartInfos = $value->PartInfos;
			$SectionInfos = $value->SectionInfos;
			$sql = "INSERT INTO apartmentinfo(ProjectId, ApartmentName, SectionCount, PictureCount, PartInfos, SectionInfos) VALUES(?,?,?,?,?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_projectId, $aptName, $secCount, $picCount, $PartInfos, $SectionInfos]);
		}
	}
	function saveProjectInfo( $_objProjectInfo){
		global $db;
		$ProjectPath = $_objProjectInfo->ProjectPath;
		$sql = "SELECT * FROM projectinfo WHERE ProjectPath='$ProjectPath'";
		$result = $db->select($sql);
		if( $result == false){
			$sql = "INSERT INTO projectinfo(ProjectPath, ProjectName, ProjectNumber, ProjectType, Zone, City, Street, No, Constructor, ProjectManager, WorksManager, Photography, DocumentDate, BuildingNumber, EntranceNumber) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_objProjectInfo->ProjectPath, $_objProjectInfo->ProjectName, $_objProjectInfo->ProjectNumber, $_objProjectInfo->ProjectType, $_objProjectInfo->Zone, $_objProjectInfo->City, $_objProjectInfo->Street, $_objProjectInfo->No, $_objProjectInfo->Constructor, $_objProjectInfo->ProjectManager, $_objProjectInfo->WorksManager, $_objProjectInfo->Photography, $_objProjectInfo->DocumentDate, $_objProjectInfo->BuildingNumber, $_objProjectInfo->EntranceNumber]);
		} else{
			$sql = "UPDATE projectinfo SET ProjectPath=?, ProjectName=?, ProjectNumber=?, ProjectType=?, Zone=?, City=?, Street=?, No=?, Constructor=?, ProjectManager=?, WorksManager=?, Photography=?, DocumentDate=?, BuildingNumber=?, EntranceNumber=? WHERE ProjectPath='$ProjectPath'";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_objProjectInfo->ProjectPath, $_objProjectInfo->ProjectName, $_objProjectInfo->ProjectNumber, $_objProjectInfo->ProjectType, $_objProjectInfo->Zone, $_objProjectInfo->City, $_objProjectInfo->Street, $_objProjectInfo->No, $_objProjectInfo->Constructor, $_objProjectInfo->ProjectManager, $_objProjectInfo->WorksManager, $_objProjectInfo->Photography, $_objProjectInfo->DocumentDate, $_objProjectInfo->BuildingNumber, $_objProjectInfo->EntranceNumber]);
		}

		$sql = "SELECT * FROM projectinfo WHERE ProjectPath='$ProjectPath'";
		$result = $db->select($sql);
		$_projectId = $result[0]['Id'];
		saveParts($_projectId, $_objProjectInfo->arrParts);
		saveApartmentInfo($_projectId, $_objProjectInfo->arrApartmentInfo);
		echo "OK";
	}
	function DeleteProject($_directoryName){
		global $db;
		$sql = "DELETE FROM projectinfo WHERE ProjectPath='$_directoryName'";
		$db->__exec__($sql);
	}
	function removeUser($_UserEmail){
		global $db;
		$sql = "DELETE FROM user WHERE UserEmail='$_UserEmail'";
		$db->__exec__($sql);
		echo "OK";
	}
	function getAllDefects($_projectId, $_aptNo){
		global $db;
		$sql = "SELECT * FROM defect_reparation WHERE ProjectId='$_projectId' AND ApartmentNumber='$_aptNo' AND TYPE='Defect' GROUP BY PhotoIdx";
		$result = $db->select($sql);
		if( $result == false)return [];
		return $result;
	}
	function getAllReparations($_projectId, $_aptNo){
		global $db;
		$sql = "SELECT * FROM defect_reparation WHERE ProjectId='$_projectId' AND ApartmentNumber='$_aptNo' AND TYPE='Reparation' GROUP BY PhotoIdx";
		$result = $db->select($sql);
		if( $result == false)return [];
		return $result;
	}
	function removeUploadedImg($_idx){
		global $db;
		$sql = "SELECT * FROM defect_reparation WHERE Id='$_idx'";
		$result = $db->select($sql);
		if( $result == false){
			return "Invalid Index.";
		}
		$orgFName = $result[0]['OriginalFilePath'];
		$smlFName = $result[0]['SmallFilePath'];
		if( file_exists(__DIR__ . "/" . $orgFName)){
			unlink(__DIR__ . "/" . $orgFName);
		}
		if( file_exists(__DIR__ . "/" . $smlFName)){
			unlink(__DIR__ . "/" . $smlFName);
		}
		$sql = "DELETE FROM defect_reparation WHERE Id='$_idx'";
		$db->__exec__($sql);
		return "OK";
	}
	function GetUploadedPhotos_DB($_projectName, $_apartNo, $_idxPhoto, $_catPhoto){
		global $db;
		$projectInfo = getProjectInfo($_projectName);
		// print_r($projectInfo);
		if( $projectInfo == false)
			return [];
		$projectId = $projectInfo[0]['Id'];
		$sql = "SELECT * FROM defect_reparation WHERE ProjectId='$projectId' AND ApartmentNumber='$_apartNo' AND PhotoIdx='$_idxPhoto' AND PhotoCat='$_catPhoto' ORDER BY idxGroup ASC";
		// print_r($sql);
		$result = $db->select($sql);
		// print_r($result);
		if( $result == false)
			return [];
		$groupId = -1;
		$arrUploads = [];
		foreach ($result as $value) {
			if( $value['idxGroup'] != $groupId){
				if( isset($newGroup)){
					$arrUploads[] = $newGroup;
				}
				$newGroup = new \stdClass;
				$groupId = $value['idxGroup'];
				$newGroup->groupId = $groupId;
				$newGroup->arrNodes = [];
				$newGroup->posRect = json_decode($value['PosRect']);
			}
			$nodes = new \stdClass;
			$nodes->fileUrl = $value['OriginalFilePath'];
			$nodes->fileSUrl = $value['SmallFilePath'];
			$nodes->info = json_decode($value['Infos']);
			$nodes->Type = $value['Type'];
			$nodes->Id = $value['Id'];
			$newGroup->arrNodes[] = $nodes;
		}
		if( isset($newGroup)){
			$arrUploads[] = $newGroup;
		}
		return $arrUploads;
	}
	function ImageUpload_DB($_projectName, $_apartNo, $_idxPhoto, $_catPhoto, $_idxGroup, $_Type, $originalName, $smallName, $_posRect, $_infos){
		global $db;
		$projectInfo = getProjectInfo($_projectName);
		if( $projectInfo == false)
			return false;
		$projectId = $projectInfo[0]['Id'];
		if( $_idxGroup == -1){
			$sql = "SELECT * FROM defect_reparation WHERE ProjectId='$projectId' AND ApartmentNumber='$_apartNo' AND PhotoIdx='$_idxPhoto' AND PhotoCat='$_catPhoto' GROUP BY idxGroup ORDER BY idxGroup DESC";
			$result = $db->select($sql);
			if( $result == false){
				$_idxGroup = 0;
			} else{
				$_idxGroup = $result[0]['idxGroup'] + 1;
			}
		}
		$sql = "INSERT INTO defect_reparation(ProjectId, ApartmentNumber, PhotoIdx, PhotoCat, idxGroup, Type, OriginalFilePath, SmallFilePath, PosRect, Infos) VALUES(?,?,?,?,?,?,?,?,?,?)";
		$stmt = $db->prepare($sql);
		$stmt->execute([$projectId, $_apartNo, $_idxPhoto, $_catPhoto, $_idxGroup, $_Type, $originalName, $smallName, $_posRect, $_infos]);
	}
	function getAllNotes($_projectId, $_aptNo){
		global $db;
		$sql = "SELECT * FROM note WHERE ProjectId='$_projectId' AND ApartmentNumber='$_aptNo' AND Notes <> ''";
		$result = $db->select($sql);
		if( $result == false)return [];
		return $result;
	}
	function updateNotes($_projectName, $_apartNo, $_photoNumber, $_strNotes){
		global $db;
		$projectInfo = getProjectInfo($_projectName);
		if( $projectInfo == false){
			echo "Invalid Project Id.";
			return;
		}
		$projectId = $projectInfo[0]['Id'];
		$sql = "SELECT * FROM note WHERE ProjectId='$projectId' AND ApartmentNumber='$_apartNo' AND PhotoIdx='$_photoNumber'";
		$result = $db->select($sql);
		if( $result == false){
			$sql = "INSERT INTO note(ProjectId, ApartmentNumber, PhotoIdx, Notes) VALUES(?,?,?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$projectId, $_apartNo, $_photoNumber, $_strNotes]);
		} else{
			$sql = "UPDATE note SET Notes=? WHERE ProjectId='$projectId' AND ApartmentNumber='$_apartNo' AND PhotoIdx='$_photoNumber'";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_strNotes]);
		}
		echo "OK";
	}
	function getNotes($_projectName, $_apartNo, $_photoNumber){
		global $db;
		$projectInfo = getProjectInfo($_projectName);
		if( $projectInfo == false){
			return "";
		}
		$projectId = $projectInfo[0]['Id'];
		$sql = "SELECT * FROM note WHERE ProjectId='$projectId' AND ApartmentNumber='$_apartNo' AND PhotoIdx='$_photoNumber'";
		$result = $db->select($sql);
		if( $result == false){
			return "";
		}
		return $result[0]['Notes'];
	}
	function makeEncryptKey($_keyword){
		if( $_keyword == "")return "";
		$_key1 = crypt(time(), "");
		$_key2 = crypt($_keyword, "");
		$_key3 = "";//crypt(date("Ymd"), "");
		$key =  $_key1 . $_key2 . $_key3;
		$key = str_replace("$", "", $key);
		$key = str_replace(".", "", $key);
		$key = str_replace("/", "", $key);
		return $key;
	}
	function sendInviteEmail($_UserEmail){
		global $db;
		$sql = "SELECT * FROM user WHERE UserEmail='$_UserEmail'";
		$result = $db->select($sql);
		if( $result == false){
			$key = makeEncryptKey($_UserEmail);

			$sql = "INSERT INTO user(UserEmail, InviteUrl) VALUES(?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_UserEmail, $key]);
			
			$to = $_UserEmail;
			$subject = "התיעוד של הפרויקט שלך מוכן באפליקציה Watchwork!";
			$txt = "Hello, there.\n I invites ";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			// $headers = "From: webmaster@example.com";
			$message = "
			<html>
			<head>
			<title>Invitation.</title>
			</head>
			<body>
			<div dir='rtl'>
			<h4>שלום,</h4>
			<p>אנו מזמינים אותך להירשם כדי להיכנס לתיעוד של הפרויקט שלך.</p>
			<p>לחץ על הקישור הבא.</p>
			<a href='http://www.watchwork.co.il/Works-Progress/invitation.php?key=" . $key . "'><span style='font-size:24px;'>&#9754; לחץ כאן</span></a>
			</div>
			</body>
			</html>
			";

			mail($to,$subject,$message,$headers);
			echo "Sent";
		} else{
			$row = $result[0];
			if( $row['InviteUrl'] != NULL){
				echo "Invite Sent, but not accepted yet.";
			} else{
				echo "Already exists.";
			}
		}
	}
?>