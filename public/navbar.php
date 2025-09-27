<link rel="stylesheet" href="../style.css">
<body class="bg-gray-100 fixed min-w-full z-10">
  <nav class="bg-white text-yellow-600 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        
        <!-- Logo -->
        <div class="flex items-center space-x-2">
          <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2H3V4zm0 4h16v8a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm2 2a1 1 0 000 2h10a1 1 0 100-2H5z" />
          </svg>
          <span class="text-xl font-bold">Inventario Pro</span>
        </div>

        <!-- Menú Principal -->
        <div class="hidden md:flex space-x-6 items-center">
          <a href="\Proyecto\Hello\vistas\Dashboard.php" class="hover:text-yellow-700">Dashboard</a>
          <a href="\Proyecto\Hello\vistas\caja.php" class="hover:text-yellow-700">Caja</a>
          <a href="\Proyecto\Hello\vistas\clientes.php" class="hover:text-yellow-700">Cliente</a>

          <!-- Inventario Dropdown -->
          <div class="relative">
            <button onclick="toggleDropdown('inventarioDropdown')" class="hover:text-yellow-700 focus:outline-none">Inventario</button>
            <div id="inventarioDropdown" class="hidden absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-10">
              <a href="\Proyecto\Hello\vistas\inventario.php" class="block px-4 py-2 hover:bg-yellow-100">Productos</a>
              <a href="\Proyecto\Hello\vistas\inventario\Proveedores.php" class="block px-4 py-2 hover:bg-yellow-100">Proveedores</a>
              <a href="\Proyecto\Hello\vistas\inventario\tags.php" class="block px-4 py-2 hover:bg-yellow-100">Etiquetas</a>
              <a href="\Proyecto\Hello\vistas\inventario\stock.php" class="block px-4 py-2 hover:bg-yellow-100">Gestión de Stock</a>
            </div>
          </div>

          <!-- Reportes Dropdown -->
          <div class="relative">
            <button onclick="toggleDropdown('reportesDropdown')" class="hover:text-yellow-700 focus:outline-none">Reportes</button>
            <div id="reportesDropdown" class="hidden absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-10">
              <a href="\Proyecto\Hello\vistas\reporte.php" class="block px-4 py-2 hover:bg-yellow-100">Resumen</a>
              <a href="\Proyecto\Hello\vistas\reportes\informes.php" class="block px-4 py-2 hover:bg-yellow-100">Ventas</a>
              <a href="\Proyecto\Hello\vistas\reportes\informes_stock.php" class="block px-4 py-2 hover:bg-yellow-100">Movimientos Stock</a>
              <?php 
                if ($_SESSION['rol'] < 3) {
                  echo '<a href="\Proyecto\Hello\vistas\reportes\bitacora.php" class="block px-4 py-2 hover:bg-yellow-100">Bitacora</a>';
                }
              ?>
            </div>
          </div>
        </div>

        <!-- Usuario Dropdown -->
        <div class="relative">
          <button onclick="toggleDropdown('userDropdown')" class="flex items-center space-x-2 focus:outline-none">
            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 7a6 6 0 1112 0H4z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div id="userDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-md py-2 z-10">
            <div class="px-4 py-2 text-sm text-gray-700 border-b">
              <div class="font-bold"><?php echo $_SESSION['usern']; ?></div>
              <div class="text-gray-500">
                <?php 
                  if ($_SESSION['rol'] == 3) {
                    echo 'Operador';
                  }elseif ($_SESSION['rol'] == 2) {
                    echo 'Supervisor';
                  }elseif ($_SESSION['rol'] == 1) {
                    echo 'Administrador';
                  }
                ?> 
               </div>
            </div>
            <?php 
                if ($_SESSION['rol'] < 3) {
                  echo '<a href="\Proyecto\Hello\vistas\usuarios\alluser.php" class="block px-4 py-2 hover:bg-yellow-100">Ver todos los usuarios</a>';
                }
              ?>
            <a href="\Proyecto\Hello\vistas\usuarios\viewuser.php" class="block px-4 py-2 hover:bg-yellow-100">Mi perfil</a>
            <a onclick="window.confirmar.showModal();" href="#" class="block px-4 py-2 hover:bg-yellow-100 text-red-600">Cerrar sesión</a>
          </div>
        </div>

      </div>
    </div>
  </nav>
<dialog id="confirmar" class="confirmar">
  <div class=" md:items-center md:justify-between mb-6">
    <p>Seguro que quiere cerrar sesion?</p>
    <button onclick='window.confirmar.close();' class="ml-3 float-right inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">No</button>
    <a href="\Proyecto\Hello\sesion\logout.php"><button class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Si</button></a>
  </div>
</dialog>

  <!-- Script para toggle -->
  <script>
    function toggleDropdown(id) {
      const dropdown = document.getElementById(id);
      const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');

      // Cierra todos los demás dropdowns
      allDropdowns.forEach(el => {
        if (el.id !== id) el.classList.add('hidden');
      });

      // Toggle actual
      dropdown.classList.toggle('hidden');
    }

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function (event) {
      const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
      dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target) && !dropdown.previousElementSibling.contains(event.target)) {
          dropdown.classList.add('hidden');
        }
      });
    });
  </script>