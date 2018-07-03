function ship_postcode_function(){

	var ship_post_code=jQuery('#shipping_postcode').val();
		var data = { action:'get_remote_content',pin: ship_post_code };
	
        jQuery.ajax({
                    type: "POST",
                    url: ajax_url.url,
                    data:data,
                    dataType: 'json',
                    beforeSend: function(){},
                    success: function( res_data ){
                                
                                var msg = '';
                                    res = JSON.parse( JSON.stringify( res_data ) );
				if( res.is_error == 'Valid' ){
                                    if(res.cod_in == 'No' && res.cod_out == 'No'){
                                    	msg = "COD is not available for - "+res.place+".";
					jQuery(".payment_method_cod").html(msg);
                                    }
				}else if( res.is_error == 'InvalidPinCode' ) {
                                    
                                    msg = "<label>[ Pin code is invalid. ]</label>";
                                    jQuery('#payment_method_cod').attr( 'disabled',true );
                                    jQuery( msg ).insertAfter(".payment_method_cod label:first");
				}
                            }
         });	
	
}

function submitForm(){

			 var validator = jQuery("#bluedart_shipping_form").validate({
			  rules: {  
				    Bluedart_information_licence_key: "required",                
			        Bluedart_information_loginid: "required",
				
				      Bluedart_information_email:{
													  required:true,
													  email:true
												},
	       
				    Bluedart_information_store_name: "required",
				    
					 Bluedart_information_phone: {
												  required:true,
												  minlength:10,
												  maxlength:10,
												  number: true
												  },
				    Bluedart_information_store_address: "required",
				    Bluedart_information_pincode: {
												  required:true,
												  number: true
												  },
				    Bluedart_information_customercode: "required",
				    Bluedart_information_vandercode: "required",
				    Bluedart_information_originarea: "required",
				    Bluedart_information_tin_no:"required"
				  },  
				 errorElement: "span" ,                              
				 messages: {
				    Bluedart_information_licence_key: "Please enter valid licence key",                
			        Bluedart_information_loginid: "Please enter login id",
				    //Bluedart_information_email: "Please enter email id",
				    Bluedart_information_store_name: "Please enter store name",
				   // Bluedart_information_phone: "Please enter contact no",
				    Bluedart_information_store_address: "Please enter store address",
				   // Bluedart_information_pincode: "Please enter valid pin code",
				    Bluedart_information_customercode: "Please enter customer code",
				    Bluedart_information_vandercode: "Please enter vander code",
				    Bluedart_information_originarea: "Please enter origin area",
				    Bluedart_information_tin_no:"Please enter tin no"
				 }
			 });
			 
			 
		   if ($('#bluedart_shipping_form').valid()) {
				$('#bluedart_shipping_form').submit(); //submitting the form
				$('#messages1').html('Information saved successfully..');
			}
}

