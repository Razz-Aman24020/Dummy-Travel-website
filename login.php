<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "config.php";

    $loginInput = trim($_POST['login'] ?? '');
    $password = trim($_POST['Password'] ?? '');

    if ($loginInput === '' || $password === '') {
        $message = "Wrong password, ID, phone number, or email.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM `user` WHERE `ID` = ? OR `Email` = ? OR `Phone` = ? LIMIT 1");
        $stmt->bind_param("sss", $loginInput, $loginInput, $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $user['Password'] === $password) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['user_phone'] = $user['Phone'];

            header("Location: dashboard.php");
            exit;
        }

        $message = "Wrong password, ID, phone number, or email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        :root {
            --navy-900: #071d35;
            --navy-800: #0b2d4f;
            --navy-700: #124d7b;
            --sky-500: #2d9cdb;
            --orange-500: #f59e0b;
            --orange-600: #df7d16;
            --ink: #17324d;
            --muted: #62748a;
            --line: #dfeaf4;
            --paper: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 30px 16px;
            color: var(--ink);
            background:
                linear-gradient(135deg, rgba(7, 29, 53, 0.96), rgba(18, 77, 123, 0.88)),
                radial-gradient(circle at top right, rgba(245, 158, 11, 0.7), transparent 25%),
                var(--navy-900);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        form {
            width: min(100%, 470px);
        }

        fieldset {
            margin: 0;
            padding: 34px clamp(22px, 6vw, 44px) 38px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.22);
        }

        .intro {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 0.96rem;
            line-height: 1.6;
            text-align: center;
        }

        .field {
            display: block;
            margin-top: 18px;
            color: var(--ink);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            margin-top: 8px;
            padding: 14px 15px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #f7fafc;
            font: 1rem "Segoe UI", sans-serif;
            transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }

        input:focus {
            outline: none;
            border-color: var(--sky-500);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(45, 156, 219, 0.12);
        }

        input[type="submit"] {
            margin-top: 28px;
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            cursor: pointer;
            font-weight: 800;
            letter-spacing: 0.03em;
            box-shadow: 0 15px 28px rgba(245, 158, 11, 0.25);
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        input[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(245, 158, 11, 0.32);
        }

        .error {
            margin: 0 0 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #b42318;
            background: #fff1f2;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="" method="post" autocomplete="off">
        <fieldset>
            <p class="intro">Login with your ID, email, or phone number.</p>

            <?php if ($message !== ""): ?>
                <p class="error"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <label class="field" for="login">ID / Email / Phone</label>
            <input id="login" type="text" name="login" placeholder="Enter your ID, email or phone number" required>

            <label class="field" for="password">Password</label>
            <input id="password" type="password" name="Password" placeholder="Enter your password" required>

            <input type="submit" value="Login">
        </fieldset>
    </form>
</body>
</html>