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
	$id_operación= $TMPL->fetch_param('id_operación');
	$operación_result= $TMPL->fetch_param('operation_result');
	$to= "gms122@gmail.com";
	$name= "Gianfranco";
	$subject= "Solicitud de documento.";
	$from= "admin@gym.com";
	//$text = $TMPL->tagdata;
	$text = 'Estimado(a) Cliente,<p>
	id_operación: '.$id_operación.'<p>.
	operación_result: '.$operación_result.'<p>.
	<br>
	Documento solicitado:<p>
	**No responder. Correo automático enviado desde el Portal de posventa Viva GyM**<br>';
	
	/*'html' => '<p>FELICIDADES!!!</p><p>Ganaste el tema'.$topic.' ve a nuestro menú de temas y sigue participando</p>',*/
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
	return ' Email enviado :3';
	}
}
// END CLASS