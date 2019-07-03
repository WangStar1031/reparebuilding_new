var arrParts = [];
var projectInfo = {};
var arrApartmentInfo = [];
var arrAllParts = [];

function clickedApart(_this){
	$(".mainContents").removeClass("selectedTr");
	$(_this).addClass("selectedTr");
}
function insertNewApartments(_curTr){
	var strHtml = "";
	var aptName = _curTr.find(".apartName").eq(0).text();
	var aptPicCount = _curTr.find(".imageCount").eq(0).text();
	strHtml += '<tr class="mainContents" onclick="clickedApart(this)">';
		strHtml += '<td><input type="checkbox" name="chk_aparts"></td>';
		strHtml += '<td class="apartDir">' + aptName + '</td>';
		strHtml += '<td class="imgCount">' + aptPicCount + '</td>';
	strHtml += '</tr>';
	$(".projectTable").append(strHtml);
	var aptInfo = {};
	aptInfo.ApartmentName = aptName;
	aptInfo.PictureCount = aptPicCount;
	aptInfo.PartInfos = "";
	aptInfo.SectionInfos = "";
	aptInfo.SectionCount = 0;
	arrApartmentInfo.push(aptInfo);
}
function insertNewApartmentsFromArray(){
	var strHtml = "";
	for( var i = 0; i < arrApartmentInfo.length; i++){
		var _aptName = arrApartmentInfo[i].ApartmentName;
		var _aptPicCount = arrApartmentInfo[i].PictureCount;
		strHtml += '<tr class="mainContents" onclick="clickedApart(this)">';
			strHtml += '<td><input type="checkbox" name="chk_aparts"></td>';
			strHtml += '<td class="apartDir">' + _aptName + '</td>';
			strHtml += '<td class="imgCount">' + _aptPicCount + '</td>';
		strHtml += '</tr>';
	}
	$(".projectTable").append(strHtml);
}
function checkInRight(_aptName){
	var arrRightTableTrs = $(".projectTable tr.mainContents");
	for( var i = 0; i < arrRightTableTrs.length; i++){
		var curTr = arrRightTableTrs.eq(i);
		var aptName = curTr.find("td.apartDir").eq(0).text();
		if( aptName == _aptName)
			return true;
	}
	return false;
}
function removeApartment(){
	var arrChks = $(".projectTable input[type=checkbox]");
	var arrCheckedApartments = [];
	for( var i = 0; i < arrChks.length; i++){
		var curChk = arrChks.eq(i);
		if( curChk.prop("checked")){
			var remApartName = curChk.parent().parent().find(".apartDir").text();
			// console.log(remApartName);
			var idx = arrApartmentInfo.indexOf(remApartName);
			arrApartmentInfo.splice( idx, 1);
			curChk.parent().parent().remove();
		}
	}
}
function addApartment(){
	var arrChks = $(".preInfoTable input[type=checkbox]");
	var arrCheckedApartments = [];
	for( var i = 0; i < arrChks.length; i++){
		var curChk = arrChks.eq(i);
		if( curChk.prop("checked")){
			arrCheckedApartments.push(curChk.parent().parent());
		}
	}
	if( arrCheckedApartments.length == 0){
		alert("No selected.");
		return;
	}
	for( var i = 0; i < arrCheckedApartments.length; i++){
		var curTr = arrCheckedApartments[i];
		var aptName = curTr.find(".apartName").eq(0).text();
		var ptCount = curTr.find(".imageCount").eq(0).text();
		if( checkInRight(aptName) || ptCount == 0){
			continue;
		}
		insertNewApartments(curTr);
	}
}
function addPartsToAPT(_partName){

}
function removePartClicked(_this){
	var partDiv = $(_this).parent();
	var partName = partDiv.find(".partName").text();
	var r = confirm("Are you sure remove current part(" + partName + ")?");
	if( r == true){
		partDiv.remove();
		var idx = arrParts.indexOf(partName);
		arrParts.splice( idx, 1);
	}
}
function makeParts() {
	var strHtml = "";
	for( var i = 0; i < arrParts.length; i++){
		if( i % 3 == 0 && i != 0){
			strHtml += "<br>";
		}
		strHtml += "<div class='partDiv'><span class='partName'>" + arrParts[i] + "</span><span class='removePart' onclick='removePartClicked(this)'>&times;</span></div>";
	}
	$("#proParts").html(strHtml);
}
function addNewParts(){
	var curPart = $("input[name=txtCurPart]").val();
	if( !curPart ){
		alert("No Part Name.");
	} else{
		if( arrParts.indexOf(curPart) == -1){
			$("input[name=txtCurPart]").val("");
			arrParts.push(curPart.trim());
			makeParts();
		} else{
			alert("Already Exists.");
			$("input[name=txtCurPart]").val("");
		}
	}
	$("input[name=txtCurPart]").focus();
}
// function onSaveProjectInfo(){
// 	projectInfo.name = $("#projectName").val();
// 	projectInfo.parts = arrParts;
// 	projectInfo.apartmentInfo = arrApartmentInfo;
// 	$.post("api_process.php", {action:"saveProjectInfo", data: JSON.stringify(projectInfo)}, function(data){
// 		if( data == "OK"){
// 			alert("Successfully saved.");
// 		} else{
// 			alert("No Save.");
// 		}
// 	});
// }

