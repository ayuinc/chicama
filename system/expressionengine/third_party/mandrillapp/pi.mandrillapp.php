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

	function send_email_request_doc(){
	global $TMPL;
	$this->EE =& get_instance(); // EEv2 syntax
	$TMPL = $this->EE->TMPL;

	require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
	$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
	$email_propietario= $TMPL->fetch_param('email_propietario');
	$nombre_propietario= $TMPL->fetch_param('nombre_propietario');
	$apellido_propietario= $TMPL->fetch_param('apellido_propietario');
	$telefono_propietario= $TMPL->fetch_param('telefono_propietario');
	$complejo_propietario= $TMPL->fetch_param('complejo_propietario');
	$torre_propietario= $TMPL->fetch_param('torre_propietario');
	$departamento_propietario= $TMPL->fetch_param('departamento_propietario');
	$documento =  $TMPL->fetch_param('documento');
	$to= "rodulfojprieto@gmail.com";
	$name= "Administrador Viva";
	$subject= "Solicitud de documento.";
	$from= "admin@gym.com";
	//$text = $TMPL->tagdata;
	$text = 'Estimado(a) Administrador Viva,<p>
	La siguiente solicitud de documento ha sido procesada a través del portal de posventa<p>.
	<br>
	Documento solicitado:<p>
	'.$documento.'<p>
	Datos del propietario<p>
	Nombre: '.$nombre_propietario.'<p>
	Apellido: '.$apellido_propietario.'<p>
	e-mail: '.$email_propietario.'<p>
	Teléfono: '.$telefono_propietario.'<p>
	Complejo: '.$complejo_propietario.'<p>
	Torre: '.$torre_propietario.'<p>
	Departamento: '.$departamento_propietario.'<p>
	<br>
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
	return '
<div class="container-fluid pt-35 pb-35 mh-630">
<div class="row">
  <div class="col-md-6 col-md-offset-3">
	  <h1>Gracias por su solicitud de documentos</h1>
	  <p>Le enviaremos su documento en menos de 24 horas.</p>
	  <p><a href="{site_url}main/user_apartment_show/{member_id}">Ir a mi departamento</a></p>	  
  </div>
</div>
</div>';
	}
	function send_email_user_close(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$subject= "No se pudo realizar el arreglo";
 		$from= $TMPL->fetch_param('from');
 		$id_sol_garantia =  $TMPL->fetch_param('id_sol_garantia');
 		$result_aus=mysql_query("SELECT * FROM exp_freeform_form_entries_4 WHERE form_field_18 = $id_sol_garantia AND form_field_19 = 9 ");http://162.243.222.54/main/user_dashboard/51
		$obten_aus=mysql_fetch_row($result_aus);
		$cliente_ausente = $obten_aus[23];
 		//$text = $TMPL->tagdata;
 		if($cliente_ausente == "no"){
 		$text = 'Estimado/a '.$name.'.<p>
 		<br>
 		Le informamos que el inspector de su caso '.$id_sol_garantia.' ha visitado su departamento pero no pudo realizar la inspección por no encontrarse nadie en el departamento.<p>
		<br>
		Por esta razón usted debe ingresar nuevamente a nuestra plataforma de servicio posventa y agendar nuevamente su visita de inspección <a href="http://162.243.222.54/main/user_request_show/'.$id_sol_garantia.'">aquí.</a><p>
		<br>
		Muchas gracias,<p>
		<br>
		Atentamente,<p>
		Servicio de posventa Viva GyM';
 		
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
		return "Nombre:".$name."Email:".$to;
		}
		else{
			return "";
		}
	}
	function send_email_write_proc(){
		
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$subject= "No se pudo realizar la inspección";
 		$from= $TMPL->fetch_param('from');
 		$id_sol_garantia =  $TMPL->fetch_param('id_sol_garantia');
 		$result_aus=mysql_query("SELECT * FROM exp_freeform_form_entries_4 WHERE form_field_18 = $id_sol_garantia AND form_field_19 = 5 ");
		$obten_aus=mysql_fetch_row($result_aus);
		$cliente_ausente = $obten_aus[23];

		if($cliente_ausente == "no"){
 		//$text = $TMPL->tagdata;
	 		$text = 'Estimado/a '.$name.',<p>
	 		<br>
			Le informamos que la persona responsable por realizar el arreglo de su caso '.$id_sol_garantia.' ha visitado su departamento pero no pudo realizar el trabajo por no encontrarse nadie en el departamento.<p>
			<br>
			Por esta razón usted debe ingresar nuevamente a nuestra plataforma de servicio posventa y agendar nuevamente su visita de arreglo <a href="http://162.243.222.54/main/user_request_show/'.$id_sol_garantia.'">aquí.</a><p>
			<br>
			Muchas gracias,<p>
			Atentamente,<p>
			Servicio de posventa Viva GyM';
	 		
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
			return "Nombre:".$name."Email:".$to;
		}
		else{
			return "";
		}
	}
	function send_email_confirm_solicitud(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$subject= "Ingreso de nueva solicitud.";
 		$from= $TMPL->fetch_param('from');
 		$dias=  $TMPL->fetch_param('dias');
 		$member_id = $TMPL->fetch_param('member_id');
 		//$text = $TMPL->tagdata;
 		$text = 'Estimado/a '.$name.'<p>
Gracias por enviar su solicitud de requerimientos por el portal de posventa en línea de Viva GyM. <p>
En los próximos '.$dias.' días le estaremos informando por correo electrónico y mediante el portal de post-venta si la inspección por un técnico de nuestro equipo procede. Recuerde que en la mayoría de los casos, la vigencia de la garantía es necesaria para que los arreglos procedan. <p>
Usted puede hacerle seguimiento a su solicitud <a href="http://162.243.222.54/main/user_dashboard/'.$member_id.'"> aquí.</a><p>
Esperamos servirle de la mejor manera durante este proceso. No olvide revisar el Manual del Propietario para cuidar de su departamento todos los días.<p>
Atentamente';
 		
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
		return "";
	}

	function send_email_cerrar_caso(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');

 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$subject= "Cierre de Caso";
 		$from= $TMPL->fetch_param('from');
 		$id_sol_garantia = $TMPL->fetch_param('id_sol_garantia');
 		//$text = $TMPL->tagdata;
 		$text = 'Estimado/a '.$name.'<p>
 			Gracias por culminar el proceso de atención de su solicitud número: '.$id_sol_garantia.'.
 		Adjunto encontrará el reporte del arreglo realizado por GyM.<p>
 		Aprovechamos para recordarle que en el Manual del Propietario otorgado al momento de la entrega de su departamento se especifica el correcto uso y mantenimiento preventivo que se le debe realizar a sus instalaciones a fin de evitar que estas fallen por el propio uso que provoca el desgaste natural.  Puede encontrar el Manual en nuestro portal de post-venta en línea. Link a Manual.<p>
 		Es importante precisar que en caso se presentara alguna solicitud, observación y/o requerimiento adicional tras el arreglo debe llenar un nuevo reclamo en nuestro portal de post-venta en línea o comunicarte a nuestro Call Center de Atención al Cliente 206-7270. Este es el único mecanismo que garantiza la atención de su solicitud de post-venta, cualquier otra forma de solicitud no será atendida.<p>
 		Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional.<p>
 		Atentamente';
 		
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
 		        '	name' => 'main',
 		        'content' => 'Hi *|FIRSTNAME|* *|LASTNAME|*, thanks for signing up.'),
 		    array(
 		        'name' => 'footer',
 		        'content' => 'Copyright 2012.')

 		);
		$mandrill->messages->sendTemplate($template_name, $template_content, $message);
		return '<div class="container-fluid pt-35 pb-35 mh-630">
	<div class="row">
	  <div class="col-md-6 col-md-offset-3">

	  		<h1>Ha cerrado la solicitud Nro. <?php echo $id_sol_garantia; ?></h1>
	  		<h2>Nuevo metodo</h2>

		  <p>Puedes hacerle seguimiento a las solicitudes en la sección de “Panel de control”</p>
		  <p><a href="{site_url}main/admin_dashboard">Ir a Panel de control</a></p>	  
	  </div>
	</div>
