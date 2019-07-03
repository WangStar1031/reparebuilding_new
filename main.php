<?php
session_start();



?>
<!doctype html>
<html class="">

<head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-121704781-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-121704781-1');
</script>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Photo Documentation</title>
	<link rel="icon" type="image/png" href="assets/images/ww_logo.jpg">

	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="assets/css/master.css">
	<link rel="stylesheet" type="text/css" href="assets/css/mastertag.css">
	<link rel="stylesheet" type="text/css" href="assets/css/masterclass.css">
	<link rel="stylesheet" type="text/css" href="assets/css/project.css">
	<link rel="stylesheet" type="text/css" href="assets/css/dropdawn.css">
	<link rel="stylesheet" type="text/css" href="assets/css/popup.css">
	<link rel="stylesheet" type="text/css" href="assets/css/receptionnotes.css">

	<link rel="stylesheet" href="assets/css/topnav/normalize.css">
	<link rel="stylesheet" href="assets/css/topnav/ospb.css">
	<link rel="stylesheet" href="assets/css/topnav/horizontal.css">
	<link rel="stylesheet" href="assets/css/topnav/displayornot.css">
	<link rel="stylesheet" href="assets/css/topnav/content.css">
	<link rel="stylesheet" href="assets/css/topnav/toptable.css">
	<link rel="stylesheet" href="assets/css/sidebar/toptable.css">
	<link rel="stylesheet" href="assets/css/sidebar/content.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="assets/css/main/main.css?<?=time()?>">

	
	<script src="assets/js/respond.min.js"></script>
	<script src="assets/js/topnav/modernizr.js"></script>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery-ui.js"></script>
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="assets/js/topnav/plugins.js"></script>
	<script src="assets/js/topnav/sly.min.js"></script>

	<script src="assets/SpryAssets/SpryEffects.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

	<script type="text/javascript">
		function MM_effectAppearFade(targetElement, duration, from, to, toggle)
		{
			Spry.Effect.DoFade(targetElement, {duration: duration, from: from, to: to, toggle: toggle});
		}

		function MM_showHideLayers() {
		  var i,p,v,obj,args=MM_showHideLayers.arguments;
		  for (i=0; i<(args.length-2); i+=3) 
		  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
		    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
		    obj.visibility=v; }
		}
		// add a class of 'loading' to the HTML, then remove it once the page has finished loading
		(function(c){
			c('scripted loading')
			window.onload = function(){setTimeout(function(){
				c(c().replace('loading',''))
			},30)}

		}(function(c){
			var h = document.lastChild
			return c ? h.className = c : h.className
		}))
	</script>

	<script type="text/javascript">
		function toggle_visibility(id) {
			var e = document.getElementById(id);
			if(e.style.display == 'block')
				e.style.display = 'none';
			else
				e.style.display = 'block';
		}
	</script>
	<style type="text/css">
		.over-white. .over-black{
			box-sizing: content-box!important;
		}
	</style>
</head>
<body>
<?php
$apartNo = 0;
require_once __DIR__ . "/libInformation.php";
if( isset($_GET['apartNo'])){
	$apartNo = $_GET['apartNo'];
}
$path = dirname($_SERVER['REQUEST_URI']) . "/";
$project = getProjectInfo("project1");
if( $project == false){
	echo "No project information.";
	exit();
}
$projectInfo = $project[0];
$documentDate = $projectInfo['DocumentDate'];
$documentMonth = date("m", strtotime($documentDate));
$documentYear = date("Y", strtotime($documentDate));
$parts4Project = getParts($projectInfo['Id']);
$apartmentInfo = getApratmentInfo($projectInfo['Id']);

if( $apartNo == 0){
	$apartNo = str_replace("ap", "", $apartmentInfo[0]['ApartmentName']);
}
$_apartIndex = 0;
foreach ($apartmentInfo as $value) {
	$_apartIndex++;
	if( $value['ApartmentName'] == "ap" . $apartNo){
		$curApartment = $value;// $apartmentInfo[$apartNo - 1];
		break;;
	}
}
$_dirID = $curApartment['ApartmentName'];
$arrPartInfos = explode(",", $curApartment['PartInfos']);
$sectionCount = $curApartment['SectionCount'];
$arrSectionInfos = explode(",", $curApartment['SectionInfos']);
?>
<!-- <a href="logout.php" class="lgout-bt"><button class="btn btn-danger" style="margin: 0;">Log out</button></a> -->	

