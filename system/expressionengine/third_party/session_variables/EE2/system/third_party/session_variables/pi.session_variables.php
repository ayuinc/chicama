<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Session Variables Plugin
 *
 * @package		Session Variables
 * @category	Plugins
 * @author		Ben Croker
 * @link		http://www.putyourlightson.net/session-variables-plugin
 */

$plugin_info = array(
				'pi_name'			=> 'Session Variables',
				'pi_version'		=> '1.2',
				'pi_author'			=> 'Ben Croker',
				'pi_author_url'		=> 'http://www.putyourlightson.net/',
				'pi_description'	=> 'Allows you to store session variables',
				'pi_usage'			=> Session_variables::usage()
			);


class Session_variables 
{  			
	/**
	  *  Constructor
	  */
	function Session_variables()
	{
		// make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------
	
	/**
	  *  Set session variable
	  */  
	function set()
	{
		// check if correct parameters were passed
		if (!$this->EE->TMPL->fetch_param('name') || !$this->EE->TMPL->fetch_param('value'))
		{
			show_error('Session Plugin: name and value parameters must be entered');
		}
		
		$name = $this->EE->TMPL->fetch_param('name');
		$value = $this->EE->TMPL->fetch_param('value');		
		
		// if no active session we start a new one
		if (session_id() == "") 
		{
			session_start(); 
		}
		
		$_SESSION[$name] = $value;
		
		// check for constants
		if ($value == "POSTED_VALUE")
		{
			$_SESSION[$name] = $this->EE->input->get_post($name);
		}
	}
	/* END */	
	
	// --------------------------------------------------------------------
	
	/**
	  *  Delete session variable
	  */ 
	function delete()
	{
		// check if correct parameters were passed
		if (!$this->EE->TMPL->fetch_param('name'))
		{
			show_error('Session Plugin: name parameter must be entered');
		}
		
		$name = $this->EE->TMPL->fetch_param('name');
		
		// if no active session we start a new one
		if (session_id() == "") 
		{
			session_start(); 
		}
		
		unset($_SESSION[$name]);
	}
	/* END */
	
	// --------------------------------------------------------------------
	
	/**
	  *  Get session variable
	  */  
	function get()
	{
		// check if correct parameters were passed
		if (!$this->EE->TMPL->fetch_param('name'))
		{
			show_error('Session Plugin: name parameter must be entered');
		}
		
		$name = $this->EE->TMPL->fetch_param('name');
		
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

/* End of file pi.session_variables.php */
/* Location: ./system/expressionengine/third_party/session_variables/pi.session_variables.php */
?>