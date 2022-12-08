<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
        <h1>Sistema di rilevazione presenze per il personale aziendale</h1>
        <form class="form-utente" method="POST" enctype="multipart/form-data">
            <label for="utente">Inserire codice utente</label>
            <input type="text" id="utente" name="utente">
            <input type="submit" value="Inviare">
        </form>
       
        <?php if ($_SERVER['REQUEST_METHOD']=='POST'){
            require_once "Utente.php";
            require_once "database.php";

            Utente::setDB(collegareDB());
            //buscar el utente del formulario, y si existe, devuelve el ultimo registro del usuario
            $utente = Utente::lastInsertUser($_POST['utente']);

            //Si el usuario existe
            if ($utente){
                //Verificamos el estado del usuario (ingresado o no a la oficina)
                //Cargamos el saludo correspondiente
                //cambiamos el estado del usuario (dentro o fuera de la oficina)
                if ($utente->estado==0){
                    $mensaje = "Benvenuto";
                    $utente->estado=1;
                } else {
                    $mensaje = "Arrivederci";
                    $utente->estado=0;
                }
                //Cargamos en DB el registro del ingreso/egreso
                $utente->insertUserLog();
            } else {
                //Usuario no existe, o no hay registros
                $mensaje="Utente errato";
            }
            
            
                        
        }
        ?>
        <?php if (isset($mensaje)){
            echo "<p class='mensaje'>";
            echo $mensaje;
            echo '</p>';
            
        } 
        ?>
    
    
</body>
</html>