function onTabClicked(_idTab){
	$("ul.nav-tabs li").removeClass("active");
	$("ul.nav-tabs li").eq(_idTab).addClass("active");
	$('.mainTab').hide();
	$('.mainTab').eq(_idTab).show();
}

function getProjectInfo(){
	$.post("api_process.php", {action: "getProjectInfo", projectPath: "project1"}, function(data){
		if( data != "false"){
			var projectInfo = JSON.parse(data)[0];
			console.log(projectInfo);
			$("#projectName").val(projectInfo.ProjectName);
			$("#projectNumber").val(projectInfo.ProjectNumber);
			var arrOptions = $("#projectType option");
			for( var i = 0; i < arrOptions.length; i++){
				var curOption = arrOptions.eq(i);
				if( curOption.val() == projectInfo.ProjectType){
					curOption.prop("selected", true);
					break;
				}
			}
			// $("#projectType ." + projectInfo.ProjectType).prop("selected", true);
			$("#zone").val(projectInfo.Zone);
			$("#city").val(projectInfo.City);
			$("#street").val(projectInfo.Street);
			$("#no").val(projectInfo.No);
			$("#constructor").val(projectInfo.Constructor);
			$("#projectmanager").val(projectInfo.ProjectManager);
			$("#worksmanager").val(projectInfo.WorksManager);
			$("#photography").val(projectInfo.Photography);
			$("#buildingnumber").val(projectInfo.BuildingNumber);
			$("#entrancenumber").val(projectInfo.EntranceNumber);
			$("#documentdate").val(projectInfo.DocumentDate);										
			var projectId = projectInfo.Id;
			$.post("api_process.php", {action: "getParts", projectId: projectId}, function(data){
				if( data != "false"){
					var arrPartInfos = JSON.parse(data);
					arrAllParts = arrPartInfos;
					console.log(arrPartInfos);
					for( var i = 0; i < arrPartInfos.length; i++){
						arrParts.push(arrPartInfos[i].PartName);
					}
					makeParts();
				}
			});
			$.post("api_process.php", {action: "getApartments", projectId: projectId}, function(data){
				if( data!= "false"){
					arrApartmentInfo = JSON.parse(data);
					console.log(arrApartmentInfo);
					insertNewApartmentsFromArray();
				}
			});
		}
	});
}
getProjectInfo();

function onSaveProjectInfo(){
	var projectInfo = {};
	projectInfo.ProjectPath = "project1";
	projectInfo.ProjectName = $("#projectName").val();
	projectInfo.ProjectNumber = $("#projectNumber").val();
	projectInfo.Zone = $("#zone").val();
	projectInfo.City = $("#city").val();
	projectInfo.Street = $("#street").val();
	projectInfo.No = $("#no").val();
	projectInfo.Constructor = $("#constructor").val();
	projectInfo.ProjectManager = $("#projectmanager").val();
	projectInfo.WorksManager = $("#worksmanager").val();
	projectInfo.Photography = $("#photography").val();
	projectInfo.ProjectType = $("#projectType option:selected").val();
	projectInfo.DocumentDate = $("#documentdate").val();
	projectInfo.BuildingNumber = $("#buildingnumber").val();
	projectInfo.EntranceNumber = $("#entrancenumber").val();
	projectInfo.arrParts = arrParts;
	projectInfo.arrApartmentInfo = arrApartmentInfo;
	$.post("api_process.php", {action: "saveProjectInfo", projectInfo: JSON.stringify(projectInfo)}, function(data){
		if( data == "OK"){
			alert("Successfully saved.");
		} else{
			alert("Can't saved.");
		}
	});
}

