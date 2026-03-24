<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRM Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }

        /* LEFT PANEL */
        .left-panel {
            width: 55%;
            background: linear-gradient(135deg, #1d4ed8 0%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            bottom: -80px;
            right: -80px;
        }

        .left-panel .brand-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        .left-panel h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }

        .left-panel p {
            color: rgba(255,255,255,0.65);
            font-size: 15px;
            text-align: center;
            max-width: 320px;
            line-height: 1.7;
        }

        .features {
            margin-top: 40px;
            width: 100%;
            max-width: 320px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        .feature-item:last-child { border-bottom: none; }

        .feature-item i {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #60a5fa;
            flex-shrink: 0;
        }

        /* RIGHT PANEL */
        .right-panel {
            width: 45%;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 50px;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-box h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .login-box .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 36px;
        }

        .form-label {
            font-weight: 600;
            font-size: 12.5px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: #ffffff;
        }

        .form-control:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
            outline: none;
        }

        .input-group-text {
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f8fafc;
            border-radius: 10px 0 0 10px;
            color: #94a3b8;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: #1d4ed8;
        }

        .btn-login {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(29,78,216,0.35);
            background: linear-gradient(135deg, #1e40af, #2563eb);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-box {
            background: #fee2e2;
            color: #dc2626;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .divider {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            margin: 24px 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .role-badges {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .role-badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .role-badge.admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .role-badge.counselor {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .footer-text {
            margin-top: 40px;
            color: #94a3b8;
            font-size: 12px;
            text-align: center;
        }

        /* Loading spinner on submit */
        .btn-login .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <div class="brand-icon">🎓</div>
        <h1>Student Admission CRM</h1>
        <p>Manage your leads, track follow-ups, and convert students to admissions — all in one place.</p>

        <div class="features">
            <div class="feature-item">
                <i class="fa-solid fa-users"></i>
                <span>Manage & assign student leads</span>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-phone"></i>
                <span>Track all calls and follow-ups</span>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Convert leads to admissions</span>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Analytics and performance reports</span>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <div class="login-box">

            <h2>Welcome Back 👋</h2>
            <p class="subtitle">Sign in to your CRM account to continue</p>

            <?php if(isset($_GET['error'])){ ?>
            <div class="error-box">
                <i class="fa-solid fa-circle-xmark"></i>
                <?php
                if($_GET['error'] == 'invalid') echo "Invalid email or password. Please try again.";
                ?>
            </div>
            <?php } ?>

            <form action="login_process.php" method="POST" onsubmit="showLoading()">

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="your@email.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Enter your password" required>
                        <span class="input-group-text" style="border-left:none; border-radius:0 10px 10px 0; cursor:pointer; border:1.5px solid #e2e8f0; border-left:none;" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <span class="spinner" id="spinner"></span>
                    <span id="btnText"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</span>
                </button>

            </form>

            <div class="divider">Login access for</div>

            <div class="role-badges">
                <div class="role-badge admin">
                    <i class="fa-solid fa-shield-halved"></i> Admin
                </div>
                <div class="role-badge counselor">
                    <i class="fa-solid fa-user-tie"></i> Counselor
                </div>
            </div>

            <div class="footer-text">
                Student Admission CRM System &copy; <?php echo date('Y'); ?>
            </div>

        </div>
    </div>

<script>
function togglePassword(){
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if(input.type === 'password'){
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function showLoading(){
    document.getElementById('spinner').style.display = 'inline-block';
    document.getElementById('btnText').textContent = 'Signing in...';
}
</script>

</body>
</html>