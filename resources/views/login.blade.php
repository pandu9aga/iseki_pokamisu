<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Iseki Pokamisu</title>
    <link href="{{ asset('assets/fonts/nunito/fonts.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
    <style>
        :root { --pink: #d63384; --pink-hover: #c5277a; --pink-light: #fdf2f8; }
        #auth { background: #f8f9fc; }
        #auth #auth-left { padding: 4rem 6rem; display: flex; flex-direction: column; justify-content: center; }
        #auth #auth-left .auth-logo { margin-bottom: 3rem; }
        #auth #auth-left .auth-logo a { font-size: 1.8rem; font-weight: 800; color: var(--pink); text-decoration: none; letter-spacing: -0.5px; }
        #auth #auth-left .auth-title { font-size: 2.5rem; font-weight: 800; color: #252525; margin-bottom: 0.3rem; }
        #auth #auth-left .auth-subtitle { font-size: 1rem; line-height: 1.5rem; color: #8a8a8a; margin-bottom: 2rem; }
        #auth #auth-left .login-card { background: #fff; border-radius: 1rem; padding: 2rem 2.5rem; box-shadow: 0 5px 30px rgba(0,0,0,.05); border: 1px solid #f0f0f0; }
        #auth #auth-left .form-control { height: 3.2rem; border-radius: 0.6rem; border: 1.5px solid #e5e7eb; font-size: 0.95rem; padding-left: 3rem; transition: all 0.2s; background: #fafafa; }
        #auth #auth-left .form-control:focus { background: #fff; border-color: var(--pink); box-shadow: 0 0 0 3px rgba(214,51,132,.12); }
        #auth #auth-left .form-group { position: relative; }
        #auth #auth-left .form-control-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #adb5bd; font-size: 1.2rem; z-index: 10; }
        #auth #auth-left .btn-login { height: 3.2rem; border-radius: 0.6rem; font-size: 1rem; font-weight: 700; background: var(--pink); border: none; transition: all 0.25s; box-shadow: 0 4px 15px rgba(214,51,132,.3); }
        #auth #auth-left .btn-login:hover { background: var(--pink-hover); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(214,51,132,.4); }
        #auth #auth-left .btn-login:active { transform: translateY(0); }
        #auth #auth-left .alert-danger { border-radius: 0.6rem; font-size: 0.9rem; border: none; background: #fef2f2; color: #dc2626; padding: 0.8rem 1rem; }
        #auth #auth-left .alert-danger i { margin-right: 0.4rem; }
        #auth #auth-left .form-label { font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
        #auth #auth-right { background: linear-gradient(135deg, #d63384 0%, #a02060 50%, #6b1040 100%), url({{ asset('assets/images/bg/4853433.jpg') }}); background-size: cover; background-position: center; background-blend-mode: overlay; position: relative; overflow: hidden; }
        #auth #auth-right::before { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,.15); }
        #auth #auth-right .auth-overlay-text { position: absolute; bottom: 4rem; left: 3rem; right: 3rem; color: #fff; z-index: 1; }
        #auth #auth-right .auth-overlay-text h2 { font-size: 2rem; font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,.2); }
        #auth #auth-right .auth-overlay-text p { opacity: 0.85; font-size: 1rem; }
        @media screen and (max-width: 991px) { #auth #auth-left { padding: 3rem 2rem; } #auth #auth-left .login-card { padding: 1.5rem; } }
    </style>
    <!-- Dynamic Favicon -->
    <script src="/iseki_pro_app/js/dynamic-favicon.js"></script>
    <script>document.addEventListener("DOMContentLoaded", function() { setDynamicFavicon("assignment", "Pokamisu"); });</script>

    <!-- Dynamic Favicon Assets -->
    <link rel="stylesheet" href="/iseki_pro_app/css/icon.css">
    <script src="/iseki_pro_app/js/dynamic-favicon.js"></script>
    <script>document.addEventListener("DOMContentLoaded", function() { setDynamicFavicon("assignment", "Pokamisu"); });</script>
</head>
<body>
    <div id="auth">
        <div class="row h-100 g-0">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ route('login') }}">
                            <i class="bi bi-clipboard-data me-2"></i>Iseki Pokamisu
                        </a>
                    </div>
                    <h1 class="auth-title">Welcome Back</h1>
                    <p class="auth-subtitle">Masukkan username dan password untuk melanjutkan.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span>{{ $errors->first('login') }}</span>
                        </div>
                    @endif

                    <div class="login-card">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="form-group position-relative">
                                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                                    <div class="form-control-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="form-group position-relative">
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                    <div class="form-control-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-login w-100 mt-4 text-white">Log In</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block" id="auth-right">
                <div class="auth-overlay-text">
                    <h2>Iseki Pokamisu</h2>
                    <p>Sistem monitoring data pokamisu produksi</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
