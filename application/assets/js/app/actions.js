// Hidding Loading Spinner
$('.loading').css('visibility','hidden');

// Ajax Load Loading Spinner
$(function(){
    $(document).ajaxStart(function(){ $('.loading').css('visibility','visible'); });
    $(document).ajaxComplete(function(){ $('.loading').css('visibility','hidden'); });
});

$(document).ready(function(){
	$(function () {
	  $('#btnCouponApply').popover({trigger:'manual'})
	})
});
$(document).ready(function(){
	//console.log($('.postcards .slide-content .item').length);
	/*$('.slide-content').owlCarousel({
	    loop:true,
	    margin:10,
	    nav:false,
	    dots: true,
	    responsive:{
	        0:{
	            items:1
	        }
	    }
	});*/
	$('#single-slider').lightSlider({
		gallery: true,
		item: 1,
		loop:true,
		slideMargin: 0,
		thumbItem: 4
	});
});    

// $(document).ready(function(){
// 	$('.bakery-gallery-content').owlCarousel();
// })

/*$('.btn-primary').on('click', function() {
            var $this = $(this);
          	$this.button('loading');
           	$this.button('reset');
           
        });*/

/*########################################################
#	Radio Button Uncheck 
########################################################*/
$(document).ready(function(){
	$('input[name^="attrgroup"]').on('click', function(e){
		console.log(this);
		$('input[name="' + this.name + '"]').not(this).prop('checked',false);
	})
})

/*########################################################
#	Add Item Into Cart
########################################################*/
$(document).ready(function(){
	$('#frmAddToCart').on('submit', function(e){
		e.preventDefault();
		//Loading button Start
		var btn = $(e.currentTarget).find('.btn-primary');
		btn.bootstrapBtn('loading');
		// get Security token name and value
		var csrf_value = $(this).find('input[name="frm_validation"]').val();
		// Set info
		var set_type 	= $('#setType').val();
		var set_id 		= $('#set').val();
		var set_qty 	= $('#qty').val();
		var set_price	= $('#price').val();
		var defined_count = $('#definedCount').val();
		//Collecting Set Products
		var setProducts = {};
// 		$.each($('#ulSetProducts li'),function(idx,prods){
// 			setProducts[idx] = {
// 									'pr_id'	: $(prods).attr('data-prid'),
// 									'name'	: $(prods).attr('data-prname'),
// 									'qty'	: $(prods).attr('data-prqty') 
// 								};
// 		});
		
		var addedCount = 0;
		var temp = [];
		$.each($('#divSetProducts div[id^="slot"]'), function(idx, slot){
			console.log(idx);
			if($(slot).find('input').attr('data-prid') !== ''){
				
				var prId = $(slot).find('input').attr('data-prid');
				//console.log(typeof(setProducts[prId]));
				if(typeof(setProducts[prId]) !== 'undefined') {
					addedCount +=1;
					setProducts[prId].qty += 1;
				} else {
					if	(set_type == 'custom'){
						addedCount +=1;
						setProducts[prId] = {
														'pr_id'	: $(slot).find('input').attr('data-prid'),
														'name'	: $(slot).find('input').attr('data-prname'),
														'qty'		: 1
													};
						
					} else if(set_type == 'static'){
						addedCount += parseInt( $(slot).find('input').attr('data-prqty'));
						setProducts[prId] = {
														'pr_id'	: $(slot).find('input').attr('data-prid'),
														'name'	: $(slot).find('input').attr('data-prname'),
														'qty'		: $(slot).find('input').attr('data-prqty')
													};
					}
				}
			}
		});
		
		console.log(addedCount);
		console.log(defined_count);
		console.log(setProducts);
		if	(addedCount < defined_count){
				$('#prStatusBar').removeClass('alert warning success');
				$('#prStatusBar').addClass('alert warning');	
				$('#prStatusBar').html('Добавьте Макаруны в набор.');
				btn.bootstrapBtn('reset');
				return;
		}
		//Collecting Set Atributes
		var attrs 		 	  = {};
		var attr_prices   = 0;
		var allAttributes = $("input[name^='attrgroup']");
		
		$.each(allAttributes, function(idx,attr){
			
			if ($(attr).is(':checked')) {
				var temp_price = parseFloat($(attr).attr('data-price'))
				attr_prices += temp_price;
				attrs[idx] = {
						   "attr_id" : $(attr).val(),
			 				   "price" : temp_price,
			 			 "allowtext" : '0',
							    "text" : ''
							};
				
				if ($(attr).attr('data-textallowed') == 1) 
				{
					attrs.idx.text = $("$txtCustomText").val();
					attrs.idx.allowtext= '1';
				}
			}
		});
		var last_price = parseFloat(set_price)+parseFloat(attr_prices);
		
		$.ajax({
			 url : '../../cart/actions',
			type : 'post',
			data : {
					frm_validation: csrf_value,
					item:{
								set_id : set_id,
								   qty : set_qty,
								 price : set_price,
							last_price : last_price,
								 attrs : attrs,
						   setproducts : setProducts
						}

			},
			success: function(response){
				$('#spSetPrice').html(last_price.toLocaleString());
				$('#prStatusBar').removeClass('alert warning');
				$('#prStatusBar').removeClass('success');
				$('#prStatusBar').addClass('alert success');
				$('#prStatusBar').html(response.message);

				update_cart_count(response.cqty);
				btn.bootstrapBtn('reset');
			},
			error: function(response){
				$('#prStatusBar').removeClass('alert warning success');
				$('#prStatusBar').addClass('alert warning');	
				$('#prStatusBar').html(response.responseJSON.message);
				btn.bootstrapBtn('reset');
			}
		});


	});
});

