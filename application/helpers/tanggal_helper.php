<?php
if(!function_exists('formatindo')){
      function formatindo($tanggal){
            $hari = date("D",strtotime($tanggal));
            switch($hari){
                  case 'Sun':
                        $dayindo = "Minggu";
                  break;
       
                  case 'Mon':			
                        $dayindo = "Senin";
                  break;
       
                  case 'Tue':
                        $dayindo = "Selasa";
                  break;
       
                  case 'Wed':
                        $dayindo = "Rabu";
                  break;
       
                  case 'Thu':
                        $dayindo = "Kamis";
                  break;
       
                  case 'Fri':
                        $dayindo = "Jumat";
                  break;
       
                  case 'Sat':
                        $dayindo = "Sabtu";
                  break;
                  
                  default:
                        $dayindo = "Tidak di ketahui";		
                  break;
            }
            
            $bulan = array (
                  1 =>   'Januari',
                  'Februari',
                  'Maret',
                  'April',
                  'Mei',
                  'Juni',
                  'Juli',
                  'Agustus',
                  'September',
                  'Oktober',
                  'November',
                  'Desember'
            );
            $pecahkan_tanggal = explode(' ',$tanggal);
            $pecahkan = explode('-', $pecahkan_tanggal[0]);
            $waktu_pecahan = explode(":",$pecahkan_tanggal[1]);
            $waktu = $waktu_pecahan[0].":".$waktu_pecahan[1];
            
            // variabel pecahkan 0 = tanggal
            // variabel pecahkan 1 = bulan
            // variabel pecahkan 2 = tahun
       
            return $dayindo."<br>".$pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0].'<br>'.$waktu;
      }
}
?>