jQuery(document).ready(function( $ ) {
	
	
	 $('#billing_postcode').blur(function(){
        
        var ship_post_code=jQuery('#billing_postcode').val();
		var data = { action:'get_remote_content',pin: ship_post_code }
        jQuery.ajax({
                    type: "POST",
                    url: ajax_url.url,
                    data:data,
                    dataType: 'json',
                    beforeSend: function(){},
                    success: function( res_data ){
                                
                                
                                    res = JSON.parse( JSON.stringify( res_data ) );
				if( res.is_error == 'Valid' ){
                                    if(res.cod_in == 'No' && res.cod_out == 'No'){
                                    	msg = "COD is not available for - "+res.place+".";
					jQuery(".payment_method_cod").html(msg);
                                    }
				}else if( res.is_error == 'InvalidPinCode' ) {
                                    
                                    msg = "<label>[ Pin code is invalid. ]</label>";
                                    jQuery('#payment_method_cod').attr( 'disabled',true );
                                    jQuery( msg ).insertAfter(".payment_method_cod label:first");
				}
                            }
         });
                   
    });	
	$('#ship-to-different-address-checkbox').click(function(){
		
		var this_checked =  $(this).prop('checked');
	
		if(this_checked){

			ship_postcode_function();
		}else{
				
			var ship_post_code=jQuery('#billing_postcode').val();
			var data = { action:'get_remote_content',pin: ship_post_code };
	
			jQuery.ajax({
						type: "POST",
						url: ajax_url.url,
						data:data,
						dataType: 'json',
						beforeSend: function(){},
						success: function( res_data ){
									
									var msg = '';
										res = JSON.parse( JSON.stringify( res_data ) );
					if( res.is_error == 'Valid' ){
										if(res.cod_in == 'No' && res.cod_out == 'No'){
											msg = "COD is not available for - "+res.place+".";
						jQuery(".payment_method_cod").html(msg);
										}
					}else if( res.is_error == 'InvalidPinCode' ) {
										
										msg = "<label>[ Pin code is invalid. ]</label>";
										jQuery('#payment_method_cod').attr( 'disabled',true );
										jQuery( msg ).insertAfter(".payment_method_cod label:first");
					}
								}
			 });
			
		}
	
	});
    
    $('#shipping_postcode').blur(function(){
        
        ship_postcode_function();
                   
    });	
    

   
   
   
    
    /*
     * edit order bluedart button
     * */	  
    $('#shipment_button').click(function(){
		var order_ids = [];
		var id      = $('#order_id').val();
		order_ids.push(id);
            //action  = $("#order_id").attr("data-action");
            length  = $('#length').val();
            breadth = $('#breadth').val();
            height  = $('#height').val();
            weight  = $('#weight').val();
        
	if(length == "" || breadth == "" || height == "" || weight == ""){
            
            jQuery("#error").html("please enter all values");
            
	}
	else if(length == 0 || breadth == 0 || height == 0 || weight == 0){
            
            jQuery("#error").html("Please enter values other than 0.");
            
	}
	else{
            
            var data = { action:'get_remote_content_admin', order_ids: order_ids, length:length, breadth:breadth, height:height, weight:weight };
            
            jQuery.ajax({
			type: "POST",
			url: ajax_url.url,
			data:data,
			dataType: 'html',
			beforeSend: function(){ jQuery('#loader').show(); },
			success: function(res) {
                                    jQuery("#bluedart_box").html(res);
                                },
                       complete: function(){  $('#loader').hide(); } 
                    });
	}
    });
	
    /*
     * Code for checkout page for checking COD on single products page
     */	
    $('#cod_check').click(function(){
        var check_price = document.getElementById('single_product_price').value;
        var pin = document.getElementById('pincodevalue').value;
        if( pin == ''){
            alert("Please enter pincode.");
            return true;
        }else{	
            //document.getElementById('loader').style.display = 'block';
            var data = { action:'get_remote_content',pin: pin };
            $.ajax({
                        type: "POST",
                        url: ajax_url.url,
                        data:data,
                        dataType: 'json',
                        beforeSend: function(){ $('#loader').show(); },
                        success: function( res_data ){
                            jQuery("#pinresult").html( res_data );		
                            var msg = '';
                            res = JSON.parse( JSON.stringify( res_data ) );
                            document.getElementById('loader').style.display = 'none';
                            if( res.is_error == 'Valid' ){
                                if( res.cod_in == 'Yes' && res.cod_out == 'Yes' ){
                                    if( check_price <= res.value_limit )
                                        msg = "COD is available for - "+res.place+".";
                                    else
                                        msg = "Product price is above then COD limit - "+res.value_limit+".";	
                                }else if( res.cod_in == 'No' && res.cod_out == 'No' ){
                                    msg = "COD is not available for - "+res.place+".";
                                }
                            }else if( res.is_error == 'InvalidPinCode' ){
                                msg = "No COD service.";
                            }
                            jQuery("#pinresult").html(msg);
                        },
                        complete: function(){  $('#loader').hide(); }                         
            });
        }
    });
	

});
