<?php
    require 'vendor/autoload.php';

    $configuration = new \Interkassa\Helper\Config();
    $configuration -> setCheckoutSecretKey('EmPyHcrXqZSm5ARB');
    $configuration -> setAuthorizationKey('xH9gfvU6YfjdwYiFm96x0YwdrGy0RDqM');
    $configuration -> setAccountId('5e81f2551ae1bd31008b458b');
    $SDKClient = new \Interkassa\Interkassa($configuration);

    $invoiceRequest = new \Interkassa\Request\GetInvoiceRequest();
    $invoiceRequest
        -> setCheckoutId('61fac74096476d4b775421b4')
        -> setPaymentNumber('ID_1234')
        -> setAmount('100')
        -> setCurrency('UAH')
        -> setDescription('');

    $url = $SDKClient->makeInvoiceSciLink($invoiceRequest);
    // $SDKClient->redirect($url);
    
    /* $invoiceRequest = new \Interkassa\Request\CalculateRequest();
    $invoiceRequest
        ->setCheckoutId('61f8e9914e7963061511b625')
        ->setPaymentNumber('ID_1234')
        ->setAmount('100')
        ->setCurrency('UAH')
        ->setDescription('')
        ->setAction('payway')
        ->setPaywayVia('test_interkassa_test_xts');
    
    $result = $SDKClient->calculateInvoice($invoiceRequest);
    $code = $result -> getCode();
    $status = $result -> getStatus();
    $message = $result -> getMessage();
    $data = $result -> getData(); */
?>