function openSectionImgModal(){
	$('#sectionImgModal').modal('toggle');
}
function drawParts(_partInfos){
	$("#tblPartInfos tr").filter(function(_index){
		return _index;
	}).remove();
	var arrInfos = [];
	if( _partInfos){
		arrInfos = _partInfos.split(",");
	}
	for( var i = 0; i < arrAllParts.length; i++){
		var partName = arrAllParts[i].PartName;
		var partId = arrAllParts[i].Id;
		var strHtml = "";
		strHtml += '<tr>';
			strHtml += '<td>' + (i + 1) + '</td>';
			strHtml += '<td class="PartName">' + partName + '</td>';
			strHtml += '<td><input type="number" class="form-control ImageNumber"';
			// for( var j = 0; j < arrInfos.length; j++){
				var curPart = arrInfos[i];
				if( curPart >= 0){
					strHtml += ' value="' + curPart + '"';
				}
			// }
			strHtml += '></td>';
		strHtml += '</tr>';
		$("#tblPartInfos").append(strHtml);
	}
}
function drawSections(_sectionInfos){
	$("#tblSections tr").filter(function(_index){
		return _index;
	}).remove();
	var arrInfos = [];
	if( _sectionInfos){
		arrInfos = _sectionInfos.split(",");
	}
	for( var i = 0; i < arrInfos.length; i++){
		var imgNumber = arrInfos[i];
		var strHtml = "";
		strHtml += '<tr>';
			strHtml += '<td>' + (i + 1) + '</td>';
			strHtml += '<td><input type="number" class="form-control ImgNumber"';
			if( imgNumber >= 0){
				strHtml += ' value="' + imgNumber + '"';
			}
			strHtml += '></td>';
		strHtml += '</tr>';
		$("#tblSections").append(strHtml);
	}
}
function clickedApart(_this){
	$(".mainContents").removeClass("selectedTr");
	$(_this).addClass("selectedTr");
	$("#sectionCount").val("");
	sectionCountChanged();
	var aptName = $(_this).find(".apartDir").text();
	var imgSection = "container/project1/sectiuni/sectiuni-" + aptName + ".jpg";
	$("#sectionImgModal .modal-body img").attr("src", imgSection);
	for( var i = 0; i < arrApartmentInfo.length; i++){
		if( arrApartmentInfo[i].ApartmentName == aptName){
			if( arrApartmentInfo[i].SectionCount != 0){
				$("#sectionCount").val(arrApartmentInfo[i].SectionCount);
			}
			drawParts(arrApartmentInfo[i].PartInfos);
			drawSections(arrApartmentInfo[i].SectionInfos);
			break;
		}
	}
}
function sectionCountChanged(){
	if( $(".mainContents.selectedTr").length == 0) return;
	$("#tblSections tr").filter(function(_index){
		return _index != 0;
	}).remove();
	var nCount = $("#sectionCount").val();
	var strHtml = "";
	for(var i = 0; i < nCount; i++){
		strHtml += '<tr>';
			strHtml += '<td class="sectionNumber">' + (i + 1) + '</td>';
			strHtml += '<td><input type="number" min="1" class="ImgNumber"></td>';
		strHtml += '</tr>';
	}
	$("#tblSections").append(strHtml);
}
function saveApartmentInfo(){
	if( $(".mainContents.selectedTr").length == 0) return;
	var strAptName = $(".mainContents.selectedTr .apartDir").text();
	for( var i = 0; i < arrApartmentInfo.length; i ++){
		if( arrApartmentInfo[i].ApartmentName == strAptName){
			var arrInputs = $("#tblPartInfos td input.ImageNumber");
			var arrNumbers = [];
			for( var j = 0; j < arrInputs.length; j++){
				var curInput = arrInputs.eq(j);
				var val = curInput.val();
				if( val == "" ) arrNumbers.push(-1);
				else arrNumbers.push(parseInt(val));
			}
			arrApartmentInfo[i].PartInfos = arrNumbers.join(",");
			var strSectionCount = $("#sectionCount").val();
			if( strSectionCount == "")strSectionCount = "0";
			arrApartmentInfo[i].SectionCount = parseInt(strSectionCount);
			var arrSecInputs = $("#tblSections td input.ImgNumber");
			var arrSecNumbers = [];
			for( var j = 0; j < arrSecInputs.length; j++){
				var curInput = arrSecInputs.eq(j);
				var val = curInput.val();
				if( val == "") arrSecNumbers.push(-1);
				else arrSecNumbers.push(parseInt(val));
			}
			arrApartmentInfo[i].SectionInfos = arrSecNumbers.join(",");
			break;
		}
	}
}