/*########################################################
#	Clear Cart Button Click
########################################################*/
$(document).ready(function(){
	$('#btnClearCart').on('click', function(e){
		e.preventDefault();
		//var btn = $(this).button('loading');
		$.ajax({
				 url : '../../cart/clear',
				type : 'post',
				data : {
						action : 'clear'
				},
				success: function(response){
					console.log(response);
					
					clear_cart();
					update_cart_count();
				},
				error: function(response){
					console.log(response);
					//btn.button('reset');	
				}
		})
	});
});

/*########################################################
#	Clear Cart Funtion
########################################################*/
function clear_cart(){
	$('#tblCheckout tbody').html('');
	$('#tblCheckout tfoot').html('');
}


/*########################################################
#	Remove item From Cart
########################################################*/
$(document).ready(function(){
	$('.csRemoveCartItem').on('click', function(e){
		e.preventDefault();
		var closest_tr = $(e.currentTarget).closest('tr');
		//var btn = $(this).button('loading');
		$.ajax({
				 url : '../../cart/actions/'+closest_tr.attr('id'),
				type : 'delete',
				data : {
						action : 'clear_item'
				},
				success: function(response){
					console.log(response);
					closest_tr.remove();
					update_total();
					update_cart_count();
					//btn.button('reset');
				},
				error: function(response){
					console.log(response);
					//btn.button('reset');	
				}
		})
	});
});

/*########################################################
#	Get Cart Total
########################################################*/
function get_total() {

	var total = 0;
	$.each($('#tblCheckout tbody tr'), function(idx, row){
			total += parseFloat($(row).find('#tbQty').val()) * parseFloat($(row).find('#spUnitPrice').attr('data-unitprice'));
		});
	
	// Check for Shipping zone for Min Price
	var minPrice = parseFloat($('#shpZone option:selected').attr('data-zminprice'));
	if ( total !== 0 && minPrice && total < minPrice) {
		total = minPrice;
	}
	console.log('total from get_total: '+total);
	if (total) {
		return total;
	} else{
		return 0;
	}
}
/*########################################################
#	Update Cart Total
########################################################*/
function update_total() {
	var total = get_total();
	if (total !== 0) {
		var coupon = $('#tblCheckout tfoot tr#trCartCoupon').find('td span#spCartCoupon');
		var discountType = coupon.attr('data-discounttype');
		var discountValue = coupon.attr('data-discountvalue');
		var finalTotal = total;
		
		if ( discountType && discountType == 'fix' ) {
			finalTotal = total - parseFloat(discountValue);
		} else if ( discountType && discountType == 'percent' ) {
			finalTotal = total - (total * parseFloat(discountValue)/100);
		}

		var shipping = $('#tblCheckout tfoot tr#trCartShipping').find('td span#spCartShipping');
		var shippingPrice = parseFloat(shipping.attr('data-shippingprice'));
		//console.log(shippingPrice);
		if ( shippingPrice && shippingPrice !== 0 ) {
			finalTotal+=shippingPrice;
		}
		//console.log(total);
		//console.log(total.toLocaleString());
		$('#tblCheckout tfoot tr#trCartTotal td span#spCartTotal').html(total.toLocaleString());
		$('#tblCheckout tfoot tr#trCartGrandTotal td span#spCartGrandTotal').html(finalTotal.toLocaleString());
	}
	else {
		$('#tblCheckout tfoot tr#trCartTotal td span#spCartTotal').html(total);
		$('#tblCheckout tfoot tr#trCartGrandTotal td span#spCartGrandTotal').html(total);
	}
}

