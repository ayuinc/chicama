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
    'pi_name'         => 'Vpost',
    'pi_version'      => '1.0',
    'pi_author'       => 'Gianfranco Montoya ',
    'pi_author_url'   => 'http://www.ayuinc.com/',
    'pi_description'  => 'Permite realizar el pago en linea por medio de plugin de Vpost',
    'pi_usage'        => Vpost::usage()
);
            
class Vpost 
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

            {exp:vpost}

        This is an incredibly simple Plugin.
            <?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    public function phpinfo(){
        
        phpinfo();
    }

    public function recepcion(){
       require_once 'vpos/vpos_pluging.php'; 
       
       $IDACQUIRER= ee()->TMPL->fetch_param('IDACQUIRER');
       $IDCOMMERCE= ee()->TMPL->fetch_param('IDCOMMERCE');
       $XMLRES= ee()->TMPL->fetch_param('XMLRES');
       $DIGITALSIGN= ee()->TMPL->fetch_param('DIGITALSIGN');
       $SESSIONKEY = ee()->TMPL->fetch_param('SESSIONKEY');

        $llavePrivadaCifrado = "-----BEGIN RSA PRIVATE KEY-----\n".
        "MIICXAIBAAKBgQDr3OXAFjoBokuPfCWs1QScfz0Ejyn4JuEuMpZCRYGhn/1pWCN1\n".
        "kHVXrfk4fDephOBRVWEJq03fKkAJAxNMTMSbcjyMdSvhP4o/n8wpPGdHT1FZq1rD\n".
        "OOkKKRUwyuZm/h5+hR97ud3WTccQ7RRQHBCl2rTi7ofG56NddecjI7iw8QIDAQAB\n".
        "AoGARfH1JDizLQbfF4b+eTmWq35ELxTxokiNYLDZxH9uSOr0MIhVw6h6U+0gyjJ0\n".
        "I05nY1dJdp+ZcUPLR6Dk2Syu5MSCFhlfEkni8kb+pK1OlkwqZ4aF3mMKW1PbXB38\n".
        "M/vBKiZyANOTHAtdp7HuOjsAFdGT6m3Xi0bEmpAUxES+YlECQQD5tbsFBwN217FQ\n".
        "OzZSarosXh8QVsTO4D9LMNQGw188OdgZZD29i9JGgtzxfG4VQqW7TaRjWJK/JqXU\n".
        "p+Fgi4olAkEA8c3fqG0bT7whPxokCJGV9xCHHDB2y5qCu8RoPAnRvTcRudpqvKJR\n".
        "ZSXkqnDgz86VGovZei32yQENm7//GtQD3QJAZ9jrERksmKT1Ca/GVJosGL1/37Sm\n".
        "Hn7l86g31SQ1G//WXiDxCD340fgkWI1t3oucDvwoLGSuiq5Q8tJqiVMevQJAGoY/\n".
        "04TsawmzB+4BJ+N4dNqeR4xVa64uSxSXboaROFVTH3UDImAX16WZeMzbDFCX8IqI\n".
        "t6tVF+WCraQZAsNhDQJBALz43SvNjptXnbaHKJCKWC0tGjtC0fDjm1Tsf8jfka8o\n".
        "hUzYmghZ2EUMA+zT18bWVBMCJ5fSD/vjBTxoF0MMmuk=\n".
        "-----END RSA PRIVATE KEY-----\n";

        $llavePublicaFirma = "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
        "TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
        "Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
        "DRr1jPeilfsiFwiO8wIDAQAB\n".
        "-----END PUBLIC KEY-----";

         $arrayIn['IDACQUIRER'] =$IDACQUIRER;
         $arrayIn['IDCOMMERCE'] = $IDCOMMERCE;
         $arrayIn['XMLRES'] = $XMLRES;
         $arrayIn['DIGITALSIGN'] = $DIGITALSIGN;
         $arrayIn['SESSIONKEY'] = $SESSIONKEY;

         $arrayOut = '';

        //valor del vector de inicializacion
         $VI = "0000000000000000";

          if(VPOSResponse($arrayIn,$arrayOut,$llavePublicaFirma,$llavePrivadaCifrado,$VI)){

            echo ('OK');
          //La salida esta en $arrayOut con todos los parámetros decifrados devueltos por  el VPOS
            $resultadoAutorizacion = $arrayOut['authorizationResult'];
            $codigoAutorizacion = $arrayOut['authorizationCode'];
            $codigoError = $arrayOut['errorCode'];
            $errormensaje = $arrayOut['errorMessage'];

            echo ('<br>');
            echo $resultadoAutorizacion;
            echo ('<br>');
            echo $codigoAutorizacion;
            echo ('<br>');
            echo $codigoError;
            echo ('<br>');
            echo $errormensaje;
            echo ('<br>');

          // se deben revisar luego los demas campos de acuerdo a lo indicado en la guia de integracion

          }else{

          //Puede haber un problema de mala configuración de las llaves,
          //vector deinicializacion o el VPOS no ha enviado valores
          //correctos
              echo "<br> Respuesta Inv&acute;lida";
          }
    }
    public function envio(){
       require_once 'vpos/vpos_pluging.php'; 
       $codigo1 ='840';
       $billingEMail = ee()->TMPL->fetch_param('billingEMail');
       $billingFirstName = ee()->TMPL->fetch_param('billingFirstName');
       $billingLastName = ee()->TMPL->fetch_param('billingLastName');
       $purchaseAmount = ee()->TMPL->fetch_param('purchaseAmount');
       $IDACQUIRER =  ee()->TMPL->fetch_param('IDACQUIRER');
       $IDCOMMERCE = ee()->TMPL->fetch_param('IDCOMMERCE');
       $XMLRES = ee()->TMPL->fetch_param('XMLRES');
       $DIGITALSIGN = ee()->TMPL->fetch_param('DIGITALSIGN');
       $SESSIONKEY = ee()->TMPL->fetch_param('SESSIONKEY');
       
       $codigoAdquirente = 144;
       $codigoComercio = 6573;
       $idorden = 00001;//valor numerico de 5 dígitos, correlativo
       
       $array_send['acquirerId']=$codigoAdquirente;
       $array_send['commerceId']=$codigoComercio;
       $array_send['purchaseAmount']=$purchaseAmount;
       $array_send['purchaseCurrencyCode']=$codigo1;
       $array_send['purchaseOperationNumber']=$idorden;
       $array_send['billingEMail']=$billingEMail;
       $array_send['billingFirstName']=$billingFirstName;
       $array_send['billingLastName']=$billingLastName;
       $array_send['language']="SP"; //En espaÃ±ol,
       $arrayOut['XMLREQ']="";
       $arrayOut['DIGITALSIGN']="";
       $arrayOut['SESSIONKEY']="";

       # Vector
       $VI = "0000000000000000";

        $llavePrivadaCifrado = "-----BEGIN RSA PRIVATE KEY-----\n".
        "MIICXAIBAAKBgQDr3OXAFjoBokuPfCWs1QScfz0Ejyn4JuEuMpZCRYGhn/1pWCN1\n".
        "kHVXrfk4fDephOBRVWEJq03fKkAJAxNMTMSbcjyMdSvhP4o/n8wpPGdHT1FZq1rD\n".
        "OOkKKRUwyuZm/h5+hR97ud3WTccQ7RRQHBCl2rTi7ofG56NddecjI7iw8QIDAQAB\n".
        "AoGARfH1JDizLQbfF4b+eTmWq35ELxTxokiNYLDZxH9uSOr0MIhVw6h6U+0gyjJ0\n".
        "I05nY1dJdp+ZcUPLR6Dk2Syu5MSCFhlfEkni8kb+pK1OlkwqZ4aF3mMKW1PbXB38\n".
        "M/vBKiZyANOTHAtdp7HuOjsAFdGT6m3Xi0bEmpAUxES+YlECQQD5tbsFBwN217FQ\n".
        "OzZSarosXh8QVsTO4D9LMNQGw188OdgZZD29i9JGgtzxfG4VQqW7TaRjWJK/JqXU\n".
        "p+Fgi4olAkEA8c3fqG0bT7whPxokCJGV9xCHHDB2y5qCu8RoPAnRvTcRudpqvKJR\n".
        "ZSXkqnDgz86VGovZei32yQENm7//GtQD3QJAZ9jrERksmKT1Ca/GVJosGL1/37Sm\n".
        "Hn7l86g31SQ1G//WXiDxCD340fgkWI1t3oucDvwoLGSuiq5Q8tJqiVMevQJAGoY/\n".
        "04TsawmzB+4BJ+N4dNqeR4xVa64uSxSXboaROFVTH3UDImAX16WZeMzbDFCX8IqI\n".
        "t6tVF+WCraQZAsNhDQJBALz43SvNjptXnbaHKJCKWC0tGjtC0fDjm1Tsf8jfka8o\n".
        "hUzYmghZ2EUMA+zT18bWVBMCJ5fSD/vjBTxoF0MMmuk=\n".
        "-----END RSA PRIVATE KEY-----\n";

        $llavePublicaFirma = "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCw9S8vnGIW04tG4N98f0HXoMJl\n".
        "B6K9v2iJZlsFYNtP6Xv6Ax5dLEBzym/edPj89lraAxzmZUVPzPVoLlT0gSZMjlPP\n".
        "PfTyQp4QRWkME9AtT71q5gw5lISCW0X8bVWqwksJ515Va0LMjSohf2V+azVw2QBT\n".
        "S3IofH5DUfPOjSAfXwIDAQAB\n".
        "-----END PUBLIC KEY-----" ; 

         $arrayIn['IDACQUIRER'] =$IDACQUIRER;
         $arrayIn['IDCOMMERCE'] = $IDCOMMERCE;
         $arrayIn['XMLRES'] = $XMLRES;
         $arrayIn['DIGITALSIGN'] = $DIGITALSIGN;
         $arrayIn['SESSIONKEY'] = $SESSIONKEY;

         $arrayOut = '';

        //valor del vector de inicializacion
         $VI = "0000000000000000";

          if(VPOSResponse($arrayIn,$arrayOut,$llavePublicaFirma,$llavePrivadaCifrado,$VI)){

            echo ('OK');
          //La salida esta en $arrayOut con todos los parámetros decifrados devueltos por  el VPOS
            $resultadoAutorizacion = $arrayOut['authorizationResult'];
            $codigoAutorizacion = $arrayOut['authorizationCode'];
            $codigoError = $arrayOut['errorCode'];
            $errormensaje = $arrayOut['errorMessage'];

            echo ('<br>');
            echo $resultadoAutorizacion;
            echo ('<br>');
            echo $codigoAutorizacion;
            echo ('<br>');
            echo $codigoError;
            echo ('<br>');
            echo $errormensaje;
            echo ('<br>');

          // se deben revisar luego los demas campos de acuerdo a lo indicado en la guia de integracion

          }else{

          //Puede haber un problema de mala configuración de las llaves,
          //vector deinicializacion o el VPOS no ha enviado valores
          //correctos
              echo "<br> Respuesta Invalida";
          }
    }

    public function test_envio(){
       require_once 'vpos/vpos_pluging.php'; 
       $codigo1 ='840';
       $codigoAdquirente = 144;
       $codigoComercio = 6573;
       $idorden = 00001;
       /*
       $billingEMail = ee()->TMPL->fetch_param('billingEMail');
       $billingFirstName = ee()->TMPL->fetch_param('billingFirstName');
       $billingLastName = ee()->TMPL->fetch_param('billingLastName');
       $IDACQUIRER =  ee()->TMPL->fetch_param('IDACQUIRER');
       $IDCOMMERCE = ee()->TMPL->fetch_param('IDCOMMERCE');
       $XMLRES = ee()->TMPL->fetch_param('XMLRES');
       $DIGITALSIGN = ee()->TMPL->fetch_param('DIGITALSIGN');
       $SESSIONKEY = ee()->TMPL->fetch_param('SESSIONKEY');
        */
       $array_send['acquirerId']=$codigoAdquirente;
       $array_send['commerceId']=$codigoComercio;
       $array_send['purchaseAmount']='100';
       $array_send['purchaseCurrencyCode']=$codigo1;
       $array_send['purchaseOperationNumber']=$idorden;
       $array_send['billingEMail']='soluciones@bizlinks.la';
       $array_send['billingFirstName']='Tony';
       $array_send['billingLastName']='Sanz';
       $array_send['language']="SP"; //En espaÃ±ol,
       $arrayOut['XMLREQ']="";
       $arrayOut['DIGITALSIGN']="";
       $arrayOut['SESSIONKEY']="";

       # Vector
       $VI = "0000000000000000";

        $llavePrivadaCifrado = "-----BEGIN RSA PRIVATE KEY-----\n".
        "MIICXAIBAAKBgQDr3OXAFjoBokuPfCWs1QScfz0Ejyn4JuEuMpZCRYGhn/1pWCN1\n".
        "kHVXrfk4fDephOBRVWEJq03fKkAJAxNMTMSbcjyMdSvhP4o/n8wpPGdHT1FZq1rD\n".
        "OOkKKRUwyuZm/h5+hR97ud3WTccQ7RRQHBCl2rTi7ofG56NddecjI7iw8QIDAQAB\n".
        "AoGARfH1JDizLQbfF4b+eTmWq35ELxTxokiNYLDZxH9uSOr0MIhVw6h6U+0gyjJ0\n".
        "I05nY1dJdp+ZcUPLR6Dk2Syu5MSCFhlfEkni8kb+pK1OlkwqZ4aF3mMKW1PbXB38\n".
        "M/vBKiZyANOTHAtdp7HuOjsAFdGT6m3Xi0bEmpAUxES+YlECQQD5tbsFBwN217FQ\n".
        "OzZSarosXh8QVsTO4D9LMNQGw188OdgZZD29i9JGgtzxfG4VQqW7TaRjWJK/JqXU\n".
        "p+Fgi4olAkEA8c3fqG0bT7whPxokCJGV9xCHHDB2y5qCu8RoPAnRvTcRudpqvKJR\n".
        "ZSXkqnDgz86VGovZei32yQENm7//GtQD3QJAZ9jrERksmKT1Ca/GVJosGL1/37Sm\n".
        "Hn7l86g31SQ1G//WXiDxCD340fgkWI1t3oucDvwoLGSuiq5Q8tJqiVMevQJAGoY/\n".
        "04TsawmzB+4BJ+N4dNqeR4xVa64uSxSXboaROFVTH3UDImAX16WZeMzbDFCX8IqI\n".
        "t6tVF+WCraQZAsNhDQJBALz43SvNjptXnbaHKJCKWC0tGjtC0fDjm1Tsf8jfka8o\n".
        "hUzYmghZ2EUMA+zT18bWVBMCJ5fSD/vjBTxoF0MMmuk=\n".
        "-----END RSA PRIVATE KEY-----\n";

        $llavePublicaFirma = "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCw9S8vnGIW04tG4N98f0HXoMJl\n".
        "B6K9v2iJZlsFYNtP6Xv6Ax5dLEBzym/edPj89lraAxzmZUVPzPVoLlT0gSZMjlPP\n".
        "PfTyQp4QRWkME9AtT71q5gw5lISCW0X8bVWqwksJ515Va0LMjSohf2V+azVw2QBT\n".
        "S3IofH5DUfPOjSAfXwIDAQAB\n".
        "-----END PUBLIC KEY-----" ; 

         $arrayIn['IDACQUIRER'] =144;
         $arrayIn['IDCOMMERCE'] = 6573;
         $arrayIn['XMLRES'] = "";
         $arrayIn['DIGITALSIGN'] = "";
         $arrayIn['SESSIONKEY'] = "";

         $arrayOut = '';

        //valor del vector de inicializacion
         $VI = "0000000000000000";

          if(VPOSResponse($arrayIn,$arrayOut,$llavePublicaFirma,$llavePrivadaCifrado,$VI)){

            echo ('OK');
          //La salida esta en $arrayOut con todos los parámetros decifrados devueltos por  el VPOS
            $resultadoAutorizacion = $arrayOut['authorizationResult'];
            $codigoAutorizacion = $arrayOut['authorizationCode'];
            $codigoError = $arrayOut['errorCode'];
            $errormensaje = $arrayOut['errorMessage'];

            echo ('<br>');
            echo $resultadoAutorizacion;
            echo ('<br>');
            echo $codigoAutorizacion;
            echo ('<br>');
            echo $codigoError;
            echo ('<br>');
            echo $errormensaje;
            echo ('<br>');

          // se deben revisar luego los demas campos de acuerdo a lo indicado en la guia de integracion

          }else{

          //Puede haber un problema de mala configuración de las llaves,
          //vector deinicializacion o el VPOS no ha enviado valores
          //correctos
              echo "<br> Respuesta Invalida";
          }
    }
    // END
}
/* End of file pi.vpost.php */
/* Location: ./system/expressionengine/third_party/vpost/pi.vpost.php */