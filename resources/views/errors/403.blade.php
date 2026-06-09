<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - eKinerja</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ef4444;
            --primary-hover: #dc2626;
            --bg-gradient-start: #0f0505;
            --bg-gradient-end: #1e0b0b;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Decorative glowing blobs */
        .blob {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
            filter: blur(50px);
            z-index: 0;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            animation: float 15s ease-in-out infinite;
        }

        .blob-2 {
            bottom: -10%;
            right: -10%;
            animation: float 18s ease-in-out infinite alternate;
        }

        @keyframes float {
            0% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(40px, -60px) scale(1.2); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        .container {
            text-align: center;
            z-index: 10;
            padding: 3rem 2rem;
            max-width: 550px;
            width: 90%;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 32px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.8),
                        inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .icon-container {
            margin-bottom: 1.5rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 96px;
            height: 96px;
            border-radius: 24px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            font-size: 3rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f87171 0%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: -0.03em;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #f8fafc;
            letter-spacing: -0.01em;
        }

        p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Access Warning Popup Alert Style */
        .access-alert {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 2rem;
            color: #fca5a5;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .timer-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.03);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            color: #e2e8f0;
        }

        .countdown-num {
            font-weight: 800;
            color: #ef4444;
            font-size: 1.25rem;
            min-width: 24px;
            display: inline-block;
            animation: tick 1s ease infinite;
        }

        @keyframes tick {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
            color: white;
            text-decoration: none;
            padding: 0.875rem 2.5rem;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(239, 68, 68, 0.6);
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        }

        .btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <div class="icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <div class="error-code">403</div>
        <h1>Akses Ditolak</h1>
        
        <div class="access-alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px; height:20px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            <span>Kamu tidak memiliki akses ke halaman ini!</span>
        </div>

        <div class="timer-box">
            <span>Kembali ke Landing Page dalam</span>
            <span id="countdown" class="countdown-num">10</span>
            <span>detik...</span>
        </div>

        <div>
            <a href="/" class="btn">Kembali Sekarang</a>
        </div>
    </div>

    <script>
        let seconds = 10;
        const countdownEl = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = '/';
            }
        }, 1000);
    </script>
</body>
</html>
