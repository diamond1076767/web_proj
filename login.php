<?php include('includes/header.php');

if(isset($_SESSION['loggedIn'])){
    $roleID = validate($_SESSION['loggedInUser']['roleID']);

        switch ($roleID) {
            case 1:
                header("Location: templates/index.php");
                exit();
            case 2:
                header("Location: templates/index.php");
                exit();
            case 3:
                header("Location: templates/index.php");
                exit();
    }
}
?>

<style>
.login-wrapper {
    min-height: calc(100vh - 56px);
    background: linear-gradient(135deg, #1a1f3a 0%, #2a3b6e 50%, #1a4f80 100%);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.login-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.35);
    overflow: hidden;
}

.login-brand {
    background: linear-gradient(135deg, #4e73df 0%, #36b9cc 100%);
    padding: 2.5rem 2rem;
    text-align: center;
    color: #fff;
}

.login-brand .brand-icon {
    font-size: 2.8rem;
    margin-bottom: 0.75rem;
    display: block;
    opacity: 0.92;
}

.login-brand h2 {
    font-weight: 800;
    font-size: 1.9rem;
    letter-spacing: 0.06em;
    margin-bottom: 0.3rem;
}

.login-brand p {
    opacity: 0.82;
    font-size: 0.88rem;
    margin: 0;
}

.login-body {
    padding: 2.25rem 2.25rem 1.75rem;
    background: #fff;
}

.login-body h4 {
    font-weight: 700;
    color: #1a1f3a;
    font-size: 1.2rem;
    margin-bottom: 0.35rem;
}

.login-body .login-subtitle {
    font-size: 0.83rem;
    color: #858796;
    margin-bottom: 1.75rem;
}

.login-body .form-label {
    font-weight: 600;
    font-size: 0.82rem;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.login-body .input-group-text {
    background: #f8f9fc;
    border: 1.5px solid #d1d7e0;
    border-right: none;
    color: #858796;
}

.login-body .form-control {
    border: 1.5px solid #d1d7e0;
    border-left: none;
    border-radius: 0 6px 6px 0;
    padding: 0.6rem 0.9rem;
    font-size: 0.9rem;
    tranion: border-color 0.15s, box-shadow 0.15s;
}

.login-body .form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
    outline: none;
}

.login-body .form-control:focus + .input-group-text,
.login-body .input-group:focus-within .input-group-text {
    border-color: #4e73df;
}

.btn-login {
    background: linear-gradient(135deg, #4e73df, #36b9cc);
    border: none;
    border-radius: 8px;
    padding: 0.72rem;
    font-weight: 600;
    font-size: 0.95rem;
    color: #fff;
    transition: all 0.2s;
    box-shadow: 0 4px 16px rgba(78, 115, 223, 0.35);
    width: 100%;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(78, 115, 223, 0.45);
    color: #fff;
}

.login-footer-bar {
    background: #f8f9fc;
    border-top: 1px solid #f0f2f8;
    padding: 0.9rem 2.25rem;
    text-align: center;
    color: #adb5bd;
    font-size: 0.78rem;
}
</style>

<div class="py-5 bg-light">
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card shadow rounded-4">
				<?php alertMessage();?>
				<div class="p-5">
					<h4 class="text-dark mb-3">Login</h4>
					<form action="login-code.php" method="POST" id="loginForm" novalidate> 

    					<div class="mb-3">
    						<label for="username">Username</label>
    						<input type="text" name="username" id="username" class="form-control" required aria-required="true" />
							<div class="invalid-feedback">Please enter your username.</div>
    					</div>

    					<div class="mb-3">
    						<label for="password">Password</label>
    						<input type="password" name="password" id="password" class="form-control" required aria-required="true" />
							<div class="invalid-feedback">Please enter your password.</div>
    					</div>
    					<div class="mb-3">
							<button type="submit" name="loginBtn" class="btn btn-primary w-100 mt-2">
							Sign In
							</button>
    					</div>
					</form>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Client-side validation for login form -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('loginForm');

    form.addEventListener('submit', function(event) {
        var isValid = true;

        form.querySelectorAll('.form-control').forEach(function(el) {
            el.classList.remove('is-invalid');
        });

        var username = document.getElementById('username');
        var password = document.getElementById('password');

        if (!username.value.trim()) {
            username.classList.add('is-invalid');
            isValid = false;
        }

        if (!password.value.trim()) {
            password.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });

    // Live: remove error on input
    form.querySelectorAll('.form-control').forEach(function(el) {
        el.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>

<?php include('includes/footer.php');?>