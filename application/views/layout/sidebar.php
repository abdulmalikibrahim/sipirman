<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <?php 
            if($this->level == "Admin"){
                $get_confirm_belanja = $this->admin_model->get_data_select("penggunaan_uang","COUNT(id) as total","id_belanja_keluarga != '' AND status = 'Need Confirm' GROUP BY id_belanja_keluarga","row");
                if(!empty($get_confirm_belanja->total)){
                    $circle_notif = '<i class="fas fa-cart-shopping fa-bounce text-danger" style="font-size:13pt;"></i>';
                }else{
                    $circle_notif = '<i class="fas fa-cart-shopping" style="font-size:13pt;"></i>';
                }
                ?>
                <ul id="sidebarnav">
                    <li>
                        <a style="font-size:9pt;" class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="<?= base_url('home/pendaftaran') ?>" style="font-size:9pt;">Pendaftaran</a></li>
                            <li><a href="<?= base_url('home/p2u') ?>" style="font-size:9pt;">P2U</a></li>
                            <li><a href="<?= base_url('home/komandan') ?>" style="font-size:9pt;">Komandan</a></li>
                        </ul>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("data") ?>" aria-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Data Titipan</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("penyimpanan") ?>" aria-expanded="false"><i class="fas fa-building-columns" style="font-size:13pt;"></i><span class="hide-menu pl-1">Penyimpanan</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("penitip") ?>" aria-expanded="false"><i class="fas fa-users" style="font-size:13pt;"></i><span class="hide-menu">Data Penitip</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("confirm_belanja") ?>" aria-expanded="false"><?= $circle_notif; ?><span class="hide-menu">Konfirmasi Belanja</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt; display:flex; align-items:center;" class="waves-effect waves-dark" href="<?= base_url("tagihan_rumbang_mart") ?>" aria-expanded="false">
                            <i class="fas fa-file-invoice-dollar" style="font-size:13pt; text-align:center; flex-shrink:0;"></i>
                            <span class="hide-menu pl-1" style="white-space:normal; line-height:1.2;">Tagihan Rumbang Mart</span>
                        </a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("account") ?>" aria-expanded="false"><i class="mdi mdi-account-check"></i><span class="hide-menu">Akun</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("tahanan") ?>" aria-expanded="false"><i class="fas fa-users" style="font-size:13pt;"></i><span class="hide-menu pl-1">Data WBP</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("profile") ?>" aria-expanded="false"><i class="mdi mdi-emoticon"></i><span class="hide-menu">Profil</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("logout") ?>" aria-expanded="false"><i class="mdi mdi-power"></i><span class="hide-menu">Keluar</span></a>
                    </li>
                </ul>
                <?php
            }else if($this->level == "Cashier"){
                $get_confirm_belanja = $this->admin_model->get_data_select("penggunaan_uang","COUNT(id) as total","id_belanja_keluarga != '' AND status = 'Process' GROUP BY id_belanja_keluarga","row");
                if(!empty($get_confirm_belanja->total)){
                    $circle_notif = '<i class="fas fa-cart-shopping fa-bounce text-danger" style="font-size:13pt;"></i>';
                }else{
                    $circle_notif = '<i class="fas fa-cart-shopping" style="font-size:13pt;"></i>';
                }
                ?>
                <ul id="sidebarnav">
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("cashier") ?>" aria-expanded="false"><i class="fas fa-cash-register pt-1" style="font-size:13pt;"></i><span class="hide-menu">Cashier</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url('rekap_penjualan') ?>" aria-expanded="false">
                            <i class="fas fa-file-invoice-dollar pt-1" style="font-size:13pt;"></i>
                            <span class="hide-menu">Rekap Penjualan</span>
                        </a>                    
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("barang_koperasi") ?>" aria-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Data Barang</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("kulakan") ?>" aria-expanded="false"><i class="fas fa-truck-loading pt-1" style="font-size:13pt;"></i><span class="hide-menu">Kulakan</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("topup_saldo") ?>" aria-expanded="false"><i class="fas fa-wallet pt-1" style="font-size:13pt;"></i><span class="hide-menu">Top Up Saldo WBP</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("daftar_hutang") ?>" aria-expanded="false"><i class="fas fa-exclamation-circle pt-1" style="font-size:13pt;"></i><span class="hide-menu">Daftar Hutang WBP</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("confirm_belanja") ?>" aria-expanded="false"><?= $circle_notif; ?><span class="hide-menu">Konfirmasi Belanja</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("logout") ?>" aria-expanded="false"><i class="mdi mdi-power"></i><span class="hide-menu">Keluar</span></a>
                    </li>
                </ul>
                <?php
            }else if($this->level == "Super Admin"){
                ?>
                <ul id="sidebarnav">
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("home_dashboard") ?>" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("logout") ?>" aria-expanded="false"><i class="mdi mdi-power"></i><span class="hide-menu">Keluar</span></a>
                    </li>
                </ul>
                <?php
            }else if($this->level == "Saku"){
                // Notif konfirmasi belanja untuk level Saku
                $get_confirm_belanja = $this->admin_model->get_data_select("penggunaan_uang","COUNT(id) as total","id_belanja_keluarga != '' AND status = 'Need Confirm' GROUP BY id_belanja_keluarga","row");
                if(!empty($get_confirm_belanja->total)){
                    $circle_notif = '<i class="fas fa-cart-shopping fa-bounce text-danger" style="font-size:13pt;"></i>';
                }else{
                    $circle_notif = '<i class="fas fa-cart-shopping" style="font-size:13pt;"></i>';
                }
                ?>
                <ul id="sidebarnav">
                    <!-- 1. Dashboard -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("dashboard") ?>" aria-expanded="false">
                            <i class="mdi mdi-gauge"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    <!-- 2. Data Saku WBP (sebelumnya: Penyimpanan) -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("penyimpanan") ?>" aria-expanded="false">
                            <i class="fas fa-wallet" style="font-size:13pt;"></i>
                            <span class="hide-menu pl-1">Data Saku WBP</span>
                        </a>
                    </li>
                    <!-- 3. Konfirmasi Belanja -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("konfirmasi_belanja") ?>" aria-expanded="false">
                            <?= $circle_notif; ?>
                            <span class="hide-menu pl-1">Konfirmasi Belanja</span>
                        </a>
                    </li>
                    <!-- 4. Tagihan Rumbang Mart -->
                    <li>
                        <a style="font-size:9pt; display:flex; align-items:center;" class="waves-effect waves-dark" href="<?= base_url("tagihan_rumbang") ?>" aria-expanded="false">
                            <i class="fas fa-file-invoice-dollar" style="font-size:13pt; flex-shrink:0;"></i>
                            <span class="hide-menu pl-1" style="white-space:normal; line-height:1.2;">Tagihan Rumbang Mart</span>
                        </a>
                    </li>
                    <!-- 5. Data Penitip -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("penitip") ?>" aria-expanded="false">
                            <i class="fas fa-users" style="font-size:13pt;"></i>
                            <span class="hide-menu pl-1">Data Penitip</span>
                        </a>
                    </li>
                    <!-- 6. Data WBP -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("data_wbp") ?>" aria-expanded="false">
                            <i class="fas fa-user-shield" style="font-size:13pt;"></i>
                            <span class="hide-menu pl-1">Data WBP</span>
                        </a>
                    </li>
                    <!-- 7. Profil -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("profile") ?>" aria-expanded="false">
                            <i class="mdi mdi-emoticon"></i>
                            <span class="hide-menu">Profil</span>
                        </a>
                    </li>
                    <!-- 8. Keluar -->
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("logout") ?>" aria-expanded="false">
                            <i class="mdi mdi-power"></i>
                            <span class="hide-menu">Keluar</span>
                        </a>
                    </li>
                </ul>
                <?php
            }else{
                ?>
                <ul id="sidebarnav">
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("home") ?>" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("data") ?>" aria-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Data Titipan</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("profile") ?>" aria-expanded="false"><i class="mdi mdi-emoticon"></i><span class="hide-menu">Profil</span></a>
                    </li>
                    <li>
                        <a style="font-size:9pt;" class="waves-effect waves-dark" href="<?= base_url("logout") ?>" aria-expanded="false"><i class="mdi mdi-power"></i><span class="hide-menu">Keluar</span></a>
                    </li>
                </ul>
                <?php
            }
            ?>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
</aside>