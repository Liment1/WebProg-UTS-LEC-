<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        body {
            background-image: url('gambar1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-form h1 {
            margin-bottom: 20px;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
        }

        .login-form input[type="email"],
        .login-form input[type="password"] {
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .login-form button {
            background-color: #007bff;
            color: white;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }

        .login-form a {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #007bff;
        }

        .login-form a:hover {
            text-decoration: none;
        }

        .login-form p {
            text-align: center;
            margin-top: 15px;
        }

    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-form" id="loginForm">
            <h1>Log In</h1>
            <form id="signinForm"  onsubmit="handleSignIn(event)" action="index.php" method="POST">
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email"required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
                <a href="#">Forgot Your Password?</a>
            </form>
            <p>Don't have an account? <a href="register.php" id="showSignup">Sign Up</a></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const showSignupBtn = document.getElementById('showSignup');
        const showLoginBtn = document.getElementById('showLogin');

        function handleSignIn(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Success!',
                text: 'You have successfully signed in!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#02a152',
                timer: 20000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = 'index.php';
            });
        }

        function handleSignUp(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Success!',
                text: 'Your account has been created successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#02a152'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('signinForm').submit();
                }
            });
        }
    </script>
</body>

</html>
