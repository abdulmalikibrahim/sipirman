<?php 
if(empty($this->nama)){
    redirect("logout");
}
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png'); ?>">
<title>SIPIRMAN</title>
<!-- Bootstrap Core CSS -->
<link href="<?= base_url("assets/plugins/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet">
<!-- chartist CSS -->
<link href="<?= base_url("assets/plugins/chartist-js/dist/chartist.min.css") ?>" rel="stylesheet">
<link href="<?= base_url("assets/plugins/chartist-js/dist/chartist-init.css") ?>" rel="stylesheet">
<link href="<?= base_url("assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css") ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--This page css - Morris CSS -->
<link href="<?= base_url("assets/plugins/c3-master/c3.min.css") ?>" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?= base_url("assets/css/style.css") ?>" rel="stylesheet">
<link href="<?= base_url("assets/style.css") ?>" rel="stylesheet">
<!-- You can change the theme colors from here -->
<link href="<?= base_url("assets/css/colors/blue.css") ?>" id="theme" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />