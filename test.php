<?php

    function gerarCNPJ() {
	
	$comPontos = 1;
    
	$n1 = rand(0,9);
	$n2 = rand(0,9);
	$n3 = rand(0,9);
	$n4 = rand(0,9);
	$n5 = rand(0,9);
	$n6 = rand(0,9);
	$n7 = rand(0,9);
	$n8 = rand(0,9);
	$n9 = rand(0,9);
	$d1 = $n9*2+$n8*3+$n7*4+$n6*5+$n5*6+$n4*7+$n3*8+$n2*9+$n1*10;
	$d1 = 11 - ($d1%11);
	if ($d1>=10) $d1 = 0;
	 $d2 = $d1*2+$n9*3+$n8*4+$n7*5+$n6*6+$n5*7+$n4*8+$n3*9+$n2*10+$n1*11;
	$d2 = 11 - ($d2%11);
	if ($d2>=10) $d2 = 0;
	$retorno = '';
	if ($comPontos) $cpf = ''.$n1.$n2.$n3.'.'.$n4.$n5.$n6.'.'.$n7.$n8.$n9.'-'.$d1.$d2;
	else $cpf = ''.$n1.$n2.$n3.$n4.$n5.$n6.$n7.$n8.$n9.$d1.$d2;


    return $cpf;

}


 $cpf = gerarCNPJ();
    /**
     * PHP发送Json对象数据
     *
     * @param $url 请求url
     * @param $jsonStr 发送的json字符串
     * @return array
     */
    function http_post_json($url, $jsonStr)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json; charset=utf-8',
          'Content-Length: ' . strlen($jsonStr)
        )
      );
      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      return array($httpCode, $response);
    }
    $url = "https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi";
    $signature= "4Vj8eK4rloUd272L48hsrarnUA~508029~payment_x_00000016~100~BRL";
    $signature= md5($signature);
    $jsonStr = json_encode(array(
    		"language" => "es",
            "command"  =>  "SUBMIT_TRANSACTION",
            "merchant" => array(
            	"apiKey" => "4Vj8eK4rloUd272L48hsrarnUA",
            	"apiLogin" => "pRRXKOl8ikMmt9u",
            	),
            "transaction"  => array(
            	  "order" => array(
            	  		"accountId" => "512327",
            	  		 "referenceCode" => "payment_x_00000016",
            	  		 "description"  => "payment test",
            	  		 "language" => "pt",
            	  		 "signature" => $signature,
            	  		  "notifyUrl" => "http://www.tes.com/confirmation",
            	  		  "additionalValues" => array(
            	  		  		"TX_VALUE" => array(
            	  		  				"value" => 100,
            	  		  				"currency" => "BRL",
            	  		  			),
            	  		  	),
            	  		  "buyer" => array(
            	   		  "emailAddress" => "",
            	   		  "fullName" => "",
            	   		  "dniNumber" => $cpf,
            	   		  "cnpj" => "",
            	   		  //"cnpj" =>
            	   		  "shippingAddress" => array(
            	   		  		"street1" => "",
            	   		  		"street2" => "",
            	   		  		"city" => "",
            	   		  		"state" => "",
            	   		  		"country" => "BR",
            	   		  		"postalCode" => "",
            	   		  	),
            	   		),
            	  	),
            	   
            	   "type" => "AUTHORIZATION_AND_CAPTURE",
            	   "paymentMethod" => "BOLETO_BANCARIO",
            	   "paymentCountry" => "BR",
            	   "ipAddress" => "127.0.0.1",
            	),
            "test" => "false",


    	));
    //var_dump($jsonStr);exit();
    $re = http_post_json($url, $jsonStr);

  //  var_dump($re);
  if($re[0] == 200){
  
    	$s = simplexml_load_string($re[1]);
 
    	if($s->code == 'SUCCESS'){

               $url= $s -> transactionResponse -> extraParameters -> entry -> string[1];
               echo $url;
    	}
   }
   
