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
        <h2>Scaricare CSV</h2>
        <form class="form-admin" method="POST" enctype="multipart/form-data">
            <label for="utente">Utente</label>
            <input type="text" id="utente" name="report[utente]">
            <label for="inizio">Data inizio</label>
            <input type="date" id="inizio" name="report[inizio]">
            <label for="fine">Data fine</label>
            <input type="date" id="fine" name="report[fine]">
            <input type="submit" value="Consultare registri">
        </form>
       
        <?php if ($_SERVER['REQUEST_METHOD']=='POST'):
            require_once "Utente.php";
            require_once "database.php";
            // debug($_POST['report']['utente']);
            if(isset($_REQUEST['report'])){

                $utente =  $_POST['report']['utente'];
                $inizio = empty($_POST['report']['inizio']) ? date('Y/m/d'):$_POST['report']['inizio'];
                $fine =  empty($_POST['report']['fine']) ? date('Y/m/d'):$_POST['report']['inizio'];
                Utente::setDB(collegareDB());
                $registros = Utente::consultarRegistros($utente, $inizio, $fine);
                
                

            }
            if (isset($_REQUEST['scarica'])){
                header('Location: /csv.php');               
                

            }
            ?>
            <?php if(isset($registros) && $registros): 
                $file = fopen('datosdescargables.csv', 'w');
                $table_head=['Id', 'Utente', 'Data e Ora', 'Tipo'];
                fputcsv($file, $table_head, ';');
                ?>
                <div class="tabla-contenidos">
                <table>
                    <thead>
                        <th>ID (registro)</th>
                        <th>Utente</th>
                        <th>Data e Ora</th>
                        <th>Tipo</th>
                    </thead>
                    <tbody>
            
                        <?php foreach ($registros as $row): ?>
                            <tr>
                                <td><?php echo $row->id ?></td>
                                <td><?php echo $row->utente ?></td>
                                <td><?php echo $row->ora ?></td>
                                <td><?php echo $row->estado? 'Acceso' : 'Uscita'; ?></td>
                            </tr>
                        <?php
                        fputcsv($file, [$row->id,$row->utente, $row->ora, $row->estado? 'Acceso' : 'Uscita'], ';');
                        endforeach;
                        fclose($file);
                        ?>
                    </tbody>
                </table>
                <?php $_GET['registros'] = $registros; ?>
            <form action="" method="POST">
                    <input type="hidden" name="scarica" value="true">
                    <input type="submit" value="Scaricare CSV">
            </form>

            </div>


            <?php else:
                $mensaje="Senza dati per l'utente nella data indicata";
            endif; ?>
            <?php                
        endif;
        ?> 
        
        <?php if (isset($mensaje)){
            echo "<p class='mensaje'>";
            echo $mensaje;
            echo '</p>';
            
        } 
        ?>
    
 
</body>
</html>