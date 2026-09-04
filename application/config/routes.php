<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'my_control';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ==========================================
// MODIFIKASI: Route God Mode Dashboard Admin
// ==========================================
$route['home'] = 'my_control/home';
$route['home/(:any)'] = 'my_control/home/$1';

$route['login'] = 'my_control';
$route['data'] = 'my_control/data';
$route['penitip'] = 'my_control/penitip';
$route['account'] = 'my_control/account';
$route['profile'] = 'my_control/profile';
$route['logout'] = 'my_control/logout';
$route['input'] = 'my_control/data/input';
$route['ainput'] = 'my_control/account/input';
$route['delete'] = 'my_control/delete_data';
$route['deliv'] = 'my_control/delivery';
$route['finish'] = 'my_control/finish';
$route['data/(:num)'] = 'my_control/edit_titipan/$1';
$route['new_pw'] = 'my_control/new_password';
$route['account/(:num)'] = 'my_control/edit_account/$1';
$route['adelete'] = 'my_control/delete_account';
$route['new_pwp'] = 'my_control/new_password_profile';
$route['t_penitip'] = 'my_control/tambah_penitip';
$route['getpen'] = 'my_control/get_penitip';
$route['getnapi'] = 'my_control/get_narapidana';
$route['pinput'] = 'my_control/penitip/input';
$route['penitip/(:num)'] = 'my_control/edit_penitip/$1';
$route['simpan_edit_penitip/(:num)'] = 'my_control/simpan_edit_penitip/$1';
$route['d_pen'] = 'my_control/delete_penitip';
$route['get_tracking'] = 'my_control/get_tracking';
$route['detail'] = 'my_control/detail';
$route['getresi'] = 'my_control/get_data_resi';
$route['detail/(:num)'] = 'my_control/detail_titipan/$1';
$route['settgl'] = 'my_control/set_tanggal_dash';
$route['settgldata'] = 'my_control/set_tanggal_data';
$route['setstatus'] = 'my_control/set_status';
$route['cstatus'] = 'my_control/cari_status';
$route['se/(:any)'] = 'my_control/send_email/$1';
$route['repairdate'] = 'my_control/repairdate';

// PENYIMPANAN
$route['penyimpanan'] = 'Penyimpanan';
$route['penyimpanan_input'] = 'Penyimpanan/input';
$route['penyimpanan_save'] = 'Penyimpanan/save';
$route['getnapi_new'] = 'my_control/get_narapidana_new';
$route['rincian_uang/(:any)'] = 'Penyimpanan/rincian_uang/$1';
$route['penyerahan_uang/(:any)'] = 'Penyimpanan/penyerahan_uang/$1';
$route['penyerahan_uang_simpan'] = 'Penyimpanan/penyerahan_uang_simpan';
$route['rincian_uang_tunai/(:any)'] = 'Penyimpanan/rincian_uang_tunai/$1';

// TAHANAN
$route['tahanan'] = 'my_control/tahanan';
$route['tahanan_input/(:any)'] = 'my_control/tahanan_input/$1';
$route['save_tahanan/(:any)'] = 'my_control/save_tahanan/$1';

// CASHIER
$route['cashier'] = 'Cashier/home';
$route['barang_koperasi'] = 'Cashier/barang_koperasi';
$route['tambah_barang_koperasi/(:num)'] = 'Cashier/tambah_barang/$1';
$route['simpan_barang_koperasi/(:num)'] = 'Cashier/simpan_barang/$1';
$route['delete_barang_koperasi'] = 'Cashier/delete_barang';
$route['check_barang'] = 'Cashier/check_barang';
$route['diserahkan_uang'] = 'Cashier/diserahkan_uang';
$route['use_money_digital'] = 'Cashier/use_money_digital';
$route['use_money_manual'] = 'Cashier/use_money_manual';
$route['verify_pin'] = 'cashier/verify_pin';
$route['rekap_penjualan']            = 'cashier/rekap_penjualan';
$route['terima_pembayaran_digital']  = 'cashier/terima_pembayaran_digital';

// KELUARGA INTI
$route["login_keluarga"] = 'Keluarga_inti/login_keluarga';
$route["saku_wbp"] = 'Keluarga_inti/saku_wbp';
$route["saku_wbp_monitor"] = 'Keluarga_inti/saku_wbp_monitor';
$route["profile_ki"] = 'Keluarga_inti/profile';
$route["simpan_profile_ki"] = 'Keluarga_inti/simpan_profile';
$route["simpan_username_ki"] = 'Keluarga_inti/simpan_username';
$route["simpan_password_ki"] = 'Keluarga_inti/simpan_password';
$route["warung_sipirman_ki"] = 'Keluarga_inti/warung_sipirman';
$route["simpan_belanja_ki"] = 'Keluarga_inti/simpan_belanja';
$route["data_barang_ki"] = 'Keluarga_inti/data_barang';
$route["status_belanja_ki"] = 'Keluarga_inti/status_belanja';
$route['rincian_saku_wbp/(:any)'] = 'Keluarga_inti/rincian_saku_wbp/$1';
$route['rincian_saku_wbp_tunai/(:any)'] = 'Keluarga_inti/rincian_saku_wbp_tunai/$1';

// KONFIRMASI BELANJA
$route["confirm_belanja"] = 'Confirm_belanja/home';
$route["proses_kasir/(:any)"] = 'Confirm_belanja/proses_kasir/$1';
$route["konfirmasi_belanja_selesai"] = 'Confirm_belanja/konfirmasi_belanja_selesai';

// DASHBOARD
$route['login_dashboard'] = 'My_control/login_dashboard';
$route['home_dashboard'] = 'My_control/home_dashboard';

// Route untuk Fitur Tagihan Rumbang Mart
$route['tagihan_rumbang_mart'] = 'my_control/tagihan_rumbang_mart';
$route['bayar_tagihan_rumbang'] = 'my_control/bayar_tagihan_rumbang';

// ==========================================
// SECRET ROUTE: LOGIN SAKU WBP
// ==========================================
$route['loginsaku'] = 'Loginsaku';          // Mengakses form login saku
$route['loginsaku/proses'] = 'Loginsaku/proses'; // Memproses form login saku