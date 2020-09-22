<?php
echo 'hola mundo';

require dirname(__FILE__).'/config/config.inc.php';
$db = \Db::getInstance();
$request = 'SELECT FROM`' . _DB_PREFIX_ . 'customer`';

/** @var array $result */
$result = $db->executeS($request);

dump($result);

$fila = 1; //**originalmente era $fila=1, se ha cambiado para omitir la primera linea**//
if (($gestor = fopen("products.csv", "r")) !== FALSE) {
	while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {
		$numero = count($datos);
		echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
		$fila++;
		//**este if es para no mostrar la primera línea
		//if($fila){
        //   $fila = false;
        //   continue;
        //}
		for ($c=0; $c < $numero; $c++) {
			echo $datos[$c] . "<br />\n";
		}
	}
	
	//**prueba**//
	$conexion = new mysqli("localhost", "root", "root", "ps_produts");
	$conexion->autocommit(false);
	$errorEnTransaccion = false;
	
	while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
   
		$arrayDatos = explode(",", $fila);
		$id = $arrayDatos[0];
		$product = $id_product ? new Product((int)$id_product, true) : new Product();
		$Active = $arrayDatos[1];
		$Nombre = $arrayDatos[2];
		$Referencia = $arrayDatos[3];
		$EAN13 = $arrayDatos[4];
		$Precio_de_coste = $arrayDatos[5];
		$Precio_de_venta = $arrayDatos[6];
		$IVA = $arrayDatos[7];
		$Cantidad = $arrayDatos[8];
		$Categoria = $arrayDatos[9];
		$Marca = $arrayDatos[10];
	
	    $sql = "INSERT INTO ps_product (id_product, active, name)" . "VALUES ('ID', 'Active', 'Nombre')";
	     if (!$conexion->query($sql)){
                $errorEnTransaccion =   true;
            }
	}
	 if ($errorEnTransaccion){
        $conexion->rollback();
        printf("Error durante la operación. No se han actualizado los datos.");
    } else {
        $conexion->commit();
        printf("Operación de actualización correcta. Se han actualizado los datos.");
    }
 
			
    $conexion->close();
 
$import = New AdminImportController();
loadProduct();
$import->productImport();
echo ('ejecutado');

 //** fin pruebas**//
	fclose($gestor);
}
?>