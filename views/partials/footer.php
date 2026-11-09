 <!-- Footer -->
    <footer class="bg-white border-top p-4 mt-auto">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="mb-2 mb-md-0">
            </div>
            <div class="d-flex gap-3 fw-medium" style="font-size: 0.9rem;">
                <span class="text-muted fw-medium" style="font-size: 0.9rem;"> Developed by H.L.P.L.Perera.</span> 
            </div>
        </div>
    </footer>    
</div>

<script>    
    (() => {
    'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
        }, false)
    })
    })()
</script>

<!-- Bootstrap 5.1.3 JS -->
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>
</body>
</html>     