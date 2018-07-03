jQuery(document).ready(function($){
	$( 'body' ).addClass( 'ksfnhksfnsj' );
	
	
	if ( $('.single-product #packaging_v_Plastic').length ) {
		$('.single-product form.variations_form').on( 'click', '.single_add_to_cart_button', function( event ) {
				var $this = $( this );
	
				if ( $this.is('.disabled') ) {
					event.preventDefault();
						if ( $this.is('.wc-variation-selection-needed') ) {
							$('.attribute_packaging label').css('border', '1px solid rgba(255,0,0,0.5)');
					        }
					
				}
		} )
	} else {
		window.alert = function() {};
	}


        // good place to my account 
	var openedWidth = $('#my_account_header #woocommercemyaccountwidget-4 .login').outerWidth();
	$('#my_account_header').css('width', openedWidth);
	
	var openedWidth_out = $('#my_account_header #woocommercemyaccountwidget-4 .logout').outerWidth();
	$('#my_account_header').css('width', openedWidth_out);
	
	
	function checkWidth() {
	        var windowSize = $(window).width();
	
	        if (windowSize < 768) {
	        	var mobile_height = $('h3.nav-toggle').outerHeight();
				$('#wrapper nav#navigation .menus #before_nav').css('height', mobile_height);
				
				$('#wrapper nav#navigation .menus ul#main-nav li').children('ul').hide();
				$('#wrapper nav#navigation .menus ul#main-nav li a').click(function (event) { 
					var ts=$(this);
				 var len=$(ts).parent('li').has('ul').length;
				   if(len>0)
				   {
					   if($(ts).hasClass('clicked'))
					   {
						   
					   }
					   else
					   {
						   $(ts).parent('li').find('ul').first().slideDown();
						   $(ts).addClass('clicked');
						   return false;
					   }
				   }
				});
				$('.logged-in #my_account_main .woocommerce-MyAccount-content .shop_table.my_account_orders.tabledesc tbody tr.actions.bacs .bank_details').click(function() {
					$(this).find('.bank_details_info').toggleClass('opened');
					$(this).toggleClass('opened__bank_details');
					$parent_box = $(this).closest('.bacs');
					$parent_box.toggleClass('opened_bank');
				});
	        }
	        else if (windowSize <= 979) {
	        	var icons_height = $('nav#navigation #main-nav').outerHeight();
			$('nav#navigation .side-nav #my_account_header').css('height', icons_height);
	        }
	    }
	
	    checkWidth();
	    $(window).resize(checkWidth);
	    
	$('body.woocommerce-orders .button.track').attr('target','_blank');
	
		
		// My account page orders
		var load_width = $('.logged-in #my_account_main .shop_table.my_account_orders.tabledesc tbody tr td .order_details').outerWidth();
		$('.logged-in #my_account_main .shop_table.my_account_orders.tabledesc tbody tr td.order-date').css('width', load_width);
		
		$('.logged-in #my_account_main #load_click').click(function() {
		    $parent_box = $(this).closest('.order');
		    $parent_box.find('.order_details').toggleClass('opened');
		    /*$parent_box.find('.order_details').toggle(function () {
			    $('.order_details').removeClass('opened');
			}, function () {
			    $('.order_details').addClass('opened');
			});*/
		    $parent_box.toggleClass('opened_details');
	        });
	        	
		
		
	    var myAccountOrderTable = document.querySelector('.logged-in #my_account_main .woocommerce-MyAccount-content .shop_table.my_account_orders.tabledesc');
	    
	    if (myAccountOrderTable) {
	    	myAccountOrderTable.addEventListener('click', function(event){
	   			
		    	if ( event.target.classList.contains('sfoo_myacc_title_button') ) {

		    		var bank_details = event.target.closest('.bank_details');
		    		bank_details.querySelector('.bank_details_info').classList.toggle('opened');
		    		bank_details.classList.toggle('opened__bank_details');
		    		bank_details.closest('.order').classList.toggle('opened_bank');

		    	}

		    });
	    }

	    wma_login_form = document.getElementById('wma_login_form');

	    if (wma_login_form) {
	    	wma_login_form.addEventListener('submit', function(event){

		    	var login_field = this.querySelector('#user_login');
		    	var password_field = this.querySelector('#user_pass');
		    	
		    	if (login_field.value == '' || password_field.value == ''){

		    		if ( !this.querySelector('.sfoo_wma_login_form_error') && !document.querySelector('.woo-ma-login-failed') ) {
		    			var error_msg = document.createElement('p');
		    			error_msg.className = 'sfoo_wma_login_form_error';
		    			error_msg.innerHTML = 'Login failed, please try again';

		    			this.insertBefore(error_msg, this.firstElementChild);
		    		}
		    	}
		    	
		    });
	    }
				
		$("form.cart.sample #sample_info").hover(function () {
			   $("form.cart.sample #Sample_Product_Text").slideToggle(550);
		});
		
		// add diferent classes to checkout filedset
		var classes_ch = ['first', 'second', 'third'];
		$(function() {
		  var target1 = $('.woocommerce-checkout .billing .woocommerce-billing-fields #checkout_sliced .blocks');
		  target1.each(function(index) {
			$(this).addClass(classes_ch[index % 3]);
		  });
		  var target2 = $('.woocommerce-checkout #main-sidebar-container #main article .woocommerce form.checkout #customer_details .woocommerce-shipping-fields .blocks_shipping');
		  target2.each(function(index) {
			$(this).addClass(classes_ch[index % 2]);
		  });
		});
		
		
		$('.home .slider').slick({
		  dots: false,
		  arrows:false,
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  autoplay: true,
		  autoplaySpeed: 2000,
		  infinite: true,
		  speed: 500,
		  fade: true,
		  cssEase: 'linear'
		});
		
		// $('#search_mobile label').click(function() {
		//     $("#search_mobile #hidden").slideToggle(550);
		// 	$(this).hide();
		// 	$('h3.nav-toggle a').css('opacity','0');
	 //    });
	    $('.icon_search_mobile').click(function(){
	    	$("#search_mobile #hidden").slideToggle(550);
	    	this.classList.toggle('active');
	    	return false;
	    });

	// $('#search_mobile').submit(function(){
	// 	invalid = $('#search_mobile #s').val();
	// 	if(invalid==""){
	// 		$("#search_mobile #hidden").hide();
	// 		$("#search_mobile label.fa-search").slideToggle(550);
	// 		return false;
	// 	}else{
	// 		return true;
	// 	}
	// });
	
	$( "#follow_up_emails" ).clone().appendTo( ".woocommerce-checkout #main-sidebar-container #main article .woocommerce form.checkout #customer_details .billing .woocommerce-billing-fields .blocks #billing_email_field" );

	function oneHeight() {
		if ($(window).width() > 767) {
			$('.related.products .products li.product a.woocommerce-LoopProduct-link').matchHeight();
			$('.woocommerce ul.products li.product>a.woocommerce-LoopProduct-link').matchHeight();
			$('.woocommerce-cart #sidebar .up-sells.upsells.products li a.woocommerce-LoopProduct-link').matchHeight();
			$('.woocommerce ul.products li.product .after_thumb').matchHeight();
			$('.woocommerce ul.products li.product .after_thumb .woocommerce-loop-product__title').matchHeight();
		}
	}
	oneHeight();

	$(window).on('resize', function(){
		oneHeight();
	});
	
	
});