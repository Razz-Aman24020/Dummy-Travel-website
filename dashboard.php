<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        :root {
            --navy-900: #071d35;
            --navy-800: #0b2d4f;
            --navy-700: #124d7b;
            --sky-500: #2d9cdb;
            --orange-500: #f59e0b;
            --orange-600: #df7d16;
            --text: #17324d;
            --muted: #62748a;
            --line: #dfeaf4;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px 14px;
            background:
                linear-gradient(135deg, rgba(7, 29, 53, 0.96), rgba(18, 77, 123, 0.88)),
                radial-gradient(circle at top right, rgba(245, 158, 11, 0.8), transparent 28%),
                var(--navy-900);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }

        .card {
            width: min(92%, 720px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 28px;
            padding: clamp(28px, 4vw, 42px);
            text-align: center;
            box-shadow: 0 30px 70px rgba(0,0,0,0.25);
            backdrop-filter: blur(8px);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #f8c97d;
            font-size: clamp(2.1rem, 4vw, 3rem);
        }

        .card p {
            margin: 12px 0;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin: 26px 0 18px;
            text-align: left;
        }

        .info-box {
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.14);
        }

        .info-box strong {
            display: block;
            margin-bottom: 6px;
            color: #f8c97d;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
        }

        .actions a {
            display: inline-block;
            padding: 12px 22px;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            color: #fff;
            text-decoration: none;
            border-radius: 999px;
            font-weight: 700;
            box-shadow: 0 16px 28px rgba(245, 158, 11, 0.22);
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .actions a:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Welcome</h1>
        <p>You have successfully logged in.</p>

        <div class="card-grid">
            <div class="info-box"><strong>User ID</strong><?php echo htmlspecialchars($_SESSION['user_id']); ?></div>
            <div class="info-box"><strong>Email</strong><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></div>
            <div class="info-box"><strong>Phone</strong><?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?></div>
        </div>

        <div class="actions">
            <a href="trip.php">Book a Trip</a>
            <a href="login.php?logout=1">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>