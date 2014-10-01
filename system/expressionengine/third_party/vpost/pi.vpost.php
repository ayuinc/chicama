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
    
    public function envio(){
     require_once 'vpos/vpos_plugin_NOTAX.php'; 
     $codigo1 ='840';
     $codigoAdquirente = 29;
     $codigoComercio = 5278;
     $idorden = "".((int)(microtime()*100000));
     $purchaseAmount = 0;
     
     $billingFirstName = ee()->TMPL->fetch_param('billingFirstName');
     $billingLastName = ee()->TMPL->fetch_param('billingLastName');
     $billingEMail = ee()->TMPL->fetch_param('billingEMail');
     $billingAddress = ee()->TMPL->fetch_param('billingAddress');
     
     $transport = ee()->TMPL->fetch_param('transport');
     $all_meals = ee()->TMPL->fetch_param('all_meals');
     $tow_back_service = ee()->TMPL->fetch_param('tow_back_service');

     $num_dias = ee()->TMPL->fetch_param('num_dias');
     $phone = ee()->TMPL->fetch_param('phone');
     $street = ee()->TMPL->fetch_param('street');
     $city = ee()->TMPL->fetch_param('city');
     $state = ee()->TMPL->fetch_param('state');
     $full_request = ee()->TMPL->fetch_param('full_request');
     $country = ee()->TMPL->fetch_param('country');
     $document_id = ee()->TMPL->fetch_param('document_id');
     $document_type = ee()->TMPL->fetch_param('document_type');
     $card_id = ee()->TMPL->fetch_param('card_id');
     $card_type = ee()->TMPL->fetch_param('card_type');

     $request = ee()->TMPL->fetch_param('full_request');

     $full_request = str_replace("$", "{", $full_request);
     $full_request = str_replace("&", "}", $full_request);
     $full_request = str_replace('(', '"', $full_request);
     $full_request = str_replace(")", ":", $full_request);
     $full_request = str_replace("?", " ", $full_request);
     $full_request = str_replace("¿", ",", $full_request);
     $data = json_decode($full_request, true);
     $rooms_serials = $data["Habitaciones"];

     for ($i=0; $i < count($rooms_serials); $i++) { 
        $serial = $data["Habitaciones"][$i]["TCodigoHabitacion"];
        ee()->db->select('*');
        ee()->db->where('serial',$serial);
        $query = ee()->db->get('exp_hotel_products');
        foreach($query->result() as $row){
          //$room_cod = $row->room_cod;
          $purchaseAmount = $purchaseAmount + $row->cost;
        }
      }

     /* 
     ee()->db->select('*');
     ee()->db->where('serial',$transport);
     $query = ee()->db->get('exp_hotel_products');
     if($query != null){
       foreach($query->result() as $row){
         $purchaseAmount = $purchaseAmount + $row->cost;
         $transport = $row->name;
         if($transport==""){
            $transport = 'Servicio No Contratado';
         }
       }
     }
     else{
         $transport = 'Servicio No Contratado';
     }
     ee()->db->select('*');
     ee()->db->where('serial',$all_meals);
     $query = ee()->db->get('exp_hotel_products');
     if($query != null){
       foreach($query->result() as $row){
         $purchaseAmount = $purchaseAmount + $row->cost;
         $all_meals = $row->name;
         if($all_meals==""){
            $all_meals = 'Servicio No Contratado';
         }
       }
     }  
     else{
         $all_meals = 'Servicio No Contratado';
     }
     ee()->db->select('*');
     ee()->db->where('serial',$tow_back_service);
     $query = ee()->db->get('exp_hotel_products');
     if($query != null){
       foreach($query->result() as $row){
         $purchaseAmount = $purchaseAmount + $row->cost;
         $tow_back_service = $row->name;
         if($tow_back_service==""){
            $tow_back_service = 'Servicio No Contratado';
         }
       }
     }
     else{
         $tow_back_service = 'Servicio No Contratado';
     }
     */
     $data = array(
          'first_name'=> $billingFirstName,
          'last_name'=> $billingLastName,
          'email' => $billingEMail,
          'phone' => $phone,
          'country'=> $country,
          'street'=> $street,
          'city'=> $city,
          'state' => $state,
          'document_id'=>$document_id,
          'document_type'=>$document_type,
          'card_id'=>$card_id,
          'card_type'=>$card_type,
          'amount_reservation'=>$purchaseAmount,
          'num_days'=> $num_dias,
          'zodiacs'=>$tow_back_service,
          'transport'=>$transport,
          'lunch_and_dinner'=>$all_meals,
          'address'=>$billingAddress,
          'validate'=>'no',
          'full_request'=>$request
          );

     if( ee()->db->insert('exp_hotel_reservations', $data) ){
           $id=ee()->db->insert_id();
           session_start();
           $_SESSION['id'] = $id;
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
           $array_send['reserved3']="5278"; // id de comercio adicional
           $arrayOut['XMLREQ']="";
           $arrayOut['DIGITALSIGN']="";
           $arrayOut['SESSIONKEY']="";
           # Vector
           $VI = "F20CA985A4B34DEC";
           /*$llaveVPOSCryptoPub = "-----BEGIN PUBLIC KEY-----\n".
            "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
            "TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
            "Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
            "DRr1jPeilfsiFwiO8wIDAQAB\n".
            "-----END PUBLIC KEY-----"; TEST*/

           $llaveVPOSCryptoPub = "-----BEGIN PUBLIC KEY-----\n".
            "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0t0Cnbne8gQoeGK4nG6O3zfwh\n".
            "q8u9Wp5zHjyVYbvx2zudSOlBnJ5qU74BcTGypbn6W7jjvSNE7AmncOAVh4RxuRXO\n".
            "+bINFIyQ7/ErH/v1YpDFk8knC/NuvFpfHqhJ/5j2I8y+WmyF0MZmGtm074nUGv4d\n".
            "qlbUMT9aYUQ+RzMO7QIDAQAB\n".
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
                return ' 
                <form style="display:none;" id="form_envio" name="params_form" method="post" action="https://vpayment.verifika.com/VPOS/MM/transactionStart20.do" >
                   <table border="0">
                  <tr>
                    <td>IDACQUIRER:</td>
                    <td><input name="IDACQUIRER" id="IDACQUIRER" value="29"></td>
                  </tr>
                  <tr>
                    <td>COMMERCE:</td>
                    <td><input name="IDCOMMERCE" id="IDCOMMERCE" value="5278"></td>
                  </tr>
                  <tr>
                    <td>XML:</td>
                    <td><input name="XMLREQ" id="XMLREQ" value='.$arrayOut['XMLREQ'].'></td>
                  </tr>
                  <tr>
                    <td>SIGNATURE:</td>
                    <td><input name="DIGITALSIGN" id="SIGNATURE" value='.$arrayOut['DIGITALSIGN'].'></td>
                  </tr>
                  <tr>
                    <td>SESSIONKEY:</td>
                    <td><input name="SESSIONKEY" id="SESSIONKEY" value='.$arrayOut['SESSIONKEY'].'></td>
                  </tr>
                  <tr>
                    <td><input type="submit" name="envio" id="envio" value="Enviar" /></td>

                    </tr>
                </table>
                </form>';
            }else{
                return "Hay un problema con el conector de pago"; //puede haber un problema de mala configuraciÃ³n de las llaves, vector de
                //inicializacion o el VPOS no ha enviado valores correctos
            }
        }
    }
    
    public function recepcion(){

     require_once 'vpos/vpos_plugin_NOTAX.php';
     $IDACQUIRER= ee()->TMPL->fetch_param('IDACQUIRER');
     $IDCOMMERCE= ee()->TMPL->fetch_param('IDCOMMERCE');
     $XMLRES= ee()->TMPL->fetch_param('XMLRES');
     $DIGITALSIGN= ee()->TMPL->fetch_param('DIGITALSIGN');
     $SESSIONKEY = ee()->TMPL->fetch_param('SESSIONKEY');
     session_start();
     $id=$_SESSION['id'];
     unset($_SESSION['id']);
     session_destroy();
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

    /*$llavePublicaFirma = "-----BEGIN PUBLIC KEY-----\n".
    "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvJS8zLPeePN+fbJeIvp/jjvLW\n".
    "Aedyx8UcfS1eM/a+Vv2yHTxCLy79dEIygDVE6CTKbP1eqwsxRg2Z/dI+/e14WDRs\n".
    "g0QzDdjVFIuXLKJ0zIgDw6kQd1ovbqpdTn4wnnvwUCNpBASitdjpTcNTKONfXMtH\n".
    "pIs4aIDXarTYJGWlyQIDAQAB\n".
    "-----END PUBLIC KEY-----"; --- TEST*/

    $llavePublicaFirma = "-----BEGIN PUBLIC KEY-----\n".
    "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCtvXnikeSS+H/Qs/51iL3ZYPfz\n".
    "KW94WUAz7IdZIOIcuG1zLIR3kUNUc/vdSmW120dwkIleB6pl4cVT5nDewBFJCzTS\n".
    "W6jGaWaryzl7xS3ZToKTHpVeQr3avN7H+Om9TfsccY7gBV3IOIauTg9xIpDjIg52\n".
    "fUcfyPq+Bhw0cWkDUQIDAQAB\n".
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
        //return "Payment success. authorizationResult: ".$arrayOut['authorizationResult']." authorizationCode: ".$arrayOut['authorizationCode']." errorCode: ".$arrayOut['errorCode']." errorMessage: ".$arrayOut['errorMessage'];
        if($arrayOut['authorizationResult'] == "00"){
          // ingresar codigo nuevo aqui
          
          $data = array(
                         'validate' => 'yes'
                      );

          ee()->db->where('id', $id);
          ee()->db->update('exp_hotel_reservations', $data); 

          ee()->db->select('*');
          ee()->db->where('id',$id);
          $query = ee()->db->get('exp_hotel_reservations');

          foreach($query->result() as $row){
            $request = $row->full_request;
            $first_name = $row->first_name;
            $last_name = $row->last_name;
            $country = $row->country;
            $document_id = $row->document_id;
            $document_type = $row->document_type;
            $card_id = $row->card_id;
            $card_type = $row->card_type;
            $div ='{exp:infhotel:insertarreservar
                        id="'.$id.'"
                        request="'.$request.'"
                        first_name="'.$first_name.'"
                        last_name="'.$last_name.'"
                        country="'.$country.'"
                        document_id="'.$document_id.'"
                        document_type="'.$document_type.'"
                        card_id="'.$card_id.'"
                        card_type="'.$card_type.'"
                        }
                    {/exp:infhotel:insertarreservar}';
              
            }
            return $div;
            //return $request;
          }
          else {
            return "Payment fail. authorizationResult: ".$arrayOut['authorizationResult']." authorizationCode: ".$arrayOut['authorizationCode']." errorCode: ".$arrayOut['errorCode']." errorMessage: ".$arrayOut['errorMessage'];
        }
      } 
    }
}
/* End of file pi.vpost.php */
/* Location: ./system/expressionengine/third_party/vpost/pi.vpost.php */