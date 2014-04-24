<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Memberlist Class
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Gianfranco Montoya 
 * @copyright   Copyright (c) 2014, Gianfranco Montoya 
 * @link        http://www.ayuinc.com/
 */

$plugin_info = array(
    'pi_name'         => 'Infhotel',
    'pi_version'      => '1.0',
    'pi_author'       => 'Gianfranco Montoya ',
    'pi_author_url'   => 'http://www.ayuinc.com/',
    'pi_description'  => 'Allow extract surf data from MagicSeaWeed API',
    'pi_usage'        => Infhotel::usage()
);
            
class Infhotel 
{

    var $return_data = "";
    // --------------------------------------------------------------------

        /**
         * Memberlist
         *
         * This function returns a list of members
         *
         * @access  public
         * @return  string
         */
    public function __construct(){
    }

    // --------------------------------------------------------------------

    /**
     * Usage
     *
     * This function describes how the plugin is used.
     *
     * @access  public
     * @return  string
     */
    public static function usage()
    {
        ob_start();  ?>
        The Memberlist Plugin simply outputs a
        list of 15 members of your site.

            {exp:Infhotel}

        This is an incredibly simple Plugin.
            <?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    // END

    public function tipodetarjeta(){
        $form = '<select name="tipo_de_tarjeta" id="tipo_de_tarjeta" > <option value="TIPO DE TARJETA" selected>TIPO DE TARJETA</option>';
        //$url = "http://es.magicseaweed.com/api/3XpBW72Em3wuAo7O0BYc17k582W308Ek/forecast/?spot_id=416&units=eu"; 
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetTipoTarjeta';
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);

        // Will dump a beauty json :3
        $data = json_decode($result, true);

        foreach($data as $row){
            $codigo_de_tarjeta = $row["TCodigoTarjeta"];
            $descripción_de_tarjeta = $row["TDescripcionCompleta"];
            $form .= '<option value='.$codigo_de_tarjeta.'>'.$descripción_de_tarjeta.'</option>';
        }
        $form = $form.'</select>';
        
        return $form;
    }

    public function tipodedocumento(){
        $form = '<select name="tipo_de_documento" id="tipo_de_documento" > <option value="TIPO DE DOCUMENTO" selected>TIPO DE DOCUMENTO</option>';
        //$url = "http://es.magicseaweed.com/api/3XpBW72Em3wuAo7O0BYc17k582W308Ek/forecast/?spot_id=416&units=eu"; 
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetTipoDocumento';
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);

        // Will dump a beauty json :3
        $data = json_decode($result, true);

        foreach($data as $row){
            $codigo_documento = $row["TCodigo"];
            $nombre_documento = $row["TResumido"];
            $form .= '<option value='.$codigo_documento.'>'.$nombre_documento.'</option>';
        }
        $form = $form.'</select>';
        