<div id="main-container">
   <div id="comercial-container" class="display-comercial">
         <div id="comercial-content">
<!--		 
            <div dir="rtl" class="defrepnot-table-title">הטבלה המציגה את המודולים שבהם הועלו תמונות והערות.</div>	
 DEFECTE, REPARATII, NOTE   			
	        <div id="quicklink-container">		
		        <div id="reception-notes">
		            <div class="col-md-12 col-xs-12" id="quick-link">
		            	<div class="row" style="color: black;">
		            		<div class="col-md-6 col-xs-4">
		            			<div class="row" style="padding: 2px;">
			            			<div class="ql-tab-header" style="border-bottom: 2px solid #fda400;">מודולים עם הערות</div>
			            			<div class="col-md-12 col-mxs12" style="border: 1px solid #cccccc; padding-right: 12%; height: 1000px;">
			            				<div class="row" id="arrNotes">
				            				<?php
				            				$arrNotes = getAllNotes($projectInfo['Id'], $apartNo);
				            				foreach ($arrNotes as $value) {
				            					$idx = $value['PhotoIdx'];
			            					?>
			            					<a class="fright photoBtn btn" href="#No<?=$idx?>"><?=$idx?></a>
			            					<?php
				            				}
				            				?>
			            				</div>
			            			</div>
		            			</div>
		            		</div>
		            		<div class="col-md-6 col-xs-4">
		            			<div class="row" style="padding: 2px;">
			            			<div class="ql-tab-header" style="border-bottom: 2px solid #fd0000;">מודולים עם תמונות</div>
			            			<div class="col-md-12 col-mxs12" style="border: 1px solid #cccccc; padding-right: 12%; height: 1000px;">
			            				<div class="row" id="arrDefects">
											<?php
											$arrDefects = getAllDefects( $projectInfo['Id'], $apartNo);
											foreach ($arrDefects as $value) {
												$idx = $value['PhotoIdx'];
											?>
											<a class="fright photoBtn btn" href="#No<?=$idx?>"><?=$idx?></a>
											<?php
											}
											?>
										</div>
			            			</div>
		            			</div>
		            		</div>
		            	</div>
		            </div>
			        
<?php
$files = [];
$dir = __DIR__ . "/container/project1/" . $_dirID . "/project/plans/";
if( file_exists($dir)){
	$files = scandir($dir);
}
$i = 0;
foreach($files as $file){
	break;
	if( $file == ".." || $file == ".")
		continue;
	$i++;
?>
<div class="rec-number"><a class="plan-popup" href="#No<?=$i?>"><?=$i?><span><img src="container/project1/<?=$_dirID?>/project/plans/<?=$i?>pl.jpg" /></span></a></div>
<?php
}


?>
		        </div>   
		    </div>
-->			
		</div>
    </div>
<div id="project-container">
<div id="top"></div>
<style type="text/css">
	#sub-header table table label{
		background-color: #d4d4d4;
	}
	#mainHeader table table tr{
		border: 1px solid #848484;
	}	
	#mobileHeader table table tr{
		border: 0px solid
    }
	#mobileHeader > div > table > tbody > tr > td, #mainHeader > div > table > tbody > tr > td{
		display: table-cell;
		vertical-align: top;
		height: 100%;
	}
	#mobileHeader > div > table td table, #mainHeader > div > table td table{
		height: 100%;
	}
	#mobileHeader > div > table td table label, #mainHeader > div > table td table label{
		/*height: 100%;*/
		vertical-align: middle;
	}
	.forLabel{
		background-color: #e8e8e8;
	}
	.forDocumentMonth{
		padding-left: 10px;
		color: #ccc;
		font-size: 1.3em;
	}
	#documentYear{
		/*border-left: 1px solid;*/
	}
