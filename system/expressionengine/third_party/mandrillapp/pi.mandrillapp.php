<?php 

$plugin_info = array(
						'pi_name'			=> 'Mandrillapp',
						'pi_version'		=> '1.0',
						'pi_author'			=> 'Gianfranco Montoya',
						'pi_author_url'		=> 'http://ayuinc.com',
						'pi_description'	=> 'Envia mensajes usando el API de Mandrillapp - https://mandrillapp.com',
						'pi_usage'			=> Mandrillapp::usage()
					);

/**
 * Send_email class
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Gianfranco Montoya
 * @copyright		Copyright (c) 2014 Engaging.net
 * @link			--
 */

class Mandrillapp {

	function usage()
	{
		ob_start(); 
		?>
			See the documentation at http://www.engaging.net/docs/send-email
		<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	function send_email_reserva_chicama(){
	global $TMPL;
	$this->EE =& get_instance(); // EEv2 syntax
	$TMPL = $this->EE->TMPL;

	require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
	$mandrill = new Mandrill('u1hYP2cmJFlaSQ9-wxcd5g');
	$id = $TMPL->fetch_param('id');

	ee()->db->select('*');
	ee()->db->where('id',$id);
	$query = ee()->db->get('exp_hotel_reservations');
	var_dump($query);
	foreach($query->result() as $row){
		$full_request = $row->full_request;
		$cod_reservation = $row->cod_reservation;
		$request = $row->full_request;
		$first_name = $row->first_name;
		$last_name = $row->last_name;
		$country = $row->country;
		$document_id = $row->document_id;
		$document_type = $row->document_type;
		$card_id = $row->card_id;
		$card_type = $row->card_type;
		$email = $row->email;
		$add_on_1 = $row->zodiacs;
		$add_on_2 = $row->transport;
		$add_on_3 = $row->lunch_and_dinner;
		$cost_reser = $row->amount_reservation;
		$days = $row->num_days;
	}
	var_dump($full_request);
	$full_request = str_replace("$", "{", $full_request);
	$full_request = str_replace("&", "}", $full_request);
	$full_request = str_replace('(', '"', $full_request);
	$full_request = str_replace(")", ":", $full_request);
	$full_request = str_replace("?", " ", $full_request);
	$full_request = str_replace("¿", ",", $full_request);
	$data = json_decode($full_request, true);

	if($add_on_1 != ''){
		ee()->db->select('*');
		ee()->db->where('serial', $add_on_1);
		$query_lunch_and_dinner = ee()->db->get('exp_hotel_products');
		if($query != null){
		  foreach($query_lunch_and_dinner->result() as $row_lunch_and_dinner){
		    $add_on_1 = $row_lunch_and_dinner->description;
		    $add_on_1_detail = '<tr><td>'.$row_lunch_and_dinner->detail.'</td><td>: US$ '.substr($row_lunch_and_dinner->cost, 0, -2).'.00 per night per '.$days.' nights</td><td>: US$ '.substr($row_lunch_and_dinner->cost*$days, 0, -2).'.00</td></tr>';
		  }
		} 
		//$add_on_1 = '<tr><td></td><td>'.$add_on_1.'</td><td></td></tr>';
	}
	if($add_on_2 != ''){
		ee()->db->select('*');
		ee()->db->where('serial', $add_on_2);
		$query_transport = ee()->db->get('exp_hotel_products');
		if($query != null){
		  foreach($query_transport->result() as $row_transport){
		    $add_on_2 = $row_transport->description;
		    $add_on_2_detail = '<tr><td>'.$row_transport->detail.'</td><td>: US$ '.substr($row_transport->cost, 0, -2).'.00</td><td> </td></tr>';
		  }
		} 
		//$add_on_2 = '<tr><td></td><td>'.$add_on_2.'</td><td></td></tr>';
	}
	if($add_on_3 != ''){
		ee()->db->select('*');
		ee()->db->where('serial', $add_on_3);
		$query_zodiacs = ee()->db->get('exp_hotel_products');
		if($query != null){
		  foreach($query_zodiacs->result() as $row_zodiacs){
		    $add_on_3 = $row_zodiacs->description;
		    $add_on_3_detail = '<tr><td>'.$row_zodiacs->detail.'</td><td>: US$ '.substr($row_zodiacs->cost, 0, -2).'.00 one time only</td><td> </td></tr>';
		  }
		} 
		//$add_on_3 = '<tr><td></td><td>'.$add_on_3.'</td><td></td></tr>';
	}

	$habitaciones_array = $data['Habitaciones'];
	$habitaciones_detalle = "";
	$habitaciones_precio = "";
	$resumen_reserva= "";
	foreach ($habitaciones_array as $hab) {
		$cost_aux = 0;
		$serial = $hab['TCodigoHabitacion'];
		ee()->db->select('*');
		ee()->db->where('serial',$serial);
		$query = ee()->db->get('exp_hotel_products');
		foreach($query->result() as $row){
		  $serial= $row->serial;
		  $cost = $row->cost;
		}
		switch ($serial) {
			case 'rmsg01':
			case 'rmsg02':
			case 'rmsg03':
				$cost_aux = $days * 100;
				$habitaciones_detalle .= '<tr><td></td><td> 01 single room with garden view</td><td></td></tr>';
				$habitaciones_precio .= '<tr><td></td><td> Bed & Breakfast Single Room Garden View (US$ 100.00 per night per room)</td><td></td></tr>';
				$resumen_reserva .= '<tr><td>Simple room garden view</td><td>: US$ 100.00 x '.$days.' nights</td><td>: US$ '.$cost_aux.'.00</td></tr>';
			break;
			case 'rmso05':
			case 'rmso06':
			case 'rmso07':
				$cost_aux = $days * 110;
				$habitaciones_detalle .= '<tr><td></td><td> 01 single room with ocean view </td><td></td></tr>';
				$habitaciones_precio .= '<tr><td></td><td> Bed & Breakfast Single Room  Ocean View  (US$ 110.00 per night per room)</td><td></td></tr>';
				$resumen_reserva .= '<tr><td>Simple room ocean view</td><td>: US$ 110.00 x '.$days.' nights</td><td>: US$ '.$cost_aux.'.00</td></tr>';
			break;
			case 'rmdg01':
			case 'rmdg02':
			case 'rmdg03':
				$cost_aux = $days * 130;
				$habitaciones_detalle .= '<tr><td></td><td> 01 double room with garden view </td><td></td></tr>';
				$habitaciones_precio .= '<tr><td></td><td>Bed & Breakfast Double Room Garden View (US$ 130.00 per night per room)</td><td></td></tr>';
				$resumen_reserva .= '<tr><td>Double room garden view</td><td>: US$ 130.00 x '.$days.' nights</td><td>: US$ '.$cost_aux.'.00</td></tr>';
			break;
			case 'rmdo05':
			case 'rmdo06':
			case 'rmdo07':
				$cost_aux = $days * 140;
				$habitaciones_detalle .= '<tr><td></td><td> 01 double room with ocean view </td><td></td></tr>';
				$habitaciones_precio .= '<tr><td></td><td> Bed & Breakfast Double Room Ocean View  (US$ 140.00 per night per room)</td><td></td></tr>';
				$resumen_reserva .= '<tr><td>Double room ocean view</td><td>: US$ 140.00 x '.$days.' nights</td><td>: US$ '.$cost_aux.'.00</td></tr>';
			break;
			case 'rmtg01':
			case 'rmtg02':
			case 'rmtg03':
				$cost_aux = $days * 160;
				$habitaciones_detalle .= '<tr><td></td><td> 01 triple room with garden view  </td><td></td></tr>';
				$habitaciones_precio .= '<tr><td></td><td>Bed & Breakfast Triple Room Garden View (US$ 160.00 per night per room)</td><td></td></tr>';
				$resumen_reserva .= '<tr><td>Triple room garden view</td><td>: US$ 160.00 x '.$days.' nights</td><td>: US$ '.$cost_aux.'.00</td></tr>';
			break;
			case 'rmto05':
			case 'rmto06':
			case 'rmto07':
				$cost_aux = $days * 180;
				$habitaciones_detalle .= '<tr><td></td><td> 01 triple room with ocean view </td><td></td></tr>';
				$habitaciones_precio .= '<tr><td></td><td> Bed & Breakfast Triple Room Ocean View  (US$ 180.00 per night per room)</td><td></td></tr>';
				$resumen_reserva .= '<tr><td>Triple room ocean view</td><td>: US$ 180.00 x '.$days.' nights</td><td>: US$ '.$cost_aux.'.00</td></tr>';
			break;
		}
	}

	$cost_reser = $cost_reser/100; 
	//$to = $email;
	$to = 'gms122@gmail.com';
	$name= $first_name." ".$last_name;
	$subject= "Booking information - Chicama Boutique Hotel.";
	$from= "reservas@chicama.com";

	$text = '<div class="container" style="margin: 0 auto;width: 786px;padding: 25px;border: 3px solid #ffc000;text-align: justify;">
	<div class="header" style="padding-left: 77px;">
		<table>
			<thead style="text-align: center;">
				<tr>
					<td class="logo">
						<img src="http://www.cuponium.com/img/fotos/empresa/1322589534_ChicamaLogo.jpg" style="box-shadow: 4px 5px 22px 0px gray;margin: 47px;border-radius: 8px;">
					</td>
				</tr>
				<tr>
					<td>
						<p>Thank you for your preference in Chicama Boutique Hotel & Spa</p>
					</td>
				</tr>
				<tr>
					<td class="header-images">
						<img src="http://s15.postimg.org/gna5l60ez/captura.png" style="padding: 4px;border-top: 2px solid #ffc000;border-bottom: 2px solid #ffc000;text-align: center;">
					</td>
				</tr>
			</thead>
		</table>
	</div>
	<tbody>
		<tr>
			<td>
				<p>Dear Mr(s).'.$name.',</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>We confirm the following reservation (s):<br>
				<break>
				<b>CHECK IN TIME: 14:00 HRS</b> <b>CHECK OUT TIME: 12:00 HRS.</b></p>			
			</td>
		</tr>
		<table>
		  <tr>
		    <td>Reservation code</td>
		    <td>: '.$cod_reservation.'</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Stay</td>
		    <td>: Check in: '.$data['FLlegada'].' Check out: '.$data['FSalida'].'</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Guests</td>
		    <td>: '.$name.'(Responsible)</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Kind of room</td>
		    <td>:</td>
		    <td></td>
		  </tr>
		    '.$habitaciones_detalle.'
		  <tr>
		    <td></td>
		    <td></td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Tarifa</td>
		    <td>: '.$habitaciones_precio.'</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td></td>
		    <td>'.$add_on_1.'<br>'.$add_on_2.'<br>'.$add_on_3.'</td>
		    <td></td>
		  </tr>
		</table>
		<tr>
			<td>
				<!--<p>Transfer in : 29/05 Lan 2204 20:25 – 21:40 hrs<br>
				Transfer out : 02/06 Lan 2209 13:05 hrs</p>-->
			</td>
		</tr>
		<br>
		<tr>
			<td>
				<p>Below details:</p>
			</td>
		</tr>	
		<table>
		  '.$resumen_reserva.'
		  <br>
		  <tr>
		    <td>Add ons:  </td>
		    <td></td>
		    <td></td>
		  </tr>
		  <br>
		  '.$add_on_1_detail.$add_on_2_detail.$add_on_3_detail.'
		  <br>
		  <br>
		  <tr>
		    <td>Reservation total</td>
		    <td> : 	US$ '.$cost_reser.'.00</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Pre-payment</td>
		    <td> : US$ '.$cost_reser.'.00</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td><b>Total to be paid at hotel </b></td>
		    <td><b>: US$ 582.00</b></td>
		    <td></td>
		  </tr>
		</table>
		<table>
			<tr>
				<td>
					<p><b>Rate includes:</b> Accommodation; Buffet Breakfast; Wifi service; Access to our SPA: Jacuzzis, Saunas, Gym; Access
					to Hotel Facilities: Pool, Tv Room, Games Room; Parking lot.<br>
					<b>Rate Does Not Include:</b> Meals: A la carte Lunch & Dinner ;Beverages; Towing Service; Transfers; Tours; Laundry
					Service; Tips; Early check in and late check out; Extras not mentioned.</p>	
				</td>
			</tr>
			<tr>
				<td>
					<p><b>Our cancelation and postponement policy is of 48 hours prior to arrival date. In case cancelation or postponement
					proceeds after this frame dates or the guest (s) does not arrive at the hotel on indicated dates (NO SHOW), we
					charge a penalty of the amount of the first night plus administration expenses.
					In case to be requested the transfer service and make a cancelation or modification without notice us 24 hours
					prior to arrival date, we do not refund the pre-payment for the amount of the transfer in.</b></p>	
				</td>
			</tr>
			<tr>
				<td>
					<p>WE ARE A NON SMOKING HOTEL (ROOMS AND PUBLIC AREAS).Hotel exchange rate S/.2.80 (Referential
					price can change without previous notice).</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>We recommend printing this document for presentation at the hotel. Please do not hesitate to contacts us for further additional information. Emergency phone of Lima´s office: 994668590, hotel: 986645895 and reservations: 940482207.</p>
				</td>
			</tr>
			<tr>
				<td>See you soon!
				</td>
			</tr>
			<tr>
				<td>
					<ul>
						<li>Marie Paredes Herrera</li>
						<li>Sales & Reservations</li>
						<li>Av. Javier Prado Oeste 1650</li>
						<li>Lima 27, Perú.</li>
						<li>Telf : (511) 440-6040</li>
						<li>RPC : 940482207</li>
						<li>reservas@chicamasurf.com</li>
						<li><a href="http://www.chicamasurf.com/">www.chicamasurf.com</a></li>
					</ul>
				</td>
			</tr>
		</table>	
	</tbody>
</div>';
	
	$message = array(
	    'subject' => $subject,
	    'from_email' => $from,
	    'html' => $text,
	    'to' => array(array('email' => $to, 'name' => $name)),
	    'merge_vars' => array(array(
		        'rcpt' => 'recipient1@domain.com',
		        'vars' =>
		        array(
		            array(
		                'name' => 'FIRSTNAME',
		                'content' => 'Recipient 1 first name'),
		            array(
		                'name' => 'LASTNAME',
		                'content' => 'Last name')
		    ))));

	$template_name = 'test';

	$template_content = array(
	    array(
	        'name' => 'main',
	        'content' => 'Hi *|FIRSTNAME|* *|LASTNAME|*, thanks for signing up.'),
	    array(
	        'name' => 'footer',
	        'content' => 'Copyright 2012.')

	);
	$mandrill->messages->sendTemplate($template_name, $template_content, $message);
	return '';
	}
}
// END CLASS