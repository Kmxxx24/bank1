<html>
<body>
<?php     require_once("config.php");

                          
echo '
<form method="POST" action="'.$path.'step2.php">  // ใส่ url เมื่อลูกค้าจ่ายเงินเสร็จแล้ว
<script type="text/javascript"
src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"  // ui script ของทางธนาคารซึ่งเป็นตัวเทส   
data-apikey=""'.$publickey.'"" // ใส่ public key 
data-amount="2000.00" // จำนวนเงินตามรูปแบบทศนิยม 2 ตำแหน่ง
data-currency="THB" // สกุลเงิน
data-payment-methods="card" // วิธีชำระเงิน
data-name="SHOPNAME"
>
</script>
</form>'; ?>

<?php   require_once("config.php");
  



if($_POST['token']!='' and isset($_POST['token']) ){

$token = $_POST['token']; // รับค่า TOKEN จากระบบธนาคาร

$url = 'https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge'; // url เรียก Api test ในการส่งค่า Token
$datasend = array(  // ค่าที่ต้องส่ง อันนี้อาจต้องดูในคู่มือว่าตัวไหนจำเป็นต้องใส่บ้าง
   "amount"=> "2000.00",   
   "currency"=> "THB",  
   "description" => "Awesome Product",
   "source_type" => "card",    
   "mode"=> "token",   
   "token"=> $token,
   "reference_order"=> "11251513" 
  
  
    
);
      
           
$ch = curl_init();



$post_string = json_encode($datasend);  

curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',              
	'Cache-Control:no-cache',
  'x-api-key: '.$secretkey // ใส่ Secret Key
  )                                                                       
);
 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
curl_setopt($ch, CURLOPT_SSLVERSION, 0 );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);



$data = curl_exec($ch);
$response = json_decode($data);


curl_close ($ch);

$response = json_decode(json_encode($response), True);   // ค่าที่ได้รับกลับมาจะอยู่ในตัวแปรนี้จะมีค่าที่สำคัญคือ chrg id เอาไว้ยืนยันตอนสุดท้ายในขั้นตอนที่ 4 


ob_clean();
header('Location: '.$response['redirect_url']); // ทำการ Redirect ไปหน้ายื่นยันตัวตนลูกค้า




     }
    

     <?php       

 require_once("config.php");


$url = 'https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge';

      
          
$ch = curl_init();



$post_string = json_encode($datasend);  
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                                       
	'Cache-Control:no-cache',
   'x-api-key: '.$secretkey // ใส่ secretkey
  )                                                                       
);                           
 
curl_setopt($ch, CURLOPT_URL, $url."/?????"); // ใส่ chrg id (ต้องตรงกับ object_id ที่รับมา) ที่ได้รับตอน step2.php


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);



$data = curl_exec($ch);
$response = json_decode($data);

if (curl_errno($ch)){
    echo  curl_error($ch);
}

curl_close ($ch);

$response = json_decode(json_encode($response), True);

print_r($response); // รายละเอียดที่ธนาคารส่งกลับมาเว็บเรามีทั้ง สถานนะและรายละเอียดลูกค้า 


?>
