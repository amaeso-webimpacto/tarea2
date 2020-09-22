<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors contact@prestashop.com
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 **/
 
if (!defined('_PS_VERSION_')) {
    exit;
}
 
 
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');

class Tarea2 extends Module
{
  public function __construct()
    {
        $this->name = 'tarea2';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'prestashop and contributors';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => '1.7'
        ];
        $this->bootstrap = true;
		
        parent::__construct();

        $this->displayName = $this->l('tarea2');
        $this->description = $this->l('Modulo para importar productos mediante tabla csv.');
		
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('TAREA2')) {
            $this->warning = $this->l('No name provided');
        }
    }
 
 
 //**fin parte para instalar modulo e inicio funciones específicas**//

  
  
  //lee el csv
  $registros = array();
  if (($archivo = fopen("products.csv", "r")) !== FALSE) {
	  $nombre_campos = fgetcsv($archivo, 1000, ",");
	  $num_campos = count ($nombre_campos);
	  while (($datos = fgetcsv($archivo, 1000, ",")) !== FALSE) {
		  for ($icampo=0; $icampo < $num_campos; $icampo++) {
			  $registros[$nombre_campos[$icampo]] = $datos[$icampo];
		  }
		  $registros[] = $registro;
	  }
	  fclose($archivo);
 
      echo "Leidos " . count($registros) . " registros\n";
 
      for ($i = 0; $i < count($registros); $i++) {
          for ($icampo = 0; $icampo < $num_campos; $icampo++) {
              echo $nombres_campos[$icampo] . ":"
              . $registros[$i][$nombres_campos[$icampo]] . "\n";
          }
      }
}
 
 //parte para guardar lo leido
 foreach ($archivo-> Product as $product_csv)
 {
	 if ($product_csv->Valid_internet_product == 1)
	 {
		 /*Actualiza un producto existente o Crea uno*/
		 $id_product = (int)Db::getInstance()->getValue('SELECT id_product FROM '._DB_PREFIX_.'product WHERE reference = \''.pSQL($product_csv->ID).'\'');
         $product = $id_product ? new Product((int)$id_product, true) : new Product();
         $product->reference = $product_csv->Reference;
		 $product->active = (int)$product_csv->Active;
         $product->name[1] = utf8_encode($product_csv->Name);
		 $product->ean13 = $product_csv->EAN13;
		 $product->price_tex = $product_csv->Cost_Price;
		 $product->price_tin = $product_csv->Sale_Price;
		 $product->additional_shipping_cost = $product_csv->IVA;
		 $product->quantity = $product_csv->Amount;
		 $product->manufacturer = $product_csv->Brand;
         $product->date_upd = date('Y-m-d H:i:s');
		 $product->category[10] = utf8_encode($product_csv->Name);
		 $id_product ? $product->updateCategories(array(2)) : $product->addToCategories(array(2));
		 $product->save();

        echo 'Product <b>'.$product->name[1].'</b> '.($id_product ? 'updated' : 'created').'<br />';
    }
 }

}