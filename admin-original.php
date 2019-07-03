<?php
session_start();

if( !isset($_SESSION['reparationUserName'])){
	header("Location: login.php");
}
if( $_SESSION['reparationUserName'] == ""){
	header("Location: login.php");
}
if( strcasecmp($_SESSION['reparationUserName'], "admin") != 0){
	header("Location: main.php");
}

$dir = __DIR__ . "/container/project1/";
$files = glob($dir . "ap*");

$count = count($files);
require_once __DIR__ . "/libInformation.php";

foreach ($files as $value) {
	$dirName = basename($value);
}
?>
<head>
	<link rel="icon" type="image/png" href="assets/images/reparation_logo.png">
</head>

<link rel="stylesheet" type="text/css" href="./assets/css/main/admin.css?<?=time()?>">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<a href="logout.php"><button class="btn btn-danger" style="margin: 10px;">Log out</button></a>
<a href="main.php"><button class="btn btn-primary" style="margin: 10px;">Go to Main</button></a>
<style type="text/css">
	.mainTab table{
		width: 100%;
	}
	.mainTab table td, .mainTab table th{
		padding: 5px;
	}
	table.nonPadding td, table.nonPadding th{
		padding: 0px;
	}
</style>
<div>
	<div class="container">
		<ul class="nav nav-tabs">
			<li class="active" onclick="onTabClicked(0)"><a href="#">Project Management</a></li>
			<li class="" onclick="onTabClicked(1)"><a href="#">User Management</a></li>
		</ul>		
		<div class="forProject mainTab">
			<h3>Project Information</h3>
			<table class="table-bordered">
				<tr>
					<td class="vertical-top" colspan="3" style="padding: 10px;">
						<table class="nonBorder">
							<tr>
								<td><label for="projectName">Project Name : </label></td>
								<td><input class="form-control" type="text" id="projectName"/><br></td>
								<td><label for="projectNumber">Project N<u>o</u> : </label></td>
								<td><input class="form-control" type="text" id="projectNumber"/><br></td>
							</tr>
							<tr>
								<td><label for="projectType">Project Type:</label></td>
								<td>
									<select id="projectType" class="form-control">
										<option class="Public">מסחריים</option>
										<option class="Commercial">משרדים</option>
										<option class="Office">תמ"א 38</option>
										<option class="Residential">מגורים</option>
									</select>
								</td>
								<td><label>Constructor : </label></td>
								<td><input type="text" class="form-control" id="constructor"></td>
							</tr>
							<tr>
								<td><label>Location : </label></td>
								<td colspan="3">
									<table class="nonPadding">
										<tr>
											<td>Zone</td>
											<td>City</td>
											<td>Street</td>
											<td>No</td>
										</tr>
										<tr>
											<td><input type="text" class="form-control" id="zone"></td>
											<td><input type="text" class="form-control" id="city"></td>
											<td><input type="text" class="form-control" id="street"></td>
											<td><input type="text" class="form-control" id="no"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td><label>Project Manager : </label></td>
								<td><input type="text" class="form-control" id="projectmanager"></td>
								<td><label>Works Manager : </label></td>
								<td><input type="text" class="form-control" id="worksmanager"></td>
							</tr>
							<tr>
								<td><label>Photography : </label></td>
								<td><input type="text" class="form-control" id="photography"></td>
								<td><label>Building N<u>o</u> : </label></td>
								<td><input type="text" class="form-control" id="buildingnumber"></td>
							</tr>
							<tr>
								<td><label>Document Date : </label></td>
								<td><input type="date" class="form-control" id="documentdate" value="<?= date("Y-m-d")?>"></td>
								<td><label>Entrance N<u>o</u> : </label></td>
								<td><input type="text" class="form-control" id="entrancenumber"></td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: right;">
									<button onclick="onSaveProjectInfo()" class="btn btn-success">Save Project Info</button>
								</td>
							</tr>
						</table>
						<!-- <p style="text-align: right;"></p> -->
					</td>
					<td class="vertical-top" style="padding: 10px;">
						<span>
							<h4>
								Parts
								<table class="nonBorder nonPadding">
									<tr>
										<td><input class="form-control" type="text" name="txtCurPart"></td>
										<td><button class="btn btn-success" onclick="addNewParts()">Add New</button></td>
									</tr>
								</table>
							</h4>
							
						</span>
						<div id="proParts">
						</div>
					</td>
				</tr>
				<tr>
					<td class="vertical-top">
						<table class="preInfoTable table-bordered table-striped"><!-- Primary Datas -->
							<tr>
								<th colspan="2">Apartment</th>
								<th>Photo Count</th>
							</tr>
						<?php
						for ($i = 0; $i < $count; $i++) {
							$apartName = "ap" . ($i + 1);
							$subDir = $dir . $apartName . "/project/photos/";
							if( !file_exists($subDir))
								continue;
							$photos = scandir($subDir);
						?>
							<tr>
								<td><input type="checkbox" name="chk_aparts"></td>
								<td class="apartName"><?=$apartName?></td>
								<td class="imageCount"><?=count($photos) - 2?></td>
							</tr>
						<?php
						}
						?>
						</table>
					</td>
					<td style="padding: 10px;">
						<table class="">
							<tr>
								<td style="text-align: center;">
									<button class="btn btn-success" onclick="addApartment()">Add >></button>
								</td>
							</tr>
							<tr>
								<td style="text-align: center;">
									<button class="btn btn-danger" onclick="removeApartment()"><< Remove</button>
								</td>
							</tr>
						</table>
					</td>
					<td class="vertical-top">
						<table class="projectTable table-bordered table-striped">
							<tr>
								<th colspan="2">Apartment</th>
								<th>Photo Count</th>
							</tr>
						</table>
					</td>
					<td class="vertical-top">
						<h4>Apartment Information</h4>
						<table class="table-bordered table-striped" id="tblPartInfos">
							<tr>
								<th>N<u>o</u></th>
								<th>Part Name</th>
								<th>Image N<u>o</u></th>
							</tr>
						</table>
						<hr>
						<table>
							<tr>
								<td>
									<label>Section Count : </label>
								</td>
								<td>
									<input type="number" id="sectionCount" min="1" class="form-control" onchange="sectionCountChanged()">
								</td>
							</tr>
						</table>
						<p style="text-align: right;">
							<button class="btn btn-success" onclick="openSectionImgModal()">View Section Image</button>
						</p>
						<table class="table-bordered table-striped" id="tblSections">
							<tr>
								<th>Section N<u>o</u></th>
								<th>Image N<u>o</u></th>
							</tr>
						</table>
						<br>
						<br>
						<p style="text-align: right;">
							<button class="btn btn-success" style="vertical-align: bottom;" onclick="saveApartmentInfo()">Save Apartment Info</button>
						</p>
					</td>
				</tr>
			</table>
		</div>
		<div class="forUser mainTab" style="display: none;">
			<h3>User Information</h3>
			<table class="table-bordered table-striped" id="tblUsers">
				<tr>
					<th>N<u>o</u></th>
					<th>User Name</th>
					<th>User Email</th>
					<th>Action</th>
				</tr>
				<?php
				$allUsers = getAllUsers();
				$index = 0;
				foreach ($allUsers as $value) {
					if( $value['UserName'] != "admin"){
						$index++;
				?>
				<tr onclick="SelectedUser(this)">
					<td><?=$index?></td>
					<td class="UserName"><?=$value['UserName']?></td>
					<td class="UserEmail"><?=$value['UserEmail']?></td>
					<td>
						<button class="btn btn-danger" onclick="removeUser(this)">Remove</button>
					</td>
				</tr>
				<?php
					}
				}
				?>
			</table>
			<br>
			<button class="btn btn-success" onclick="inviteUser()">Invite User</button>
			<?php
			$allProjects = getAllProjects();
			?>
		</div>
	</div>
