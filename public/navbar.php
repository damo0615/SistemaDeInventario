<link rel="stylesheet" href="../style.css">
<nav class="bg-white border-gray-200 fixed w-full z-10 dark:bg-gray-900 shadow-md barra">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-boxes text-primary-500 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Inventory Pro</span>
                    </div>
  <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
      <button type="button" class="flex text-sm bg-primary-400 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
        <span class="sr-only">Open user menu</span>
        <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
</svg>


      </button>
      <!-- Dropdown menu -->
      <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
        <div class="px-4 py-3">
          <span class="block text-sm text-gray-900 dark:text-white"><?php echo $_SESSION['usern']; ?></span>
          <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">
            <?php 
                if ($_SESSION['rol'] == 1) {
                    echo 'Administrador';
                }elseif ($_SESSION['rol'] == 2) {
                    echo 'Supervisor';
                }elseif ($_SESSION['rol'] == 3) {
                    echo 'Operador';
                }
            ?>
              
          </span>
        </div>
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                    <?php 
                    if ($_SESSION['rol'] < 3) { ?>
                    <li>
                        <a href="\Proyecto\Hello\vistas\usuarios\alluser.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Todos los usuarios</a>
                    </li>
                      <?php } ?>
                      <li>
                        <a href="\Proyecto\Hello\vistas\usuarios\viewuser.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mi Perfil</a>
                      </li>
                    </ul>
                    <div class="py-1">
                        <a href="\Proyecto\Hello\sesion\logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-200 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Cerrar Sesion</a>
                    </div>
        
      </div>
      <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
      <li>
        <a href="\Proyecto\Hello\vistas\dashboard.php" class="block py-2 px-3 text-primary-500 bg-blue-700 rounded-sm md:bg-transparent md:text-primary-500 md:p-0 md:text-primary-500" aria-current="page">Dashboard</a>
      </li>
      <li>
        <a href="\Proyecto\Hello\vistas\caja.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-500 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Caja</a>
      </li>
      <li>
            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar1" class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary-500 md:p-0 md:w-auto dark:text-white md:dark:hover:text-primary-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Reportes <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg></button>
            <!-- Dropdown menu -->
            <div id="dropdownNavbar1" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                    <li>
                        <a href="\Proyecto\Hello\reportes.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Resumen</a>
                    </li>
                    <li>
                        <a href="\Proyecto\Hello\vistas\reportes\informes.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Movimientos de Cuentas</a>
                    </li>
                    <li>
                        <a href="\Proyecto\Hello\vistas\reportes\informes_stock.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Movimientos de Stock</a>
                    </li>
                </ul>
                <?php 
                if ($_SESSION['rol'] < 3) { ?>
                <div class="py-1">
                    <a href="\Proyecto\Hello\vistas\reportes\Bitacora.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Bitacora</a>
                </div>
                <?php } ?>
            </div>
        </li>
      
      <li>
        <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-500 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Clientes</a>
      </li>
      <li>
            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary-500 md:p-0 md:w-auto dark:text-white md:dark:hover:text-primary-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Inventario<svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg></button>
            <!-- Dropdown menu -->
            <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                  <li>
                    <a href="\Proyecto\Hello\vistas\inventario.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Productos</a>
                  </li>
                  <li>
                    <a href="\Proyecto\Hello\vistas\inventario\proveedores.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Proveedores</a>
                  </li>
                  <li>
                    <a href="\Proyecto\Hello\vistas\inventario\tags.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Etiquetas</a>
                  </li>
                </ul>
                <div class="py-1">
                  <a href="\Proyecto\Hello\vistas\inventario\stock.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Gestionar Stock</a>
                </div>
            </div>
        </li>
    </ul>
  </div>
  </div>
</nav>