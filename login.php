<?php include('includes/header.php');

if(isset($_SESSION['loggedIn'])){
    header("Location: templates/index.php");
    exit();
}
?>

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
