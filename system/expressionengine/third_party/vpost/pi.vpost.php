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

       require_once 'vpos/vpos_plugin.php'; 
       
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
        "-----END RSA PRIVATE KEY-----";

        $llavePublicaFirma = "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvJS8zLPeePN+fbJeIvp/jjvLW\n".
        "Aedyx8UcfS1eM/a+Vv2yHTxCLy79dEIygDVE6CTKbP1eqwsxRg2Z/dI+/e14WDRs\n".
        "g0QzDdjVFIuXLKJ0zIgDw6kQd1ovbqpdTn4wnnvwUCNpBASitdjpTcNTKONfXMtH\n".
        "pIs4aIDXarTYJGWlyQIDAQAB\n".
        "-----END PUBLIC KEY-----";

         $arrayIn['IDACQUIRER'] =$IDACQUIRER;
         $arrayIn['IDCOMMERCE'] = $IDCOMMERCE;
         $arrayIn['XMLRES'] = $XMLRES;
         $arrayIn['DIGITALSIGN'] = $DIGITALSIGN;
         $arrayIn['SESSIONKEY'] = $SESSIONKEY;

         $arrayOut['authorizationResult'] = '';
         $arrayOut['authorizationCode'] = '';
         $arrayOut['errorCode'] = '';
         $arrayOut['errorMessage'] = '';

        //valor del vector de inicializacion
         $VI = "F20CA985A4B34DEC";

          if (VPOSResponse($arrayIn, $arrayOut, $llavePublicaFirma, $llavePrivadaCifrado, $VI)) {
              return "Payment success. authorizationResult: ".$arrayOut['authorizationResult']." authorizationCode: ".$arrayOut['authorizationCode']." errorCode: ".$arrayOut['errorCode']." errorMessage: ".$arrayOut['errorMessage'];
          } else {
              return "Payment fail. authorizationResult: ".$arrayOut['authorizationResult']." authorizationCode: ".$arrayOut['authorizationCode']." errorCode: ".$arrayOut['errorCode']." errorMessage: ".$arrayOut['errorMessage'];
          }
    }
    public function envio(){
       require_once 'vpos/vpos_plugin.php'; 
       $codigo1 ='840';

       /*obtecion de parametros (métodos del framework) 
       $billingEMail = ee()->TMPL->fetch_param('billingEMail');
       $billingFirstName = ee()->TMPL->fetch_param('billingFirstName');
       $billingLastName = ee()->TMPL->fetch_param('billingLastName');
       $purchaseAmount = ee()->TMPL->fetch_param('purchaseAmount');
       $IDACQUIRER =  ee()->TMPL->fetch_param('IDACQUIRER');
       $IDCOMMERCE = ee()->TMPL->fetch_param('IDCOMMERCE');
       */
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
       $VI = "F20CA985A4B34DEC";

        $llaveVPOSCryptoPub = "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
        "TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
        "Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
        "DRr1jPeilfsiFwiO8wIDAQAB\n".
        "-----END PUBLIC KEY-----";

        $llavePrivadaFirmaComercio = "-----BEGIN RSA PRIVATE KEY-----\n".
        "MIICWwIBAAKBgQCw9S8vnGIW04tG4N98f0HXoMJlB6K9v2iJZlsFYNtP6Xv6Ax5d\n".
        "LEBzym/edPj89lraAxzmZUVPzPVoLlT0gSZMjlPPPfTyQp4QRWkME9AtT71q5gw5\n".
        "lISCW0X8bVWqwksJ515Va0LMjSohf2V+azVw2QBTS3IofH5DUfPOjSAfXwIDAQAB\n".
        "AoGAfb060jHk4SNgC/Ut2GD0gCuS9gb+9KVVuowokSHJtHbLyVL9+GbBRYXLB99G\n".
        "LTlARTmBB5VeMt4IYwbJBxPeCcj49sHwDpPzltSZSN6kp11f8o28Tj/UjjGnuMZY\n".
        "l4tSGlUWErojWe8/61YMoSK7700yOxOHTtZIlzuJ1a7OgcECQQDhtF2R1Zx9oRtb\n".
        "Bt7RQqyiCZV2X/8dEJgoLV+ruCDBVcnpM4Irbiw9Bio+iHpo8gZA0FCfdGUMjF0A\n".
        "i+LmNR0dAkEAyLXJZNbmN6wyTwzT+838guo8W+6/CiZ6rfCqk4Sooqibph6k5QZu\n".
        "g84mMJdm4F8zIZGNvqtKVUwGk149DZXRqwJAXrqGsxiGb6vYgWHmzsk/D1saYe50\n".
        "ckxusB4rEzVaegp5jejSb0v0QUB/JnO9fKJnwXgs/l+psZSPzBTqDFiqeQJAGMK8\n".
        "brFIQ3P93NyzRiw6S5hEC/9fGAx5M/4tvPcvqqlsUkkThKGPfrku4u26plF4SFrh\n".
        "hrUw/WbcpM+KbqOd8wJAeOJbi6H9y9VonyQYJM7yXwhNeAvlKTYEyYPeW2O7oitg\n".
        "1Nxmog30epbOchoAmCAr2TPzbpentnvCO1hKbO3Jkw==\n".
        "-----END RSA PRIVATE KEY-----";
        if (VPOSSend($array_send,$arrayOut,$llaveVPOSCryptoPub,$llavePrivadaFirmaComercio,$VI)) {
        }else{
            return "Hay un problema con el conector de pago"; //puede haber un problema de mala configuraciÃ³n de las llaves, vector de
            //inicializacion o el VPOS no ha enviado valores correctos
        }
        return $idorden;
    }

    public function test_envio(){
       require_once 'vpos/vpos_plugin.php'; 
       $codigo1 ='840';
       $codigoAdquirente = 144;
       $codigoComercio = 6573;
       $idorden = "".((int)(microtime()*100000));
       
       $purchaseAmount = ee()->TMPL->fetch_param('purchaseAmount');
       $billingFirstName = ee()->TMPL->fetch_param('billingFirstName');
       $billingLastName = ee()->TMPL->fetch_param('billingLastName');
       $billingEMail = ee()->TMPL->fetch_param('billingEMail');
       $billingAddress = ee()->TMPL->fetch_param('billingAddress');
       
       /*
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
       $array_send['purchaseAmount']=$purchaseAmount;
       $array_send['purchaseCurrencyCode']=$codigo1;
       $array_send['purchaseOperationNumber']= $idorden;
       $array_send['billingEMail']=$billingEMail;
       $array_send['billingFirstName']=$billingFirstName;
       $array_send['billingLastName']=$billingLastName;
       $array_send['billingAddress']=$billingAddress;
       $array_send['billingCity']='Lima';
       $array_send['billingZIP']='Lima 32';
       $array_send['billingState']='LI';
       $array_send['billingCountry']='PE';
       $array_send['language']="SP";
       $array_send['reserved1']="840"; // codigo de moneda nacional
       $array_send['reserved2']=$purchaseAmount; //mismo  monto quee purchaseAmount en nuestro caso
       $array_send['reserved3']="6573"; // id de comercio adicional
       $arrayOut['XMLREQ']="";
       $arrayOut['DIGITALSIGN']="";
       $arrayOut['SESSIONKEY']="";

       # Vector
       $VI = "F20CA985A4B34DEC";

       $llaveVPOSCryptoPub = "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
        "TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
        "Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
        "DRr1jPeilfsiFwiO8wIDAQAB\n".
        "-----END PUBLIC KEY-----";

         $llavePrivadaFirmaComercio = "-----BEGIN RSA PRIVATE KEY-----\n".
        "MIICWwIBAAKBgQCw9S8vnGIW04tG4N98f0HXoMJlB6K9v2iJZlsFYNtP6Xv6Ax5d\n".
        "LEBzym/edPj89lraAxzmZUVPzPVoLlT0gSZMjlPPPfTyQp4QRWkME9AtT71q5gw5\n".
        "lISCW0X8bVWqwksJ515Va0LMjSohf2V+azVw2QBTS3IofH5DUfPOjSAfXwIDAQAB\n".
        "AoGAfb060jHk4SNgC/Ut2GD0gCuS9gb+9KVVuowokSHJtHbLyVL9+GbBRYXLB99G\n".
        "LTlARTmBB5VeMt4IYwbJBxPeCcj49sHwDpPzltSZSN6kp11f8o28Tj/UjjGnuMZY\n".
        "l4tSGlUWErojWe8/61YMoSK7700yOxOHTtZIlzuJ1a7OgcECQQDhtF2R1Zx9oRtb\n".
        "Bt7RQqyiCZV2X/8dEJgoLV+ruCDBVcnpM4Irbiw9Bio+iHpo8gZA0FCfdGUMjF0A\n".
        "i+LmNR0dAkEAyLXJZNbmN6wyTwzT+838guo8W+6/CiZ6rfCqk4Sooqibph6k5QZu\n".
        "g84mMJdm4F8zIZGNvqtKVUwGk149DZXRqwJAXrqGsxiGb6vYgWHmzsk/D1saYe50\n".
        "ckxusB4rEzVaegp5jejSb0v0QUB/JnO9fKJnwXgs/l+psZSPzBTqDFiqeQJAGMK8\n".
        "brFIQ3P93NyzRiw6S5hEC/9fGAx5M/4tvPcvqqlsUkkThKGPfrku4u26plF4SFrh\n".
        "hrUw/WbcpM+KbqOd8wJAeOJbi6H9y9VonyQYJM7yXwhNeAvlKTYEyYPeW2O7oitg\n".
        "1Nxmog30epbOchoAmCAr2TPzbpentnvCO1hKbO3Jkw==\n".
        "-----END RSA PRIVATE KEY-----";
        if (VPOSSend($array_send,$arrayOut,$llaveVPOSCryptoPub,$llavePrivadaFirmaComercio,$VI)) {
        }else{
            return "Hay un problema con el conector de pago"; //puede haber un problema de mala configuraciÃ³n de las llaves, vector de
            //inicializacion o el VPOS no ha enviado valores correctos
        }
        return "<!--<p> Numero de Orden:".$idorden."</p> -->".
'<form  id="book_a_room" name="params_form" method="post" action="https://test2.alignetsac.com/VPOS/MM/transactionStart20.do" >
  <div id="form_box_4">
    <div class="row">
      <div id="title_reservation" class="">
          <div class="large-4 columns">
            <h3>PAYMENT</h3>
          </div>
          <div class="large-4 large-offset-4 columns">
            <p>Phone reservations +511-440-6040</p>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="large-6 large-centered columns">
        <h1>REDIRECCIONANDO . . . </h1>
        <div class="row">
          <div class="large-3 columns">
            <p>IDACQUIRER:</p>
          </div>
          <div class="large-9 columns">
            <input name="IDACQUIRER" id="IDACQUIRER" value="144">
          </div>
        </div>
        <div class="row">
          <div class="large-3 columns">
            <p>COMMERCE:</p>
          </div>
          <div class="large-9 columns">
            <input name="IDCOMMERCE" id="IDCOMMERCE" value="6573">
          </div>
        </div>
        <div class="row">
          <div class="large-3 columns">
            <p>XML:</p>
          </div>
          <div class="large-9 columns">
            <input name="XMLREQ" id="XMLREQ" value='.$arrayOut['XMLREQ'].'>
          </div>
        </div>
        <div class="row">
          <div class="large-3 columns">
            <p>SIGNATURE:</p>
          </div>
          <div class="large-9 columns">
            <input name="DIGITALSIGN" id="SIGNATURE" value='.$arrayOut['DIGITALSIGN'].'>
          </div>
        </div>
        <div class="row">
          <div class="large-3 columns">
            <p>SESSIONKEY:</p>
          </div>
          <div class="large-9 columns">
            <input name="SESSIONKEY" id="SESSIONKEY" value='.$arrayOut['SESSIONKEY'].'>
          </div>
        </div>
        <div class="large-7 columns">
            <input type="submit" name="envio" id="envio" class="send button" value="Enviar" />
        </div>
      </div>
    </div>
  </div>
</form>';
    // END
        }
}
/* End of file pi.vpost.php */
/* Location: ./system/expressionengine/third_party/vpost/pi.vpost.php */