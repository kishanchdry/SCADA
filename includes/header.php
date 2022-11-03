<?php
include_once './core/init.php';
$isLoggedIn = new User();

if(!$isLoggedIn->isLoggedIn()){
    Redirect::to('index.php');
}

$page_title = Session::url();
//$role = $isLoggedIn->data()->group;

$user=escape($isLoggedIn->data()->user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Balotra Water Pollution Control, Treatment & Research Foundation</title>
    <link rel="icon" href="img/logo.jpg" type="image/jpg" sizes="16x16">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <script src="vendor/jquery/jquery.min.js"></script>
    <link href="css/font-family.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/loader.js"></script>
  
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Side-bar -->
        <?php include_once 'includes/side-bar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Top-bar -->
                <?php include_once 'includes/top-bar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid pb-100">
                    <!-- Page Heading --
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800" id="breadcrumb">
                            
                        </h1>
                    </div>-->
                    