</style>
<div id="project-content">
<!--
    <div id="sub-header">
 PROJECT INFO HEADER  	
        <header class="pagespan">
        	<div class="row">
        		<div class="col-lg-12" id="mainHeader">
        			<div class="">
						<table style="width: 100%;">
							<tr>
								<td style="width: 70%;">
									<table style="width: 100%;" id="left-table">
										<tr class="desk-left-row">
											<td class="desk-left-text pType-text" colspan="3" id="projectType"><?=$projectInfo['ProjectType']?></td>
											<td class="forLabel pType-label"><label>סוג  </label></td>
											<td class="desk-left-text pName-text" id="projectName"><?=$projectInfo['ProjectName']?></td>
											<td class="forLabel pName-label"><label> פרויקט </label></td>
											
										</tr>
										<tr class="desk-left-row">
											<td class="desk-left-text" colspan="3" id="addressStreet"><?=$projectInfo['Street']?></td>
											<td class="forLabel"><label> רחוב </label></td>
											<td class="desk-left-text" id="addressCity"><?=$projectInfo['City']?></td>
											<td class="forLabel"><label> יישוב </label></td>
										</tr>
									</table>
								</td>
								<td style="width: 25%;">
									<table style="width: 100%;" id="right-table">
										<tr class="desk-right-row">
											<td class="desk-right-text" id="buildingNo"><?=$projectInfo['BuildingNumber']?></td>
											<td class="forLabel"><label> בניין </label></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
        			</div>
        		</div>
        		<div class="col-xs-12" id="mobileHeader" style="display: none; margin-bottom: 5px;">
					<table style="width:100%; font-size: 12px;">
						<tr>
							<td class="mobile-table">
								<table style="width:100%;">
									<tr>
										<td colspan="2" style="font-size: 14px;"><?=$projectInfo['ProjectName']?></td>
										<td class="forLabel"><label> פרויקט </label></td>
										
									</tr>
									<tr>
										<td colspan="2" style="font-size: 14px;"><?=$projectInfo['ProjectType']?></td>
										<td class="forLabel"><label> סוג </label></td>
									</tr>
									<tr>
										<td colspan="2" style="font-size: 14px;"><?=$projectInfo['City']?></td>
										<td class="forLabel"><label> יישוב </label></td>
									</tr>
								</table>
							</td>
							<td>
								<table style="width:100%; height: 91px;">
									<tr>
										<td colspan="2" style="font-size: 16px;"><?=$projectInfo['BuildingNumber']?></td>
										<td class="forLabel"><label> בניין </label></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
        		</div>
        	</div>
        	<table class="table-bordered" style="width: 100%; display: none;">
        		<tr>
        			<td>
        				<table style="width: 100%">
        					<tr>
        						<td id="projectNumber"><?=$projectInfo['ProjectNumber']?></td>
        						<td><label> מס' </label></td>
        						<td colspan="2" id="projectType"><?=$projectInfo['ProjectType']?></td>
        						<td><label> סוג </label></td>
        						<td colspan="2" id="projectName"><?=$projectInfo['ProjectName']?></td>
        						<td><label> שם </label></td>
        						<td> פרויקט </td>
        					</tr>
        					<tr>
        						<td id="addressNo"><?=$projectInfo['No']?></td>
        						<td><label> מס' </label></td>
        						<td colspan="2" id="addressStreet"><?=$projectInfo['Street']?></td>
        						<td><label> רחוב </label></td>
        						<td id="addressCity"><?=$projectInfo['City']?></td>
        						<td><label> יישוב </label></td>
        						<td id="addressZone"><?=$projectInfo['Zone']?></td>
        						<td><label> אזור </label></td>
        					</tr>
        					<tr>
        						<td colspan="3" id="projectManager"><?=$projectInfo['ProjectManager']?></td>
        						<td colspan="2"><label> מנהל פרויקט </label></td>
        						<td colspan="2" id="constructor"><?=$projectInfo['Constructor']?></td>
        						<td colspan="2"><label> קבלן </label></td>
        					</tr>
        					<tr>
        						<td colspan="3" id="photography"><?=$projectInfo['Photography']?></td>
        						<td colspan="2"><label> צילום </label></td>
        						<td colspan="2" id="worksManager"><?=$projectInfo['WorksManager']?></td>
        						<td colspan="2"><label> מנהל עבודה </label></td>
        					</tr>
        				</table>
        			</td>
        			<td>
        				<table style="width: 100%">
        					<tr>
        						<td colspan="2" id="buildingNo"><?=$projectInfo['BuildingNumber']?></td>
        						<td><label>בניין </label></td>
        					</tr>
        					<tr>
        						<td colspan="2" id="entranceNumber"><?=$projectInfo['EntranceNumber']?></td>
        						<td><label>כניסה </label></td>
        					</tr>
        					<tr>
        						<td id="documentMonth"><?=$documentMonth?></td>
        						<td id="documentYear"><?=$documentYear?></td>
        						<td><label> תאריך תיעוד </label></td>
        					</tr>
        				</table>
        			</td>
        		</tr>
        	</table>
	    </header>
 CARUSEL APARTAMENTE   		
		<div class="pagespan">
		    <div class="wrap">
			    <?php
			    $nApartCount = count($apartmentInfo);
			    ?>
			    <div class="frame" id="centered" style=" overflow-x: scroll; overflow-y: hidden;">
				    <ul class="clearfix" style="width: <?=260*$nApartCount?>px;">
				    	<?php
				    	for($i = 1; $i <= $nApartCount; $i++){
							$_curApartment = $apartmentInfo[$i - 1];
							$_apartName = $_curApartment['ApartmentName'];
							$_apartNo = str_replace("ap", "", $_apartName);
					    	$href = $path . "main.php?apartNo=" . $_apartNo;
			    		?>
					    <li class="<?php if($apartNo == $_apartNo) echo "active";?>">
                            <div class="horiz-top" title="Apartment"><a href="<?=$href?>"><span class="doc-number"><?=$_apartNo?></span><span class="doc">קומה</span></a></div>
							<div class="horiz-bottom">							
								<?php
								$width = 100 / count($parts4Project);
								for( $j = 0; $j < count($parts4Project); $j++){
									$val = $parts4Project[$j]['PartName'];
									$arrPartInfos = explode(",", $_curApartment['PartInfos']);
									$imgNumber = 0;
									if( count($arrPartInfos) > $j){
										$imgNumber = $arrPartInfos[$j];
									}
								?>
								<div class="fleft horiz-bottoms" title="<?=$val?>" style="width: <?=$width?>%"><a href="<?=$href?>#No<?=$imgNumber?>"><?=$val?></a></div>
								<?php
								}
								?>
							</div>
						</li>
			    		<?php
						}
				    	?>
				    </ul>
			    </div>
		    </div>
	    </div>

        <div id="wraperr">
            <div id="navMenu">
                <ul>
                    <li class="buttons-lines-color  project-li-section-project display-only-mobile" title="quick link">
 START WRAPPER         
