<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#f4f4f4;
        }

        .container{
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.2);
            width:300px;
        }

        input{
            width:100%;
            padding:10px;
            margin:8px 0;
            box-sizing:border-box;
        }

        button{
            width:100%;
            padding:10px;
            background:#007bff;
            color:white;
            border:none;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }

        .register{
            text-align:center;
            margin-top:15px;
        }

        a{
            text-decoration:none;
            color:#007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>

    <form action="process_login.php" method="POST">

        <input
            type="text"
            name="username_email"
            placeholder="Email atau Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <div class="register">
        New User?
        <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>