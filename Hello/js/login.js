// Validación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Validación simple
            if (!email || !password) {
                alert('Por favor completa todos los campos');
                return;
            }
            
            // Aquí iría la lógica de autenticación real
            alert(`Inicio de sesión exitoso para: ${email}`);
            
            // Limpiar formulario
            this.reset();
        });