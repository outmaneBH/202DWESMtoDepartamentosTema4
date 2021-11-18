<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <title>OB - Editar Departamento</title>
        <style>
            span{
                color:red;
            }
            #t1{

                position: relative;
                left:  35%;
                top: 110px;
                border-top:  1px solid aqua;
                border-bottom:  1px solid aqua;
            }
            #t1 td{
                padding: 20px;
            }
            h2{
                text-align: center;
                font-weight: bold;
                text-decoration: underline;
            }
        </style>

    </head>
    <body>
        <?php
        /*
         * author: OUTMANE BOUHOU
         * Fecha: 18/11/2021
         * description: Page to update Departamento
         */
        /* usar la libreria de validacion */
        require_once '../core/210322ValidacionFormularios.php';

        /* Llamar al fichero de configuracion de base de datos */
        require '../config/confDBPDO.php';

        /* definir un variable constante obligatorio a 1 */
        define("OBLIGATORIO", 1);
        /* definir un variable constante opcional a 0 */
        define("OPCIONAL", 0);

        /* Variable de entrada correcta inicializada a true */
        $entradaOK = true;

        /* definir un array para alamcenar errores del codeDep,description y salary */
        $aErrores = [
            "description" => null,
            "salary" => null
        ];

        /* Array de respuestas inicializado a null */
        $aRespuestas = [
            "description" => null,
            "salary" => null
        ];

        if (isset($_REQUEST['cancelbtn'])) {
           header("Location:MtoDepartamentos.php");
            
        }


        /* comprobar si ha pulsado el button enviar */
        if (isset($_REQUEST['submitbtn'])) {
            //Para cada campo del formulario: Validamos la entrada y actuar en consecuencia
            //Validar entrada
      

            //Comprobar si el campo description  esta rellenado 
            $aErrores["description"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['description'], 1000, 1, OBLIGATORIO);

            //Comprobar si el campo salary  esta rellenado 
            $aErrores["salary"] = validacionFormularios::comprobarFloat($_REQUEST['salary'], 10000, 1, OBLIGATORIO);

            if (!$aErrores["codeDep"]) {
                /* comprobamos si el codigo existe en la base de datos */
                try {
                    /* Establecemos la connection con pdo en global */
                    $miDB = new PDO(HOST, USER, PASSWORD);

                    /* configurar las excepcion */
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "SELECT CodDepartamento from Departamento where CodDepartamento='" . $_REQUEST['codeDep'] . "'";
                    $resultadoConsulta = $miDB->query($sql);

                    /* Si existe mostramos el error que esta */
                    if ($resultadoConsulta->rowCount() > 0) {
                        $aErrores['codeDep'] = "Ya existe ese código";
                    }
                } catch (PDOException $exception) {
                    /* Si hay algun error el try muestra el error del codigo */
                    echo '<span> Codigo del Error :' . $exception->getCode() . '</span> <br>';

                    /* Muestramos su mensage de error */
                    echo '<span> Error :' . $exception->getMessage() . '</span> <br>';
                } finally {
                    /* Ceramos la connection */
                    unset($miDB);
                }
            }

            //recorrer el array de errores
            foreach ($aErrores as $nombreCampo => $value) {
                //Comprobar si el campo ha sido rellenado
                if ($value != null) {
                    $_REQUEST[$nombreCampo] = "";
                    // cuando encontremos un error
                    $entradaOK = false;
                }
            }
        } else {
            //El formulario no se ha rellenado nunca
            $entradaOK = false;
        }
        ?>
  <h2>Formulario de editar de departamentos por descripción </h2>
            <div>
                <table id="t1">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                        <tr>
                            <td><label>Codigo de Departamento   :</label></td>
                            <td><input type="text"  name="codeDep" value="<?php echo (isset($_REQUEST['codeDep']) ? $_REQUEST['codeDep'] : null); ?>"/></td>
                            <!--<td><span><?php echo ($aErrores["codeDep"] != null ? $aErrores['codeDep'] : null); ?></span></td>-->
                        </tr>

                        <tr>
                            <td><label>Descripción   :</label></td>
                            <td><input type="text"  name="description" value="<?php echo (isset($_REQUEST['description']) ? $_REQUEST['description'] : null); ?>"/></td>
                            <td><span><?php echo ($aErrores["description"] != null ? $aErrores['description'] : null); ?></span></td>
                        </tr>
                        <tr>
                            <td><label>Fecha de Baja   :</label></td>
                            <td><input type="text"  name="dateDown" value="<?php echo (isset($_REQUEST['dateDown']) ? $_REQUEST['dateDown'] : null); ?>"/></td>
                           <!-- <td><span><?php echo ($aErrores["dateDown"] != null ? $aErrores['dateDown'] : null); ?></span></td>-->
                        </tr>
                        <tr>
                            <td><label>Volumen de Negocio  :</label></td>
                            <td><input type="text"  name="salary" value="<?php echo (isset($_REQUEST['salary']) ? $_REQUEST['salary'] : null); ?>"/></td>
                            <td><span><?php echo ($aErrores["salary"] != null ? $aErrores['salary'] : null); ?></span></td>
                        </tr>
                        <tr> 
                            <td><input type="submit" class="w3-btn w3-teal" name="submitbtn" value="Aceptar"/></td>
                            <td><input type="submit" class="w3-btn w3-red" name="cancelbtn" value="Cancelar"/></td>
                        </tr>
                    </form>
                </table>

            </div>

    </body>
</html>

