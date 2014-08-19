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
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetTipoTarjeta';
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
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetTipoDocumento';
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
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetPais';
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
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetHabitacionTarifa';
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
        $fecha_checkin = str_replace("/", "",$fecha_checkin);
        $fecha_checkout = str_replace("/", "",$fecha_checkout);
        $url = 'http://190.41.151.102/Infhotel/ServiceReservaWeb.svc/GetHabitacionesDisponiblesDetallado/'.$fecha_checkin.'/'.$fecha_checkout;
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
                                    <a class="text-center" href="#"><span>+</span></a>
                                  </figure>
                                </div>
                                <div class="large-5 columns room-description">
                                  <h2 id="type_hab_simple_garden'.$i.'" >GARDEN VIEW</h2>
                                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
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
                                    <a class="text-center" href="#"><span>+</span></a>
                                  </figure>
                                </div>
                                <div class="large-5 columns room-description">
                                  <h2 id="type_hab_doble_garden'.$i.'" >GARDEN VIEW</h2>
                                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
                                </div>
                                <div class="large-3 columns">
                                  <div class="row">
                                    <div class="large-9 large-centered columns">
                                      <input id="final_cost_doble_garden'.$i.'" type="hidden" name="final_cost_doble_garden'.$i.'" value="100"> 
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
                                    <a class="text-center" href="#"><span>+</span></a>
                                  </figure>
                                </div>
                                <div class="large-5 columns room-description">
                                  <h2 id="type_hab_triple_garden'.$i.'" >GARDEN VIEW</h2>
                                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
                                </div>
                                <div class="large-3 columns">
                                  <div class="row">
                                    <div class="large-9 large-centered columns">
                                      <input id="final_cost_triple_garden'.$i.'" type="hidden" name="final_cost_triple_garden'.$i.'" value="100"> 
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
                                    <a class="text-center" href="#"><span>+</span></a>
                                  </figure>
                                </div>
                                <div class="large-5 columns room-description">
                                  <h2 id="type_hab_simple_ocean'.$i.'" >OCEAN VIEW</h2>
                                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
                                </div>
                                <div class="large-3 columns">
                                  <div class="row">
                                    <div class="large-9 large-centered columns">
                                      <input id="final_cost_simple_ocean'.$i.'" type="hidden" name="final_cost_simple_ocean'.$i.'" value="100"> 
                                      <select name="persons_number" id="guests_simple_ocean'.$i.'" required="" pattern="number" data-invalid="">
                                        <option value="1" selected="selected">Single (1 person)</option>
                                        <option value="2">Double (2 persons)</option>
                                        <option value="3">Triple (3 persons)</option>
                                      </select>
                                      <h2 id="cost_simple_ocean'.$i.'" class="text-center">USD 120/night</h2>
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
                                    <a class="text-center" href="#"><span>+</span></a>
                                  </figure>
                                </div>
                                <div class="large-5 columns room-description">
                                  <h2 id="type_hab_doble_ocean'.$i.'" >OCEAN VIEW</h2>
                                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
                                </div>
                                <div class="large-3 columns">
                                  <div class="row">
                                    <div class="large-9 large-centered columns">
                                      <input id="final_cost_doble_ocean'.$i.'" type="hidden" name="final_cost_doble_ocean'.$i.'" value="100">
                                      <select name="persons_number" id="guests_doble_ocean'.$i.'" required="" pattern="number" data-invalid="">
                                        <option value="1" selected="selected">Single (1 person)</option>
                                        <option value="2">Double (2 persons)</option>
                                        <option value="3">Triple (3 persons)</option>
                                      </select>
                                      <h2 id="cost_doble_ocean'.$i.'" class="text-center">USD 120/night</h2>
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
                                    <a class="text-center" href="#"><span>+</span></a>
                                  </figure>
                                </div>
                                <div class="large-5 columns room-description" >
                                  <h2 id="type_hab_triple_ocean'.$i.'" >OCEAN VIEW</h2>
                                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
                                </div>
                                <div class="large-3 columns">
                                  <div class="row">
                                    <div class="large-9 large-centered columns">
                                    <input id="final_cost_triple_ocean'.$i.'" type="hidden" name="final_cost_triple_ocean'.$i.'" value="100">
                                      <select name="persons_number" id="guests_triple_ocean'.$i.'" required="" pattern="number" data-invalid="">
                                        <option value="1" selected="selected">Single (1 person)</option>
                                        <option value="2">Double (2 persons)</option>
                                        <option value="3">Triple (3 persons)</option>
                                      </select>
                                      <h2 id="cost_triple_ocean'.$i.'" class="text-center">USD 120/night</h2>
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
        return $response; 
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
        return "your reservation code is <b>".$result."</b>";
    }
    public function reservation_3(){
        $response= '
        <div id="contenedor">
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
                    <p>Phone reservations +511-440-6040</p>
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
                          <input type="submit" class="button tiny send expand" value="check again">
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
                      <div class="large-3 columns"><span class="circle active">3</span><p>GUEST INFORMATION</p></div>
                      <div class="large-3 columns"><span class="circle">4</span><p>CONFIRMATION</p></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end breadscrumbs -->
            <!-- end reservation-header -->

            <div class="row">
              <div class="large-12 columns">
                <h4>ADD - ONS</h4>
              </div>

              <!-- row-container -->
              <div class="large-12 columns">
                <div class="row">
                  <div class="large-9 columns">
                    <p>Add additional services that we offer.</p>
                    <form action="/?/content/newreservation4" class="book_a_room" method="POST">
                        <input type="hidden" name="XID" value="{XID_HASH}" /> 
                    <!-- add-ons list -->
                    <div class="row">
                      <div class="large-11 columns">
                        <!-- add-on item -->
                        <div class="row item-addon">
                          <!-- add-on img -->
                          <div class="large-5 columns">
                            <figure>
                              <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                              <a class="text-center" href="#"><span>+</span></a>
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
                                  </select>
                                </p>
                              </div>
                              <div class="large-4 columns">
                                <p id="encabezado_lunch_and_dinner" class="addon-cost right">$100.00</p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="large-12 columns">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
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
                              <img src="http://chicamasurf.com/images/imagenes_secciones/22/the_rooms__small.jpg" alt="">
                              <a class="text-center" href="#"><span>+</span></a>
                            </figure>
                          </div>
                          <!-- end add-on img -->
                          <!-- add-on description -->
                          <div class="large-7 columns addon-description">
                            <h2>TRANSPORT</h2>
                            <div class="row selector">
                              <div class="large-7 columns">
                                <p>Number of persons
                                  <select id="transport">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                  </select>
                                </p>
                              </div>
                              <div class="large-4 columns">
                                <p id="encabezado_transport" class="addon-cost right">$100.00</p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="large-12 columns">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro, accusantium? Quod facere laborum laboriosam, voluptatum quas, possimus optio quam rem maxime omnis, nesciunt repellat excepturi, eum neque! Provident, incidunt!</p>
                              </div>

                              <div class="large-3 columns right">
                                <a id="add_transport_buttom" href="#" class="add expand button active">Add</a>
                                <input style="display:none" id="transport_checkbox" name="transport_checkbox" type="checkbox">
                              </div>
                            </div>
                          </div>
                          <!-- end add-on description -->
                        </div>
                      </div>
                      <input type="hidden" name="lunch_and_dinner_input" maxlength="1000"  id="lunch_and_dinner_input">
                      <input type="hidden" name="transport_input" maxlength="1000"  id="transport_input">
                      <input type="hidden" name="rooms_input" maxlength="1000"  id="rooms_input">
                      <input type="hidden" name="request" maxlength="1000"  id="full_request">
                      <input type="hidden" name="purchase_amount" maxlength="1000"  id="purchase_amount">
                      <input type="hidden" name="summary" maxlength="1000"  id="summary_input">
                      <button id="submit_paso3" type="submit" class="send">Continue</button> 
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
                          <h2 class="text-center">YOUR STAY</h2>
                          <hr>
                          {exp:get_post_vars parse="inward"}
                          <p class="ys-label">ARRIVAL:</p>
                          <p class="ys-field">{post_check_in_date}</p>
                          <p class="ys-label">DEPARTURE:</p>
                          <p class="ys-field">{post_check_out_date}</p>
                                          
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
                          <p id="transport_summary" class="ys-field"></p>
                          
                          <hr>

                          <p id="total_summary" class="ys-label">TOTAL <span>$ 600.00</span></p>
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