/*########################################################
#	Increase Item Qty 
########################################################*/
$(document).ready(function(){
	$('#tblCheckout tr td button.lnkChangeQtyUp').on('click', function(e){
		e.preventDefault();
		var closest_tr = $(e.currentTarget).closest('tr');
		var	qty = closest_tr.find('#tbQty').val();
		var newQty = parseInt(qty)+1;
		if (closest_tr.attr('id')) {
			updateCartItemQty(closest_tr, newQty);
		}
	});
});
/*########################################################
#	decrease Item Qty 
########################################################*/
$(document).ready(function(){
	$('#tblCheckout tr td button.lnkChangeQtyDown').on('click', function(e){
		e.preventDefault();
		var closest_tr = $(e.currentTarget).closest('tr');
		var	qty = closest_tr.find('#tbQty').val();
		if (qty>1) {
			var newQty = parseInt(qty)-1;
			if (closest_tr.attr('id')) {
				updateCartItemQty(closest_tr, newQty);
			}
		}
	});
});

/*########################################################
#	Update Item Qty 
########################################################*/
function updateCartItemQty(closest_tr,qty){
	$.ajax({
			 url : '../../cart/actions/'+closest_tr.attr('id'),
			type : 'put',
			data : {
					   qty : qty
			},
			success: function(response){
				console.log(response);
				closest_tr.find('#tbQty').val(qty);
				new_item_total = qty* parseInt(closest_tr.find('#spUnitPrice').attr('data-unitprice'));
				closest_tr.find('td.product-total-price').html(new_item_total.toLocaleString());
				console.log(closest_tr.find('td.product-total-price'));
				
				//btn.button('reset');
				update_total();
			},
			error: function(response){
				console.log(response);
				//btn.button('reset');	
			}
	});
}


/*########################################################
#	Get Cart items Count
########################################################*/
function get_cart_items_count() {
	if ($('#tblCheckout tbody tr').length) {
		return $('#tblCheckout tbody tr').length;
	} else {
		return 0;
	}
}

/*########################################################
#	Update Header Basket Cart Count
########################################################*/
function update_cart_count(cqty) {
	if (typeof(cqty)!==undefined && cqty) {
		$('.cart-order-quantity').html(cqty);
	} else {
		$('.cart-order-quantity').html( get_cart_items_count() );
	}
}

/*########################################################
#	Apply Coupon to cart
########################################################*/
$(document).ready(function(){
	$('#btnCouponApply').on('click', function(e){
		var coupon_code = $.trim($('#tbCoupon').val());

		$.ajax({
				 url : '../../cart/applycoupon',
				type : 'post',
				data : {
						   coupon : coupon_code
				},
				success: function(response){
					console.log(response);
					
					coupon_data = {};
					coupon_data.coupon_code = coupon_code;
					coupon_data.type = response.type;
					coupon_data.discount = response.discount;
					coupon_data.label = response.label;

					update_coupon_in_table(coupon_data);

					update_total();
					$('#btnCouponApply').attr('data-content',"Купон Добавлен в таблицу.")
					$('#btnCouponApply').popover('toggle');
				},
				error: function(response){
					$('#btnCouponApply').attr('data-content',response.responseJSON.message)
					$('#btnCouponApply').popover('toggle');
					//console.log(response);
					//btn.button('reset');	 
				}
		})
	})

});


/*########################################################
#	Remove Coupon From Cart
########################################################*/
$(document).ready(function(){
	$('#tblCheckout tfoot tr td button.csRemoveCartCoupon').on('click', function(e){
		e.preventDefault();
		var closest_tr = $(e.currentTarget).closest('tr');
		console.log(closest_tr.attr('data-code'));
		//var btn = $(this).button('loading');
		coupon_code = closest_tr.attr('data-code');
		if (typeof(coupon_code) === "undefined" || coupon_code === '') {
			return;
		}
		$.ajax({
				 url : '../../cart/removecoupon/'+coupon_code,
				type : 'delete',
				data : {
						   coupon : 'delete'
				},
				success: function(response){
					
					// reset coupon row
					update_coupon_in_table();
					// Update Checkout Table total
					update_total();
				},
				error: function(response){
					console.log(response);
					//btn.button('reset');
				}
		})
	})
});