<div id="wrapper" class="">
    <div id="popupBox1">  
        <div class="popupBoxWrapper1">
            <div class="popupBoxContent1">
                  <a href="javascript:void(0)" onclick="toggle_visibility('popupBox1');"><span style="display:block; background-color: #333333; height: 50px;">x</span></a>	   
	            <div id="reception-notes-container">		 
		            <div id="reception-notes">
		            	<div class="col-md-12 col-xs-12" id="quick-link">
		            		<div class="row" style="color: black;">
		            			<div class="col-md-6 col-xs-6">
		            				<div class="row" style="padding: 2px;">
			            				<div class="ql-tab-header" style="border-bottom: 2px solid #fda400;"> הערות </div>
			            				<div class="col-md-12 col-mxs12" style="border: 1px solid #cccccc; padding-right: 12%; height: 1000px;">
			            					<div class="row" id="arrNotes">
				            					<?php
				            					$arrNotes = getAllNotes($projectInfo['Id'], $apartNo);
				            					foreach ($arrNotes as $value) {
				            						$idx = $value['PhotoIdx'];
			            						?>
			            						<a class="fright" style="height: 1.8em; width: 1.8em; line-height: 0.6em; border-radius: 3px; background-color: #fda400; font-size: 20px; margin-top: 3px;" href="#No<?=$idx?>"><?=$idx?></a>
			            						<?php
				            					}
				            					?>
			            					</div>
			            				</div>
		            				</div>
		            			</div>
		            			<div class="col-md-6 col-xs-6">
		            				<div class="row" style="padding: 2px;">
			            				<div class="ql-tab-header" style="border-bottom: 2px solid #fd0000;"> תמונות </div>
			            				<div class="col-md-12 col-mxs12" style="border: 1px solid #cccccc; padding-right: 12%; height: 1000px;">
			            					<div class="row" id="arrDefects">
												<?php
												$arrDefects = getAllDefects( $projectInfo['Id'], $apartNo);
												
												foreach ($arrDefects as $value) {
													$idx = $value['PhotoIdx'];
												?>
												<a class="fright" style="height: 1.8em; width: 1.8em; line-height: 0.6em; border-radius: 3px; background-color: #ec0a0a; font-size: 20px; margin-top: 3px;" href="#No<?=$idx?>"><?=$idx?></a>
												<?php
												}
												?>
											</div>
			            				</div>
		            				</div>
		            			</div>
		            		</div>
		            	</div>
			            
