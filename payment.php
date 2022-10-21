<?php
//these lines are to enable debug mode and it will display errors here.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//this is required to load autoload file.
require_once ('vendor/autoload.php');

use Fena\PaymentSDK\Connection;
use Fena\PaymentSDK\Payment;
use Fena\PaymentSDK\Error;
class fenaPaymentModuleFrontController extends ModuleFrontController{

public function init()
{
    return parent::init();
    //Here we check whether the user is signed in or not
    if (!$this->module->active||
        !$this->context->cart->id_address_delivery ||
    !$this->context->cart->id_address_invoice) {
        Tools::redirect($this->context->link->getPageLink('order'));
    }
}
public function initContent()
{
    parent::initContent();
    //here we assign the user InterFace
        $this->setTemplate('module:fena/views/templates/front/payment1.tpl');
}
public function setMedia()
{
    return parent::setMedia();
}
public function postProcess(){

parent::postProcess();
//this is the SDK integeration part

if (Tools::isSubmit('placeorder')){
    $cart=$this->context->cart;
  $integrationId =Configuration::get('FENA_CLIENTID');
  $integrationSecret =Configuration::get('FENA_CLIENTSECRET');

 //amount will be stored in total
  $total=(float)$cart->getOrderTotal(true,Cart::BOTH);
  $amount=strval($total);
  $customer=new Customer($cart->id_customer);
  //$orderIdd=$this->module->currentOrder;
  $orderIdd=$cart->id;
  $orderId=strval($orderIdd);
     //implimented try catch cz it throws unknwn errors
     try{
        $connection = Connection::createConnection(
            $integrationId,
            $integrationSecret
        );

        $payment = Payment::createPayment(
            $connection,
            $amount,
            $orderId
        );
        $url = $payment->process();
         Tools::redirect($url);


     }catch(Exception $e){
        echo 'error in connection';
        echo 'Message' .$e->getMessage();
     }


     }
 }


}