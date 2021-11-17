<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ejercicio9- PDO - mtoProyectoTema4</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <style>
            span{
                color:red;
            }
            #t1{

                position: relative;
                left:  33%;
                top: 110px;
                border-top:  1px solid aqua;
                border-bottom:  1px solid aqua;
            }
            #t1 td{
                padding: 20px;
            }

        </style>
    </head>
    <body>

        <?php
        /*
         * author: OUTMANE BOUHOU
         * Fecha: 17/11/2021
         * description: 9. Aplicación resumen MtoDeDepartamentosTema4. (Incluir PHPDoc y versionado en el repositorio
          GIT)
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

        /* definir un array para alamcenar errores del description */
        $aErrores = [
            "description" => null];

        /* Array de respuestas inicializado a null */
        $aRespuestas = ["description" => null
        ];


        /* comprobar si ha pulsado el button enviar */
        if (isset($_REQUEST['submitbtn'])) {
            //Para cada campo del formulario: Validamos la entrada y actuar en consecuencia
            //Validar entrada
            //Comprobar si el campo description  esta rellenado 
            $aErrores["description"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['description'], 1000, 2, OPCIONAL);

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
        } else {
            ?>

            <div>
                <table id="t1">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                        <!--El campo description obligatorio -->
                        <tr>
                            <td><label><strong>Que quieres buscar :</strong></label></td>
                        </tr>
                        <tr>
                            <td><label>Descripción   :</label></td>
                            <td><input type="text"  name="description" value="<?php echo (isset($_REQUEST['description']) ? $_REQUEST['description'] : null); ?>"/></td>
                            <td><span><?php echo ($aErrores["description"] != null ? $aErrores['description'] : null); ?></span></td>
                        </tr>
                        <tr> 
                            <td><input type="submit" class="w3-btn w3-teal" name="submitbtn" value="Buscar"/></td>
                            <td><input type="reset" class="w3-btn w3-red" name="resetbtn" value="Borrar"/></td>
                        </tr>
                    </form>
                </table>

            </div>
    <?php
}
?>
    </body>
</html>




