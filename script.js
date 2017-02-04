/**
 * Created by Pratik on 11/25/2016.
 */
var addToCart = function(name,count,isbn) {
    console.log(isbn)
    //get the quantity
    var qty = document.getElementById(isbn).value ;
    console.log(qty);
    if(count >= qty && qty > 0){
        $.post('processAddToCart.php',{bookname:name,ISBN:isbn,Quantity:qty},function (data) {
            //console.log(data);
            document.getElementById('result').innerHTML  = "Cart Items :"+ data;
        });
    }
}