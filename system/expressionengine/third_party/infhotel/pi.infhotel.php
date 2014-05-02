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

    public function disponibilidadinicialhabitaciones3(){
        $response ='';
        $fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        $fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');
        $rooms_num = ee()->TMPL->fetch_param('rooms_num');

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
        $n=0;
        // Will dump a beauty json :3
        $data = json_decode($result, true);
        foreach($data as $row){
            $fecha[$n] = $row["FFecha"];
            $n=$n+1;
        }
        $fecha = array_unique($fecha);
        $disponibilidad["simple"]=2000;
        $disponibilidad["double"]=2000;
        $disponibilidad["triple"]=2000;
        $disponibilidad["suite"]=2000;
        foreach ($fecha as $fech) {
            foreach($data as $row){
                if($row["TCodigoHabitacion"]==110001 && $row["NDisponible"]<$disponibilidad["simple"] ){
                    $disponibilidad["simple"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110002 && $row["NDisponible"]<$disponibilidad["double"] ){
                    $disponibilidad["double"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110003 && $row["NDisponible"]<$disponibilidad["triple"] ){
                    $disponibilidad["triple"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110004 && $row["NDisponible"]<$disponibilidad["suite"] ){
                    $disponibilidad["suite"]=$row["NDisponible"];
                }
            }
        }
        if( $disponibilidad["simple"] == 2000){
             $disponibilidad["simple"] = 0;
        }
        if( $disponibilidad["double"] == 2000){
             $disponibilidad["double"] = 0;
        }
        if( $disponibilidad["triple"] == 2000){
             $disponibilidad["triple"] = 0;
        }
        if( $disponibilidad["suite"] == 2000){
             $disponibilidad["suite"] = 0;
        }
        //simple+doble = ocean
        //triples+suites =garden
        $ocean_view = $disponibilidad["simple"] + $disponibilidad["double"];
        $garden_view = $disponibilidad["triple"] + $disponibilidad["suite"];
        $total_hab = $ocean_view + $garden_view;
        if($total_hab == 0){
            $response = '<p>Lo sentimos, no tenemos habitaciones disponibles.<p>';
        }
        else{
            for ($i=0; $i<$rooms_num ; $i++) { 
                $result .= '<div class="row" id="rooms">';
                if ( $i<$garden_view) {
                    $result .= '<div class="large-5 columns">
                    <div class="row">
                        <div class="large-5 columns">
                            <figure>
                                 <img src="http://placehold.it/250x110" alt="Single Room" width="250" height="100">
                            </figure>
                        </div>
                        <div class="large-7 columns">
                            <div class="row">
                                <div class="large-12 columns">
                                    <h2>Garden View</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-6 columns">
                                    <p>Personas</p>
                                </div>
                                <div class="large-6 columns">
                                    <select name="persons_number"  id="freeform_persons_number" required="" pattern="number" data-invalid="">
                                      <option value="1" selected="selected">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <figcaption>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius deserunt vitae id possimus dolores quidem distinctio nostrum consequatur et laudantium. Corrupti, eum delectus tenetur doloremque totam dolor perferendis minima consectetur.</figcaption>
                        </div>
                    </div>
                </div>
                <div class="large-7 columns">
                    <div class="row">
                        <div class="large-8 columns">
                            <p>Breakfast only</p>
                            <h2>USD 120/night</h2>
                        </div>
                        <div class="large-4 columns">
                            <button type="button">Click Me!</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-8 columns">
                            <p>Value pack, including breakfast, lunch and meals.</p>
                            <h2>USD 150/night</h2>
                        </div>
                        <div class="large-4 columns">
                            <button type="button">Click Me!</button>
                        </div>
                    </div>  
                </div>
            </div>';
                }
                if ($i<$ocean_view) {
                    $result .= '<div class="row" id="rooms">
                <div class="large-5 columns">
                    <div class="row">
                        <div class="large-5 columns">
                            <figure>
                                 <img src="http://placehold.it/250x110" alt="Single Room" width="250" height="100">
                            </figure>
                        </div>
                        <div class="large-7 columns">
                            <div class="row">
                                <div class="large-12 columns">
                                    <h2>Ocean View</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-6 columns">
                                    <p>Personas</p>
                                </div>
                                <div class="large-6 columns">
                                    <select name="persons_number"  id="freeform_persons_number" required="" pattern="number" data-invalid="">
                                      <option value="1" selected="selected">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <figcaption>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius deserunt vitae id possimus dolores quidem distinctio nostrum consequatur et laudantium. Corrupti, eum delectus tenetur doloremque totam dolor perferendis minima consectetur.</figcaption>
                        </div>
                    </div>
                </div>
                <div class="large-7 columns">
                    <div class="row">
                        <div class="large-8 columns">
                            <p>Breakfast only</p>
                            <h2>USD 120/night</h2>
                        </div>
                        <div class="large-4 columns">
                            <button type="button">Click Me!</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-8 columns">
                            <p>Value pack, including breakfast, lunch and meals.</p>
                            <h2>USD 150/night</h2>
                        </div>
                        <div class="large-4 columns">
                            <button type="button">Click Me!</button>
                        </div>
                    </div>  
                </div>';
                }
                $result .= '</div>';
            }
        }
        return $result ;
    }

    public function disponibilidadinicialhabitaciones2(){
        $response =" ";
        $fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        $fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');
        $rooms_num = ee()->TMPL->fetch_param('rooms_num');

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
        $n=0;
        // Will dump a beauty json :3
        $data = json_decode($result, true);
        foreach($data as $row){
            $fecha[$n] = $row["FFecha"];
            $n=$n+1;
        }
        $fecha = array_unique($fecha);
        $disponibilidad["simple"]=2000;
        $disponibilidad["double"]=2000;
        $disponibilidad["triple"]=2000;
        $disponibilidad["suite"]=2000;
        foreach ($fecha as $fech) {
            foreach($data as $row){
                if($row["TCodigoHabitacion"]==110001 && $row["NDisponible"]<$disponibilidad["simple"] ){
                    $disponibilidad["simple"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110002 && $row["NDisponible"]<$disponibilidad["double"] ){
                    $disponibilidad["double"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110003 && $row["NDisponible"]<$disponibilidad["triple"] ){
                    $disponibilidad["triple"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110004 && $row["NDisponible"]<$disponibilidad["suite"] ){
                    $disponibilidad["suite"]=$row["NDisponible"];
                }
            }
        }
        if( $disponibilidad["simple"] == 2000){
             $disponibilidad["simple"] = 0;
        }
        if( $disponibilidad["double"] == 2000){
             $disponibilidad["double"] = 0;
        }
        if( $disponibilidad["triple"] == 2000){
             $disponibilidad["triple"] = 0;
        }
        if( $disponibilidad["suite"] == 2000){
             $disponibilidad["suite"] = 0;
        }
        //simple+doble = ocean
        //triples+suites =garden
        $ocean_view = $disponibilidad["simple"] + $disponibilidad["double"];
        $garden_view = $disponibilidad["triple"] + $disponibilidad["suite"];
        $total_hab = $ocean_view + $garden_view;
        if($total_hab == 0){
            $response = 0;
        }
        else{
            $response = 1;
        }
        return $response ;
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
        $n=0;
        // Will dump a beauty json :3
        $data = json_decode($result, true);
        foreach($data as $row){
            $fecha[$n] = $row["FFecha"];
            $n=$n+1;
        }
        $fecha = array_unique($fecha);
        $n=0;
        foreach($data as $row){
            $cod_hab[$n] = $row["TCodigoHabitacion"];
            $n=$n+1;
        }
        $disponibilidad["simple"]=20;
        $disponibilidad["double"]=20;
        $disponibilidad["triple"]=20;
        $disponibilidad["suite"]=20;
        $cod_hab = array_unique($cod_hab);
        foreach ($fecha as $fech) {
            $html .=  '<li> <div>
                        <p>Fecha :'.$fech.'</p>' ;
            foreach($data as $row){
                if($row["TCodigoHabitacion"]==110001 && $row["NDisponible"]<$disponibilidad["simple"] ){
                    $disponibilidad["simple"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110002 && $row["NDisponible"]<$disponibilidad["double"] ){
                    $disponibilidad["double"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110003 && $row["NDisponible"]<$disponibilidad["triple"] ){
                    $disponibilidad["triple"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110004 && $row["NDisponible"]<$disponibilidad["suite"] ){
                    $disponibilidad["suite"]=$row["NDisponible"];
                }
                if ($fech == $row["FFecha"]){
                    $disponible = $row["NDisponible"];
                    $precio_base = $row["NPrecioBase"];
                    $codigo_habitacion = $row["TCodigoHabitacion"];
                    $tipo_de_habitacion = $row["TDescripcionCompletaProducto"];

                    $html .= '<p> Tipo :'.$tipo_de_habitacion.' - Precio : '.$precio_base.' - Cant. de hab. disponibles :'.$disponible.':</p>';
                }
            }
            $html .= '</div> </li>' ;
        }
        $html .= '</ul></div>';
        $html .= '<div><p>Simples:'. $disponibilidad["simple"].'</p><p>Dobles:'.$disponibilidad["double"].'</p><p>Triples:'.$disponibilidad["triple"].'</p><p>Suites:'.$disponibilidad["suite"].'</p>
                    </div>';
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
        /*
        $nombre = ee()->TMPL->fetch_param('nombre');
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
        $hora_checkout = ee()->TMPL->fetch_param('hora_checkin');
        */
        $data = array(  "FLlegada" => "2014-07-06 12:00:00.000",
                        "FSalida" => "2014-07-13 12:00:00.000", 
                        "HLlegada" => "2014-07-06 12:00:00.000",
                        "HSalida" => "2014-07-13 12:00:00.000",
                        "Habitaciones" => array(
                                            array("CantHab" => 1, 
                                                "NPrecio" => 120,  
                                                "TCodigoHabitacion" => "110001"  
                                            ),
                                            array("CantHab" => 1, 
                                                "NPrecio" => 150,  
                                                "TCodigoHabitacion" => "110001"
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