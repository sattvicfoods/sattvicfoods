<?php 
    include_once( plugin_dir_path(__FILE__).'DebugSoapClient.php' );
    $Bluedart_information_select_mode=unserialize(get_option('Bluedart_information_select_mode'));
    $Bluedart_information_licence_key= unserialize(get_option('Bluedart_information_licence_key'));
    $Bluedart_information_loginid= unserialize(get_option('Bluedart_information_loginid'));
    $Bluedart_information_email= unserialize(get_option('Bluedart_information_email') );
    $Bluedart_information_store_name= unserialize(get_option('Bluedart_information_store_name') );
    $Bluedart_information_phone= unserialize(get_option('Bluedart_information_phone') );
    $Bluedart_information_store_address= unserialize(get_option('Bluedart_information_store_address') );	
    $Bluedart_information_pincode= unserialize(get_option('Bluedart_information_pincode') ); 
    $Bluedart_information_customercode= unserialize(get_option('Bluedart_information_customercode') ); 
    $Bluedart_information_vandercode= unserialize(get_option('Bluedart_information_vandercode') ); 
    $Bluedart_information_originarea= unserialize(get_option('Bluedart_information_originarea') ); 
    $Bluedart_information_tin_no= unserialize(get_option('Bluedart_information_tin_no') ); 
    $blueAddress = $Bluedart_information_store_address;
    $line_store_address = $blueAddress;
    $line_store_address_1=substr($line_store_address,0,30);
    $line_store_address_2=substr($line_store_address,30,30);
    $line_store_address_3=substr($line_store_address,60,30);
    $line_store_address_4=substr($line_store_address,90,30);
    $line_store_address_5=substr($line_store_address,120,30);
     
    $order_ids=$_REQUEST['order_ids'];
    $dimension_breadth =$_REQUEST['breadth'];
	$dimension_height = $_REQUEST['height'];
	$dimension_length = $_REQUEST['length'];
	$weight= $_REQUEST['weight'];
    
    foreach($order_ids as $order_id){
	$order = new WC_Order($order_id);	
    $order_post = get_post($order_id);
    $datetime=$order_post->post_date;
    $date= explode(" ", $datetime);
    $order_date= $date[0];
    $payment_method_code = get_post_meta( $order_id, '_payment_method', true ); //cod
    $collectableAmount = 0;
    $SubProductCode = 'p';
    $pdf_method = "PREPAID ORDER";
        
    if( $payment_method_code == 'cod' ){
        $collectableAmount = $order->get_total(); 
        $SubProductCode = 'c';
        $pdf_method = "CASH ON DELIVERY (COD)"; 
    }	

    $mrp = 0;
	$commodityDetail = array();
	$i = 1;
	$qty = 0;
    $specialInstruction = ''; 
    $items_name = ''; 
	$ordered_items = $order->get_items();
	
	foreach($ordered_items as $item){    
				   
            $product_id=$item['product_id']; //product id     
            $sku = get_post_meta( $product_id, '_sku', true ); 
            $qty = $qty + $item['qty']; //ordered qty of item     
            $mrp = $mrp + $item['line_total']; 
        
            $commodityDetail['CommodityDetail'.$i] =  preg_replace('/[^a-zA-Z0-9]/', ' ', $item['name']);
            $commodityDetail['CommodityDetail'.$i] =  preg_replace('/[^a-zA-Z0-9]/', ' ', substr($item['name'], 0,30));
            $specialInstruction = $commodityDetail['CommodityDetail'.$i].' '.$specialInstruction; 
            $items_name = $commodityDetail['CommodityDetail'.$i].','.$items_name; 
            
            $i++;
				  
	}
 
	if($collectableAmount){ 
            $mrp = $collectableAmount; 
	}
			
	
      
    $dimension = $dimension_length.'*'.$dimension_breadth.'*'.$dimension_height;
	$customer_name = $order->shipping_first_name.' '.$order->shipping_last_name;
	$shipping_address_3 = $order->shipping_city.' '.$order->shipping_state.' '.$order->shipping_country;
	    
	/*-------- Start Blue Dart API---------*/
			
	if($Bluedart_information_select_mode == 1)
        $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/Demo/ShippingAPI/WayBill/WayBillGeneration.svc';
	else
        $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/ShippingAPI/WayBill/WayBillGeneration.svc';
		
	$commodityDetail = array();
							 
        $soap = new DebugSoapClient($ApiUrl.'?wsdl', array(
                                                            'trace'         => 1,  
                                                            'style'         => SOAP_DOCUMENT,
                                                            'use'           => SOAP_LITERAL,
                                                            'soap_version'  => SOAP_1_2
                                                        )
                                    );
				
	$soap->__setLocation($ApiUrl);
	$soap->sendRequest = true;
	$soap->printRequest = false;
	$soap->formatXML = true; 			
	$actionHeader = new SoapHeader(
                                        'http://www.w3.org/2005/08/addressing',
                                        'Action','http://tempuri.org/IWayBillGeneration/GenerateWayBill',
                                        true
                                    );
	$soap->__setSoapHeaders($actionHeader);	
        $consignee = array(
                            'ConsigneeAddress1'     => substr($order->shipping_address_1,0,30),
                            'ConsigneeAddress2'     => substr($order->shipping_address_2,0,30),
                            'ConsigneeAddress3'     => $shipping_address_3,
                            'ConsigneeAttention'    => '',
                            'ConsigneeMobile'       => $order->billing_phone,
                            'ConsigneeName'         => $customer_name,
                            'ConsigneePincode'      => $order->shipping_postcode,
                            'ConsigneeTelephone'    => $order->billing_phone,
                        );
        
	$services = array(
                        'ActualWeight'          => $weight,
                        'CollectableAmount'     => $collectableAmount,
                        'Commodity'             => $commodityDetail,
                        'CreditReferenceNo'     => $order_id,    //imp
                        'DeclaredValue'         => $mrp,
                        'Dimensions'            => array(
                                                        'Dimension' => array (
                                                                            'Breadth' =>$dimension_breadth,
                                                                            'Count' => '',
                                                                            'Height' => $dimension_height,
                                                                            'Length' => $dimension_length
                                                                        ),
																	),
						'InvoiceNo'             => '',
						'PackType'              => '',
						'PickupDate'            => date('Y-m-d'),
						'PickupTime'            => '1800',//(optional)
                        'PieceCount'            => '1',//(#default)
                        'ProductCode'           => 'A',//(#default)
                        'ProductType'           => 'Dutiables',//(#default)
                        'SpecialInstruction' => mb_strimwidth($specialInstruction,0, 49 ,"..."), 
                        'SubProductCode'        => $SubProductCode,
                    );
        
        $shipper = array(
            'CustomerAddress1'  => $line_store_address_1,
			'CustomerAddress2'  => $line_store_address_2,
			'CustomerAddress3'  => $line_store_address_3,
			'CustomerAddress4'  => $line_store_address_4,
			'CustomerAddress5'  => $line_store_address_5,
			'CustomerCode'      => $Bluedart_information_customercode,
			'CustomerEmailID'   => $Bluedart_information_email,
			'CustomerMobile'    => $Bluedart_information_phone,
			'CustomerName'      => $Bluedart_information_store_name,
			'CustomerPincode'   => $Bluedart_information_pincode,
			'CustomerTelephone' => $Bluedart_information_phone,
			'IsToPayCustomer'   => '',
			'OriginArea'        => $Bluedart_information_originarea,
			'Sender'            => '',
			'VendorCode'        => $Bluedart_information_vandercode
                    );
        
        $subshipper = array(
                            'CustomerAddress1'  => $line_store_address_1,
							'CustomerAddress2'  => $line_store_address_2,
							'CustomerAddress3'  => $line_store_address_3,
							'CustomerAddress4'  => $line_store_address_4,
							'CustomerAddress5'  => $line_store_address_5,
                            'CustomerCode' => $Bluedart_information_customercode,
                            'CustomerEmailID' =>$Bluedart_information_email,
                            'CustomerMobile' => $Bluedart_information_phone,
                            'CustomerName' => $Bluedart_information_store_name,
                            'CustomerPincode' => $Bluedart_information_pincode,
                            'CustomerTelephone' => $Bluedart_information_phone,
                            'IsToPayCustomer' => '',
                            'OriginArea' => $Bluedart_information_originarea,
                            'Sender' => '',
                            'VendorCode' => $Bluedart_information_vandercode
                        );
        
	$params = array(
                        'Request' => array(
                                            'Consignee' => $consignee,
                                            'Services'  => $services,
                                            'Shipper' => $shipper,
                                            'SubShipper' => $subshipper
					),
                        'Profile' => array(
                                            'Api_type' => 'S',
                                            'LicenceKey'=>$Bluedart_information_licence_key,
                                            'LoginID'=>$Bluedart_information_loginid,
                                            'Version'=> '1.3'
                                        )
                    );
      
        $result = $soap->__soapCall('GenerateWayBill',array($params));	
      //echo "<pre>"; print_r($result); die;
        $data = $result->GenerateWayBillResult->AWBPrintContent;
        $error = $result->GenerateWayBillResult->IsError;				
	
        if( !empty( $error ) ){
	    				
            $check_err = $result->GenerateWayBillResult->Status->WayBillGenerationStatus;
				
            if( is_array( $check_err ) ){
		
						$k=1;
						$error_msg = '';
						foreach( $check_err as $err ){
									if( $k==1 )
											$error_msg = $k.')-'.$err->StatusInformation;
									else	
											$error_msg = $error_msg.'. '.$k.')-'.$err->StatusInformation;	
												
									$k++;	
						}
					
			}
			else{
		  
				$error_msg = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation ;
			}
			
			echo 'For order #'.$order_id.' errors:-'.$error_msg.' ';
			
			
	}
	else{
		
	    $AWB_No = $result->GenerateWayBillResult->AWBNo;
	    $des_area =$result->GenerateWayBillResult->DestinationArea; 
		$des_loc =$result->GenerateWayBillResult->DestinationLocation; 
		
	    update_post_meta($order_id,'awb_no',$AWB_No);
	    global $wpdb;
			$count_result = $wpdb->get_results(
									"SELECT *
										FROM `".$wpdb->prefix."orders_manifests`
										WHERE `order_id` =".$order_id."
										ORDER BY `order_id` DESC Limit 1"
									);
			
			if(count($count_result) == 0){
				$wpdb->insert(
								''.$wpdb->prefix.'orders_manifests',
								array(
									'order_id' => $order_id,
									'awb_no'=> $AWB_No,
									'customer_name'=>$customer_name,
									'shipping_address'=>$shipping_address_3,
									'pin_code'=>$order->shipping_postcode,
									'items'=>rtrim($items_name,','),
									'weight'=>$weight,
									'declared_value'=>$mrp,
									'collectable'=>$collectableAmount,
									'mode'=>$pdf_method,
									'destination'=>$des_area.'/'.$des_loc,
									'created_at'=>date("Y-m-d H:i:s")
								),
								array(
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s'
								)
					);
			}
		
		
				
				   require_once( plugin_dir_path(__FILE__).'lib/mpdf60/mpdf.php' );
				    $mpdf=new mPDF('c','A4','','',9,9,9,9,9,9); 
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->showImageErrors = true;

					$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list   
					
					$AWB_No = $result->GenerateWayBillResult->AWBNo;
					
					$field_awb_no="order_".$order_id."_awbno";
					
					update_option( $field_awb_no,$AWB_No); 
					
					
					
					$order_date =$order_date;
					$address = '';
					
			     	$cust_street = $order->shipping_address_1;
					
					
					$cust_resion = $order->shipping_city;
					$cust_pin = $order->shipping_postcode;
					$cust_phone = 'cust_phone';
					
					foreach($line_store_address as $add)
					{
						$address .= '<p>'.$add.'</p>';
						$oneLine_address .= $add;
					}
					
					$html_2 = '';
					if($payment_method_code == 'cashondelivery')
					{	
						$html_2 = '<div class="ttl-amnt">
										<h2>AMOUNT TO BE COLLECTED <br> Rs. '.$collectableAmount.'</h2>
									</div>';
					}
					
					$html_3 = '<table width="100%" cellspacing="0" cellpadding="8" >
											<tr>
												<td align="center" valign="middle" style="width: 6%;">Sr.</td>
												<td align="center" valign="middle" style="width: 10%;">Item Code</td>
												<td align="center" valign="middle" style="width: 30%;">Item Description</td>
												<td align="center" valign="middle" style="width: 12%;">Quantity</td>
												<td align="center" valign="middle" style="width: 12%;">Value</td>
												<td align="center" valign="middle" style="width: 12%;">Total Amount</td>
											</tr>';
											
					$j = 1;
					foreach($ordered_items as $item){    
						$product_id=$item['product_id']; 
						$sku = get_post_meta( $product_id, '_sku', true ); 
						$item_reg_price =get_post_meta($product_id, '_regular_price', true ); 
					    $item_sale_price =get_post_meta($product_id, '_sale_price', true ); 
					    if($item['qty']>0){
						  $item_price=(float)$item['line_subtotal']/(float)$item['qty'];
						}else{ $item_price=0;}
						
						//$final_price =  number_format((($item['qty']) * ($item_price)),2);
						$html_3 .= '<tr>
										<td align="center" valign="middle" style="width: 6%;">'.$j.'</td>
										<td align="center" valign="middle" style="width: 10%;">'.$sku.'</td>
										<td align="center" valign="middle" style="width: 30%;">'.$item['name'].'</td>
										<td align="center" valign="middle" style="width: 12%;">'.$item['qty'].'</td>
										<td align="center" valign="middle" style="width: 12%;">'.$item_price.'</td>
										<td align="center" valign="middle" style="width: 12%;">'.$item['line_total'].'</td>
									</tr>';
						$j++;	
						
					}
					
						
					$order_total = get_post_meta( $order_id, '_order_total', true ); 
					$grand_total = number_format($order_total,2);
					$shipping_charges=number_format(get_post_meta( $order_id, '_order_shipping', true ),2); 
					$ship_charges = number_format($shipping_charges,2);
					$order_shipping_tax = number_format(get_post_meta( $order_id, '_order_shipping_tax', true ),2); 
					$order_tax=number_format(get_post_meta( $order_id, '_order_tax', true ),2); 
					$tax_amt = number_format($order_tax,2)+number_format($order_shipping_tax,2);
					$order_discount = new WC_Order($order_id);	
					$discount=number_format($order_discount->get_total_discount(),2);
				    $discount = !empty($discount)? $discount: 0;
					$html_3 .= '<tr>
								<td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
								<td colspan="2" align="center" valign="middle" style="width: 24%;">Shipping Charges</td>
								<td align="center" valign="middle" style="width: 12%;">'.$ship_charges.'</td>
								</tr>
								
								<tr>
								<td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
								<td colspan="2" align="center" valign="middle" style="width: 24%;">Discount</td>
								<td align="center" valign="middle" style="width: 12%;">'.$discount.'</td>
								</tr>
								
								<tr>
									<td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
									<td colspan="2" align="center" valign="middle" style="width: 24%;">Tax Charges</td>
									<td align="center" valign="middle" style="width: 12%;">'.$tax_amt.'</td>
								</tr>	
								
								<tr>
									<td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
									<td colspan="2" align="center" valign="middle" style="width: 24%;">Total</td>
									<td align="center" valign="middle" style="width: 12%;">'.$grand_total.'</td>
								</tr>	
							</table>';
				
				    $logoUrl = home_url().'/wp-content/uploads/'.unserialize(get_option('Bluedart_information_logo')); 
					$html = '<html>
					<head>
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<title></title>
					<meta name="description" content="">

					</head>
					<body>
						<div class="main-block">
							<div class="sectn-top">
								<div class="log-main">
									<img alt="logo" src="'.$logoUrl.'"  style="height:70px;width:250px;"/>
								</div>
								<div class="ship-adrs">
									<h2>'.$Bluedart_information_store_name.'</h2>
									<p>'.$Bluedart_information_store_address.'</p>
									<p>PIN : '.$Bluedart_information_pincode.'</p>
									<p>Phone : '.$Bluedart_information_phone.'</p>
									<p>Email : '.$Bluedart_information_email.'</p>
									<h4>TIN : '.$Bluedart_information_tin_no.'</h4>
								</div>
								<div class="inv-dtails">
									<p>INVOICE NO <span style="font-size:12px;">: '.$order_id.'</span></p>
									<p>INVOICE DATE <span style="font-size:12px;">: '.date("Y-m-d").'</span></p>
									<p>VAT REG NO <span style="font-size:12px;">: '.$Bluedart_information_tin_no.'</span></p>
									
								</div>
							</div>
						
							
							<div class="sectn-mid">
								<div class="ship-adrs border-no">
									<h2>DELIVER TO</h2>
									<p>'.$customer_name.'<br>'.$order->shipping_address_1.' '.$order->shipping_address_2.'</p>
									<p>'.$order->shipping_city.'</p>
									<h2>'.$order->shipping_postcode.' - '.$des_area.'/'.$des_loc.'</h2>
									<p>'.$order->shipping_state.'</p>
									<p>Phone '.$order->billing_phone.'</p>
									
								</div>
								
								<div class="ordr-dtails">
									<div class="o-id">
										<h2>ORDER ID</h2>
										<div class="img-cntr">
										<barcode code='.$order_id.' type="C39" size="1.0" height="2.0" /></div>
										<p style="text-align:center;">'.$order_id.'</p>
									</div>
									<div class="o-id o-cod">
										<h2>'.$pdf_method.'</h2>
										<div class="img-cntr"><barcode code='.$AWB_No.'  type="C39" size="1.0" height="2.0" /></div>
										<p style="width:100%; text-align:center;">'.$AWB_No.'</p>
									</div>
										
									
									<div class="p-details">
										<p>AWB No. <span>: '.$AWB_No.'</span></p>
										<p>Weight (kgs) <span>: '.number_format($weight,2).'</span></p>
										<p>Dimensions (cms) <span>: '.$dimension.'</span></p>
										<p>Order ID <span>: '.$order_id.'</span></p>
										<p>Order Date <span>: '.$order_date.'</span></p>
										<p>Pieces <span>: '.$qty.'</span></p> 
									</div>
								</div>'.$html_2.'
							</div>
							<div class="tble-btm">
								'.$html_3.'
							</div>
							<p style="width: 100%; text-align:center; font-size: 12px; margin-bottom: 5px;">This is computer generated document, hence does not require signature.</p>
							<p style="width: 100%; text-align:center; font-size: 12px; margin-top: 10px;margin-bottom:20px;">Return Address :'.' ' .$Bluedart_information_store_name.', '.$oneLine_address.','.$Bluedart_information_pincode.'</p>
						</div>
					</body>
					</html>'; 
					$cssPath = plugin_dir_path(__FILE__).'lib/css/pdf.css'; 

					$stylesheet = file_get_contents($cssPath); 					  

					$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

					$mpdf->WriteHTML($html,2);

					$file_name = 'order_'.$order_id.'.pdf';	
					$filename = plugin_dir_path(__FILE__).'pdf_invoice_bluedart/'.$file_name ;
				 	$mpdf->Output($filename,'F');
					echo "For order #'".$order_id."'Waybill number generated successfully.Waybill number is".$result->GenerateWayBillResult->AWBNo;
					$pdf_link=plugin_dir_url(__FILE__).'pdf_invoice_bluedart/order_'.$order->id.'.pdf';
					update_post_meta($order_id,'pdf_link',$pdf_link);
			}            
}?>
