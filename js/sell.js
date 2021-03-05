'use strict';

$(function(){
	var delivery_fee = 200; //
	var tagInput = $('.jsPrice'); // 入力対象のinputタグID名
	var tagOutput = $('.jsSumPrice'); // 出力対象のinputタグID名
	tagInput.on('change', function() {
		var str = $(this).val();
		var num = Number(str.replace(/[^0-9]/g, '')); // 整数以外の文字列を削除
		$(this).val(num);
    var price = num + delivery_fee;
    tagOutput.val(price);
	});
});