<?php
$files = [];
$dir = __DIR__ . "/container/project1/" . $_dirID . "/project/plans/";
if( file_exists($dir)){
	$files = scandir($dir);
}
$i = 0;
foreach($files as $file){
	break;
	if( $file == ".." || $file == ".")
		continue;
	$i++;
?>
<div class="rec-number"><a class="plan-popup" href="#No<?=$i?>"><?=$i?><span><img src="container/project1/<?=$_dirID?>/project/plans/<?=$i?>pl.jpg" /></span></a></div>
<?php
}


?>
		            </div>   
		        </div> 
                  				
            </div>
        </div>
    </div>           
                   <a href="javascript:void(0)" onclick="toggle_visibility('popupBox1');">קישור מהיר</a>
</div>

 END WRAPPER 

                    </li> 	

                     <li class="buttons-lines-color  project-li-section-project" title="Go to Sections">
 START WRAPPER 
<div id="wrapper" class="">
  <div id="popupBox3">
    <div class="popupBoxWrapper3">
      <div class="popupBoxContent3">
       <a href="javascript:void(0)" onclick="toggle_visibility('popupBox3');"><span style="display:block; background-color: #333333; height: 50px;">x</span></a>
	    <div class="popup-content-container">
		    <div class="fleft popup-container-numere">
	            <div class="popup-content-numere">
	            	<?php
	            	for($i = 1; $i <= $sectionCount; $i++){
	            		$curSectionNumber = $arrSectionInfos[$i - 1];
	            	?>
					<span class="sections"><a href="#No<?=$curSectionNumber?>"><?=$i?></a></span>
	            	<?php
		            }
	            	?>
	            </div>
			</div>
			<div class="fright popup-content-plan">
	               <img src="container/project1/sectiuni/sectiuni-ap<?=$apartNo?>.jpg">  
			</div>	
			<div class="clear"></div>
	    </div>
    </div>
  </div>
</div>           
      <a href="javascript:void(0)" onclick="toggle_visibility('popupBox3');"><strong>אזורים</strong></a>
</div>
 END WRAPPER 
            </li>					
					
<?php
$onClickString = "";
$i = 0;
foreach($files as $file){
	if( $file == ".." || $file == ".")
		continue;
	$i ++;
	if( $i == 1){
		$onClickString .= "MM_effectAppearFade('photo" . $i . "', 500, 100, 0, true)";
	} else{
		$onClickString .= ", " . "MM_effectAppearFade('photo" . $i . "', 500, 100, 0, true)";
	}
}
?>
					<li style="display:none;" class="buttons-lines-color  project-li-project" title="click to switch between photos and plans"><div onClick="<?=$onClickString?>">

<a href="#">תוכניות / תמונות</a></div></li>

				    <li style="display:none;" class="buttons-lines-color project-li-return-project" style="border-right: 0px solid #fff;" title="Refresh page"><div onclick="myFunction()"><a href="#"><strong>&#x21ba;</strong></a></div></li>

                </ul> 

<br class="clear"/>             

            </div>

        </div> 

    </div>  
--> 

    <div id="sub-home">