        return $form;
    }

    public function pais(){
        $form = '<select name="pais" id="pais" > <option value="PAÍS" selected>PAÍS</option>';
        //$url = "http://es.magicseaweed.com/api/3XpBW72Em3wuAo7O0BYc17k582W308Ek/forecast/?spot_id=416&units=eu"; 
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetPais';
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);

        // Will dump a beauty json :3
        $data = json_decode($result, true);

        foreach($data as $row){
            $codigo_pais = $row["TCodigoPais"];
            $nombre_pais = $row["TDescripcionPais"];
            //$nacionalidad = $rom["TDescripcionNacionalidad"]; -> Dato NO utilizado.
            $form .= '<option value='.$codigo_pais.'>'.$nombre_pais.'</option>';
        }
        $form = $form.'</select>';
        
        return $form;
    }

    public function tipodehabitacion(){
        $form = '<select name="tipo_de_habitacion" id="tipo_de_habitacion" > <option value="TIPO DE HABITACIÓN" selected>TIPO DE HABITACIÓN</option>';
        //$url = "http://es.magicseaweed.com/api/3XpBW72Em3wuAo7O0BYc17k582W308Ek/forecast/?spot_id=416&units=eu"; 
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetHabitacionTarifa';
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);

        // Will dump a beauty json :3
        $data = json_decode($result, true);

        foreach($data as $row){
            //$n_cant_pax = $row["NCantPax"];
            $precio_base = $row["NPrecioBase"];
            //$codigo_compania = $row["TCodigoCompania"];
            $codigo_habitacion = $row["TCodigoHabitacion"];
            //$codigo_tarifa = $row["TCodigoTarifa"];
            $tipo_de_habitacion = $row["TDescripcionCompletaProducto"];
            //$descripcion_tarifa = $row["TDescripcionTarifa"];
            //$razon_social_compania = $row["TRazonSocialCompania"];
            
            $form .= '<option value='.$codigo_habitacion.'>'.$tipo_de_habitacion.' - '.$precio_base.'</option>';
        }
        $form = $form.'</select>';
        
        return $form;
    }

    public function disponibilidadhabitaciones(){
        $html ="<div> <ul>";
        $fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        $fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');

        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetHabitacionesDisponiblesDetallado/'.$fecha_checkin.'/'.$fecha_checkout;
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        $test='[
   {
      "FFecha":"01\/04\/2014 12:00:00 a.m.",
      "NDisponible":5,
      "NPrecioBase":195,
      "TCodigoHabitacion":"110001",
      "TDescripcionCompletaProducto":"SIMPLE"
   },
   {
      "FFecha":"03\/04\/2014 12:00:00 a.m.",
      "NDisponible":5,
      "NPrecioBase":195,
      "TCodigoHabitacion":"110001",
      "TDescripcionCompletaProducto":"SIMPLE"
   },
   {
      "FFecha":"04\/04\/2014 12:00:00 a.m.",
      "NDisponible":5,
      "NPrecioBase":195,
      "TCodigoHabitacion":"110001",
      "TDescripcionCompletaProducto":"SIMPLE"
   },
   {
      "FFecha":"05\/04\/2014 12:00:00 a.m.",
      "NDisponible":6,
      "NPrecioBase":195,
      "TCodigoHabitacion":"110001",
      "TDescripcionCompletaProducto":"SIMPLE"
   },
   {
      "FFecha":"01\/04\/2014 12:00:00 a.m.",
      "NDisponible":7,
      "NPrecioBase":300,
      "TCodigoHabitacion":"110002",
      "TDescripcionCompletaProducto":"DOBLE"
   },
   {
      "FFecha":"02\/04\/2014 12:00:00 a.m.",
      "NDisponible":7,
      "NPrecioBase":300,
      "TCodigoHabitacion":"110002",
      "TDescripcionCompletaProducto":"DOBLE"
   },
   {
      "FFecha":"03\/04\/2014 12:00:00 a.m.",
      "NDisponible":7,
      "NPrecioBase":300,
      "TCodigoHabitacion":"110002",
      "TDescripcionCompletaProducto":"DOBLE"
   },
   {
      "FFecha":"04\/04\/2014 12:00:00 a.m.",
      "NDisponible":7,
      "NPrecioBase":300,
      "TCodigoHabitacion":"110002",
      "TDescripcionCompletaProducto":"DOBLE"
   },
   {
      "FFecha":"05\/04\/2014 12:00:00 a.m.",
      "NDisponible":8,
      "NPrecioBase":300,
      "TCodigoHabitacion":"110002",
      "TDescripcionCompletaProducto":"DOBLE"
   },
   {
      "FFecha":"01\/04\/2014 12:00:00 a.m.",
      "NDisponible":4,
      "NPrecioBase":390,
      "TCodigoHabitacion":"110003",
      "TDescripcionCompletaProducto":"TRIPLE"
   },
   {
      "FFecha":"02\/04\/2014 12:00:00 a.m.",
      "NDisponible":4,
      "NPrecioBase":390,
      "TCodigoHabitacion":"110003",
      "TDescripcionCompletaProducto":"TRIPLE"
   },
   {
      "FFecha":"03\/04\/2014 12:00:00 a.m.",
      "NDisponible":4,
      "NPrecioBase":390,
      "TCodigoHabitacion":"110003",
      "TDescripcionCompletaProducto":"TRIPLE"
   },
   {
      "FFecha":"04\/04\/2014 12:00:00 a.m.",
      "NDisponible":4,
      "NPrecioBase":390,
      "TCodigoHabitacion":"110003",
      "TDescripcionCompletaProducto":"TRIPLE"
   },
   {
      "FFecha":"05\/04\/2014 12:00:00 a.m.",
      "NDisponible":4,
      "NPrecioBase":390,
      "TCodigoHabitacion":"110003",
      "TDescripcionCompletaProducto":"TRIPLE"
   },
   {
      "FFecha":"01\/04\/2014 12:00:00 a.m.",
      "NDisponible":2,
      "NPrecioBase":690,
      "TCodigoHabitacion":"110004",
      "TDescripcionCompletaProducto":"SUITE"
   },
   {
      "FFecha":"02\/04\/2014 12:00:00 a.m.",
      "NDisponible":2,
      "NPrecioBase":690,
      "TCodigoHabitacion":"110004",
      "TDescripcionCompletaProducto":"SUITE"
   },
   {
      "FFecha":"03\/04\/2014 12:00:00 a.m.",
      "NDisponible":2,
      "NPrecioBase":690,
      "TCodigoHabitacion":"110004",
      "TDescripcionCompletaProducto":"SUITE"
   },
   {
      "FFecha":"04\/04\/2014 12:00:00 a.m.",
      "NDisponible":2,
      "NPrecioBase":690,
      "TCodigoHabitacion":"110004",
      "TDescripcionCompletaProducto":"SUITE"
   },
   {
      "FFecha":"05\/04\/2014 12:00:00 a.m.",
      "NDisponible":2,
      "NPrecioBase":690,
      "TCodigoHabitacion":"110004",
      "TDescripcionCompletaProducto":"SUITE"
   }
]'
        $n=0; 
        // Will dump a beauty json :3
        $data = json_decode($test, true);
        foreach($data as $row){
            $fecha[$n] = $row["FFecha"];
            $n=$n+1;
        }
        $n=0;
        foreach($data as $row){
            $codigo_habitacion[$n] = $row["TCodigoHabitacion"];
            $n=$n+1;
        }
        $n=0;
        foreach($data as $row){
            $tipo_de_habitacion[$n] = $row["TDescripcionCompletaProducto"];
            $n=$n+1;
        }
        $flag = false;
        $codigo_habitacion = array_unique($codigo_habitacion);
        $tipo_de_habitacion = array_unique($tipo_de_habitacion);
        foreach ($tipo_de_habitacion as $tip_hab) {
            $html .=  '<li> <div>';
            $html .=  '<b><p> Habitaciones'.$tip_hab.':</p></b>';
            foreach ($codigo_habitacion as $cod_hab) {
                foreach ($data as $row) { 
                    if($tip_hab == $row["TDescripcionCompletaProducto"]){
                        if($cod_hab == $row["TCodigoHabitacion"]){
                            $flag = true;
                        }
                        else{
                            $flag = false;
                        }
                    }       
                }
                if($flag==true){
                    $html .=  '<p>'.$row["FFecha"].' Habitacion:'.$tip_hab.'- Codigo'.$cod_hab.'- Precio'.$precio_base.'</p>';
                }
                $flag = false;
            }
            $html .= '</div> </li>' ;
        }
        /*
        foreach ($data as $row) {
            $html .=  '<li> <div>';
            foreach ($codigo_habitacion as $cod_hab) {
                if($cod_hab == $row["TCodigoHabitacion"]){
                    $var = true;
                    $html .=  $row["FFecha"].'<p>Habitacion:'.$tipo_de_habitacion = $row["TDescripcionCompletaProducto"].'- Codigo'.$row["TCodigoHabitacion"].'- Precio'.$precio_base = $row["NPrecioBase"].'</p>';
                    break;
                }
            }
            $html .= '</div> </li>' ;
        }
        foreach ($fecha as $fech) {
            $html .=  '<li> <div>
                        <p>Fecha :'.$fech.'</p>' ;
            foreach($data as $row){
                if ($fech == $row["FFecha"]){
                    $disponible = $row["NDisponible"];
                    $precio_base = $row["NPrecioBase"];
                    $codigo_habitacion = $row["TCodigoHabitacion"];
                    $tipo_de_habitacion = $row["TDescripcionCompletaProducto"];

                    $html .= '<p> Tipo :'.$tipo_de_habitacion.' - Precio : '.$precio_base.' - Cant. de hab. disponibles :'.$disponible.':</p>';
                }
            }
            $html .= '</div> </li>' ;
        }*/

        $html .= '</ul></div>';
        
        return $html ;
    }

    public function tarifadehabitacionesdisponibles(){
        $fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        $fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');
        $form = '<select name="tipo_de_habitacion" id="tipo_de_habitacion" > <option value="TIPO DE HABITACIÓN" selected>TIPO DE HABITACIÓN</option>';
        //$url = "http://es.magicseaweed.com/api/3XpBW72Em3wuAo7O0BYc17k582W308Ek/forecast/?spot_id=416&units=eu"; 
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetTarifaHabitacionesDisponibles/'.$fecha_checkin.'/'.$fecha_checkout;
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);

        // Will dump a beauty json :3
        $data = json_decode($result, true);

        foreach($data as $row){
            //$n_cant_pax = $row["NCantPax"];
            $precio_base = $row["NPrecioBase"];
            //$codigo_compania = $row["TCodigoCompania"];
            $codigo_habitacion = $row["TCodigoHabitacion"];
            //$codigo_tarifa = $row["TCodigoTarifa"];
            $tipo_de_habitacion = $row["TDescripcionCompletaProducto"];
            //$descripcion_tarifa = $row["TDescripcionTarifa"];
            //$razon_social_compania = $row["TRazonSocialCompania"];
            
            $form .= '<option value='.$codigo_habitacion.'>'.$tipo_de_habitacion.' - '.$precio_base.'</option>';
        }
        $form = $form.'</select>';
        
        return $form;
    }

    public function insertarreservar(){
        /*$nombre = ee()->TMPL->fetch_param('nombre');
        $apellido = ee()->TMPL->fetch_param('apellido');
        $dni = ee()->TMPL->fetch_param('dni');
        $tipo_dni = ee()->TMPL->fetch_param('tipo_dni');
        $tarjeta = ee()->TMPL->fetch_param('tarjeta');
        $tipo_tarjeta = ee()->TMPL->fetch_param('tipo_tarjeta');
        $precio = ee()->TMPL->fetch_param('preio');
        $cantidad_de_habitaciones = ee()->TMPL->fetch_param('cantidad_de_habitaciones');
        $fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        $fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');
        $hora_checkin = ee()->TMPL->fetch_param('hora_checkin');
        $hora_checkout = ee()->TMPL->fetch_param('hora_checkin');*/
        $data = array(  "FLlegada" => "2014-05-05 13:30:00.000",
                        "FSalida" => "2014-05-07 15:45:00.000", 
                        "HLlegada" => "2014-05-05 13:30:00.000",
                        "HSalida" => "2014-05-07 15:45:00.000",
                        "Habitaciones" => array(
                                            array("CantHab" => 1, 
                                                "NPrecio" => 120,  
                                                "TCodigoHabitacion" => "110001"  
                                            ),
                                            array("CantHab" => 1, 
                                                "NPrecio" => 150,  
                                                "TCodigoHabitacion" => "110002"
                                            )
                                        ),
                        "NPasajero" => 2,
                        "Pasajeros" => array(
                                            array(
                                                "TDocumento" => "12345678",
                                                "TMaterno" => "pasajero01",  
                                                "TNacionalidad" => "069",      
                                                "TNombre" => "pasajero01",
                                                "TPaterno" => "pasajero01",  
                                                "TTarjeta" => "1234567890",
                                                "TTipoDocumento" => "02",
                                                "TTipoTarjeta" => "04" 
                                            ),
                                            array(
                                                "TDocumento" => "12345678",
                                                "TMaterno" => "pasajero02",  
                                                "TNacionalidad" => "069",      
                                                "TNombre" => "pasajero02",
                                                "TPaterno" => "pasajero02",  
                                                "TTarjeta" => "1234567890",
                                                "TTipoDocumento" => "02",
                                                "TTipoTarjeta" => "02"    
                                            )
                                        )
        );

        $data_string = json_encode($data, true);
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/InsertReserva';
        //  Initiate curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_string)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json', 'charset=utf-8')
        ); 
        $result = curl_exec($ch);
        curl_close($ch);
        return $result."  ".$data_string; 
    }
}
/* End of file pi.infhotel.php */
/* Location: ./system/expressionengine/third_party/infhotel/pi.infhotel.php */