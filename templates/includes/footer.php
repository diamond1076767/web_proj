				</main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; INF1005 Lab P1-7 Project</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="assets/js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.mySelect2').select2();
            });
        </script>
        <script src ="assets/js/sweetalert.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
        <script src="assets/js/custom.js"></script>
        <?php $sessionTimeout = (int) getEnvValue('SESSION-TIMEOUT', 900000); ?>
        <script>
            let timeout;
            const inactivityTimeout = <?= json_encode($sessionTimeout) ?>;

            function resetTimer() {
                clearTimeout(timeout);
                timeout = setTimeout(logout, inactivityTimeout);
            }

            function logout() {
                window.location.href = '../logout.php?inactivity=true';
            }

            document.addEventListener('mousemove', resetTimer);
            document.addEventListener('keypress', resetTimer);

            resetTimer();
        </script>

    </body>
</html>
