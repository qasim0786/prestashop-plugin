<?php
 class fenaNotificationModuleFrontController extends ModuleFrontController{
    public function init()
    {
        return parent::init();


        }
        public function initContent()
{
    parent::initContent();
    //here we assign the user InterFace
        $this->setTemplate('module:fena/views/templates/front/Notification.tpl');
}


    public function postProcess(){
        parent::postProcess();
        //get Order Id comming in url
        $order= $_GET['order_id'];
        //Get status comming in url
        $status=$_GET['status'];
        $cart=$this->context->cart;
        $cartId=$cart->id;
        //convert cart id into string
        $cartValue=strval($cartId);
        if($order==$cartValue){
            if($status=='paid'){

                //Validate the Order Empty the cart.
 //get info of the current cart
        $cart=$this->context->cart;
        $cartId=$cart->id;
        //convert cart id into string
        $cartValue=strval($cartId);
        $total=(float)$cart->getOrderTotal(true,Cart::BOTH);
        $customer=new Customer($cart->id_customer);

        $this->module->validateOrder($cart->id ,
        Configuration::get('PS_OS_WS_PAYMENT'),
        $total,
        $this->module->displayName,
        null,
        array(),
        $this->context->currency->id,
        false,
        $customer->secure_key
    );
    Tools::redirect($this->context->link->getPageLink('order-confirmation',
    Configuration::get('PS_SSL_ENABLED'),
    $this->context->language->id,
    'id_cart='.$cart->id.
    '&id_module='.$this->module->id.
    '&id_order='.$this->module->currentOrder.
    '&key='.$customer->secure_key

));

            }
            else if($status=='rejected'){
                //redirect to order page dont empty the cart.
                Tools::redirect($this->context->link->getPageLink('order'));
            }
            else{
                //if something else is comming dont empty the cart return to order page.
                Tools::redirect($this->context->link->getPageLink('order'));
            }
        }
        else{
            //if order Id doesnot matches
            Tools::redirect($this->context->link->getPageLink('order'));
        }

       

         

         }


}


