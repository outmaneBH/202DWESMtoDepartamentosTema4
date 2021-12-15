<?php
/*
 * author: OUTMANE BOUHOU
 * Fecha: 09/11/2021
 * description: 3.Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.
 */

if (isset($_REQUEST['cancelbtn'])) {
    header("Location:MtoDepartamentos.php");
}

/* usar la libreria de validacion */
require_once '../core/210322ValidacionFormularios.php';

/* Llamar al fichero de configuracion de base de datos */
require_once '../config/confDBPDO.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>OB-Añadir Departamento</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
     
        <style>
            #t1{
                position: relative;
                left:  30%;
                top: 110px;
                border-top:  1px solid aqua;
                border-bottom:  1px solid aqua;
            }
            #t1 td{
                padding: 20px;
            }
            span{
                color: red;
            }
            .w3-btn{
                width: 105px;
                font-size: small;
            }
            #div2{
                padding: 20px;
            }
            #t2 {
                text-align: center;
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
        /* definir un variable constante obligatorio a 1 */
        define("OBLIGATORIO", 1);

        /* Variable de entrada correcta inicializada a true */
        $entradaOK = true;

        /* definir un array para alamcenar errores del codeDep,description y salary */
        $aErrores = ["codeDep" => null,
            "description" => null,
            "salary" => null
        ];

        /* Array de respuestas inicializado a null */
        $aRespuestas = ["codeDep" => null,
            "description" => null,
            "salary" => null
        ];



        /* comprobar si ha pulsado el button añadir */
        if (isset($_REQUEST['submitbtn'])) {
            //Para cada campo del formulario: Validamos la entrada y actuar en consecuencia
            //Validar entrada
            //Comprobar si el campo codeDep esta rellenado
            $aErrores["codeDep"] = validacionFormularios::comprobarAlfabetico($_REQUEST['codeDep'], 3, 3, OBLIGATORIO);

            //Comprobar si el campo description  esta rellenado 
            $aErrores["description"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['description'], 1000, 10, OBLIGATORIO);

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
        if ($entradaOK) {
            //Tratamiento del formulario - Tratamiento de datos OK
            /* almacenamos los datos correctos */
            $aRespuestas = [
                "codeDep" => $_REQUEST['codeDep'],
                "description" => $_REQUEST['description'],
                "salary" => $_REQUEST['salary']
            ];

            try {
                /* Establecemos la connection con pdo en global */
                $miDB = new PDO(HOST, USER, PASSWORD);

                /* configurar las excepcion */
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                /* insertamos los valores que hemos cogido desde el formolario */
                $insert = ' INSERT INTO Departamento(CodDepartamento,DescDepartamento,FechaBaja,VolumenNegocio) VALUES ("' . $aRespuestas['codeDep'] . '","' . $aRespuestas['description'] . '", null,"' . $aRespuestas['salary'] . '")';
                $numRegistros = $miDB->exec($insert);

                header("Location:MtoDepartamentos.php");
            } catch (PDOException $exception) {
                /* Si hay algun error el try muestra el error del codigo */
                echo '<span> Codigo del Error :' . $exception->getCode() . '</span> <br>';

                /* Muestramos su mensage de error */
                echo '<span> Error :' . $exception->getMessage() . '</span> <br>';
            } finally {
                /* Ceramos la connection */
                unset($miDB);
            }
        } else {
            //Mostrar el formulario hasta que lo rellenemos correctamente
            //Mostrar formulario
            ?>
            <h2> Formulario para añadir un departamento</h2>
            <div>
                <table id="t1">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                        <tr>
                            <!--El campo codeDep obligatorio -->
                            <td><label>Código del Departamento (*)   :</label></td>
                            <td> <input type="text" name="codeDep" value="<?php echo (isset($_REQUEST['codeDep']) ? $_REQUEST['codeDep'] : null); ?>"/></td>
                            <td><span><?php echo ($aErrores["codeDep"] != null ? $aErrores['codeDep'] : null); ?></span></td>
                        </tr>

                        <!--El campo description obligatorio -->
                        <tr>
                            <td><label>Descripción(*)   :</label></td>
                            <td><input type="text"  name="description" value="<?php echo (isset($_REQUEST['description']) ? $_REQUEST['description'] : null); ?>"/></td>
                            <td><span><?php echo ($aErrores["description"] != null ? $aErrores['description'] : null); ?></span></td>
                        </tr>

                        <!--El campo salary -->
                        <tr>
                            <td> <label>Volumen del negocio (*) :</label></td>
                            <td> <input type="text"  name="salary" value="<?php echo (isset($_REQUEST['salary']) ? $_REQUEST['salary'] : null); ?>"/></td>
                            <td> <span><?php echo ($aErrores["salary"] != null ? $aErrores['salary'] : null); ?></span></td>
                        </tr>

                        <tr> 

                            <td><input type="submit" class="w3-btn w3-green" name="submitbtn" value="Aceptar"/></td>
                            <td><input type="submit" class="w3-btn w3-teal" name="cancelbtn" value="Cancelar"/></td>
                        </tr>
                    </form>
                </table>

            </div>
            <?php
        }
        ?>
<footer style="position: fixed;bottom: 0;width: 100%" class="bg-dark text-center text-white">
                <!-- Grid container -->
                <div class="container p-3 pb-0">
                    <!-- Section: Social media -->
                    <section class="mb-3">
                        <!-- Github -->
                        <a class="btn btn-outline-light btn-floating m-1" href="https://github.com/outmaneBH/202DWESMtoDepartamentosTema4" target="_blank" role="button">
                            <img id="git" style="width: 30px;height:30px; " src="../webroot/media/icons/git.png" alt="github"/>  
                        </a>
                    </section>
                </div>
                <!-- Grid container -->
                <!-- Copyright -->
                <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    Copyrights © 2021 
                    <a class="text-white" href="../index.html">OUTMANE BOUHOU</a>
                    . All rights reserved.
                </div>
                <!-- Copyright -->
            </footer>


    </body>
</html>



