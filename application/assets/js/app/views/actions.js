$(document).ready(function(){
	
	$('#frmAddToCart').on('submit', function(e){
		e.preventDefault();
		var set_id = $('#set').val();
		var set_qty = $('#qty').val();
		var set_price = $('#price').val();

		var allAttributes =$('#attrs').val();

		var attrs = [];
		/*attrs[set_id][]
		$attr['attr_id']
		$attr['qty']
		$attr['price']
		$attr['text']*/
		console.log(attrs);

	});
});