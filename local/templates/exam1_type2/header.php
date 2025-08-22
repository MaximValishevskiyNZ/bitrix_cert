<? $APPLICATION->ShowHead();
use Bitrix\Main\Application;
require_once $_SERVER["DOCUMENT_ROOT"] . "/local/templates/exam1_type2/boot.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?$APPLICATION->ShowTitle()?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?=DEFAULT_TEMPLATE_PATH?>/assets/img/favicon.png" rel="icon">

  <!-- Vendor CSS Files -->
  <link href="<?=DEFAULT_TEMPLATE_PATH?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=DEFAULT_TEMPLATE_PATH?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?=DEFAULT_TEMPLATE_PATH?>/assets/css/style.css" rel="stylesheet">

</head>

<body>
  
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      
      <a href="dashboard.html" class="logo d-flex align-items-center">
        <img src="<?=DEFAULT_TEMPLATE_PATH?>/assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Статистика</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
    
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form", 
	"auth", 
	array(
		"FORGOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "/statistic_na/profile/",
		"REGISTER_URL" => "",
		"SHOW_ERRORS" => "N",
		"COMPONENT_TEMPLATE" => "auth"
	),
	false
);?>


       

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->


  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"stats_menu", 
	array(
		"ROOT_MENU_TYPE" => "st_first",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"CHILD_MENU_TYPE" => "st_second",
		"USE_EXT" => "Y",
		"ALLOW_MULTI_SELECT" => "Y",
		"MAX_LEVEL" => "2",
		"DELAY" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"COMPONENT_TEMPLATE" => "stats_menu",
		"MENU_CACHE_GET_VARS" => array(
		)
	),
	false
);?>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle mb-4">
      <h1><?$APPLICATION->ShowTitle(false)?></h1>
    </div><!-- End Page Title -->

    <section class="<?$APPLICATION->ShowProperty("page_css_class")?>"><?$APPLICATION->ShowPanel();?>