/*########################################################
#	Coupon Table Data operations Add/Remove
########################################################*/
function update_coupon_in_table(data) {
	var coupon_row = $('#tblCheckout tfoot tr#trCartCoupon');
	if (typeof(data) !== undefined && data) {

		coupon_row.attr('data-code', data.coupon_code);
		coupon_row.find('td span#spCartCouponCode').attr('data-code', data.coupon_code);
		coupon_row.find('td span#spCartCouponCode').text(data.coupon_code);
		coupon_row.find('td span#spCartCoupon').attr('data-discounttype', data.type);
		coupon_row.find('td span#spCartCoupon').attr('data-discountvalue', data.discount);
		coupon_row.find('td span#spCartCoupon').text('-'+data.discount+data.label);
	} else {
		coupon_row.attr('data-code','');
		coupon_row.find('td span#spCartCouponCode').attr('data-code', '');
		coupon_row.find('td span#spCartCouponCode').text('');
		coupon_row.find('td span#spCartCoupon').attr('data-discounttype', '');
		coupon_row.find('td span#spCartCoupon').attr('data-discountvalue', '');
		coupon_row.find('td span#spCartCoupon').text('');
	}
}


/*########################################################
#	Hide Coupon Windows
########################################################*/
$(document).ready(function(){
	$('#tbCoupon').on('click', function(e){
		$('#btnCouponApply').popover('hide');
	});
});

/*########################################################
# Refresh Custom Set
########################################################*/
$(document).ready(function(){
	$("#lnkRefreshSet").on('click',function(e){
		e.preventDefault();
			$.each($('#divSetProducts div[id^="slot"]'), function(idx, slot){
			console.log(idx);
				var prId = $(slot).find('input').attr('data-prid');
				$(slot).find('input').attr('data-prid', '');
				$(slot).find('input').attr('data-prname', '');
				$(slot).find('input').attr('data-prqty', '');
				$(slot).removeClass('slot-busy');
				$(slot).addClass('slot-empaty');
				$(slot).find('img').attr('src',$(slot).find('img').attr('data-defsrc'));
				$(slot).find('img').removeClass('transform-rotate');
				$("#frmAddToCart .additional-desc #lnkRefreshSet").css("display","none");
				$("#frmAddToCart .additional-desc .macaroon-box-type").find('.macaroon-box-bantik').css("display","none");
		});
	});
});


/*########################################################
# Add/Remove Item Into Custom Set
########################################################*/
$(document).ready(function(){
	$('a[id^="setCustomItem_"]').on('click',function(e){
		e.preventDefault();
		boxAction(e,'add');
	});
});

/*########################################################
# Add/Remove Item Into Custom Set
########################################################*/
// $(document).ready(function(){
// 	$('#ulSetProducts').on('click','button.removeOne',function(e){
// 		e.preventDefault();
// 		console.log('remove it');
// 		boxAction(e,'sub');
// 	});
// });

$(document).ready(function(){
	$('div[class^="macaroon-box"]').on('click','div img',function(e){
		e.preventDefault();
		console.log('remove it');
		boxAction(e,'sub');
	});
});



