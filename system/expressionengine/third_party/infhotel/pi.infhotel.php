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
        $form = '<select name="card_type" id="tipo_de_tarjeta" > <option value="TIPO DE TARJETA" selected>TIPO DE TARJETA</option>';
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
        $form = '<select name="document_type" id="document_type" > <option value="00" selected>TIPO DE DOCUMENTO</option>';
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
        //$form = '<select name="pais" id="pais" > <option value="PAÍS" selected>PAÍS</option>';
        $form = '<select name="country" id="freeform_country" placeholder="ENTER YOUR COUNTRY" required="">';
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

    public function disponibilidadinicialhabitaciones_final(){
        $response ='';
        //$fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        //$fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');
        //$rooms_num = ee()->TMPL->fetch_param('rooms_num');
        $rooms_num = '4';
        $result='[
        {"FFecha":"01\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":120,"TCodigoHabitacion":"110001","TDescripcionCompletaProducto":"SIMPLE GARDEN VIEW"},
        {"FFecha":"02\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":120,"TCodigoHabitacion":"110001","TDescripcionCompletaProducto":"SIMPLE GARDEN VIEW"},
        {"FFecha":"03\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":120,"TCodigoHabitacion":"110001","TDescripcionCompletaProducto":"SIMPLE GARDEN VIEW"},
        {"FFecha":"04\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":120,"TCodigoHabitacion":"110001","TDescripcionCompletaProducto":"SIMPLE GARDEN VIEW"},
        {"FFecha":"05\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":120,"TCodigoHabitacion":"110001","TDescripcionCompletaProducto":"SIMPLE GARDEN VIEW"},
        {"FFecha":"01\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":150,"TCodigoHabitacion":"110002","TDescripcionCompletaProducto":"DOBLE GARDEN VIEW"},
        {"FFecha":"02\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":150,"TCodigoHabitacion":"110002","TDescripcionCompletaProducto":"DOBLE GARDEN VIEW"},
        {"FFecha":"03\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":150,"TCodigoHabitacion":"110002","TDescripcionCompletaProducto":"DOBLE GARDEN VIEW"},
        {"FFecha":"04\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":150,"TCodigoHabitacion":"110002","TDescripcionCompletaProducto":"DOBLE GARDEN VIEW"},
        {"FFecha":"05\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":150,"TCodigoHabitacion":"110002","TDescripcionCompletaProducto":"DOBLE GARDEN VIEW"},
        {"FFecha":"01\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110003","TDescripcionCompletaProducto":"TRIPLE GARDEN VIEW"},
        {"FFecha":"02\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110003","TDescripcionCompletaProducto":"TRIPLE GARDEN VIEW"},
        {"FFecha":"03\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110003","TDescripcionCompletaProducto":"TRIPLE GARDEN VIEW"},
        {"FFecha":"04\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110003","TDescripcionCompletaProducto":"TRIPLE GARDEN VIEW"},
        {"FFecha":"05\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110003","TDescripcionCompletaProducto":"TRIPLE GARDEN VIEW"},
        {"FFecha":"01\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":190,"TCodigoHabitacion":"110005","TDescripcionCompletaProducto":"SIMPLE OCEAN VIEW"},
        {"FFecha":"02\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":190,"TCodigoHabitacion":"110005","TDescripcionCompletaProducto":"SIMPLE OCEAN VIEW"},
        {"FFecha":"03\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":190,"TCodigoHabitacion":"110005","TDescripcionCompletaProducto":"SIMPLE OCEAN VIEW"},
        {"FFecha":"04\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":190,"TCodigoHabitacion":"110005","TDescripcionCompletaProducto":"SIMPLE OCEAN VIEW"},
        {"FFecha":"05\/09\/2014 12:00:00 a.m.","NDisponible":7,"NPrecioBase":190,"TCodigoHabitacion":"110005","TDescripcionCompletaProducto":"SIMPLE OCEAN VIEW"},
        {"FFecha":"01\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":190,"TCodigoHabitacion":"110006","TDescripcionCompletaProducto":"DOBLE OCEAN VIEW"},
        {"FFecha":"02\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":190,"TCodigoHabitacion":"110006","TDescripcionCompletaProducto":"DOBLE OCEAN VIEW"},
        {"FFecha":"03\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":190,"TCodigoHabitacion":"110006","TDescripcionCompletaProducto":"DOBLE OCEAN VIEW"},
        {"FFecha":"04\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":190,"TCodigoHabitacion":"110006","TDescripcionCompletaProducto":"DOBLE OCEAN VIEW"},
        {"FFecha":"05\/09\/2014 12:00:00 a.m.","NDisponible":8,"NPrecioBase":190,"TCodigoHabitacion":"110006","TDescripcionCompletaProducto":"DOBLE OCEAN VIEW"},
        {"FFecha":"01\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110007","TDescripcionCompletaProducto":"TRIPLE OCEAN VIEW"},
        {"FFecha":"02\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110007","TDescripcionCompletaProducto":"TRIPLE OCEAN VIEW"},
        {"FFecha":"03\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110007","TDescripcionCompletaProducto":"TRIPLE OCEAN VIEW"},
        {"FFecha":"04\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110007","TDescripcionCompletaProducto":"TRIPLE OCEAN VIEW"},
        {"FFecha":"05\/09\/2014 12:00:00 a.m.","NDisponible":4,"NPrecioBase":190,"TCodigoHabitacion":"110007","TDescripcionCompletaProducto":"TRIPLE OCEAN VIEW"}
        ]';

        $data = json_decode($result, true);
        foreach($data as $row){
            $fecha[$n] = $row["FFecha"];
            $n=$n+1;
        }

        $fecha = array_unique($fecha);
        $disponibilidad["simple_garden"]=2000;
        $disponibilidad["doble_garden"]=2000;
        $disponibilidad["triple_garden"]=2000;
        //$disponibilidad["suite"]=2000; No se usa
        $disponibilidad["simple_ocean"]=2000;
        $disponibilidad["doble_ocean"]=2000;
        $disponibilidad["triple_ocean"]=2000;
        foreach ($fecha as $fech) {
            foreach($data as $row){
                if($row["TCodigoHabitacion"]==110001 && $row["NDisponible"]<$disponibilidad["simple_garden"] ){
                    $disponibilidad["simple_garden"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110002 && $row["NDisponible"]<$disponibilidad["doble_garden"] ){
                    $disponibilidad["doble_garden"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110003 && $row["NDisponible"]<$disponibilidad["triple_garden"] ){
                    $disponibilidad["triple_garden"]=$row["NDisponible"];
                }
                /*if($row["TCodigoHabitacion"]==110004 && $row["NDisponible"]<$disponibilidad["suite"] ){
                    $disponibilidad["suite"]=$row["NDisponible"];
                }*/
                if($row["TCodigoHabitacion"]==110005 && $row["NDisponible"]<$disponibilidad["simple_ocean"] ){
                    $disponibilidad["simple_ocean"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110006 && $row["NDisponible"]<$disponibilidad["doble_ocean"] ){
                    $disponibilidad["doble_ocean"]=$row["NDisponible"];
                }
                if($row["TCodigoHabitacion"]==110007 && $row["NDisponible"]<$disponibilidad["triple_ocean"] ){
                    $disponibilidad["triple_ocean"]=$row["NDisponible"];
                }
            }
        }
        if( $disponibilidad["simple_garden"] == 2000){
             $disponibilidad["simple_garden"] = 0;
        }
        if( $disponibilidad["doble_garden"] == 2000){
             $disponibilidad["doble_garden"] = 0;
        }
        if( $disponibilidad["triple_garden"] == 2000){
             $disponibilidad["triple_garden"] = 0;
        }
        /*if( $disponibilidad["suite"] == 2000){
             $disponibilidad["suite"] = 0;
        }*/
        if( $disponibilidad["simple_ocean"] == 2000){
             $disponibilidad["simple_ocean"] = 0;
        }
        if( $disponibilidad["doble_ocean"] == 2000){
             $disponibilidad["doble_ocean"] = 0;
        }
        if( $disponibilidad["triple_ocean"] == 2000){
             $disponibilidad["triple_ocean"] = 0;
        }    
        $garden_view = $disponibilidad["simple_garden"] + $disponibilidad["doble_garden"] + $disponibilidad["triple_garden"];
        $ocean_view = $disponibilidad["simple_ocean"] + $disponibilidad["doble_ocean"] + $disponibilidad["triple_ocean"];
        $total_hab = $ocean_view + $garden_view;
        if($total_hab < $rooms_num){
            $response = '<p>Lo sentimos, no tenemos habitaciones disponibles.</p>';
        }
        else{
            $rooms_rest = $rooms_num - $disponibilidad["simple_garden"];
            if($rooms_rest<=0){
                $it_simple_garden = $disponibilidad["simple_garden"];
                $it_doble_garden = 0;
                $it_triple_garden = 0;
            }
            else{
                $it_simple_garden = $disponibilidad["simple_garden"];
                $rooms_rest = $rooms_rest -  $disponibilidad["doble_garden"];
                if($rooms_rest<=0){
                    $it_doble_garden = $disponibilidad["doble_garden"];
                    $it_triple_garden = 0;
                }
                else{
                    $it_doble_garden = $disponibilidad["doble_garden"];
                    $rooms_rest = $rooms_rest -  $disponibilidad["triple_garden"];
                    if($rooms_rest<=0){
                        $it_triple_garden = $rooms_rest;
                    }
                }
            }
            if($rooms_rest<=0){
                $it_simple_ocean = $disponibilidad["simple_ocean"];
                $it_doble_ocean = 0;
                $it_triple_ocean = 0;
            }
            else{
                $it_simple_ocean = $disponibilidad["simple_ocean"];
                $rooms_rest = $rooms_rest -  $disponibilidad["doble_ocean"];
                if($rooms_rest<=0){
                    $it_doble_ocean = $disponibilidad["doble_ocean"];
                    $it_triple_ocean = 0;
                }
                else{
                    $it_doble_ocean = $disponibilidad["doble_ocean"];
                    $rooms_rest = $rooms_rest -  $disponibilidad["triple_ocean"];
                    if($rooms_rest<=0){
                        $it_triple_ocean = $rooms_rest;
                    }
                }
            }
            $first_iteration_garden = $it_simple_garden;
            $second_iteration_garden = $it_simple_garden + $it_doble_garden;
            //$third_iteration_garden = 
            $first_iteration_ocean = $it_simple_ocean;
            $second_iteration_ocean = $it_simple_ocean + $it_doble_ocean;
            //$third_iteration_ocean = 
            for ($i=0; $i<$rooms_num ; $i++) { 
                $response .= '<div class="row" id="rooms'.$i.'">';
                if ( $i<$garden_view) {
                    if($i<$first_iteration_garden){ //Simple Garden
                        $response .= '<div class="row">
                         <div class="row">
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
                                            <h2 id="type_hab_simple_garden'.$i.'">Garden View</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="large-6 columns">
                                            <p>Guests</p>
                                        </div>
                                        <div class="large-6 columns">
                                            <select name="persons_number"  id="guests_simple_garden'.$i.'" required="" pattern="number" data-invalid="">
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
                                    <input id="final_cost_simple_garden'.$i.'" type="text" name="final_cost_simple_garden'.$i.'" value="100"/>
                                    <p id="num_guest_simple_garden'.$i.'">One Guest</p>
                                    <h2 id="cost_simple_garden'.$i.'">USD 100/night</h2>
                                    <span>*OFERTA</span>
                                </div>
                                <div class="large-4 columns">
                                    <button id="add_room_simple_garden'.$i.'" type="button">Click Me Simple Garden(?)!</button>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                    else{
                        if($i<$second_iteration_garden){ //Doble Garden
                            $response .= '<div class="row">
                                <div class="large-5 columns">
                                <div class="row">
                                    <div class="large-5 columns">
                                        <figure>
                                             <img src="http://placehold.it/250x110" alt="Doble Room" width="250" height="100">
                                        </figure>
                                    </div>
                                    <div class="large-7 columns">
                                        <div class="row">
                                            <div class="large-12 columns">
                                                <h2 id="type_hab_doble_garden'.$i.'">Garden View</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="large-6 columns">
                                                <p>Guests</p>
                                            </div>
                                            <div class="large-6 columns">
                                                <select name="persons_number"  id="guests_doble_garden'.$i.'" required="" pattern="number" data-invalid="">
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
                                        <input id="final_cost_doble_garden'.$i.'" type="text" name="final_cost_doble_garden'.$i.'" value="100"/>
                                        <p id="num_guest_doble_garden'.$i.'">One Guest</p>
                                        <h2 id="cost_doble_garden'.$i.'">USD 100/night</h2>
                                        <span>*OFERTA</span>
                                    </div>
                                    <div class="large-4 columns">
                                        <button id="add_room_doble_garden'.$i.'" type="button">Click Me Doble Garden(?)!</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        }
                        else{//Triple Garden
                            $response .= '<div class="row">
                            <div class="large-5 columns">
                                <div class="row">
                                    <div class="large-5 columns">
                                        <figure>
                                             <img src="http://placehold.it/250x110" alt="Triple Room" width="250" height="100">
                                        </figure>
                                    </div>
                                    <div class="large-7 columns">
                                        <div class="row">
                                            <div class="large-12 columns">
                                                <h2 id="type_hab_triple_garden'.$i.'">Garden View</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="large-6 columns">
                                                <p>Guests</p>
                                            </div>
                                            <div class="large-6 columns">
                                                <select name="persons_number"  id="guests_triple_garden'.$i.'" required="" pattern="number" data-invalid="">
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
                                        <input id="final_cost_triple_garden'.$i.'" type="text" name="final_cost_trple_garden'.$i.'" value="100"/>
                                        <p id="num_guest_triple_garden'.$i.'">One Guest</p>
                                        <h2 id="cost_triple_garden'.$i.'">USD 100/night</h2>
                                        <span>*OFERTA</span>
                                    </div>
                                    <div class="large-4 columns">
                                        <button id="add_room_triple_garden'.$i.'" type="button">Click Me Triple Garden(?)!</button>
                                    </div>
                                </div>
                            </div>
                        </div>'; 
                        }
                    }
                }
                if ($i<$ocean_view) {
                    if($i<$first_iteration_ocean){ //Simple Ocean
                        $response .= '<div class="row">
                         <div class="row">
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
                                            <h2 id="type_hab_simple_ocean'.$i.'">Ocean View</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="large-6 columns">
                                            <p>Guests</p>
                                        </div>
                                        <div class="large-6 columns">
                                            <select name="persons_number"  id="guests_simple_ocean'.$i.'" required="" pattern="number" data-invalid="">
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
                                    <input id="final_cost_simple_ocean'.$i.'" type="text" name="final_cost_simple_ocean'.$i.'" value="100"/>
                                    <p id="num_guest_simple_ocean'.$i.'">One Guest</p>
                                    <h2 id="cost_simple_ocean'.$i.'">USD 100/night</h2>
                                    <span>*OFERTA</span>
                                </div>
                                <div class="large-4 columns">
                                    <button id="add_room_simple_ocean'.$i.'" type="button">Click Me Simple Ocean(?)!</button>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                    else{
                        if($i<$second_iteration_ocean){ //Doble Ocean
                            $response .= '<div class="row">
                                <div class="large-5 columns">
                                <div class="row">
                                    <div class="large-5 columns">
                                        <figure>
                                             <img src="http://placehold.it/250x110" alt="Doble Room" width="250" height="100">
                                        </figure>
                                    </div>
                                    <div class="large-7 columns">
                                        <div class="row">
                                            <div class="large-12 columns">
                                                <h2 id="type_hab_doble_ocean'.$i.'">Ocean View</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="large-6 columns">
                                                <p>Guests</p>
                                            </div>
                                            <div class="large-6 columns">
                                                <select name="persons_number"  id="guests_doble_ocean'.$i.'" required="" pattern="number" data-invalid="">
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
                                        <input id="final_cost_doble_ocean'.$i.'" type="text" name="final_cost_doble_ocean'.$i.'" value="100"/>
                                        <p id="num_guest_doble_ocean'.$i.'">One Guest</p>
                                        <h2 id="cost_doble_ocean'.$i.'">USD 100/night</h2>
                                        <span>*OFERTA</span>
                                    </div>
                                    <div class="large-4 columns">
                                        <button id="add_room_doble_ocean'.$i.'" type="button">Click Me Doble Ocean(?)!</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        }
                        else{
                            $response .= '<div class="row">
                            <div class="large-5 columns">
                                <div class="row">
                                    <div class="large-5 columns">
                                        <figure>
                                             <img src="http://placehold.it/250x110" alt="Triple Room" width="250" height="100">
                                        </figure>
                                    </div>
                                    <div class="large-7 columns">
                                        <div class="row">
                                            <div class="large-12 columns">
                                                <h2 id="type_hab_triple_ocean'.$i.'">Ocean View</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="large-6 columns">
                                                <p>Guests</p>
                                            </div>
                                            <div class="large-6 columns">
                                                <select name="persons_number"  id="guests_triple_ocean'.$i.'" required="" pattern="number" data-invalid="">
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
                                        <input id="final_cost_triple_ocean'.$i.'" type="text" name="final_cost_trple_ocean'.$i.'" value="100"/>
                                        <p id="num_guest_triple_ocean'.$i.'">One Guest</p>
                                        <h2 id="cost_triple_ocean'.$i.'">USD 100/night</h2>
                                        <span>*OFERTA</span>
                                    </div>
                                    <div class="large-4 columns">
                                        <button id="add_room_triple_ocean'.$i.'" type="button">Click Me Triple Ocean(?)!</button>
                                    </div>
                                </div>
                            </div>
                        </div>'; //Triple Ocean
                        }
                    }
                }
                $response .= '</div>';
            }
        }
        return $response; 
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
        if($total_hab < $rooms_num){
                    $response = '<p>Lo sentimos, no tenemos habitaciones disponibles.</p>';
                }
            else{
            $it_sui = $rooms_num - $disponibilidad["triple"];
            //$it_tri = $rooms_num - $disponibilidad["triple"];
            $it_sim = $rooms_num - $disponibilidad["double"];
            //$it_dou = $rooms_num - $disponibilidad["double"];
            if($it_sui<=0){
                $it_tri=$rooms_num;
            }
            else{
                $it_tri=$rooms_num-$disponibilidad["suite"];
            }      
            if($it_sim<=0){
                $it_dou=$rooms_num;
            }
            else{
                 $it_dou=$rooms_num - $disponibilidad["simple"];
            }
        
            for ($i=0; $i<$rooms_num ; $i++) { 
                $response .= '<div class="row" id="rooms'.$i.'">';
                if ( $i<$garden_view) {
                    if($i<$it_tri){
                        $response .= ' <div class="row">
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
                                        <h2 id="type_hab_triple'.$i.'">Garden View</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="large-6 columns">
                                        <p>Guests</p>
                                    </div>
                                    <div class="large-6 columns">
                                        <select name="persons_number"  id="guests_triple'.$i.'" required="" pattern="number" data-invalid="">
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
                                <input id="final_cost_triple'.$i.'" type="text" name="final_cost_triple'.$i.'" value="100"/>
                                <p id="num_guest_triple'.$i.'">One Guest</p>
                                <h2 id="cost_triple'.$i.'">USD 100/night</h2>
                                <span>*OFERTA</span>
                            </div>
                            <div class="large-4 columns">
                                <button id="add_room_triple'.$i.'" type="button">Click Me triple(?)!</button>
                            </div>
                        </div>
                    </div>
                </div>';
                    }
                    else{
                            $response .= ' <div class="row">
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
                                            <h2 id="type_hab_suite'.$i.'" >Garden View</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="large-6 columns">
                                            <p>Guests</p>
                                        </div>
                                        <div class="large-6 columns">
                                            <select name="persons_number"  id="guests_suite'.$i.'" required="" pattern="number" data-invalid="">
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
                                    <input id="final_cost_suite'.$i.'" type="text" name="final_cost_suite'.$i.'" value="100"/>           
                                    <p id="num_guest_suite'.$i.'">One Guest</p>
                                    <h2 id="cost_suite'.$i.'">USD 100/night</h2>
                                    <span>*OFERTA</span>
                                </div>
                                <div class="large-4 columns">
                                    <button id="add_room_suite'.$i.'" type="button">Click Me suite(?)!</button>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                }
                if ($i<$ocean_view) {
                    if($i<$it_sim){
                        $response .= '<div class="row">
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
                                        <h2 id="type_hab_simple'.$i.'" >Ocean View</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="large-6 columns">
                                        <p>Guests</p>
                                    </div>
                                    <div class="large-6 columns">
                                        <select name="persons_number"  id="guests_simple'.$i.'" required="" pattern="number" data-invalid="">
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
                                <input id="final_cost_simple'.$i.'" type="text" name="final_cost_simple'.$i.'" value="110"/>   
                                <p id="num_guest_simple'.$i.'">One Guest</p>
                                <h2 id="cost_simple'.$i.'">USD 110/night</h2>
                                <span>*OFERTA</span>
                            </div>
                            <div class="large-4 columns">
                                <button id="add_room_simple'.$i.'" type="button">Click Me simple(?)!</button>
                            </div>
                        </div>
                    </div>
                    </div>';
                }
                else{
                    $response .= '<div class="row">
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
                                    <h2 id="type_hab_doble'.$i.'">Ocean View</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-6 columns">
                                    <p>Guests</p>
                                </div>
                                <div class="large-6 columns">
                                    <select name="persons_number"  id="guests_doble'.$i.'" required="" pattern="number" data-invalid="">
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
                            <input id="final_cost_doble'.$i.'" type="text" name="final_cost_doble'.$i.'" value="110"/>
                            <p id="num_guest_doble'.$i.'">One Guest</p>
                            <h2 id="cost_doble'.$i.'">USD 110/night</h2>
                            <span>*OFERTA</span>
                        </div>
                        <div class="large-4 columns">
                            <button id="add_room_doble'.$i.'" type="button">Click Me double(?)!</button>
                        </div>
                    </div>
                </div>
                </div>';
            
                    }
                }
                $response .= '</div>';
            }
        }
        return $response ;
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
        $first_name = ee()->TMPL->fetch_param('first_name');
        $last_name = ee()->TMPL->fetch_param('last_name');
        $dni = ee()->TMPL->fetch_param('dni');
        $country = ee()->TMPL->fetch_param('country');
        $document_id = ee()->TMPL->fetch_param('document_id');
        $document_type = ee()->TMPL->fetch_param('document_type');
        $card_id = ee()->TMPL->fetch_param('card_id');
        $card_type = ee()->TMPL->fetch_param('card_type');
        
        $json = ee()->TMPL->fetch_param('request');
        
        $person = array(
            "TDocumento" => $document_id,
            "TMaterno" => "ApMaterno",  
            "TNacionalidad" => $country,      
            "TNombre" => $first_name,
            "TPaterno" => $last_name,  
            "TTarjeta" => $card_id,
            "TTipoDocumento" => $document_type,
            "TTipoTarjeta" => $card_type 
        );

        $json = str_replace("(a)", "{", $json);
        $json = str_replace("(b)", "}", $json);
        $json = str_replace('(c)', '"', $json);
        $json = str_replace("(d)", ":", $json);
        $json = str_replace("(e)", " ", $json);
        $json = str_replace("(f)", ",", $json);
        
        $data = json_decode($json, true);
        $data["Pasajeros"]["0"]= $person;
        $data["NPasajero"]="10";
        
        /*
        $data = array(  "FLlegada" => "2014-10-06 12:00:00.000",
                        "FSalida" => "2014-10-13 12:00:00.000", 
                        "HLlegada" => "2014-10-06 12:00:00.000",
                        "HSalida" => "2014-10-13 12:00:00.000",
                        "Habitaciones" => array(
                                            array("CantHab" => 1, 
                                                "NPrecio" => 220,  
                                                "TCodigoHabitacion" => "110001"  
                                            ),
                                            array("CantHab" => 1, 
                                                "NPrecio" => 180,  
                                                "TCodigoHabitacion" => "110001"
                                            )
                                        ),
                        "NPasajero" => 2,
                        "MObservacion" => "Esta es mi xxxxx observación, espero que grabe",     
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
                                            )
                                        )
        );
        */
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
    public function tercer_paso(){
        $form = '<div id="form_box_3">
        <div class="row">
            <div id="title_reservation" class="row">
                    <div class="large-4 columns">
                        <h2>NEW RESERVATION</h2>
                    </div>
                    <div class="large-4 large-offset-4 columns">
                        <p>Phone reservations +511-440-6040</p>
                        {exp:get_post_vars parse="inward"}
                            <p>FECHA CHECKIN: {post_check_in_date} </p>
                            <p>FECHA CHECKOUT: {post_check_out_date}</p>
                            <p>ROOM: {post_room_number}</p>
                        {/exp:get_post_vars}
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="large-9 columns">
                
                <div class="row">
                    <div id="sub_title_reservation" class="row">
                        <b><h3>Guest Information</h3></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Firt/Given Name *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="first_name" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER YOUR FIRST NAME" required="" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Last/Family Name *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="last_name" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER YOUR LAST NAME" required="">
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Document type *</p>
                    </div>
                    <div class="large-9 columns">
                        {exp:infhotel:tipodedocumento}
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Document ID *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="document_id" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER YOUR DOCUMENT ID" required="" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>E-mail*</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="email" value="" id="freeform_email" maxlength="150" placeholder="ENTER YOUR E-MAIL" required="" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Phone Number *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="phone" value="" id="freeform_phone" maxlength="9" placeholder="ENTER YOUR PHONE NUMBER" required=""  >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Country *</p>
                    </div>
                    <div class="large-9 columns">
                        {exp:infhotel:pais}
                    </div>
                </div>
                <div class="row">
                    <div id="sub_title_reservation" class="row">
                    <b><h3>Guarantee</h3></b>
                </div>
                </div>
                <div class="row">
                    <div class="large-12 columns "> 
                        <b><h4>Credit Card Information</h4></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Name on card *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="first_name" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER NAME OF CARD" required="" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Card type *</p>
                    </div>
                    <div class="large-9 columns">
                        {exp:infhotel:tipodetarjeta}
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Card Number *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="card_id" value="" id="freeform_phone" maxlength="9" placeholder="ENTER CARD NUMBER" required="" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Expiration Date *</p>
                    </div>
                    <div class="large-3 columns">
                        <select name="expiration_date_month" id="freeform_country" placeholder="ENTER MONTH" required="">
                            <option value="Month">Month</option>
                            <option value="Enero">Enero</option>
                            <option value="Febrero">Febrero</option>
                            <option value="Marzo">Marzo</option>
                            <option value="Abril">Abril</option>
                        </select>
                    </div>
                    <div class="large-3 columns">
                        <select name="expiration_date_year" id="freeform_country" placeholder="ENTER YEAR" required="">
                            <option value="Year">Year</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns "> 
                        <b><h4>Billing Address</h4></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Country *</p>
                    </div>
                    <div class="large-9 columns">
                        <select name="country_billing" id="freeform_country" placeholder="ENTER YOUR COUNTRY" required="">
                            <option value="af">Afghanistan</option>
                            <option value="ax">Aland Islands</option>
                            <option value="al">Albania</option>
                            <option value="dz">Algeria</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Street *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="street_billing" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER STREET BILLING" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>City *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="city_billing" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER CITY BILLING" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>State *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="state_billing" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER STATE BILLING" >
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns">
                        <p>Postal Code *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="postal_code" value="" id="freeform_first_name" maxlength="150" placeholder="ENTER POSTAL CODE" >
                    </div>
                </div>
                
                <div class="row">
                    <div class="large-3 columns">
                        <p>Request *</p>
                    </div>
                    <div class="large-9 columns">
                        <input type="text" name="request" maxlength="1000"  id="full_request">
                    </div>
                </div>
            </div>
            <!--
            <div class="large-3 columns" id="sumary">
                <div class="row">
                    <div class="large-12 columns">
                        <b><h4>Sumary</h4></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-3 columns ">
                        <p>Arrival:</p>
                    </div>
                    <div id="checkout_date" class="large-9 columns">
                        <p>15/05/2014</p>
                    </div>
                </div>
                <div class="row"> 
                    <div class="large-3 columns">
                        <p>Departure:</p>
                    </div>
                    <div id="checkin_date" class="large-9 columns">
                        <p>25/05/2014</p>
                    </div>
                </div>
                <div class="row"> 
                    <div class="large-6 columns">
                        <p>Number of nights:</p>
                    </div>
                    <div id="checkin_date" class="large-6 columns">
                        <p>2</p>
                    </div>
                </div>
                <div class="row"> 
                    <div class="large-6 columns">
                        <p>Number of rooms:</p>
                    </div>
                    <div id="checkin_date" class="large-6 columns">
                        <p>2</p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <b><h5>Room 1</h5></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <p>Room Type:</p>
                    </div>
                    <div class="large-6 columns">
                        <p>Single</p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <p>Reservation Type:</p>
                    </div>
                    <div class="large-6 columns">
                        <p>Value Pack</p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <b><h5>Room 2</h5></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <p>Room Type:</p>
                    </div>
                    <div class="large-6 columns">
                        <p>Single</p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <p>Reservation Type:</p>
                    </div>
                    <div class="large-6 columns">
                        <p>Breakfast Only</p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <b><h4>Costs</h4></b>
                    </div>
                </div>
                <div class="row">
                    <div class="large-4 columns">
                        <p>Rooms</p>
                    </div>
                    <div class="large-4 columns">
                        <p>USD 440</p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-4 columns">
                        <p>Tax</p>
                    </div>
                    <div class="large-4 columns">
                        <p>USD 50</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="large-4 large-offset-4 columns">
                        <p>Total Cost</p>
                    </div>
                    <div class="large-4 columns">
                        <p>USD 490</p>
                    </div>
                </div>
            </div>
            -->
        </div>
        <div class="row">
            <div class="large-6 columns">
                <input type="submit" name="submit" value="Book Now" class="send button">
            </div>
        </div>
    </div>';  
        return $form;
    }
}
/* End of file pi.infhotel.php */
/* Location: ./system/expressionengine/third_party/infhotel/pi.infhotel.php */