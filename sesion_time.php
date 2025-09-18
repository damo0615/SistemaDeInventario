<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <script type="text/javascript">
      n = 200;
      var id = window.setInterval(function(){
        document.onmousemove = function(){
          n = 200;
        };
        n--;
        if(n == 0){
          window.alert("La sesion fue cerrada por inactividad");
          window.location.assign("proyecto/hello/sesion/logout.php"); 
        }
      },1200);
    </script>
</body>
</html>