<?php
$i = 0;
foreach($files as $file){
	if( $file == ".." || $file == ".")
		continue;
	$i++;
?>
<div id="No<?=$i?>" >
	<div id="photoplan">
<!--	
		<div id="plan" onClick="MM_effectAppearFade('photo<?=$i?>', 500, 100, 0, true)">
			<div id="hideonload">
				<img class="desktop" src="container/project1/<?=$_dirID?>/project/plans/<?=$i?>pl.jpg">
			</div>
			<div class="photo" id="photo<?=$i?>">
				<img class="desktop" src="container/project1/<?=$_dirID?>/project/photos/<?=$i?>pi.jpg">
			</div>
		</div>
 
		<div class="textBox">
			<div>
				<div style="float: left;" onclick="closeNote(this)">&#10006;</div>
				<div style="float: right;">הערות</div>
				<div style="clear: both;"></div>

			</div>
			<textarea id="text<?=$i?>" rows="3"></textarea><br>
			<button class="btn-success" style="float: right; margin-top: 10px;" onclick="onNoteSave(this)">שמור</button>
		</div>
--> 		
		<div id="dwld">
			<div class="fright" style="width: 40%; color: #a3a3a3;" title="photo number">
				<strong>Floor <?=$i-1?></strong>
			</div>
<!--			
			<div class="fright upload-over-button" title="go to top">
				<a href="#top">
					<span><i class="fas fa-arrow-up"></i></span>
				</a>
			</div>
			<div class="fleft upload-over-button" title="zoom">
				<a href="container/project1/<?=$_dirID?>/project/big/<?=$i?>pi.jpg" target="_blank">
					<span><i class="fas fa-search-plus"></i></span>
				</a>
			</div>
			<div class="fleft upload-over-button" title="edit">
				
					<span onclick="showNotes(this)" style="cursor: pointer;"><i class="far fa-edit"></i></span>
				
			</div>
-->			
			<div class="fleft upload-over-button" title="">
				<div style="cursor: pointer; border-right: 1px solid #726e6e;">
					<span class="upload-over-button" title="photo over plan" onclick="popup(<?=$i?>, 'plan')">
						<i class="fas fa-eye"></i>
					</span>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<!-- UPLOADS CONTAINER -->
<?php
}
?>
<div class="uploadImgWnd" style="display: none; max-height: 100%; height: 100%; overflow: auto;">
	<div class="imgBorder">
		<div class="xBtn" onclick="closeUploadImgWnd()">X</div>
		<div class="imgBorder-imgcontainer">
			<img src="container/project1/<?=$_dirID?>/project/photos/1pi.jpg">
		</div>
		<div class="uploadedImgPan" style="position: absolute; top:10px;">
		</div>
		<div id="uploadedContainer" style="position: absolute; top: 10px">
		</div>
	</div>
	<div class="contrlBorder hideondesktop">
		<div class="imgUpload" id="mob-firstleft-column">
			<div style="display: none"><img src="" id="img_drawer"></div>
			<input type="file" id="img_picker" style="display: none;">
			<div class="hideondesktop" id="uploadonmobile">
			    <label for="img_picker">העלה תמונה</label>
			</div>	
			<div  style="margin-top: 20px;" onclick="SaveImage()" class="forMobile popupBtn btn">שמור</div>
		</div>
		<div class="infFields col-md-12 forDesktop" style="text-align: right; display: none;"><!-- INITIAL for desktop uploads -->
			<div class="row">
				<div class="col-md-4" id="desktopsave">
																	 
					<table>									
						<tr class="infFields-box">
							<td>
																	
								<textarea  style="padding: 0 10px;" name="description" placeholder="תיאור" rows="4"></textarea>
							</td>
						</tr>
					</table>			 
				</div>			
													   
																   
																  
		   
		  
				<div class="col-md-6">
					<table>
						<tr class="infFields-box">
							<td><input type="text" name="Year" placeholder="שנה" style="border-right: 1px solid #cccccc; text-align: center;"></td>
							<td><input type="text" name="Month" placeholder="חודש" style="border-right: 1px solid #cccccc; text-align: center;"></td>
							<td><input type="text" name="Day" placeholder="יום" style="text-align: center;"></td>
						</tr>
					</table>
					<br/>
					<table>
						<tr class="infFields-box">
							<td>
								<input style="text-align: right; padding: 0 10px;" type="text" name="desc" placeholder="נושא">
			 
													   
			  
								   
			  
										  
			   
			  
			 
												   
			  
								   
			  
												   
			   
			  
				
							</td>
						</tr>
					</table>									  
				</div>	
				<div class="col-md-2">
					<div class="imgUploadButton" id="imgUploadButton">
					    <input type="file" id="img_picker" style="display: none;">
			            <label for="img_picker">העלה תמונה</label>
					</div>	
					<div onclick="SaveImage()" class="desktoppopupBtn">שמור</div>
				</div>
			</div>
		</div>
		<div class="infFields col-md-12 forMobile" style="text-align: right;">
			<div class="row">
			<div style="height: auto; overflow: auto; display: block;">															  
				<div class="col-xs-12">		   
					<table style="margin-bottom: 5px;">
						<tr class="infFields-box">
		  
							<td style="border-right: 1px solid #cccccc;"><input style="text-align: center;" type="text" name="Year" placeholder="שנה"></td>
							<td style="border-right: 1px solid #cccccc;"><input style="text-align: center;" type="text" name="Month" placeholder="חודש"></td>
							<td style=""><input style="text-align: center;" type="text" name="Day" placeholder="יום"></td>
						</tr>
					</table>
					<table style="margin-bottom: 5px;">
		  
													
		   
						<tr>
							<td class="infFields-box">
								<input style="text-align: right; padding: 0 10px;" type="text" name="desc"  placeholder="נושא">
							</td>
						</tr>
					</table>
												
		   
		  
					<table>
						<tr>
							<td>
								<textarea style="text-align: right; padding: 0 10px;" name="description" placeholder="תיאור" rows="2"></textarea>
							</td>
						</tr>
					</table>									  
				</div>
			</div>
			</div>		   
		</div>
		<div style="clear: both;"></div>

	</div>
</div>

</div>

<!--
    <div id="home-footer" class="header-footer-background footer-border">
        <div class="left footer-left"><h1>Concept and Design: <b>Sorel Alexander</b><br> <a href="http://www.watchwork.co.il" target="_blank"><b>© 2017 Watchwork</b></a><br> All Rights Rezerved</h1></div>
	    <div class="left footer-left"><img src="assets/images/watchwork-logo.png"></div>
        <div class="left footer-left"><h1>054-2096602 <br> office@watchwork.net <br> <a href="http://www.watchwork.co.il" target="_blank"><b>www.watchwork.co.il</b></a></h1></div>	

	    <div class="clear"></div>	
    </div>
-->		
</div>

</div>

</div>
<div class="modal fade" role="dialog" id="listPhotoModal" style="z-index: 10000;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
<div id="loadingImg" style="position: absolute;z-index: 20000;width: 100vw; height: 100vh;vertical-align: middle;text-align: center; background-color: #35353591; display: none;">
	<img src="assets/images/loading.gif" style="top: 50%; left: 50%;  -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); position: absolute;">
</div>
<style type="text/css">
	.mobile_logout{
		width: 100%;
		/*height: 1em;*/
		text-align: right;
		color: white;
		background-color: black;
		margin: 0px !important;
	}
	.popupBtn{
		background-color: #333;
		color: #ccc;
	}
	.popupBtn:hover{
		background-color: #444 !important;
		color: #ddd;
	}
	.horiz-bottom .horiz-bottoms a{
		position: relative;
		top: 0;
		line-height: 43px;
	}
	@media only screen and (max-width: 768px) {
		.horiz-bottom .horiz-bottoms a{
			position: relative;
			top: 0px;
		}
	}
	
</style>

<script>
var apartNo = <?=$apartNo?>;
var prevId = -1, prevCat = "";
var uploadedInfos = [];
var fullWidth = document.body.clientWidth;
var aptCount = "<?=$nApartCount?>";
$("#centered").scrollLeft($("#centered ul li").eq("<?=$_apartIndex - 1?>").position().left);
function setHrefTags4Mobile(_arrContents){
	for( var i = 0; i < _arrContents.length; i++){
		var curNode = _arrContents.eq(i);
		var nId = curNode.text();
		if( nId == 1){
			_arrContents.eq(i).attr("href", "#");
		} else{
			_arrContents.eq(i).attr("href", "#No" + ( nId - 1) );
		}
	}
}
if( fullWidth < 769){
	$(".forDesktop").remove();
	setHrefTags4Mobile($("#arrNotes a"));
	setHrefTags4Mobile($("#arrReparation a"));
	setHrefTags4Mobile($("#arrDefects a"));
	var arrSectionAs = $(".popup-content-numere a");
	for( var i = 0; i < arrSectionAs.length; i++){
		var curA = arrSectionAs.eq(i);
		var href = curA.attr("href");
		var nNumber = href.replace("#No", "") * 1 - 1;
		if( nNumber == 0){
			curA.attr("href", "#");
		} else{
			curA.attr("href", "#No" + nNumber);
		}
	}
} else{
	$(".forMobile").remove();
}
</script> 
<script type="text/javascript" src="assets/js/main/main.js?<?=time()?>"></script>
</html>

