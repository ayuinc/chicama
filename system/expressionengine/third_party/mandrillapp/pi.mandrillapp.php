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

	$full_request = str_replace("$", "{", $full_request);
	$full_request = str_replace("&", "}", $full_request);
	$full_request = str_replace('(', '"', $full_request);
	$full_request = str_replace(")", ":", $full_request);
	$full_request = str_replace("?", " ", $full_request);
	$full_request = str_replace("¿", ",", $full_request);
	$data = json_decode($full_request, true);
	
	$habitaciones_array = $data['Habitaciones'];
	$habitaciones_detalle = "";
	$habitaciones_precio = "";
		foreach ($habitaciones_array as $hab) {
			$serial = $hab['TCodigoHabitacion'];
			ee()->db->select('*');
			ee()->db->where('serial',$serial);
			$query = ee()->db->get('exp_hotel_products');
			foreach($query->result() as $row){
			  $room_cod = $row->room_cod;
			  $cost = $row->cost;
			}
			switch ($room_cod) {
				case 'rmsg01':
				case 'rmsg02':
				case 'rmsg03':
					$habitaciones_detalle .= '<td>: 01 habitación simple con vista al jardin </td>';
					$habitaciones_precio .= '<td>: Bed & Breakfast Paxs Directos Simple Garden View  (US$ 100.00 por habitación x noche)</td>';
				break;
				case 'rmso01':
				case 'rmso02':
				case 'rmso03':
					$habitaciones_detalle .= '<td>: 01 habitación simple con vista al mar </td>';
					$habitaciones_precio .= '<td>: Bed & Breakfast Paxs Directos Simple Oean View  (US$ 110.00 por habitación x noche)</td>';
				break;
				case 'rmdg01':
				case 'rmdg02':
				case 'rmdg03':
					$habitaciones_detalle .= '<td>: 01 habitación doble con vista al jardin </td>';
					$habitaciones_precio .= '<td>: Bed & Breakfast Paxs Directos Double Garden View  (US$ 130.00 por habitación x noche)</td>';
				break;
				case 'rmdo01':
				case 'rmdo02':
				case 'rmdo03':
					$habitaciones_detalle .= '<td>: 01 habitación doble con vista al mar </td>';
					$habitaciones_precio .= '<td>: Bed & Breakfast Paxs Directos Double Ocean View  (US$ 140.00 por habitación x noche)</td>';
				break;
				case 'rmtg01':
				case 'rmtg02':
				case 'rmtg03':
					$habitaciones_detalle .= '<td>: 01 habitación triple con vista al jardin </td>';
					$habitaciones_precio .= '<td>: Bed & Breakfast Paxs Directos Triple Garden View  (US$ 160.00 por habitación x noche)</td>';
				break;
				case 'rmto01':
				case 'rmto02':
				case 'rmto03':
					$habitaciones_detalle .= '<td>: 01 habitación triple con vista al mar </td>';
					$habitaciones_precio .= '<td>: Bed & Breakfast Paxs Directos Triple Ocean View  (US$ 180.00 por habitación x noche)</td>';
				break;
			}
			
		}
	}
	

	$to = $email;
	$name= $first_name." ".$last_name;
	$subject= "Reserva de habitaciones - Chicama Boutique Hotel.";
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
						<p>Gracias por su preferencia por el Chicama Boutique Hotel & Spa</p>
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
				<p>Estimado Sr(a).'.$name.',</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Por medio del presente, le confirmamos la(s) reserva(s) solicitada (s):<br>
				<b>HORA CHECK IN : 14:00</b> <b>HRS HORA CHECK OUT : 12:00 HRS.</b></p>			
			</td>
		</tr>
		<table>
		  <tr>
		    <td>Código de reserva</td>
		    <td>: '.$cod_reservation.'</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Estadía</td>
		    <td>: Check in:'.$data['FLlegada'].' Check out: '.$data['FSalida'].'</td>
		    <td> </td>
		  </tr>
		  <tr>
		    <td>Pasajeros</td>
		    <td>: '.$name.' (Responsable)</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Tipo de habitación</td>
		    <td>: 01 habitación triple con vista al mar (twin beds)</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Tarifa</td>
		    <td>: Bed & Breakfast Paxs Directos Ocean View con 20% dscto (US$ 144.00 por habitación x noche)</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Servicios adicionales</td>
		    <td>: Traslados aeropuerto de Trujillo / hotel / aeropuerto de Trujillo</td>
		    <td></td>
		  </tr>
		</table>
		<tr>
			<td>
				<p>Transfer in : 29/05 Lan 2204 20:25 – 21:40 hrs<br>
				Transfer out : 02/06 Lan 2209 13:05 hrs</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Detalles a continuación los siguientes detalles:</p>
			</td>
		</tr>	
		<table>
		  <tr>
		    <td>Habitación triple</td>
		    <td>: US$ 144.00 x 04 noches</td>
		    <td>: US$ 576.00</td>
		  </tr>
		  <tr>
		    <td>Traslados  </td>
		    <td>: US$ 25.00 x 03 personas x 02 idas</td>
		    <td>: US$ 150.00</td>
		  </tr>
		  <tr>
		    <td>Total de la reserva</td>
		    <td> : US$ 726.00</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td>Pre pago realizado</td>
		    <td> : US$ 144.00</td>
		    <td></td>
		  </tr>
		  <tr>
		    <td><b>Saldo a pagar en el hotel </b></td>
		    <td><b>: US$ 582.00</b></td>
		    <td></td>
		  </tr>
		</table>
		<table>
			<tr>
				<td>
					<p><b>La tarifa mencionada incluye:</b> Desayuno Buffet; Wireless Service en las habitaciones; Uso de las instalaciones del 
					hotel: Saunas, Jacuzzis, Gimnasio, Piscina, Sala de Tv, Sala de Juegos y Estacionamiento Privado. <br>
					<b>La tarifa mencionada no incluye:</b> Almuerzos ni Cenas a la Carta; Bebidas (alcohólicas y no alcohólicas); Towing 
					Service – Zodiac; Otros traslados; Masajes; Tours; Propinas; Early check in y late check out; Consumos no 
					mencionados.</p>	
				</td>
			</tr>
			<tr>
				<td>
					<p><b>Nuestra política de anulación y/o postergación es de 48 horas antes de la fecha de arribo del pasajero(s). En caso 
					contrario, la reserva sea anulada y/o postergada fuera del plazo indicado y/o el (los) pasajero (s) no se presente en 
					el hotel, en la fecha indicada (NO SHOW). Se cobrara la penalidad por el valor de la primera noche de 
					alojamiento más gastos administrativos.</b></p>
					<p><b>En caso de haber solicitado el servicio de traslado, la cancelación y/o modificación del mismo es de 24 horas antes 
					de la fecha de arribo del pasajero. En caso contrario, el servicio de traslado sea cancelado y/o modificado fuera del 
					plazo indicado y/o el (los) pasajero (s) no se presente en la fecha indicada (NO SHOW). Se cobrara la penalidad por 
					el valor del transfer in.</b></p>	
				</td>
			</tr>
			<tr>
				<td>
					<p>Somos un hotel NO fumador (habitaciones y áreas públicas).Tipo de cambio Hotelero S/.2.80 (Precio referencial puede variar sin previo aviso).</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>Le recomendamos imprimir este documento y presentarlo al momento de su llegada al hotel. Si tuviera alguna consulta adicional previa a su llegada, no dude en contactarse con nosotros. Teléfonos de emergencia de oficina Lima: 994668590, del hotel: 986645895 y de reservas 940482207.</p>
				</td>
			</tr>
			<tr>
				<td>Saludos!
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