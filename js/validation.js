$(function(){
  $('#form').submit(function(){
    if(input_check()){
      return false;
    }
  });
});

// 入力内容チェックのための関数
function input_check(){
  // 入力内容セット
  var image = $('#img').val();
  var name = $('#name').val();
  var categories_id = $('#categories_id').val();
  var status = $('#status').val();
  var introduce = $('#introduce').val();
  var delivery = $('#delivery').val();
  var userPrice = $('#userPrice').val();

  if(image == ""){
    alert("画像は必須です");
    return true;
  }
  if(name == ""){
    alert("商品名は必須です");
    return true;
  }
  if(name.length >= 40) {
    alert("商品名は40文字以内です");
    return true;
  }
  if(categories_id == ""){
    alert("カテゴリーを選択してください");
    return true;
  }
  if(status == ""){
    alert("商品の状態を選択してください");
    return true;
  }
  if(introduce == ""){
    alert("商品の説明を入力してください");
    return true;
  }
  if(delivery == ""){
    alert("配送方法を選択してください");
    return true;
  }
  if(userPrice == ""){
    alert("値段を入力してください");
    return true;
  }
  if(!preg_match("/^[0-9]+$/"), userPrice){
    alert("半角で入力してください");
    return true;
  }
}