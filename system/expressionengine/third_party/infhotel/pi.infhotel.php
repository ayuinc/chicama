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
        $form = '<select name="card_type" id="tipo_de_tarjeta" > <option value="" selected>SELECT YOUR TYPE CARD</option>';
        $url = 'http://190.41.141.198/Infhotel/ServiceReservaWeb.svc/GetTipoTarjeta';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
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
        $form = '<select name="document_type" id="document_type" > <option value="00" selected>SELECT YOUR TYPE DOCUMENT</option>';
        $url = 'http://190.41.141.198/Infhotel/ServiceReservaWeb.svc/GetTipoDocumento';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
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
        $form = '<select name="country" placeholder="ENTER YOUR COUNTRY" required="">';
        $url = 'http://190.41.141.198/Infhotel/ServiceReservaWeb.svc/GetPais';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        $data = json_decode($result, true);

        foreach($data as $row){
            $codigo_pais = $row["TCodigoPais"];
            $nombre_pais = $row["TDescripcionPais"];
            if($nombre_pais == "Ninguno"){
                $nombre_pais = 'ENTER YOUR COUNTRY';
            }
            $form .= '<option value='.$codigo_pais.'>'.$nombre_pais.'</option>';
        }
        $form = $form.'</select>';
        
        return $form;
    }

    public function tipodehabitacion(){
        $form = '<select name="tipo_de_habitacion" id="tipo_de_habitacion" > <option value="TIPO DE HABITACIÓN" selected>TIPO DE HABITACIÓN</option>';
        $url = 'http://190.41.141.198/Infhotel/ServiceReservaWeb.svc/GetHabitacionTarifa';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
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
        $fecha_checkin = ee()->TMPL->fetch_param('fecha_checkin');
        $fecha_checkout = ee()->TMPL->fetch_param('fecha_checkout');
        $rooms_num = ee()->TMPL->fetch_param('rooms_num');
        if($rooms_num == "5" ){
            $response = "Para la reserva de 5 a más habitaciones, por favor llamar al 511-440-6040 o enviar un email a aaaa@bbbb.com";
        }
        else{
            $fecha_checkin = str_replace("/", "",$fecha_checkin);
            $fecha_checkout = str_replace("/", "",$fecha_checkout);
            $url = 'http://190.41.141.198/Infhotel/ServiceReservaWeb.svc/GetHabitacionesDisponiblesDetallado/'.$fecha_checkin.'/'.$fecha_checkout;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            $result=curl_exec($ch);
            $n=0;
            $data = json_decode($result, true);
            if($data != ""){
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
                    $first_iteration_ocean = $it_simple_ocean;
                    $second_iteration_ocean = $it_simple_ocean + $it_doble_ocean;
                    for ($i=0; $i<$rooms_num ; $i++) { 
                        $response .= '<div class="row" id="rooms'.$i.'">';
                        if ( $i<$garden_view) {
                            if($i<$first_iteration_garden){ //Simple Garden
                                $response .= '<div class="large-12 columns item-room">
                                  <div class="row">
                                    <div class="large-4 columns">
                                      <figure>
                                        <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                                        <a class="text-center" href="#" data-reveal-id="gardenView"><span>+</span></a>
                                      </figure>
                                    </div>
                                    <div class="large-5 columns room-description">
                                      <h2 id="type_hab_simple_garden'.$i.'" >GARDEN VIEW</h2>
                                      <p>10 of our rooms in the first floor have a private terrace with view to our interior gardens full of local flowers and palm trees, perfect place to read a book after surf. Rooms number 1 & 2 are rooms that connect and can easily accommodate a family with small kids.</p>
                                    </div>
                                    <div class="large-3 columns">
                                      <div class="row">
                                        <div class="large-9 large-centered columns">
                                          <input id="final_cost_simple_garden'.$i.'" type="hidden" name="final_cost_simple_garden'.$i.'" value="100"> 
                                          <select name="persons_number" id="guests_simple_garden'.$i.'" required="" pattern="number" data-invalid="">
                                            <option value="1" selected="selected">Single (1 person)</option>
                                            <option value="2">Double (2 persons)</option>
                                            <option value="3">Triple (3 persons)</option>
                                          </select>
                                          <h2 id="cost_simple_garden'.$i.'" class="text-center">USD 100/night</h2>
                                          <button id="add_room_simple_garden'.$i.'" type="button" class="tiny send expand">Select</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                            }
                            else{
                                if($i<$second_iteration_garden){ //Doble Garden
                                     $response .= '<div class="large-12 columns item-room">
                                  <div class="row">
                                    <div class="large-4 columns">
                                      <figure>
                                        <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                                        <a class="text-center" href="#" data-reveal-id="gardenView"><span>+</span></a>
                                      </figure>
                                    </div>
                                    <div class="large-5 columns room-description">
                                      <h2 id="type_hab_doble_garden'.$i.'" >GARDEN VIEW</h2>
                                      <p>10 of our rooms in the first floor have a private terrace with view to our interior gardens full of local flowers and palm trees, perfect place to read a book after surf. Rooms number 1 & 2 are rooms that connect and can easily accommodate a family with small kids.</p>
                                    </div>
                                    <div class="large-3 columns">
                                      <div class="row">
                                        <div class="large-9 large-centered columns">
                                          <input id="final_cost_doble_garden'.$i.'" type="hidden" name="final_cost_doble_garden'.$i.'" value="130"> 
                                          <select name="persons_number" id="guests_doble_garden'.$i.'" required="" pattern="number" data-invalid="">
                                            <option value="1" selected="selected">Single (1 person)</option>
                                            <option value="2">Double (2 persons)</option>
                                            <option value="3">Triple (3 persons)</option>
                                          </select>
                                          <h2 id="cost_doble_garden'.$i.'" class="text-center">USD 100/night</h2>
                                          <button id="add_room_doble_garden'.$i.'" type="button" class="tiny send expand">Select</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }
                                else{//Triple Garden
                                      $response .= '<div class="large-12 columns item-room">
                                  <div class="row">
                                    <div class="large-4 columns">
                                      <figure>
                                        <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                                        <a class="text-center" href="#" data-reveal-id="gardenView"><span>+</span></a>
                                      </figure>
                                    </div>
                                    <div class="large-5 columns room-description">
                                      <h2 id="type_hab_triple_garden'.$i.'" >GARDEN VIEW</h2>
                                      <p>10 of our rooms in the first floor have a private terrace with view to our interior gardens full of local flowers and palm trees, perfect place to read a book after surf. Rooms number 1 & 2 are rooms that connect and can easily accommodate a family with small kids.</p>
                                    </div>
                                    <div class="large-3 columns">
                                      <div class="row">
                                        <div class="large-9 large-centered columns">
                                          <input id="final_cost_triple_garden'.$i.'" type="hidden" name="final_cost_triple_garden'.$i.'" value="160"> 
                                          <select name="persons_number" id="guests_triple_garden'.$i.'" required="" pattern="number" data-invalid="">
                                            <option value="1" selected="selected">Single (1 person)</option>
                                            <option value="2">Double (2 persons)</option>
                                            <option value="3">Triple (3 persons)</option>
                                          </select>
                                          <h2 id="cost_triple_garden'.$i.'" class="text-center">USD 100/night</h2>
                                          <button id="add_room_triple_garden'.$i.'" type="button" class="tiny send expand">Select</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }
                            }
                        }
                        if ($i<$ocean_view) {
                            if($i<$first_iteration_ocean){ //Simple Ocean
                                $response .= ' 
                                <div class="large-12 columns item-room">
                                  <div class="row">
                                    <div class="large-4 columns">
                                      <figure>
                                        <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                                        <a class="text-center" href="#" data-reveal-id="oceanView"><span>+</span></a>
                                      </figure>
                                    </div>
                                    <div class="large-5 columns room-description">
                                      <h2 id="type_hab_simple_ocean'.$i.'" >OCEAN VIEW</h2>
                                      <p>The other 10 rooms are in the second floor. They have an extraordinary view of the bay, which you can appreciate from its private balcony. These rooms are highly requested by guests.</p>
                                    </div>
                                    <div class="large-3 columns">
                                      <div class="row">
                                        <div class="large-9 large-centered columns">
                                          <input id="final_cost_simple_ocean'.$i.'" type="hidden" name="final_cost_simple_ocean'.$i.'" value="110"> 
                                          <select name="persons_number" id="guests_simple_ocean'.$i.'" required="" pattern="number" data-invalid="">
                                            <option value="1" selected="selected">Single (1 person)</option>
                                            <option value="2">Double (2 persons)</option>
                                            <option value="3">Triple (3 persons)</option>
                                          </select>
                                          <h2 id="cost_simple_ocean'.$i.'" class="text-center">USD 110/night</h2>
                                          <button id="add_room_simple_ocean'.$i.'" type="button" class="tiny send expand">Select</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                            }
                            else{
                                if($i<$second_iteration_ocean){ //Doble Ocean
                                    $response .= ' 
                                <div class="large-12 columns item-room">
                                  <div class="row">
                                    <div class="large-4 columns">
                                      <figure>
                                        <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                                        <a class="text-center" href="#" data-reveal-id="oceanView"><span>+</span></a>
                                      </figure>
                                    </div>
                                    <div class="large-5 columns room-description">
                                      <h2 id="type_hab_doble_ocean'.$i.'" >OCEAN VIEW</h2>
                                      <p>The other 10 rooms are in the second floor. They have an extraordinary view of the bay, which you can appreciate from its private balcony. These rooms are highly requested by guests.</p>
                                    </div>
                                    <div class="large-3 columns">
                                      <div class="row">
                                        <div class="large-9 large-centered columns">
                                          <input id="final_cost_doble_ocean'.$i.'" type="hidden" name="final_cost_doble_ocean'.$i.'" value="140">
                                          <select name="persons_number" id="guests_doble_ocean'.$i.'" required="" pattern="number" data-invalid="">
                                            <option value="1" selected="selected">Single (1 person)</option>
                                            <option value="2">Double (2 persons)</option>
                                            <option value="3">Triple (3 persons)</option>
                                          </select>
                                          <h2 id="cost_doble_ocean'.$i.'" class="text-center">USD 140/night</h2>
                                          <button id="add_room_doble_ocean'.$i.'" type="button" class="tiny send expand">Select</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }
                                else{
                                     $response .= ' 
                                <div class="large-12 columns item-room">
                                  <div class="row">
                                    <div class="large-4 columns">
                                      <figure>
                                        <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                                        <a class="text-center" href="#" data-reveal-id="oceanView"><span>+</span></a>
                                      </figure>
                                    </div>
                                    <div class="large-5 columns room-description" >
                                      <h2 id="type_hab_triple_ocean'.$i.'" >OCEAN VIEW</h2>
                                      <p>The other 10 rooms are in the second floor. They have an extraordinary view of the bay, which you can appreciate from its private balcony. These rooms are highly requested by guests.</p>
                                    </div>
                                    <div class="large-3 columns">
                                      <div class="row">
                                        <div class="large-9 large-centered columns">
                                        <input id="final_cost_triple_ocean'.$i.'" type="hidden" name="final_cost_triple_ocean'.$i.'" value="180">
                                          <select name="persons_number" id="guests_triple_ocean'.$i.'" required="" pattern="number" data-invalid="">
                                            <option value="1" selected="selected">Single (1 person)</option>
                                            <option value="2">Double (2 persons)</option>
                                            <option value="3">Triple (3 persons)</option>
                                          </select>
                                          <h2 id="cost_triple_ocean'.$i.'" class="text-center">USD 180/night</h2>
                                          <button id="add_room_triple_ocean'.$i.'" type="button" class="tiny send expand">Select</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }
                            }
                        }
                        $response .= '</div>';
                    }
                }
            }
            else{
                $response = 'Error de conexión intentelo nuevamente';
            }
        }
        return $response; 
    }

    public function insertarreservar(){
        $cod_reservation = "";
        $llegada = "";
        $salida = "";
        $lunch_and_dinner = "";
        $transport = "";
        $zodiacs = "";
        $id = ee()->TMPL->fetch_param('id'); 
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
        $json = str_replace("$", "{", $json);
        $json = str_replace("&", "}", $json);
        $json = str_replace('(', '"', $json);
        $json = str_replace(")", ":", $json);
        $json = str_replace("?", " ", $json);
        $json = str_replace("¿", ",", $json);
        $data = json_decode($json, true);
        $rooms_serials = $data["Habitaciones"];
        for ($i=0; $i < count($rooms_serials); $i++) { 
            $serial = $data["Habitaciones"][$i]["TCodigoHabitacion"];
            ee()->db->select('*');
            ee()->db->where('serial',$serial);
            $query = ee()->db->get('exp_hotel_products');
            foreach($query->result() as $row){
              $room_cod = $row->room_cod;
              $cost = $row->cost;
              }
              $data["Habitaciones"][$i]["CantHab"] = '1';
              $data["Habitaciones"][$i]["TCodigoHabitacion"] = $room_cod;
              $data["Habitaciones"][$i]["NPrecio"] = $cost;
         } 
        $data["Pasajeros"]["0"]= $person;
        $llegada = substr($data["FLlegada"], 0, -13); 
        $salida = substr($data["FSalida"], 0, -13);
        $dias = (strtotime($llegada)-strtotime($salida))/86400;
        $dias = abs($dias); 
        $dias = floor($dias); 

        ee()->db->select('*');
        ee()->db->where('id',$id);
        $query = ee()->db->get('exp_hotel_reservations');
        if($query != null){
          foreach($query->result() as $row){
            ee()->db->select('*');
            ee()->db->where('serial', $row->transport);
            $query_transport = ee()->db->get('exp_hotel_products');
            if($query_transport != null){
              foreach($query_transport->result() as $row_transport){
                $transport = $row_transport->name;
              }
            }

            ee()->db->select('*');
            ee()->db->where('serial', $row->lunch_and_dinner);
            $query_lunch_and_dinner = ee()->db->get('exp_hotel_products');
            if($query != null){
              foreach($query_lunch_and_dinner->result() as $row_lunch_and_dinner){
                $lunch_and_dinner = $row_lunch_and_dinner->name;
              }
            } 

            ee()->db->select('*');
            ee()->db->where('serial', $row->zodiacs);
            $query_zodiacs = ee()->db->get('exp_hotel_products');
            if($query_zodiacs != null){
              foreach($query_zodiacs->result() as $row_zodiacs){
                $zodiacs = $row_zodiacs->name;
              }
            }
          }
        }

        $data_string = json_encode($data, true);
        $url = 'http://190.41.141.198/Infhotel/ServiceReservaWeb.svc/InsertReserva';
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

        $cod_reservation = str_replace('"', '', $result);
        if( strlen($cod_reservation) == 6 ) {
            ee()->db->update(
                'exp_hotel_reservations',
                array(
                    'cod_reservation'  => $cod_reservation
                ),
                array(
                    'id' => $id 
                )
            );
            return '{exp:mandrillapp:send_email_reserva_chicama  
                      id = "'.$id .'"                    }
                    {/exp:mandrillapp:send_email_reserva_chicama}
                    
                    <div class="large-12 columns">
                    <div class="row">
                    <h4 class="text-center sub-title first">thank you. your reservation is complete</h4>
                    <div class="large-6 large-centered columns">
                      <div class="your-stay text-center">
                        <h4 class="text-center sub-title">
                    
                        Your reservation code is <b>'.$result.'</h4>

                        <hr>

                        <p class="">ARRIVAL: <span>'.$llegada.'</span></p>
                        <p class="">DEPARTURE: <span>'.$salida.'</span></p>
                        <p class="">'.$dias.' Nights</p>
                        '.$lunch_and_dinner.$transport.$zodiacs.'
                      </div>
                    </div>
                  </div>
                </div>';
        }
        else{
            ee()->db->update(
                'exp_hotel_reservations',
                array(
                    'cod_reservation'  => 'Error en la reserva'
                ),
                array(
                    'id' => $id 
                )
            );   
            return '{exp:mandrillapp:send_email_reserva_chicama  
                      id_operación= "200"
                      operation_result= "220"
                    }
                    {/exp:mandrillapp:send_email_reserva_chicama}
                    {exp:mandrillapp:send_email_reserva_chicama
                        id_operación= "100"
                        operation_result= "120"
                    }
                    {/exp:mandrillapp:send_email_reserva_chicama}
                    <div class="large-12 columns">
                    <div class="row">
                    <h4 class="text-center sub-title first">sorry! </h4>
                    <div class="large-6 large-centered columns">
                      <div class="your-stay text-center">
                        <h4 class="text-center sub-title">
                        There was a problem with your reservation</h4>

                        <hr>

                        <p> Your credit card will be reimburse</p>
                        <p> If you have problem call 511-440-6040</p>

                      </div>
                    </div>
                  </div>
                </div>';
        }
    }
    
    public function reservation_3(){
        $response= '
        <div id="contenedor">
        <div id="add1" class="reveal-modal medium" data-reveal>
          <img src="http://chicamasurf.com/img/addon1.png" alt="">
          <a class="close-reveal-modal">&#215;</a>
        </div>
        <div id="add2" class="reveal-modal medium" data-reveal>
          <img src="http://chicamasurf.com/img/addon2.png" alt="">
          <a class="close-reveal-modal">&#215;</a>
        </div>
        <div id="add3" class="reveal-modal medium" data-reveal>
          <img src="http://chicamasurf.com/img/addon3.png" alt="">
          <a class="close-reveal-modal">&#215;</a>
        </div>
          <div id="form_box_3_container" class="large-12 large-centered columns"> 
          <div id="form_box_3">
            <!-- reservation-header -->
            <div class="row">
              <div id="title_reservation" class="large-12 columns">
                <div class="row">
                  <div class="large-4 columns">
                    <h3>NEW RESERVATION</h3>
                  </div>
                  <div class="large-4 large-offset-4 columns">
                    <p class="phone">Phone reservations +511-440-6040</p>
                  </div>
                </div>
                <!-- check-in form -->
                <div class="row">
                  <div class="large-9 large-centered columns">
                    <form class="book_a_room" action="/?/content/newreservation2" method="POST">
                      <input type="hidden" name="XID" value="{XID_HASH}" /> 
                      <!-- check-in inputs row -->
                      <div class="row">
                        {exp:get_post_vars parse="inward"}
                    <div class="large-3 columns">
                      <input type="text" id="check_in_date" name="check_in_date" placeholder="CHECK-IN" value="{post_check_in_date}">
                    </div>
                    <div class="large-3 columns">
                      <input type="text" id="check_out_date" name="check_out_date" placeholder="CHECK-OUT" value="{post_check_out_date}">
                    </div>
                    <div class="large-1 columns">
                      <label for="right-label" class="inline">Rooms</label>
                    </div>
                    <div class="large-2 columns">
                      <select name="room_number"  pattern="number" data-invalid="">
                        {if {post_room_number} == "1"}
                        <option selected value="1">1</option> 
                        {if:else}
                        <option value="1">1</option>
                        {/if}
                        {if {post_room_number} == "2"}
                        <option selected value="2">2</option> 
                        {if:else}
                        <option value="2">2</option>
                        {/if}
                        {if {post_room_number} == "3"}
                        <option selected value="3">3</option> 
                        {if:else}
                        <option value="3">3</option>
                        {/if}
                        {if {post_room_number} == "4"}
                        <option selected value="4">4</option> 
                        {if:else}
                        <option value="4">4</option>
                        {/if}
                      </select>
                    </div>
                     {/exp:get_post_vars}
                        <div class="large-3 columns end">
                          <input type="submit" class="button tiny send expand" value="search again">
                        </div>
                      </div>
                      <!-- end check-in inputs row -->
                    </form>
                  </div>
                </div>
                <!-- end check-in form -->
              </div>
            </div>

            <!-- breadscrumbs -->
            <div class="row">
              <div class="large-12 columns">
                <div class="row" id="steps_list">
                  <div class="large-9 large-centered columns text-center">
                    <div class="row">
                      <div class="large-3 columns"><span class="circle done">1</span><p>SELECT ROOMS</p></div>
                      <div class="large-3 columns"><span class="circle active">2</span><p>ADD SERVICE OPTIONS</p></div>
                      <div class="large-3 columns"><span class="circle ">3</span><p>GUEST INFORMATION</p></div>
                      <div class="large-3 columns"><span class="circle last">4</span><p>CONFIRMATION</p></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end breadscrumbs -->
            <!-- end reservation-header -->

            <div class="row">
              <!-- <div class="large-12 columns">
                <h4>ADD - ONS</h4>
              </div> -->

              <!-- row-container -->
              <div class="large-12 columns">
                <div class="row">
                  <div class="large-9 columns">
                    <form action="/?/content/newreservation4" class="book_a_room" method="POST">
                    <h4>ADD - ONS</h4>
                    <p>Add additional services that we offer.</p>
                        <input type="hidden" name="XID" value="{XID_HASH}" /> 
                    <!-- add-ons list -->
                    <div class="row">
                      <div class="large-11 columns">
                        <!-- add-on item -->
                        <div class="row item-addon">
                          <!-- add-on img -->
                          <div class="large-5 columns">
                            <figure>
                              <img src="img/addon1.png" alt="">
                              <!-- <a class="text-center" href="#" data-reveal-id="add1"><span>+</span></a> -->
                            </figure>
                          </div>
                          <!-- end add-on img -->
                          <!-- add-on description -->
                          <div class="large-7 columns addon-description">
                            <h2>LUNCH AND DINNER</h2>
                            <div class="row selector">
                              <div class="large-7 columns">
                                <p>Number of persons
                                  <select id="lunch_and_dinner">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <!-- <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option> -->
                                  </select>
                                </p>
                              </div>
                              <div class="large-4 columns">
                                <p id="encabezado_lunch_and_dinner" class="addon-cost right">$60.00</p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="large-12 columns">
                                <p>1 Lunch + 1 Dinner + 1 Non-Alcoholic Beverage per Guest: You can choose from any Entrée, Main Course and Dessert from any of our 48 exquisite dishes. Remember it is only 1 of each, for example you may not choose 2 main courses.
Note: If you don’t choose to Include meals, then you have the option to choose the dish of your preference and just pay for what you eat at the time of your check out.</p>
                              </div>

                              <div class="large-4 columns right">
                                <a id="add_lunch_and_dinner_buttom" href="#" class="add expand button">Add</a>
                                <input style="display:none" id="lunch_and_dinner_checkbox" name="lunch_and_dinner_checkbox" type="checkbox">
                              </div>
                            </div>
                          </div>
                          <!-- end add-on description -->
                        </div>
                        <!-- end add-on item -->
                        
                        <!-- add-on item -->
                        <div class="row item-addon">
                          <!-- add-on img -->
                          <div class="large-5 columns">
                            <figure>
                              <img src="img/addon2.png" alt="">
                              <!-- <a class="text-center" href="#" data-reveal-id="add2"><span>+</span></a> -->
                            </figure>
                          </div>
                          <!-- end add-on img -->
                          <!-- add-on description -->
                          <div class="large-7 columns addon-description">
                            <h2>TOW-BACK SERVICE</h2>
                            <div class="row selector">
                              <div class="large-7 columns">
                                <p>Number of persons
                                  <select id="tow_back_service">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <!-- <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option> -->
                                  </select>
                                </p>
                              </div>
                              <div class="large-4 columns">
                                <p id="encabezado_tow_back_service" class="addon-cost right">$30.00</p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="large-12 columns">
                                <p>For Surfers we provide the Tow-back Service with our Zodiacs. During a day the Zodiacs service is provided during 2 periods, the 1st period starts 8:30am and ends at 12:00pm, the second period starts at 3:00pm and ends at 6:00pm. We recommend you book this service while being at the resort, that way you can choose to use it or not depending on the surf conditions.</p>
                              </div>

                              <div class="large-4 columns right">
                                <a id="add_tow_back_service_buttom" href="#" class="add expand button">Add</a>
                                <input style="display:none" id="tow_back_service_checkbox" name="tow_back_service_checkbox" type="checkbox">
                              </div>
                            </div>
                          </div>
                          <!-- end add-on description -->
                        </div>
                        <!-- end add-on item -->

                        <!-- add-on item -->
                        <div class="row item-addon">
                          <!-- add-on img -->
                          <div class="large-5 columns">
                            <figure>
                              <img src="img/addon3.png" alt="">
                              <!-- <a class="text-center" href="#" data-reveal-id="add3"><span>+</span></a> -->
                            </figure>
                          </div>
                          <!-- end add-on img -->
                          <!-- add-on description -->
                          <div class="large-7 columns addon-description">
                            <h2>AIRPORT TO HOTEL TRANSPORTATION</h2>
                            <div class="row selector">
                              <div class="large-7 columns">
                                <p>Number of persons
                                  <select id="transport">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <!-- <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option> -->
                                  </select>
                                </p>
                              </div>
                              <div class="large-4 columns">
                                <p id="encabezado_transport" class="addon-cost right">$48.00</p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="large-12 columns">
                                <p>Chicama is located about 70 km North of Trujillo, which we consider our hub for guests arrivingfrom Lima. Guests may arrive by plane or bus. Both the airport and bus stations are based in Trujillo city, so to get to Chicama you will require a proper and comfortable transfer, which will 
take about 1 hour. Our professional drivers carry with them a sign that will have your names on it and will identify him as crew member of the Chicama Surf Resort. Prices for this service change depending on the amount of guest in one reservation.</p>
                              </div>

                              <div class="large-4 columns right">
                                <a id="add_transport_buttom" href="#" class="add expand button active">Add</a>
                                <input style="display:none" id="transport_checkbox" name="transport_checkbox" type="checkbox">
                              </div>
                            </div>
                          </div>
                          <!-- end add-on description -->
                        </div>
                        <input type="hidden" name="num_dias" maxlength="1000"  id="num_dias">
                        <input type="hidden" name="lunch_and_dinner_input" maxlength="1000"  id="lunch_and_dinner_input">
                        <input type="hidden" name="transport_input" maxlength="1000"  id="transport_input">
                        <input type="hidden" name="tow_back_service_input" maxlength="1000"  id="tow_back_service_input">

                        <input type="hidden" name="lunch_and_dinner_serial" maxlength="1000" id="lunch_and_dinner_serial">
                        <input type="hidden" name="transport_serial" maxlength="1000" id="transport_serial">
                        <input type="hidden" name="tow_back_service_serial" maxlength="1000" id="tow_back_service_serial">
                        <input type="hidden" name="numero_de_personas" maxlength="1000" id="numero_de_personas">
                        
                        <input type="hidden" name="rooms_input" maxlength="1000"  id="rooms_input">
                        <input type="hidden" name="request" maxlength="1000"  id="full_request">
                        <input type="hidden" name="purchase_amount" maxlength="1000"  id="purchase_amount">
                        <input type="hidden" name="summary" maxlength="1000"  id="summary_input">
                        {exp:get_post_vars parse="inward"}
                        <input type="hidden" name="check_in_date" value="{post_check_in_date}">
                        <input type="hidden" name="check_out_date" value="{post_check_out_date}">
                        <input type="hidden" name="room_number" value="{post_room_number}">
                        {/exp:get_post_vars}
                        <button id="submit_paso3" type="submit" class="send right">Continue</button> 
                      </div>
                      
                    </div>
                    <!-- end add-on item -->
                  </div>
                  </form>
                  <!-- end add-ons list -->
                  <!-- your stay -->
                  <div class="large-3 columns">
                    <div class="row">
                      <div class="large-12 large-centered columns">
                        <div id="reserva_summary" class="your-stay">
                          <h2 class="text-center">SUMMARY</h2>
                          <hr>
                          {exp:get_post_vars parse="inward"}
                          <p class="ys-label">ARRIVAL:</p>
                          <p id="arrival_date" class="ys-field">{post_check_in_date}</p>
                          <p class="ys-label">DEPARTURE:</p>
                          <p id="departure_date" class="ys-field">{post_check_out_date}</p>
                          <p id="dias_num_encabezado" class="ys-label"></p>                
                          {/exp:get_post_vars}
                          <hr>

                          <p id="title_room1_summary"class="ys-label"></p>
                          <p id="cost_room1_summary" class="ys-field"></p>
                          <p id="title_room2_summary" class="ys-label"></p>
                          <p id="cost_room2_summary" class="ys-field"></p>
                          <p id="title_room3_summary" class="ys-label"></p>
                          <p id="cost_room3_summary" class="ys-field"></p>
                          <p id="title_room4_summary" class="ys-label"></p>
                          <p id="cost_room4_summary" class="ys-field"></p>
                          <p class="ys-label">ADD - ONS:</p>
                          <p id="lunch_and_dinner_summary" class="ys-field"></p>
                          <p id="tow_back_service_summary" class="ys-field"></p>
                          <p id="transport_summary" class="ys-field"></p>
                          
                          <hr>

                          <p id="total_summary_per_night" class="ys-label"></p>
                          <p id="dias_num" class="ys-label"></p>
                          <p id="total_summary" class="ys-label"></p>
                          <p id="total_summary_per_person" class="ys-label"></p>
                          <p id="total_summary_per_room" class="ys-label"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end your stay -->
                </div>
              </div>
              <!-- end container -->
            </div>
          </div>
      </div>
    </div>';
            return $response;
    }
}
/* End of file pi.infhotel.php */
/* Location: ./system/expressionengine/third_party/infhotel/pi.infhotel.php */