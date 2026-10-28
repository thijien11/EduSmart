    </div> <!-- Close container -->
    
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Auth System</h5>
                    <p>A secure PHP authentication system with login, registration, and user management features.</p>
                </div>
                <div class="col-md-3">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="../index.php" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="login.php" class="text-white text-decoration-none">Login</a></li>
                        <li><a href="signup.php" class="text-white text-decoration-none">Sign Up</a></li>
                        <li><a href="dashboard.php" class="text-white text-decoration-none">Dashboard</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> support@authsystem.com</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Auth System. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <?php if (isset($page_scripts)) echo $page_scripts; ?>
</body>
</html>