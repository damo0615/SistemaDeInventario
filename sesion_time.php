<body>
    <dialog id="alerta" class="alerta">
      <p>La sesion fue cerrada por inactividad</p>
      <section>
        <p>
          La sesion se cerrara en <span id="countdown"></span> segundos, pulse el boton si desea extenderla
        </p>
      </section>
      <button type="submit" onclick="extenderConfirm();" class="btn-glow w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition">Extender</button>
    </dialog>
    <script type="text/javascript">
      n = 100;
      var id = window.setInterval(function(){
        document.onmousemove = function(){
          n = 100;
        };
        n--;
        if(n == 0){
          window.alerta.showModal();
          extenderSesion();
        }
      },300);
      function extenderSesion(){ 
        let timeLeft = 30;

        const countdownElement = document.getElementById('countdown');

        // Inicia el temporizador
        const timerInterval = setInterval(() => {
            timeLeft--;

            countdownElement.textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                cerrarSesion();
            }
        }, 1000);
      }
      function extenderConfirm(){
        window.confirmar.close();
        location.reload();
      }
      function cerrarSesion(){
        window.confirmar.close();
        window.location.assign("/proyecto/hello/sesion/logout.php");
      }
    </script>
</body>