/*########################################################
# Actions For Add/Remove Item Into Custom Set
########################################################*/
function boxAction(e,action) {

	var definedCount = parseInt($('#definedCount').val());
	if (action == 'add') {
		var prId = $(e.currentTarget).attr('data-prid');
		var prName = $(e.currentTarget).attr('data-prname');
		var prImgName = $(e.currentTarget).attr('data-primgname');
		
// 		var is_exists = false;
// 		var existingItemQty = 0;
// 		var foundItem = '';
// 		var overallQty = 0;
// 		$.each($('#ulSetProducts li'), function(idx, li){
// 			overallQty = overallQty + parseInt($(li).attr('data-prqty'));
// 			if ($(li).attr('data-prid')== prId) {
// 				is_exists = true;
// 				foundItem = $(li);
// 				existingItemQty = parseInt(foundItem.attr('data-prqty'))+1;
// 			}
// 		});
		var empty_slot = $('[class^="macaroon-box"]').find('.slot-empaty');
		console.log(empty_slot[0]);
		if(typeof(empty_slot[0]) !== undefined){
			$(empty_slot[0]).removeClass('slot-empaty');
			$(empty_slot[0]).addClass('slot-busy');
			$(empty_slot[0]).find('img').attr('src',prImgName);
			$(empty_slot[0]).find('img').addClass('transform-rotate');
			$(empty_slot[0]).find('input').attr('data-prid',prId);
			$(empty_slot[0]).find('input').attr('data-prname',prName);
			
			var empty_slot_after = $('[class^="macaroon-box"]').find('.slot-empaty');
			
			if(empty_slot_after.length === 0){
				$("#frmAddToCart .additional-desc #lnkRefreshSet").css("display","block");
				$($("#frmAddToCart .additional-desc .macaroon-box-type").find('.macaroon-box-bantik')).show(300);//css("display","block");
			}
			//$(empty_slot[0]).find('img').css('transform','rotate(-90deg)');
			//$(empty_slot[0]).find('img').css('height','86');
			//$(empty_slot[0]).find('img').css('width','54');
		} 
		
// 		if (overallQty < definedCount) {
// 			if (!is_exists) {
// 				$('#ulSetProducts').append('<li '+
// 											 'data-prid="'+prId+'"'+
// 											 'data-prname="'+prName+'"'+
// 											 'data-prqty="'+1+'">'+
// 											'<button type="button" class="removeOne btn btn-danger btn-xs" id="removeOne"> '+
// 												'<i class="fa fa-minus" aria-hidden="true"></i>'+
// 											'</button> '+
// 											 prName+
// 											' -<span class="curProdQty">1</span>шт. '+
// 											'</li>');
// 			} else {
// 				foundItem.attr('data-prqty', existingItemQty);
// 				foundItem.find('.curProdQty').text(existingItemQty);
// 			}
// 		} else if( overallQty > definedCount ) {
// 			location.reload();
// 		}
	} else if(action == 'sub') {
			$(e.currentTarget).closest('div').removeClass('slot-busy');
			$(e.currentTarget).closest('div').addClass('slot-empaty');
			$(e.currentTarget).closest('input').attr('data-prid','');
			$(e.currentTarget).closest('input').attr('data-prname','');
			//$(e.currentTarget).css('transform','rotate()');
			$(e.currentTarget).attr('src',$(e.currentTarget).attr('data-defsrc'));
			$(e.currentTarget).removeClass('transform-rotate');
// 		var prId = $(e.currentTarget).closest('li').attr('data-prid');
// 		var is_exists = false;
// 		var existingItemQty = 0;
// 		var foundItem = '';
// 		var overallQty = 0;

// 		$.each($('#ulSetProducts li'), function(idx, li){
// 			overallQty = overallQty + parseInt($(li).attr('data-prqty'));
// 			if ($(li).attr('data-prid')== prId) {
// 				is_exists = true;
// 				foundItem = $(li);
// 				existingItemQty = parseInt(foundItem.attr('data-prqty'))-1;
// 			}
// 		});

// 		if (existingItemQty < 1) {
// 			foundItem.remove();
// 		} else {
// 			foundItem.attr('data-prqty', existingItemQty);
// 			foundItem.find('.curProdQty').text(existingItemQty);
// 		}

	}
}

/*########################################################
# Update Client Info
########################################################*/
$(document).ready(function(){
	$('#clientForm').on('submit', function(e){
		e.preventDefault();
		var alldata = $(this).serialize();
		//console.log(alldata);

		$.ajax({
			 url : 'cprofile/clactions',
			type : 'post',
			data : alldata,
			success: function(response){
				console.log(response);
				$('#updateStatus').removeClass('alert alert-danger');
				$('#updateStatus').addClass('alert alert-success');

				$('#updateStatus').text(response.message);
			},	
			error: function(response){
				console.log(response.responseJSON);
				$('#updateStatus').removeClass('alert alert-success');
				$('#updateStatus').addClass('alert alert-danger');
				$('#updateStatus').html(response.responseJSON.message);
			}
		})
	});
});



/*########################################################
# Addres list Change Event
########################################################*/
$(document).ready(function(){
	$('#addressList').on('change', function(e){
		$('#shpName').val($(this).find(':selected').attr('data-fname') +' '+ $(this).find(':selected').attr('data-sname'));
		/*$('#shpSname').val($(this).find(':selected').attr('data-sname'));*/
		$('#shpCity').val($(this).find(':selected').attr('data-city'));
		$('#shpStreet').val($(this).find(':selected').attr('data-street'));
		$('#shpBld').val($(this).find(':selected').attr('data-bld'));
		$('#shpApt').val($(this).find(':selected').attr('data-apt'));
		$('#shpPhone').val($(this).find(':selected').attr('data-phone'));
		$('#shpEmail').val($(this).find(':selected').attr('data-email'));
	})
});

