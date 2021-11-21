<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <title>OB - Borrar Departamento</title>
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
         if (isset($_REQUEST['cancelbtn'])) {
            header("Location:MtoDepartamentos.php");
        }
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
            "volumenNegocio" => null
        ];

        /* Array de respuestas inicializado a null */
        $aRespuestas = [
            "description" => null,
            "volumenNegocio" => null
        ];
       

        try {
            /* usar el ficherod de configuracion */
            $miDB = new PDO(HOST, USER, PASSWORD);

            /* Preparamos las excepciones */
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Consulta preparada para buscar  */
            $sql = "SELECT * from Departamento where CodDepartamento=:codDepartamento";
            $resultadoConsulta = $miDB->prepare($sql);

            /* usar el bindparam para asegnar el codigo para sacar sus datos */
            $resultadoConsulta->bindParam(":codDepartamento", $_GET['codDepartamento']);
            /*ejecutar la consulta */
            $resultadoConsulta->execute();

            $registro = $resultadoConsulta->fetchObject();

            /*meter los datos del departamento en array aRespuestas para usar lo despues*/
            $aRespuestas = [
                "codDepartamento" => $registro->CodDepartamento,
                "description" => $registro->DescDepartamento,
                "fechaBaja" => $registro->FechaBaja,
                "volumenNegocio" => $registro->VolumenNegocio,
            ];
        } catch (PDOException $exception) {
            /* Si hay algun error el try muestra el error del codigo */
            echo '<span> Codigo del Error :' . $exception->getCode() . '</span> <br>';

            /* Muestramos su mensage de error */
            echo '<span> Error :' . $exception->getMessage() . '</span> <br>';
        } finally {
            /*Cerramos the connection*/
            unset($miDB);
        }



        /* comprobar si ha pulsado el button editar */
        if (isset($_REQUEST['submitbtn'])) {
            //Para cada campo del formulario: Validamos la entrada y actuar en consecuencia
            //Validar entrada
            //Comprobar si el campo description  esta rellenado 
            $aErrores["description"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['description'], 1000, 1, OBLIGATORIO);

            //Comprobar si el campo volumenNegocio  esta rellenado 
            $aErrores["volumenNegocio"] = validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'], 10000, 1, OBLIGATORIO);

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
            /* Cambiamos los datos en la base de datos */

            try {
                /* usar el fichero  de configuracion para conectarnos con la base de datos */
                $miDB = new PDO(HOST, USER, PASSWORD);

                /* Preparamos las excepciones */
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                /* Editar la tabla departamento con los parametros  */
                $sql = "DELETE FROM Departamento  WHERE CodDepartamento=:CodDepartamento";

                /*Preparamos  la consulta*/
                $consulta = $miDB->prepare($sql);

                /* usamos bindParam */
                $consulta->bindParam(":CodDepartamento", $aRespuestas['codDepartamento']);
            

                //Ejecución de la consulta
                $consulta->execute();
                echo '';
                header("Location:MtoDepartamentos.php");
            } catch (PDOException $exception) {
                /* Si hay algun error el try muestra el error del codigo */
                echo '<span> Codigo del Error :' . $exception->getCode() . '</span> <br>';

                /* Muestramos su mensage de error */
                echo '<span> Error :' . $exception->getMessage() . '</span> <br>';
            } finally {
                /*cerramos la connection*/
                unset($miDB);
            }
        }
        ?>
        <h2>Formulario de Borrar  departamento </h2>
        <div>
            <table id="t1">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                    <tr>
                        <td><label>Codigo de Departamento  :</label></td>
                        <td><input type="text" disabled name="codDepartamento" value="<?php echo (isset($aRespuestas['codDepartamento']) ? $aRespuestas['codDepartamento'] : null); ?>"/></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><label>Descripción   :</label></td>
                        <td><input type="text"  disabled name="description" value="<?php echo (isset($aRespuestas['description']) ? $aRespuestas['description'] : null); ?>"/></td>
                        <td><span><?php echo ($aErrores["description"] != null ? $aErrores['description'] : null); ?></span></td>
                    </tr>
                    <tr>
                        <td><label>Fecha de Baja   :</label></td>
                        <td><input type="text" disabled  name="fechaBaja" value="<?php echo (isset($aRespuestas['fechaBaja']) ? $aRespuestas['fechaBaja'] : "-"); ?>"/></td>
                        <td></span></td>
                    </tr>
                    <tr>
                        <td><label>Volumen de Negocio  :</label></td>
                        <td><input type="text" disabled  name="volumenNegocio" value="<?php echo (isset($aRespuestas['volumenNegocio']) ? $aRespuestas['volumenNegocio'] : null); ?>"/></td>
                        <td><span><?php echo ($aErrores["volumenNegocio"] != null ? $aErrores['volumenNegocio'] : null); ?></span></td>
                    </tr>
                    <tr> 
                        <td><input type="submit" class="w3-btn w3-green" name="submitbtn" value="Aceptar"/></td>
                        <td><input type="submit" class="w3-btn w3-teal" name="cancelbtn" value="Cancelar"/></td>
                    </tr>
                </form>
            </table>

        </div>

    </body>
</html>

