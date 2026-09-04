<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBANG MART</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/rumbangmart-fav.png'); ?>">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root{
            --navy-900:#0b2f6b;
            --navy-800:#123f88;
            --navy-700:#1658c0;
            --blue:#1687ff;
            --gold:#ffbf1f;
            --orange:#ff7a00;
            --cream:#f6f8fc;
            --white:#ffffff;
            --text:#0f2242;
            --muted:#6e7f9f;
            --border:#dce6f5;
            --danger:#c0392b;
            --shadow:0 24px 60px rgba(11,47,107,.18);
        }

        html,body{
            height:100%;
            font-family:'DM Sans',sans-serif;
            background:linear-gradient(135deg,#edf4ff 0%, #f9fbff 50%, #fffaf2 100%);
            color:var(--text);
        }

        body{
            min-height:100vh;
            display:block;
            padding:0;
        }

        .page{
            width:100%;
            min-height:100vh;
            background:rgba(255,255,255,.78);
            overflow:hidden;
            display:grid;
            grid-template-columns: 1.1fr .9fr;
            backdrop-filter: blur(10px);
        }

        /* LEFT PANEL */
        .brand-panel{
            position:relative;
            overflow:hidden;
            padding:28px 38px 24px;
            background:
                radial-gradient(circle at 85% 20%, rgba(255,191,31,.28), transparent 22%),
                radial-gradient(circle at 72% 36%, rgba(255,122,0,.20), transparent 18%),
                linear-gradient(145deg, #0a2b61 0%, #0f4699 45%, #1169d7 100%);
            color:#fff;
        }

        .brand-panel::before{
            content:"";
            position:absolute;
            inset:0;
            background:
                linear-gradient(135deg, rgba(255,255,255,.08) 0%, transparent 26%),
                repeating-linear-gradient(-45deg, rgba(255,255,255,.05) 0 2px, transparent 2px 22px);
            opacity:.55;
            pointer-events:none;
        }

        .brand-panel::after{
            content:"";
            position:absolute;
            width:560px;
            height:560px;
            right:-160px;
            bottom:-220px;
            border-radius:50%;
            background:radial-gradient(circle, rgba(255,255,255,.16) 0%, rgba(255,255,255,.04) 45%, transparent 70%);
            filter: blur(4px);
        }

        .brand-inner{
            position:relative;
            z-index:2;
            height:100%;
            display:flex;
            flex-direction:column;
        }

        .topbar{
            display:flex;
            align-items:center;
            gap:14px;
            margin-bottom:20px;
        }

        .brand-badge{
            width:52px;
            height:52px;
            background:rgba(255,255,255,.12);
            border:1px solid rgba(255,255,255,.18);
            border-radius:18px;
            padding:8px;
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
            backdrop-filter: blur(8px);
        }

        .brand-badge img{
            width:100%;
            height:100%;
            object-fit:contain;
        }

        .brand-name{
            font-size:13px;
            letter-spacing:2.4px;
            text-transform:uppercase;
            font-weight:800;
            opacity:.95;
        }
        .brand-sub{
            margin-top:3px;
            font-size:11px;
            letter-spacing:1.1px;
            text-transform:uppercase;
            color:rgba(255,255,255,.72);
        }

        .hero{
            display:grid;
            grid-template-columns: 1.02fr .98fr;
            gap:20px;
            align-items:center;
            flex:1;
        }

        .hero-copy h1{
            font-size:38px;
            line-height:1.06;
            font-weight:800;
            margin-bottom:18px;
        }

        .hero-copy h1 .accent{
            display:block;
            color:#ffd24f;
            text-shadow:0 6px 24px rgba(255,191,31,.18);
        }

        .hero-copy p{
            max-width:440px;
            color:rgba(255,255,255,.82);
            font-size:14px;
            line-height:1.65;
            margin-bottom:18px;
        }

        .feature-grid{
            display:grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap:10px;
            max-width:520px;
        }

        .feature-card{
            background:rgba(255,255,255,.11);
            border:1px solid rgba(255,255,255,.14);
            border-radius:16px;
            padding:12px 12px 11px;
            backdrop-filter: blur(10px);
            min-height:78px;
        }

        .feature-icon{
            width:32px;
            height:32px;
            border-radius:12px;
            background:linear-gradient(135deg, var(--gold), var(--orange));
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:14px;
            margin-bottom:8px;
            box-shadow:0 10px 20px rgba(255,122,0,.28);
        }

        .feature-title{
            font-size:13px;
            font-weight:700;
            margin-bottom:3px;
        }

        .feature-desc{
            font-size:11.5px;
            line-height:1.4;
            color:rgba(255,255,255,.76);
        }

        .visual-wrap{
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .visual-card{
            width:100%;
            max-width:320px;
            background:rgba(255,255,255,.10);
            border:1px solid rgba(255,255,255,.16);
            border-radius:28px;
            padding:16px;
            box-shadow:0 20px 40px rgba(0,0,0,.16);
            backdrop-filter: blur(12px);
        }

        .visual-card img{
            width:100%;
            display:block;
            object-fit:contain;
            filter: drop-shadow(0 10px 26px rgba(0,0,0,.18));
        }

        .brand-footer{
            position:relative;
            z-index:2;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            padding-top:12px;
            border-top:1px solid rgba(255,255,255,.12);
            color:rgba(255,255,255,.72);
            font-size:12px;
        }

        /* RIGHT PANEL */
        .login-panel{
            position:relative;
            padding:30px 34px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:
                radial-gradient(circle at 15% 15%, rgba(22,135,255,.08), transparent 25%),
                radial-gradient(circle at 100% 0%, rgba(255,191,31,.12), transparent 28%),
                linear-gradient(180deg, rgba(255,255,255,.96), rgba(247,250,255,.96));
        }

        .login-shell{
            width:100%;
            max-width:430px;
        }

        .mobile-logo{
            display:none;
            text-align:center;
            margin-bottom:18px;
        }

        .mobile-logo img{
            width:88px;
            height:88px;
            object-fit:contain;
        }

        .eyebrow{
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-size:12px;
            font-weight:800;
            letter-spacing:1.8px;
            text-transform:uppercase;
            color:var(--navy-700);
            padding:8px 14px;
            border-radius:999px;
            background:rgba(22,88,192,.08);
            margin-bottom:18px;
        }

        .login-title{
            font-size:32px;
            line-height:1.08;
            font-weight:800;
            color:var(--navy-900);
            margin-bottom:10px;
        }

        .login-title .accent{
            color:var(--orange);
        }

        .login-subtitle{
            color:var(--muted);
            font-size:13px;
            line-height:1.65;
            margin-bottom:18px;
            max-width:360px;
        }

        .alert-err{
            background:#fff5f5;
            border:1px solid rgba(192,57,43,.16);
            border-left:4px solid var(--danger);
            color:var(--danger);
            border-radius:14px;
            padding:12px 14px;
            font-size:13px;
            margin-bottom:20px;
            display:flex;
            gap:10px;
            align-items:flex-start;
        }

        .login-card{
            background:var(--white);
            border:1px solid rgba(22,88,192,.10);
            border-radius:22px;
            padding:18px;
            box-shadow:0 16px 40px rgba(15,34,66,.08);
        }

        .field{
            margin-bottom:14px;
        }

        .field-label{
            display:block;
            font-size:12px;
            font-weight:800;
            letter-spacing:1px;
            text-transform:uppercase;
            color:var(--navy-800);
            margin-bottom:8px;
        }

        .field-wrap{
            position:relative;
        }

        .field-icon{
            position:absolute;
            left:16px;
            top:50%;
            transform:translateY(-50%);
            width:22px;
            text-align:center;
            color:#7f96bb;
            transition:.2s ease;
        }

        .f-input{
            width:100%;
            height:48px;
            border-radius:16px;
            border:1.5px solid var(--border);
            background:#fbfdff;
            padding:0 18px 0 48px;
            font-size:14px;
            color:var(--text);
            outline:none;
            transition:.2s ease;
        }

        .f-input::placeholder{ color:#aab8cf; }
        .field-wrap:focus-within .field-icon{ color:var(--navy-700); }
        .f-input:focus{
            border-color:var(--blue);
            background:#fff;
            box-shadow:0 0 0 4px rgba(22,135,255,.10);
        }

        .btn-login{
            width:100%;
            height:50px;
            border:none;
            border-radius:16px;
            cursor:pointer;
            color:#fff;
            font-size:15px;
            font-weight:800;
            letter-spacing:.4px;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            background:linear-gradient(135deg, var(--navy-900) 0%, var(--navy-700) 60%, var(--blue) 100%);
            box-shadow:0 18px 34px rgba(22,88,192,.26);
            transition:transform .15s ease, box-shadow .2s ease, filter .2s ease;
            position:relative;
            overflow:hidden;
        }

        .btn-login::before{
            content:"";
            position:absolute;
            inset:0;
            background:linear-gradient(120deg, transparent 20%, rgba(255,255,255,.18) 45%, transparent 70%);
            transform:translateX(-100%);
            transition:transform .6s ease;
        }

        .btn-login:hover{
            transform:translateY(-1px);
            box-shadow:0 22px 40px rgba(22,88,192,.30);
            filter:saturate(1.04);
        }

        .btn-login:hover::before{
            transform:translateX(100%);
        }

        .btn-login:active{ transform:translateY(0); }

        .login-note{
            margin-top:12px;
            font-size:11.5px;
            color:var(--muted);
            text-align:center;
            line-height:1.6;
        }

        .login-note strong{
            color:var(--navy-800);
        }

        @media (max-width: 1100px){
            .page{ grid-template-columns: 1fr; }
            .brand-panel{ padding-bottom:28px; }
            .hero{ grid-template-columns: 1fr; }
            .visual-wrap{ order:-1; }
            .visual-card{ max-width:320px; }
            .login-panel{ padding:36px 24px 42px; }
            .mobile-logo{ display:block; }
        }

        @media (max-width: 768px){
            .page{ min-height:100vh; }
            .brand-panel{ display:none; }
            .login-panel{ padding:26px 18px; }
            .login-title{ font-size:30px; }
            .login-card{ padding:20px; border-radius:20px; }
            .eyebrow{ font-size:11px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- LEFT BRAND / HERO -->
        <section class="brand-panel">
            <div class="brand-inner">
                <div class="topbar">
                    <div class="brand-badge">
                        <img src="<?= base_url('assets/img/rumbangmart-brand.png'); ?>" alt="Logo Rumbang Mart">
                    </div>
                    <div>
                        <div class="brand-name">Rumbang Mart</div>
                        <div class="brand-sub">Koperasi Pemasyarakatan Rutan Rembang</div>
                    </div>
                </div>

                <div class="hero">
                    <div class="hero-copy">
                        <h1>Kelola Belanja WBP Secara <span class="accent">Digital-Terintegrasi</span></h1>
                        <p>
                            Rumbang Mart terintegrasi dengan Aplikasi Saku WBP. Support pembayaran cashless, Menuju zero-uang kartal.
                        </p>

                        <div class="feature-grid">
                            <div class="feature-card">
                                <div class="feature-icon"><i class="fa fa-list-alt"></i></div>
                                <div class="feature-title">Daftar Barang Penjualan</div>
                                <div class="feature-desc">Menampilkan daftar, harga barang dan ketersediaan stok.</div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon"><i class="fa fa-money"></i></div>
                                <div class="feature-title">Pembayaran Cashless</div>
                                <div class="feature-desc">Pembayaran belanja WBP akan mengurangi saldo uang WBP pada Aplikasi Saku WBP.</div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon"><i class="fa fa-bar-chart"></i></div>
                                <div class="feature-title">Monitoring & Rekap</div>
                                <div class="feature-desc">Mendukung pemantauan penjualan, mutasi saldo, dan laporan transaksi.</div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon"><i class="fa fa-lock"></i></div>
                                <div class="feature-title">Security</div>
                                <div class="feature-desc">Autentifikasi pembayaran menggunakan PIN Saku WBP & unggah foto. Update PIN melalui Petugas Registrasi.</div>
                            </div>
                        </div>
                    </div>

                    <div class="visual-wrap">
                        <div class="visual-card">
                            <img src="<?= base_url('assets/img/rumbangmart-logo.png'); ?>" alt="Rumbang Mart">
                        </div>
                    </div>
                </div>

                <div class="brand-footer">
                    <span>&copy; <?= date('Y') ?> RUMBANG MART</span>
                    <span>Digitalisasi Toko Koperasi Pemasyarakatan Rutan Rembang</span>
                </div>
            </div>
        </section>

        <!-- RIGHT LOGIN -->
        <section class="login-panel">
            <div class="login-shell">
                <div class="mobile-logo">
                    <img src="<?= base_url('assets/img/rumbangmart-logo.png'); ?>" alt="Rumbang Mart">
                </div>

                <div class="eyebrow"><i class="fa fa-lock"></i> Login</div>
                <h2 class="login-title">Masuk ke <span class="accent">Rumbang Mart</span></h2>
                <p class="login-subtitle">
                    Pengelolaan akun melalui Aplikasi SIPIRMAN. Silahkan hubungi Admin SIPIRMAN jika belum memiliki akun.
                </p>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert-err">
                        <i class="fa fa-exclamation-circle"></i>
                        <div><?= $this->session->flashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <div class="login-card">
                    <form action="<?= base_url('logincashier/proses') ?>" method="POST">
                        <div class="field">
                            <label class="field-label" for="username">Username</label>
                            <div class="field-wrap">
                                <i class="fa fa-user field-icon"></i>
                                <input id="username" type="text" name="username" class="f-input" placeholder="Masukkan username" required autofocus>
                            </div>
                        </div>

                        <div class="field">
                            <label class="field-label" for="password">Password</label>
                            <div class="field-wrap">
                                <i class="fa fa-lock field-icon"></i>
                                <input id="password" type="password" name="password" class="f-input" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-login">
                            <span>Masuk ke Sistem</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
</body>
</html>