/*########################################################
# Shipping Type Change Event
########################################################*/
$(document).ready(function(){
	$('#shpType').on('change', function(e){

		// Shipping Type Values
		// st1 - с курьером
		// st2 - самовывоз
		if ($(e.currentTarget).find(':selected').attr('data-shtcode') == 'st2') {
				$('#shpCity').prop('disabled', true);
				$('#shpStreet').prop('disabled', true);
				$('#shpBld').prop('disabled', true);
				$('#shpApt').prop('disabled', true);
				$('#shpZone').val('').trigger('change');
				$('#shpZone').prop('disabled', true);
				$('#spLblShpDate').html('Самовывоза');
				$('#spLblShpTime').html('Самовывоза');
		} else {
				$('#shpCity').prop('disabled', false);
				$('#shpStreet').prop('disabled', false);
				$('#shpBld').prop('disabled', false);
				$('#shpApt').prop('disabled', false);
				$('#shpZone').prop('disabled', false);
				$('#spLblShpDate').html('Доставки');
				$('#spLblShpTime').html('Доставки');
		}
	})
});

/*########################################################
# Shipping Zone change Event
########################################################*/
$(document).ready(function(){
	$('#shpZone').on('change', function(e){
		
		$('#shpZoneDescription').html($(this).find(':selected').attr('data-description'));
		if ($(this).find(':selected').val() === "") {
			$('#tblCheckout tfoot tr#trCartShipping').find('td span#spCartShipping').text('');
			$('#tblCheckout tfoot tr#trCartShipping').find('td span#spCartShipping').attr('data-shippingprice','0');
			update_total();
			return;
		}
		// check mi price and 
		// if min price ignore coupon and add min price into footer
		var shp_min_price = parseFloat($(this).find(':selected').attr('data-zminprice'));
		var shp_same_day_price = parseFloat($(this).find(':selected').attr('ddata-zsamedayprice'));
		var shp_next_day_price = parseFloat($(this).find(':selected').attr('data-znextdayprice'));

		// check time for next day/ same day
		/*var now = new Date.now();
		var shp_date = now.getDate()+1;*/
		// add shipping price into footer
		$('#tblCheckout tfoot tr#trCartShipping').find('td span#spCartShipping').text(shp_next_day_price);
		$('#tblCheckout tfoot tr#trCartShipping').find('td span#spCartShipping').attr('data-shippingprice', shp_next_day_price);

		// Recalculate And Update Table final total
		update_total();

	})
});

/*########################################################
# Shipping Date change Event
########################################################*/
$(document).ready(function(){
    $( "#shpDate" ).datepicker({
    	dateFormat:'yy-mm-dd',
		 minDate:0,
		 maxDate: "+7D"
    });
});



/*########################################################
#	Submit Cart Checkout
########################################################*/
$(document).ready(function(){
	$('#frmCheckout').on('submit', function(e){
		e.preventDefault();

		var btn = $(e.currentTarget).find('.btn-default');
		btn.bootstrapBtn('loading');

		console.log('frmcheckout');
		alldata = $(this).serialize();
		alldata+='&btnSbmtCart=';
		$.ajax({
				 url : 'cart/submit',
				type : 'post',
				data : alldata,
				success: function(response){
					console.log(response.message);
					$('#chktStatusBar').removeClass('alert warning');
					$('#chktStatusBar').removeClass('success');
					$('#chktStatusBar').addClass('alert success');
					$('#chktStatusBar').html(response.message)
					btn.bootstrapBtn('reset');
					clear_cart();
					update_cart_count(0);
					if (response.location) {
						window.location = response.location;
					}
				},	
				error: function(response){
					$('#chktStatusBar').removeClass('alert warning success');
					$('#chktStatusBar').addClass('alert warning');	
					btn.bootstrapBtn('reset');
					 message = '';
					if (response.responseJSON) {
						message = response.responseJSON.message;
						console.log(response.responseJSON);
					} else {
						message = 'something went wrong refresh the page and try again!!!';
						console.log(message);

					}
					$('#chktStatusBar').html(message);
					$(window).scrollTop(0);
				}
		})

	});
});



/*########################################################
#	Validate Checkout
########################################################*/