</div>

<div class="modal fade" role="dialog" id="sectionImgModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3>Section Image for Current Apartment</h3>
			</div>
			<div class="modal-body">
				<img src="" style="width: 100%;">
			</div>
		</div>

	</div>
</div>

<script type="text/javascript" src="assets/js/jquery-1.9.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/main/admin.js?<?=time()?>"></script>
<script type="text/javascript">

	function SelectedUser(_this){
		$("#tblUsers tr").removeClass("selectedTr");
		$(_this).addClass("selectedTr");
	}
	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	}
	function inviteUser(){
		var UserEmail = prompt("Please enter user email that you want to invite.", "");
		if (UserEmail != null) {
			if( !validateEmail(UserEmail)){
				alert("Invalid Email format.");
				return;
			}
			$.post("api_process.php", {action: "sendInviteEmail", UserEmail: UserEmail}, function(data){
				alert(data);
				var strHtml = "";
				strHtml += '<tr>';
					strHtml += '<td>' + $("#tblUsers tr").length + '</td>';
					strHtml += '<td class="UserName"></td>';
					strHtml += '<td class="UserEmail">' + UserEmail + '</td>';
					strHtml += '<td>';
						strHtml += '<button class="btn btn-danger" onclick="removeUser(this)">Remove</button>';
					strHtml += '</td>';
				strHtml += '</tr>';
				$("#tblUsers").append(strHtml);
			});
		}
	}
	function removeUser(_this){
		console.log(_this);
		var r = confirm("Are you sure remove selected User?");
		if( r == true){
			var UserEmail = $(_this).parent().parent().find(".UserEmail").text();
			$.post("api_process.php", {action:"removeUser", UserEmail: UserEmail}, function(data){
				console.log(data);
				if( data == "OK"){
					$(_this).parent().parent().remove();
				} else{
					alert("Failed to remove.");
				}
			});
		}
	}
</script>