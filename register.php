<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
            background:#28a745;
            color:white;
            border:none;
            cursor:pointer;
        }

        button:hover{
            background:#218838;
        }

        .login{
            text-align:center;
            margin-top:15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>

    <form action="proses/process_register.php" method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">
            Register
        </button>

    </form>

    <div class="login">
        Already have an account?
        <a href="login.php">Login</a>
    </div>
</div>

</body>
</html>