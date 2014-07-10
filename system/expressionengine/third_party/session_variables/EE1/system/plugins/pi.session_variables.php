<?php

/*
=====================================================
 Author: Ben Croker
 http://www.putyourlightson.net
=====================================================
 File: pi.session_variables.php
-----------------------------------------------------
 Purpose: Allows you to store session variables
=====================================================
*/


$plugin_info = array(
				'pi_name'			=> 'Session Variables',
				'pi_version'		=> '1.2',
				'pi_author'			=> 'Ben Croker',
				'pi_author_url'		=> 'http://www.putyourlightson.net/session-variables-plugin',
				'pi_description'	=> 'Allows you to store session variables',
				'pi_usage'			=> Session_variables::usage()
			);


class Session_variables {	
		
		
	/** ----------------------------------------
	/**  Set session variable
	/** ----------------------------------------*/

	function set()
	{
		global $TMPL, $OUT;
		
		// check if correct parameters were passed
		if (!$TMPL->fetch_param('name') || !$TMPL->fetch_param('value'))
		{
			return $OUT->show_user_error('general', 'Session Plugin: name and value parameters must be entered');
		}
		
		$name = $TMPL->fetch_param('name');
		$value = $TMPL->fetch_param('value');		
		
		// if no active session we start a new one
		if (session_id() == "") 
		{
			session_start(); 
		}
		
		$_SESSION[$name] = $value;
		
		// check for constants
		if ($value == "POSTED_VALUE")
		{
			$_SESSION[$name] = $IN->GBL($name, 'POST');
		}
	}
	/* END */
	
	
	/** ----------------------------------------
	/**  Unset session variable
	/** ----------------------------------------*/

	function delete()
	{
		global $TMPL, $OUT;
		
		// check if correct parameters were passed
		if (!$TMPL->fetch_param('name'))
		{
			return $OUT->show_user_error('general', 'Session Plugin: name parameter must be entered');
		}
		
		$name = $TMPL->fetch_param('name');
		
		// if no active session we start a new one
		if (session_id() == "") 
		{
			session_start(); 
		}
		
		unset($_SESSION[$name]);
	}
	/* END */
	
	
	/** ----------------------------------------
	/**  Get session variable
	/** ----------------------------------------*/

	function get()
	{
		global $TMPL, $OUT;
		
		// check if correct parameters were passed
		if (!$TMPL->fetch_param('name'))
		{
			return $OUT->show_user_error('general', 'Session Plugin: name parameter must be entered');
		}
		
		$name = $TMPL->fetch_param('name');
		
		// if no active session we start a new one
		if (session_id() == "") 
		{
			session_start(); 
		}
		
		if (isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
		
		else
		{
			return '';
		}
	}
	/* END */
	
		
// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.
//  Make sure and use output buffering

function usage()
{
ob_start(); 
?>

To set a session variable:

{exp:session_variables:set name='my_name' value='my_value'}


To get a session variable:

{exp:session_variables:get name='my_name'}


To delete a session variable:

{exp:session_variables:delete name='my_name'}

<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
/* END */


}
// END CLASS
?>