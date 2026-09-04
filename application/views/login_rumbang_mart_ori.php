<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal RUMBANG MART</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png'); ?>">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:      #0b1220;
            --ink-mid:  #1b2a45;
            --ink-soft: #2e4168;
            --gold:     #c8a84b;
            --gold-lt:  #e8ca7a;
            --cream:    #f8f5ef;
            --border:   rgba(200, 168, 75, 0.22);
            --muted:    #7a8ba8;
            --danger:   #c0392b;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            background: var(--cream);
        }

        /* ── LEFT PANEL ── */
        .panel-left {
            background-color: var(--ink);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 52px;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 80%, rgba(200,168,75,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 85% 10%, rgba(46,65,104,0.6) 0%, transparent 60%);
        }

        /* subtle diagonal lines texture */
        .panel-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                -55deg,
                transparent,
                transparent 40px,
                rgba(255,255,255,0.018) 40px,
                rgba(255,255,255,0.018) 41px
            );
        }

        .left-top { position: relative; z-index: 2; }
        .left-bottom { position: relative; z-index: 2; }

        .wordmark {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 64px;
        }
        .wordmark-icon {
            width: 42px; height: 42px;
            background: var(--gold);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .wordmark-icon .fa {
            font-size: 18px;
            color: var(--ink);
        }
        .wordmark-text {
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .left-headline {
            font-family: 'DM Serif Display', serif;
            font-size: 42px;
            line-height: 1.2;
            color: #ffffff;
            margin-bottom: 18px;
            font-weight: 400;
        }
        .left-headline em {
            font-style: italic;
            color: var(--gold-lt);
        }

        .left-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.45);
            line-height: 1.7;
            max-width: 320px;
            font-weight: 300;
        }

        .left-divider {
            width: 40px;
            height: 2px;
            background: var(--gold);
            border-radius: 2px;
            margin: 32px 0;
            opacity: 0.7;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255,255,255,0.5);
        }
        .feature-list li .dot {
            width: 5px; height: 5px;
            background: var(--gold);
            border-radius: 50%;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .left-year {
            font-size: 11px;
            color: rgba(255,255,255,0.18);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ── RIGHT PANEL ── */
        .panel-right {
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 52px 48px;
        }

        .form-shell {
            width: 100%;
            max-width: 380px;
        }

        .form-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }

        .form-title {
            font-family: 'DM Serif Display', serif;
            font-size: 30px;
            font-weight: 400;
            color: var(--ink);
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 36px;
            font-weight: 300;
        }

        /* Error */
        .alert-err {
            background: #fff5f5;
            border: 1px solid rgba(192,57,43,0.2);
            border-left: 3px solid var(--danger);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: var(--danger);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Field */
        .field { margin-bottom: 20px; }

        .field-label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--ink-soft);
            margin-bottom: 7px;
        }

        .field-wrap {
            position: relative;
        }
        .field-wrap .f-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #b0bec5;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }
        .field-wrap:focus-within .f-icon {
            color: var(--gold);
        }

        .f-input {
            width: 100%;
            height: 48px;
            background: #ffffff;
            border: 1.5px solid #dde3ec;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--ink);
            padding: 0 16px 0 42px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .f-input::placeholder { color: #c5cdd8; }
        .f-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(200,168,75,0.12);
        }

        /* Submit */
        .btn-primary-custom {
            width: 100%;
            height: 50px;
            background: var(--ink);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-primary-custom::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(200,168,75,0.15) 0%, transparent 55%);
            pointer-events: none;
        }
        .btn-primary-custom:hover {
            background: var(--ink-mid);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(11,18,32,0.2);
        }
        .btn-primary-custom:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .btn-primary-custom .fa {
            font-size: 13px;
            opacity: 0.7;
        }

        /* Gold accent bar under button */
        .gold-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-lt) 50%, transparent 100%);
            border-radius: 0 0 10px 10px;
            margin-top: -3px;
            opacity: 0.5;
        }

        /* Responsive — single column on small screens */
        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { padding: 40px 28px; }
        }
    </style>
</head>
<body>

    <!-- LEFT: Brand Panel -->
    <div class="panel-left">
        <div class="left-top">
            <div class="wordmark">
                <div class="wordmark-icon">
                    <i class="fa fa-shopping-basket"></i>
                </div>
                <span class="wordmark-text">Rumbang Mart</span>
            </div>

            <h1 class="left-headline">Kelola kasir<br>dengan <em>lebih<br>cerdas.</em></h1>
            <p class="left-desc">Platform manajemen kasir terpadu untuk operasional Kantin yang efisien dan terorganisir.</p>

            <div class="left-divider"></div>

            <ul class="feature-list">
                <li><span class="dot"></span> Manajemen transaksi WBP</li>
                <li><span class="dot"></span> Rekap penjualan otomatis</li>
                <li><span class="dot"></span> Manajemen data barang</li>
            </ul>
        </div>

        <div class="left-bottom">
            <p class="left-year">&copy; <?= date('Y') ?> Rumbang Mart</p>
        </div>
    </div>

    <!-- RIGHT: Login Form -->
    <div class="panel-right">
        <div class="form-shell">

            <p class="form-eyebrow">Portal Kasir</p>
            <h2 class="form-title">Selamat datang</h2>
            <p class="form-subtitle">Masuk untuk mengakses dasbor kasir Anda.</p>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert-err">
                    <i class="fa fa-exclamation-circle"></i>
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('logincashier/proses') ?>" method="POST">

                <div class="field">
                    <label class="field-label" for="username">USERNAME</label>
                    <div class="field-wrap">
                        <i class="fa fa-user f-icon"></i>
                        <input id="username" type="text" name="username" class="f-input"
                               placeholder="Masukkan USERNAME" required autofocus>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="field-wrap">
                        <i class="fa fa-lock f-icon"></i>
                        <input id="password" type="password" name="password" class="f-input"
                               placeholder="Masukkan kata sandi" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom">
                    Masuk ke Sistem <i class="fa fa-arrow-right"></i>
                </button>
                <div class="gold-bar"></div>

            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
</body>
</html>