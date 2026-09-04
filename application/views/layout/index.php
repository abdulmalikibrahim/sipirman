<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("layout/head"); ?>
<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div> -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <?php $this->load->view("layout/navbar"); ?>
        <?php
        if($this->level == "keluarga"){
            $this->load->view("layout/sidebar_ki");
        }else{
            $this->load->view("layout/sidebar");
        }
        ?>
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles p-2">
                    <div class="col-12 align-self-center">
                        <h3 class="text-themecolor" id="headertop"><?= $title ?></h3>
                    </div>
                </div>
                <?php $this->load->view("content/".$content); ?>
            </div>
        </div>
    </div>
<?php $this->load->view("layout/footer"); ?>
<?php 
if(!empty($javascript)){
    $this->load->view("js/".$javascript);
}
?>
<?php $this->load->view("layout/js_footer"); ?>
</body>
</html>