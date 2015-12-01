<?php
/*
 * Copyright 2015 be.wan s.p.r.l.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 */
 
/*
 * @author Ahmed Zarioh (ahmed.zarioh@gmail.com)
 */

function include_all_javascript($folder)
{

    foreach (glob(__DIR__."/../{$folder}/*.js") as $filename)
    {
    	//$filename strrchr($filename,'/');
    	?>
<script src="<?php echo "{$folder}/".basename($filename); ?>"></script>
        <?php
    }

    foreach (glob(__DIR__."/../{$folder}/*") as $foldername)
    {
    	if(is_dir($foldername))
    	{
			include_all_javascript("{$folder}/".basename($foldername));
    	}
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ExecutionWareUI</title>
		<link rel="icon" type="image/png" href="public/img/icon.png" />

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script>var mainConfig = <?= json_encode($GLOBALS['client']) ?>;</script>
		<!-- JQUERY -->
		<script src="public/ext/jquery-1.11.2.min.js"></script>	

		<!-- Angular 1.4 -->	
		<script src="public/ext/angular/angular.js"></script>	
		<script src="public/ext/angular/angular-route.min.js"></script>	
		
		<!-- Angular Drag and Drop List -->
		<script src="public/ext/angular-drag-and-drop-lists.js"></script>

		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="public/ext/bootstrap/css/bootstrap.min.css" media="all"/>
		<script src="public/ext/bootstrap/js/bootstrap.min.js"></script>	
		<script src="public/ext/bootstrap/js/collapse.js"></script>	
		<script src="public/ext/bootstrap/js/transition.js"></script>	
		<script src="public/ext/bootstrap/js/tooltip.js"></script>	

		<!-- Bootstrap SB ADMIN-->
		<link rel="stylesheet" type="text/css" href="public/ext/startbootstrap-sb-admin-1.0.3/css/sb-admin.css"/>

		<!-- FLOT -->
		<script src="public/ext/flot-0.8.3-dist/jquery.flot.min.js"></script>
		<script src="public/ext/flot-0.8.3-dist/jquery.flot.time.min.js"></script>
		<script src="public/ext/flot-0.8.3-dist/jquery.flot.selection.min.js"></script>
		<script src="public/ext/flot-0.8.3-dist/jquery.flot.resize.min.js"></script>
		
		<!-- Bootstrap-Touchspin -->
		<link href="public/ext/bootstrap-touchspin-dist/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet">					
		<script src="public/ext/bootstrap-touchspin-dist/js/jquery.bootstrap-touchspin.min.js"></script>
		

		<!-- UI-Bootstrap -->
		<script src="public/ext/ui-bootstrap-tpls-0.14.3.min.js"></script>	

		<!-- Mine 				-->
		<link href="public/css/style.css" rel="stylesheet">
		<script src="public/js/chartscript.js"></script>
		<script src="public/js/script.js"></script>

		<!-- App -->	
		<script src="public/js/app.js"></script>



		<!-- Controllers-->	
		<?php include_all_javascript("public/js/controller"); ?>

		<!-- Services-->	
		<?php include_all_javascript("public/js/service"); ?>


		<script src="public/js/filters.js"></script>
	</head>
	<body ng-app="paasageApp" ng-controller="mainCtrl">
		<div id="wrapper">
			<div hidden>
				<pre>$location.path() = {{location.path()}}</pre>
				<pre>$route.current.templateUrl = {{route.current.templateUrl}}</pre>
				<pre>$route.current.params = {{route.current.params}}</pre>
				<pre>$route.current.scope.name = {{route.current.scope.name}}</pre>
				<pre>$routeParams = {{routeParams.apiname}}</pre>
			</div>
			<div ng-include="'public/partials/navbar.html'"></div>
			<div id="page-wrapper">
				<div ng-if="debug" ng-click="clearError()" class="container-fluid alert alert-danger" role="alert">
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  {{errors}}
				</div>
				<div ng-if="debug" class="container-fluid alert alert-info" role="alert">
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">debug:</span>
				  {{debug}}
				</div>
				<div class="container-fluid" style="height : 20px;">
					<span ng-show="loading">
						Loading ...
					</span>
				</div>
				<div class="container-fluid" ng-view class="view-frame"></div>

			</div>
		</div>
	</body>
</html>