
function myFunction() {
    location.reload();
}
function countClicked(_i){
	var curInfo = uploadedInfos[_i];
	console.log(curInfo);
	var strHtml = "";
	for( var i = 0; i < curInfo.arrNodes.length; i++){
		var curNode = curInfo.arrNodes[i];
		strHtml += '<div style="padding: 5px; border-bottom:1px solid #333333; margin-bottom:10px;" id="imgId' + curNode.Id + '">';
			strHtml += '<table style="width:100%;">';
				strHtml += '<tr>';
					strHtml += '<td class="photocontainer-info">';
						strHtml += '<table>';
							strHtml += '<tr>';
								strHtml += '<td colspan="3">';
								    strHtml += '<div class="photocontainer-title">תעריך צילום</div>';
								    strHtml += '<div class="photocontainer-text">' + curNode.info.ShootingDate + '</div>';
									strHtml += '<div class="photocontainer-title" style="display: block;">נושא</div>';
									strHtml += '<span class="photocontainer-text">' + curNode.info.Desc + '</span>';
									strHtml += '<div class="photocontainer-title">תיאור</div>';
									strHtml += '<textarea  class="photocontainer-description" style="height: 80px; text-align: right;" placeholder="Description" readonly>' + curNode.info.Description + '</textarea>';
								strHtml += '</td>';
							strHtml += '</tr>';
						strHtml += '</table>';
					strHtml += '</td>';
					strHtml += '<td style="width: 10%; vertical-align: text-bottom;">';
						strHtml += '<div><a target="_blank" href="' + curNode.fileUrl + '"><i class="fas fa-search-plus"></i></a></div>';
						// strHtml += '<div><a href="#" onclick="editDetails(this)"><i class="fas fa-pencil-alt"></i></a></div>';
						strHtml += '<div><a href="#" onclick="removeImage(' + curNode.Id + ')"><i class="fas fa-trash-alt"></i></a></div>';
					strHtml += '</td>';
					strHtml += '<td style="width:50%; vertical-align:top;">';
						strHtml += '<table>';
							strHtml += '<tr>';
								strHtml += '<td><img src="' + curNode.fileUrl + '" style="width:100%;"></td>';
							strHtml += '</tr>';
							strHtml += '<tr>';
								strHtml += '<td class="photocontainer-photo-info">';                                  
									strHtml += '<div>';
								strHtml += '</td>';
							strHtml += '</tr>';
						strHtml += '</table>';
					strHtml += '</td>';
				strHtml += '</tr>';
			strHtml += '</table>';
		strHtml += '</div>';
	}
	$("#listPhotoModal .modal-body").html(strHtml);
	$('#listPhotoModal').modal('toggle');
}
function uploadedPhotoDraw(){
	var _id = prevId;
	var _cat = prevCat;
	$(".uploadedImgPan").html("");
	$(".imgUpload img").attr("src", "");

	var el = $('#img_picker');
	el.val("");
	$(".groupContainer").remove();
	$.post("api_process.php", {action: "getUploadedInfo", projectName: "project1", apartNo: apartNo, idxPhoto: _id, catPhoto: _cat}, function(data){
		var infos = JSON.parse(data);
		uploadedInfos = infos;
		var strHtml = "";
		var pW = $(".imgBorder").width();
		var pH = $(".imgBorder").height();
		$("input[name=Year]").val("");
		$("input[name=Month]").val("");
		$("input[name=Day]").val("");
		for( var i = 0; i < infos.length; i++){
			var curInfo = infos[i];
			var xScale = pW / curInfo.posRect.parentWidth;
			var yScale = pH / curInfo.posRect.parentHeight;
			var left = parseInt(xScale * curInfo.posRect.left);
			var width = parseInt(xScale * curInfo.posRect.width);
			var top = parseInt(yScale * curInfo.posRect.top);
			var height = parseInt(yScale * curInfo.posRect.height);
			var arrNodes = curInfo.arrNodes;
			var ptCount = arrNodes.length;
			var lastImgPath = arrNodes[ ptCount - 1].fileSUrl;

			strHtml += '<div class="groupContainer" style="top:' + top + 'px;left:' + left + 'px; position: absolute;" groupId="' + curInfo.groupId + '">';
				strHtml += '<img src="' + lastImgPath + '" style="width:' + width + 'px;">';
				strHtml += '<div class="photoCount" onclick="countClicked(' + i + ')">' + ptCount + '</div>';
			strHtml += '</div>';
		}
		$(".imgBorder").append(strHtml);
		$(".groupContainer").droppable({
			accept: ".uploadedImgPan",
			classes: {
				"ui-droppable-active": "ui-state-highlight"
			},
			drop: function( event, ui ) {
				droppedUi(event, ui);
			},
			out: function( event, ui){
				outUi(event, ui);
			}
		});
	});
}
function popup(_id, _cat){
	var imgPath = "";
	if( _cat == "photo"){
		imgPath = "container/project1/ap" + apartNo + "/project/photos/" + _id + "pi.jpg";
	} else{
		imgPath = "container/project1/ap" + apartNo + "/project/plans/" + _id + "pl.jpg";
	}
	$(".imgBorder img").eq(0).attr("src",imgPath);

	if( $(".uploadImgWnd").is(":visible")){
		if( prevId == _id && prevCat == _cat){
			$(".uploadImgWnd").hide();
		}
	} else{
		$(".uploadImgWnd").show();
	}
	prevId = _id;
	prevCat = _cat;
	uploadedPhotoDraw();
}
function droppedUi(event, ui){									 
	var target = event.target;
	var groupId = target.getAttribute("groupid");
	$(".uploadedImg").attr("groupId", groupId);
}
function outUi(event, ui){				 
	var curEle = event.toElement;
	curEle.setAttribute("groupId", "");
}
function showImage(src, target){
	var fr = new FileReader();
	fr.onload = function(e) {
		// debugger;
		// console.log(e);
		// console.log(this);
		const img = new Image();
		img.src = this.result;
		img.onload = function(){
			if( e.total >= 350000 || img.width > 1600 || img.height > 1600){
				const elem = document.createElement('canvas');
				var longEdge = img.width >= img.height ? img.width : img.height;
				var fRate = longEdge / 1600;
				elem.width = img.width / fRate;
				elem.height = img.height / fRate;
				const ctx = elem.getContext('2d');
				ctx.drawImage(img, 0, 0, elem.width, elem.height);
				// var fileName = src.files[0].name;
				// var strFileType = fileName.split(".").pop();
				var strFileType = base64MimeType(img.src).split("/").pop();
				console.log(strFileType);
				var imgReduced = elem.toDataURL("image/" + strFileType, 0.7);
				var strHtml = "";
					strHtml += '<img class="uploadedImg" style="width:40px; height:40px;" src="' + imgReduced + '">';
				$(".uploadedImgPan").html(strHtml);
				$(".uploadedImgPan").css({"top":"10px", "left":"10px"});
				$(".uploadedImgPan").draggable();
				target.src = imgReduced;
			} else{
				target.src = img.src;
				var strHtml = "";
				if( fullWidth < 769 ){		  
					strHtml += '<img class="uploadedImg" style="width:40px; height:40px;" src="' + target.src + '">';
		        } else{
					strHtml += '<img class="uploadedImg" style="width:60px; height:60px;" src="' + target.src + '">';
				}	
				$(".uploadedImgPan").html(strHtml);
				$(".uploadedImgPan").css({"top":"10px", "left":"10px"});
				$(".uploadedImgPan").draggable();
			}
		}
	};
	src.addEventListener("change", function(){
		var lastModifiedDate = src.files[0].lastModifiedDate;
		var year = lastModifiedDate.getFullYear();
		var month = lastModifiedDate.getMonth() + 1;
		var day = lastModifiedDate.getDate();
		$("input[name=Year]").val(year);
		$("input[name=Month]").val(month);
		$("input[name=Day]").val(day);
		fr.readAsDataURL(src.files[0]);
	});
}
var src = document.getElementById("img_picker");
var target = document.getElementById("img_drawer");
showImage(src, target);
function updateArrNotes(){
	$(".textBox").hide();
	$.post("api_process.php", {action: "getArrNotes", projectPath: "project1", apartNo: apartNo}, function(response){
		// console.log(response);
		var arrNotes = JSON.parse(response);
		var strHtml = "";
		for( var i = 0; i < arrNotes.length; i++){
			var curNote = arrNotes[i];
			strHtml += '<a class="fright photoBtn btn" href="#No' + curNote.PhotoIdx + '">' + curNote.PhotoIdx + '</a>';
		}
		$("#arrNotes").html(strHtml);
	});
}
function updateArrDefect_Reparation(){
	$.post("api_process.php", {action: "getArrDefects", projectPath: "project1", apartNo: apartNo}, function(response){
		// console.log(response);
		var arrNodes = JSON.parse(response);
		var strHtml = "";
		for( var i = 0; i < arrNodes.length; i++){
			var curNode = arrNodes[i];
			strHtml += '<a class="fright photoBtn btn" href="#No' + curNode.PhotoIdx + '">' + curNode.PhotoIdx + '</a>';
		}
		$("#arrDefects").html(strHtml);
	});
	$.post("api_process.php", {action: "getArrReparation", projectPath: "project1", apartNo: apartNo}, function(response){						   
		var arrNodes = JSON.parse(response);
		var strHtml = "";
		for( var i = 0; i < arrNodes.length; i++){
			var curNode = arrNodes[i];
			strHtml += '<a class="fright photoBtn btn" href="#No' + curNode.PhotoIdx + '">' + curNode.PhotoIdx + '</a>';
		}
		$("#arrReparation").html(strHtml);
	});
}
function base64MimeType(encoded) {
  var result = null;

  if (typeof encoded !== 'string') {
    return result;
  }

  var mime = encoded.match(/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).*,.*/);

  if (mime && mime.length) {
    result = mime[1];
  }

  return result;
}
function SaveImage(){															 
	// var fileName = src.files[0].name;
	// var strFileType = fileName.split(".").pop();
	var curCtrl = $(".uploadedImgPan .uploadedImg");
	var srcImg = curCtrl.attr("src");
	var strFileType = base64MimeType(srcImg).split("/").pop();
	var idxGroup = -1;
	if( curCtrl.attr("groupId")){
		idxGroup = parseInt(curCtrl.attr("groupId"));
	}
	var pW = $(".imgBorder").width();
	var pH = $(".imgBorder").height();
	var w = $(".uploadedImgPan .uploadedImg").width();
	var h = $(".uploadedImgPan .uploadedImg").height();
	var pos = $(".uploadedImgPan").position();
	var posRect = {left: pos.left, top: pos.top, width: w, height: h, parentWidth: pW, parentHeight: pH};
	var year = $("input[name=Year]").val();
	var month = $("input[name=Month]").val();
	var day = $("input[name=Day]").val();
	var ShootingDate = year + '-' + month + '-' + day;
	var ShootingTime = $("#ShootingTime").val();
	var ShootingPerson = $("#ShootingPerson").val();
	var Frequency = $("#frequency").val();
	var Origin = $("#origin").val();
	var Structure = $("#structure").val();
	var Level = $("#level").val();
	var Type = "Defect";
	if( $("#btnTypeGroup div").eq(0).hasClass("popupBtn"))
		Type = "Reparation";
	var Desc = $("input[name=desc]").val();
	var Description = $("textarea[name=description]").val();
	var Worker = $("input[name=worker]").val();
	var Contractor = $("input[name=contractor]").val();

	var infos = {ShootingDate: ShootingDate, ShootingTime: ShootingTime, ShootingPerson: ShootingPerson, Frequency: Frequency, Origin: Origin, Structure: Structure, Level: Level, Desc: Desc, Description: Description, Worker: Worker, Contractor: Contractor};
	$("#loadingImg").show();
	$.post("api_process.php", {action: "imgUpload", projectName: "project1", apartNo: apartNo, idxPhoto: prevId, catPhoto: prevCat, idxGroup: idxGroup, Type: Type, strFileType: strFileType, imgSrc: srcImg, posRect: JSON.stringify(posRect), infos: JSON.stringify(infos)}, function(data){
		console.log(data);
		var response = JSON.parse(data);
		if( response.message == "OK"){
			uploadedPhotoDraw();
			updateArrDefect_Reparation();
			$("#loadingImg").hide();
		}
	})
}
	if( fullWidth < 769){
		$("#centered ul").width(181 * aptCount);
		$("a").eq(0).css("position", "relative");
		$("a").eq(0).find("button").addClass("mobile_logout").removeClass("btn-danger").removeClass("btn");
		$("#mainHeader").hide();
		$("#mobileHeader").show();
	}
	$(".forReparations").hide();
	function onNoteSave(_this){
		var textId = $(_this).parent().find("textarea").attr("Id");
		var photoNumber = textId.replace("text", "");
		var strNotes = $("#" + textId).val();
		$.post("api_process.php", {action: "updateNotes", projectName: "project1", apartNo: apartNo, photoNumber: photoNumber, strNotes: strNotes}, function(data){
			if( data == "OK"){
				alert("Saved.");
				updateArrNotes();
			}
		});
	}
	function showNotes(_this){
		var textBox = $(_this).parent().parent().parent().find(".textBox").eq(0);
		if( textBox.is(':visible')){
			textBox.hide();
		} else{
			var textId = $(_this).parent().parent().parent().find("textarea").attr("Id");
			var photoNumber = textId.replace("text", "");
			$.post("api_process.php", {action: "getNotes", projectName: "project1", apartNo: apartNo, photoNumber: photoNumber}, function(data){
				$("#" + textId).val(data);
				textBox.show();
			});
		}
	}
	function closeNote(_this){
		$(_this).parent().parent().hide();
	}
	function onReparation(_this){
		$(_this).parent().find("div").removeClass("popupBtn");
		$(_this).addClass("popupBtn");
		$(".forDefects").hide();
		$(".forReparations").show();
		var sels = $(".forReparations").parent();
		for( var i = 0; i < sels.length; i++){
			sels.eq(0).find("option.forReparations").eq(0).prop("selected", true);
		}
		$("#desc").html("תיקון");//Reparation
	}
	function onDefect(_this){
		$(_this).parent().find("div").removeClass("popupBtn");
		$(_this).addClass("popupBtn");
		$(".forReparations").hide();
		$(".forDefects").show();
		var sels = $(".forDefects").parent();
		for( var i = 0; i < sels.length; i++){
			sels.eq(0).find("option.forDefects").eq(0).prop("selected", true);
		}
		$("#desc").html("פגם");//Defect
	}
	function closeUploadImgWnd(){
		$(".uploadImgWnd").hide();
	}
	function editDetails(_this){
		console.log(_this);
	}
	function removeImage(_idx){
		var r = confirm("Are you sure remove this?");
		if( r == true){
			$.post("api_process.php", {action: "removeUploadedImg", idx : _idx}, function(data){
				if( data == "OK"){
					$("#imgId" + _idx).remove();
					uploadedPhotoDraw();
					updateArrDefect_Reparation();
				}
			});
		}
	}
	var nTop = $("#wraperr").offset().top;
	console.log(nTop);
	function setNavTop() {
		$('#wraperr').css('position','');
		var nScrollTop = $("#project-container").scrollTop();
		var nRealTop = Math.max(0, nTop - nScrollTop);
		if( nRealTop == 0){
			$('#wraperr').css('position','absolute');
		}
		$('#wraperr').css('z-index', 1000);
		$('#wraperr').css('top', nRealTop);
		$("#wraperr").css('width',$("#wraperr").parent().width());
	}
	$("#project-container").scroll(function(){
		setNavTop();
	});
	var prevScrollTop = 0;
	function setNavTop4Mobile(){
		var nScrollTop = $(document).scrollTop();
		if( nScrollTop >= nTop){
			$('#wraperr').css('position','fixed');
			if( prevScrollTop < nScrollTop){
				$('#wraperr').css('top', '20px');
			} else{
				$('#wraperr').css('top', '0px');
			}
		} else{
			$('#wraperr').css('position','');
			$('#wraperr').css('top', nTop);
		}
		prevScrollTop = nScrollTop;
		$('#wraperr').css('z-index', 1000);
		$("#wraperr").css('width',$("#wraperr").parent().width());
	}
	$(window).scroll(function(e){
		setNavTop4Mobile();
	});
