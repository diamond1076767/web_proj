<?php include('includes/header.php');

if(isset($_SESSION['loggedIn'])){
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    header("Location: templates/index.php");
}
?>

<style>
    body {
        background-color: #111;
        color: #f0f0f0;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background-color: #111;
    }

    .login-card {
        background-color: #1c1c1c;
        border: 1px solid #2e2e2e;
        border-radius: 16px;
        padding: 2.5rem 2.5rem;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);
    }

    .login-card .brand-icon {
        width: 52px;
        height: 52px;
        background-color: #2e2e2e;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .login-card .brand-icon svg {
        width: 26px;
        height: 26px;
        stroke: #f0f0f0;
    }

    .login-card h4 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .login-card .subtitle {
        font-size: 0.875rem;
        color: #888;
        margin-bottom: 2rem;
    }

    .login-card label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #aaa;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
        display: block;
    }

    .login-card .form-control {
        background-color: #262626;
        border: 1px solid #333;
        color: #f0f0f0;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .login-card .form-control:focus {
        background-color: #2a2a2a;
        border-color: #666;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.06);
        outline: none;
    }

    .login-card .form-control::placeholder {
        color: #555;
    }

    .login-card .btn-login {
        background-color: #f0f0f0;
        color: #111;
        border: none;
        border-radius: 8px;
        padding: 0.7rem;
        font-size: 0.95rem;
        font-weight: 600;
        width: 100%;
        margin-top: 0.5rem;
        transition: background-color 0.2s, transform 0.1s;
    }

    .login-card .btn-login:hover {
        background-color: #d8d8d8;
    }

    .login-card .btn-login:active {
        transform: scale(0.98);
    }

    .login-card .divider {
        border-top: 1px solid #2e2e2e;
        margin: 1.5rem 0;
    }

    /* Override alert colours for dark background */
    .alert {
        border-radius: 8px;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
    }

    .alert-danger {
        background-color: #2a1414;
        border-color: #5c2020;
        color: #f08080;
    }

    .alert-success {
        background-color: #122a18;
        border-color: #1e5c30;
        color: #80f0a0;
    }

    .alert-warning {
        background-color: #2a2010;
        border-color: #5c4510;
        color: #f0c060;
    }

    .alert-info {
        background-color: #101e2a;
        border-color: #103a5c;
        color: #60b0f0;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        <div class="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <h4>Welcome back</h4>
        <p class="subtitle">Sign in to your account to continue</p>

        <?php alertMessage(); ?>

        <form action="login-code.php" method="POST">

            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required />
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required />
            </div>

            <button type="submit" name="loginBtn" class="btn-login">
                Sign In
            </button>

        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>