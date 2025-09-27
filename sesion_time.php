<body>
    <dialog id="alerta" class="alerta">
      <p>La sesion fue cerrada por inactividad</p>
      <button type="submit" onclick="cerrarSesion();" class="btn-glow w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition">Aceptar</button>
    </dialog>
    <script type="text/javascript">
      n = 100;
      var id = window.setInterval(function(){
        document.onmousemove = function(){
          n = 100;
        };
        n--;
        if(n == 0){
          window.alerta.showModal() 
        }
      },1800);
      function cerrarSesion(){
        window.confirmar.close();
        window.location.assign("/proyecto/hello/sesion/logout.php");
      }
    </script>
</body>