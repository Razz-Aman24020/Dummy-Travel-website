<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razz Travels</title>
    <style>
        :root {
            --navy-900: #071d35;
            --navy-800: #0b2d4f;
            --navy-700: #124d7b;
            --sky-500: #2d9cdb;
            --orange-500: #f59e0b;
            --orange-600: #df7d16;
            --white: #ffffff;
            --text: #17324d;
            --muted: #5e7085;
            --glass: rgba(255, 255, 255, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
            background: url('oip.jpg');
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            
        }

        .hero {
            width: min(88%, 980px);
            padding: 42px 28px 34px;
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            text-align: center;
        }

        #logo {
            display: block;
            width: min(280px, 70%);
            height: auto;
            margin: 0 auto 18px;
            border-radius: 24px;
            opacity: 0.96;
            animation: rotate 2.2s linear infinite;
            transition-duration: 1s;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.18);
        }

        h1 {
            margin: 0;
            color: #f8c97d;
            font-size: clamp(2.3rem, 5vw, 4.2rem);
            letter-spacing: 0.04em;
            text-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 14px;
            margin-top: 28px;
        }

        .bt {
            border: 0;
            border-radius: 999px;
            padding: 14px 26px;
            font-size: 1rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--sky-500), var(--navy-700));
            color: var(--white);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            box-shadow: 0 12px 24px rgba(45, 156, 219, 0.28);
        }

        .bt:hover {
            transform: translateY(-2px);
            filter: brightness(1.06);
            box-shadow: 0 18px 28px rgba(45, 156, 219, 0.35);
        }

        @keyframes rotate {
            0% {
                transform: rotateY(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <img src="logo.png" id="logo" width="250px" height="120px" alt="Razz Travels logo">

        <h1>Razz Tours and Travels</h1>

        <div class="hero-actions">
            <button class="bt" onclick="window.location.href='Register.php'">Register</button>
            <button class="bt" onclick="window.location.href='login.php'">Login</button>
            <button class="bt" onclick="window.location.href='trip.php'">Book a Trip</button>
        </div>
    </div>
    <script>
        
    </script>
</body>
</html>