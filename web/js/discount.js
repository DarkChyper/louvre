
// foo is the number of the ticket (begining with 0)
function setDiscountInfo(foo){

    var checkbox = document.getElementById("order_tickets_tickets_"+foo+"_discount");
    var divInfo = document.getElementById("discount_"+foo);

    console.debug(checkbox.checked);

    if(checkbox.checked){
        divInfo.className = "card card-info center";
    } else {
        divInfo.className = "invisible";
    }

}


