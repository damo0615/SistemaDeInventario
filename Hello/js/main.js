   document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    closeBtn.parentNode.style.display = 'none';
                });
            }
        });
   src="https://cdn.tailwindcss.com"
   tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fffbf0',
                            100: '#fff7e0',
                            200: '#feecb8',
                            300: '#fde08f',
                            400: '#fdca4d',
                            500: '#fcb40b',
                            600: '#e3a20a',
                            700: '#bd8708',
                            800: '#976c07',
                            900: '#7b5805',
                        }
                    }
                }
            }
        }
        // Funci�n para alternar men� m�vil
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Event listeners cuando el DOM est� cargado
        document.addEventListener('DOMContentLoaded', () => {
            // Configurar bot�n de men� m�vil
            document.getElementById('mobile-menu-button').addEventListener('click', toggleMobileMenu);
            
            // Llenar la tabla con datos iniciales
            populateInventoryTable(inventoryData);
            
            // Configurar tema claro/oscuro
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
            }
            
            themeToggleBtn.addEventListener('click', function() {
                themeToggleLightIcon.classList.toggle('hidden');
                themeToggleDarkIcon.classList.toggle('hidden');
                
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
            
            // Configurar el modal para agregar productos
            const addProductBtn = document.getElementById('add-product-btn');
            const addProductModal = document.getElementById('add-product-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelAddBtn = document.getElementById('cancel-add');
            const saveProductBtn = document.getElementById('save-product');
            
            addProductBtn.addEventListener('click', () => {
                // Restaurar texto del bot�n a "Guardar" por si estaba en "Actualizar"
                saveProductBtn.textContent = 'Guardar Producto';
                saveProductBtn.onclick = addProduct;
                
                addProductModal.classList.remove('hidden');
            });
            
            closeModalBtn.addEventListener('click', () => {
                addProductModal.classList.add('hidden');
            });
            
            cancelAddBtn.addEventListener('click', () => {
                addProductModal.classList.add('hidden');
            });
            
            saveProductBtn.addEventListener('click', addProduct);
            
            // Configurar b�squeda de productos
            const searchProductInput = document.getElementById('search-product');
            searchProductInput.addEventListener('input', (e) => {
                filterProducts(e.target.value);
            });
            
            // Configurar ordenamiento de productos
            const sortBySelect = document.getElementById('sort-by');
            sortBySelect.addEventListener('change', (e) => {
                sortProducts(e.target.value);
            });
            
            // Cerrar modal al hacer clic fuera del contenido
            window.addEventListener('click', (e) => {
                if (e.target === addProductModal) {
                    addProductModal.classList.add('hidden');
                }
            });
        });