</div>
';
	}
	
	function send_email_viva_cierre(){ //dos veces
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$subject= 'Cierre de Caso';
 		$from= $TMPL->fetch_param('from');
 		$id_sol_garantia = $TMPL->fetch_param('id_sol_garantia');
 		//$text = $TMPL->tagdata;
 		$text = 'Estimado/a '.$name.'<p>
 			Gracias por culminar el proceso de atención de su solicitud número: '.$id_sol_garantia.'.
 		Adjunto encontrará el reporte del arreglo realizado por GyM.<p>
 		Aprovechamos para recordarle que en el Manual del Propietario otorgado al momento de la entrega de su departamento se especifica el correcto uso y mantenimiento preventivo que se le debe realizar a sus instalaciones a fin de evitar que estas fallen por el propio uso que provoca el desgaste natural.  Puede encontrar el Manual en nuestro portal de post-venta en línea. Link a Manual.<p>
 		Es importante precisar que en caso se presentara alguna solicitud, observación y/o requerimiento adicional tras el arreglo debe llenar un nuevo reclamo en nuestro portal de post-venta en línea o comunicarte a nuestro Call Center de Atención al Cliente 206-7270. Este es el único mecanismo que garantiza la atención de su solicitud de post-venta, cualquier otra forma de solicitud no será atendida.<p>
 		Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional.<p>
 		Atentamente';
 		
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
		return '
				<div class="row">
				  <div class="col-md-6 col-md-offset-3">

				  		<h1>¡Listo! El caso de la solicitud #.'.$id_sol_garantia.' ha sido CERRADO</h1>

					  <p>Puedes hacerle seguimiento a las solicitudes en la sección de “Panel de control”</p>
					  <p><a href="{site_url}main/admin_dashboard">Ir a Panel de control</a></p>	  
				  </div>
				</div>'	;
	}

	function send_email_viva_approve_fix(){ //no llega
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$from= $TMPL->fetch_param('from');
 		$acc= $TMPL->fetch_param('acc');
 		$id_sol_garantia = $TMPL->fetch_param('id_sol_garantia');
 		$comentarios = $TMPL->fetch_param('comentarios');

 		if ($acc == "si"){

 			$result=mysql_query("SELECT * FROM exp_freeform_form_entries_2 WHERE entry_id=$id_sol_garantia");
 			$obten=mysql_fetch_row($result);
 			$tit_problema = $obten[15];

 			$subject = " Arreglo procede.";
 			$text = 'Estimado/a '.$name.'<p>
					 Tras analizar su solicitud de requerimiento número '.$id_sol_garantia.' le confirmamos que se ha determinado que el arreglo reportado: '.$tit_problema.', procede. Para que se acerque un especialista a arreglar el daño debe agendar una cita ingresando a nuestro portal de post-venta en línea <a href="http://162.243.222.54/main/user_request_show/'.$id_sol_garantia.'">aquí.</a> <p>
					 Es importante precisar que en caso se presentara alguna solicitud, observación y/o requerimiento adicional tras el arreglo debe llenar un nuevo reclamo en nuestro portal de post-venta en línea o comunicarte a nuestro Call Center de Atención al Cliente 206-7270. Este es el único mecanismo que garantiza la atención de su solicitud de post-venta, cualquier otra forma de solicitud no será atendida.<p>
					 Atentamente';
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
	 		return "";
 		}
 		else if($acc == "no"){

 			$subject=" Arreglo no procede.";
 			$text='Estimado/a '.$name.'<p>
 				Tras analizar su solicitud de requerimiento número '.$id_sol_garantia.' le informamos que los especialistas de GyM han determinado que su arreglo no procede. La razón de esta decisión es: '.$comentarios.'.<p>
 			Aprovechamos para recordarle que en el Manual del Propietario otorgado al momento de la entrega de su departamento se especifica el correcto uso y mantenimiento preventivo que se le debe realizar a sus instalaciones a fin de evitar que estas fallen por el propio uso que provoca el desgaste natural.  Puede encontrar el Manual en nuestro portal de post-venta en línea. Link a Manual.<p>
 			Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional puede llamar a nuestro Call Center de Atención al Cliente al 206-7270.<p>
 			Atentamente';
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
	 		return "";
 		}
	}

	function send_email_viva_approve_sol(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$from= $TMPL->fetch_param('from');
 		$acc= $TMPL->fetch_param('acc');
 		$id_sol_garantia = $TMPL->fetch_param('id_sol_garantia');
 		$comentarios = $TMPL->fetch_param('comentarios');

 		if ($acc == 'si'){

 			$result=mysql_query("SELECT * FROM exp_freeform_form_entries_2 WHERE entry_id=$id_sol_garantia");
 			$obten=mysql_fetch_row($result);
 			$tit_problema = $obten[15];

 			$subject = "Inspección Procede.";
 			$text = 'Estimado/a '.$name.'.<p>
	Tras analizar su solicitud de requerimiento número '.$id_sol_garantia.', le confirmamos que se realizará la inspección del daño reportado 
	'.$tit_problema.' a través del sistema de post-venta en línea de Viva GyM. Para proceder con la inspección debe agendar su cita en nuestro portal de posventa en línea. Por favor agende su visita <a href="http://162.243.222.54/main/user_request_show/'.$id_sol_garantia.'">aquí.</a> Ahí deberá seleccionar un horario en el que con seguridad usted o alguien más se encontrará en su hogar para que reciba al especialista que inspeccionará el problema.<p>
Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional puede llamar a nuestro Call Center de Atención al Cliente al 206-7270.<p>
Atentamente';
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
	 		return "";
 		}
 		else if($acc == 'no'){
 			$result=mysql_query("SELECT * FROM exp_freeform_form_entries_2 WHERE entry_id=$id_sol_garantia");
 			$obten=mysql_fetch_row($result);
 			$tit_problema = $obten[15];

 			$subject="Inspección no procede.";
 			$text='Estimado/a '.$name.'<p>
	Tras analizar su solicitud de requerimiento número '.$id_sol_garantia.', le confirmamos que se ha determinado que su requerimiento es improcedente debido a: '.$comentarios.'.<p>
	Por esta razón no corresponde enviar a un especialista a verificar el problema.
Aprovechamos para recordarle que en el Manual del Propietario otorgado al momento de la entrega de su departamento se especifica el correcto uso y mantenimiento preventivo que se le debe realizar a sus instalaciones a fin de evitar que estas fallen por el propio uso que provoca el desgaste natural.  Puede encontrar el Manual en nuestro portal de post-venta en línea. Link a Manual.<p>
Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional puede llamar a nuestro Call Center de Atención al Cliente al 206-7270.<p>
Atentamente';
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
	 		return "";
 		}
	}

	function send_email_guardar_3er_paso(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

 		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
 		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
 		
 		$to= $TMPL->fetch_param('to');
 		$name= $TMPL->fetch_param('name');
 		$subject= $TMPL->fetch_param('subject');
 		$from= $TMPL->fetch_param('from');
 		$id_sol_garantia = $TMPL->fetch_param('id_sol_garantia');
 		$fecha_atencion_ticket = $TMPL->fetch_param('fecha_atencion_ticket');
 		//$text = $TMPL->tagdata;
 		$text = 'Estimado/a '.$name.'<p>
 			Muchas gracias por agendar su cita de inspección en el  el portal de post-venta en línea de Viva GyM. Su inspección se realizará según la siguiente información:<p>
 		Número de solicitud: '.$id_sol_garantia.'<p>
 		Fecha de Inspección: '.$fecha_atencion_ticket.'<p>
 		Horario de Inspección: 9:00 am - 2:00 pm de lunes a viernes<p>
 		Recuerde que usted o alguien responsable de su departamento debe estar presente en el momento de la inspección.  En caso no pueda estar presente deberá reagendar su cita en el portal de post-venta de Viva GyM con al menos 24 horas de anticipación a la misma. Si tiene una emergencia el mismo día de la cita y no podrá estar presente por favor comuníquese al 206-7270.<p>
 		Recuerde que si reagenda su cita deberá pasar nuevamente por todo el proceso. Tiene, además, un máximo de dos oportunidades para reagendar su cita.<p>
 		Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional puede llamar a nuestro Call Center de Atención al Cliente al 206-7270.<p>
 		Atentamente';
 		
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
		return "";
	}

	function send_email_guardar_4to_paso(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
		
		$to= $TMPL->fetch_param('to');
		$name= $TMPL->fetch_param('name');
		$subject= "Visita de arreglo agendada.";
		$from= $TMPL->fetch_param('from');
		$id_sol_garantia = $TMPL->fetch_param('id_sol_garantia');
		$fecha_atencion_ticket = $TMPL->fetch_param('fecha_atencion_ticket');
		//$text = $TMPL->tagdata;
		$text = 'Estimado/a '.$name.'<p>
				Muchas gracias por agendar su arreglo en el portal de post-venta en línea de Viva GyM. Su inspección se realizará según la siguiente información:<p>
				Número de solicitud: '.$id_sol_garantia.'<p>
				Fecha de Inspección: '.$fecha_atencion_ticket.'<p>
				Horario de Inspección: 9:00 am - 2:00 pm de lunes a viernes<p>
				Recuerde que usted o alguien responsable de su departamento debe estar presente en el momento de la inspección.  En caso no pueda estar presente deberá reagendar su cita en el portal de post-venta de Viva GyM con al menos 24 horas de anticipación a la misma. Si tiene una emergencia el mismo día de la cita y no podrá estar presente por favor comuníquese al 206-7270.
				Recuerde que si reagenda su cita deberá pasar nuevamente por todo el proceso. Tiene, además, un máximo de dos oportunidades para reagendar su cita. <p>
				Quedamos como siempre a su disposición si tiene alguna consulta o solicitud adicional puede llamar a nuestro Call Center de Atención al Cliente al 206-7270.<p>
				Atentamente VIVA GYM<p>'; 	
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
		return "";
	}

	function send_email_bienvenida(){
		global $TMPL;
		$this->EE =& get_instance(); // EEv2 syntax
		$TMPL = $this->EE->TMPL;

		require_once 'mailchimp-mandrill-api-php/src/Mandrill.php'; 
		$mandrill = new Mandrill('Svqgcw575OLrORu2WiD09g');
		
		$to= $TMPL->fetch_param('to');
		$name= $TMPL->fetch_param('name');
		$subject= $TMPL->fetch_param('subject');
		$from= $TMPL->fetch_param('from');
		//$text = $TMPL->tagdata;
		$text = 'Estimado/a '.$name.'<p>
				Gracias por registrarse en el portal de post-venta en línea de Viva GyM. Por favor tome nota de su usuario y clave para poder ingresar al portal a hacer todas sus solicitudes de post-venta. A través de nuestro portal podrá acceder al manual del propietario, reportar daños, agendar citas de inspección y arreglos, solicitar documentos, hacer reclamaciones y estar al día sobre novedades de Viva GyM.<p>
				Esperamos servirle de la mejor manera.<p>
				Atentamente VIVA GYM<p>';
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
		return "";
	}

}
// END CLASS