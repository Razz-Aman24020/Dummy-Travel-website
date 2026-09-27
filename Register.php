<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        * {
            box-sizing: border-box;
        }

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

        legend {
            width: 100%;
            margin-bottom: 8px;
            color: var(--navy-800);
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            font-weight: 800;
            text-align: center;
        }

        .intro {
            margin: 0 0 28px;
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

        input::placeholder {
            color: #9aabc0;
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
            transition: transform 160ms ease, box-shadow 160ms ease;
            box-shadow: 0 15px 28px rgba(245, 158, 11, 0.25);
        }

        input[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(245, 158, 11, 0.32);
        }

        input[type="submit"]:active {
            transform: translateY(0);
        }

        @media (max-width: 420px) {
            body {
                padding: 18px 12px;
            }

            fieldset {
                padding: 28px 18px 30px;
            }
        }
    </style>
</head>
<body>
    <form action="" method="post" autocomplete="off">
        <fieldset>
           
            <p class="intro">Create your account and start planning your next journey.</p>

            <label class="field" for="traveler-id">Name or ID</label>
            <input id="traveler-id" type="text" placeholder="Enter your name or ID" name="ID" required autocomplete="off">

            <label class="field" for="email">Email address</label>
            <input id="email" type="email" placeholder="you@example.com" name="Email" required autocomplete="off">

            <label class="field" for="password">Password</label>
            <input id="password" type="password" placeholder="Create a password" name="Password" required autocomplete="off">

            <label class="field" for="phone">Phone number</label>
            <input id="phone" type="tel" placeholder="Enter your phone number" name="Phone" required autocomplete="off">

            <input type="submit" value="Create account">
        </fieldset>



    </form> 
</body>
</html>
<?php 
  if($_SERVER["REQUEST_METHOD"]=="POST")
  {
    include("config.php"); 
 
if(isset($_REQUEST['ID'])){ 

    $name = $_REQUEST['ID']; 
    $Email = $_REQUEST['Email']; 
    $Password = $_REQUEST['Password']; 
    $Phone = $_REQUEST['Phone']; 

    $data = $conn->prepare(
        "INSERT INTO `user` (`ID`, `Email`, `Password`, `Phone`) 
         VALUES ('$name', '$Email', '$Password', '$Phone')"
    ); 

    $show = $data->execute(); 
}
  }
    // if($show){ 
    //     echo "<br>";
    //     echo "Insertion successful"; 
    // } 
    // else{ 
    //     echo "Insertion unsuccessful: " . $data->error;
    // } 
// }


?>