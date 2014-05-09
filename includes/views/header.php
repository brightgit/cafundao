<!DOCTYPE>
<html>
<head lang="pt">
	<meta charset="utf-8">
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Virtual Data Room</title>
	
	<meta name="description" content="Backoffice Bright" />
	<meta name="keywords" content="Backoffice Bright" />
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="content-language" content="pt">
	<!-- Jquery -->
	<script src="<?php echo base_url("vendor/jquery-1.10.2.min.js"); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/jquery.ui/jquery-ui-1.10.4/themes/base/jquery-ui.css") ?>" />
	

	<!-- BootStrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/bootstrap.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/vendor/jquery.pnotify.default.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/vendor/select2/select2.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/fancybox/jquery.fancybox.css") ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/vendor/datatables.css") ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/vdr.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/vendor/animate.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/font-awesome.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("vendor/vdr.ui/css/font-titillium.css") ?>" />


	<link rel="stylesheet" type="text/css" href="<?php echo base_url("css/admin.css") ?>" />


	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/modernizr.js"); ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/jquery.cookie.js"); ?>"></script>



	<script type="text/javascript">
		CKEDITOR_PARENT = '<?php echo base_url("vendor/"); ?>';
		CKEDITOR_BASEPATH = '<?php echo base_url("vendor/ckeditor/"); ?>';
		CKFINDER_BASEPATH = '<?php echo base_url("vendor/kcfinder/browse.php?type=images"); ?>';
		CKFINDER_BASEPATH_FILE = '<?php echo base_url("vendor/kcfinder/browse.php"); ?>';
		BASEPATH = '<?php echo base_url(""); ?>';
		BASEURL = '<?php echo base_url(""); ?>';
		SITE_URL = '<?php echo base_url(""); ?>';
		REQUEST_FOLDER = null;
	</script>

</head>
<?php if (!$_GET["mod"] || $_GET["mod"] == "home"): ?>
	<body class="dashboard-page">
<?php else: ?>
	<body>
<?php endif ?>