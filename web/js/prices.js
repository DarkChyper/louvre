function calculateOnLoad($foo){
    console.info("onload");
    for(i = 0; i < $foo; i++ ){
        calculateTicketPrice(i);
        console.info(i);
    }
    calculateTotalPrice($foo);
}


function calculateTicketPrice($foo,$ticketType){

    var $spanPrice = document.getElementById("price_"+$foo);

    $spanPrice.textContent = definePrice($foo,$ticketType);

}

function definePrice($foo,$ticketType){
    var $checkbox = document.getElementById("order_tickets_tickets_"+$foo+"_discount");
    var $dateBirth = document.getElementById("order_tickets_tickets_"+$foo+"_birth");

    var $price = 0.00;
    var $quotient = 1.0;
    console.info($ticketType);
    if($ticketType === "HALF") {
        $quotient = 0.5;
    }

    if($dateBirth.value !== 'undefined' && $dateBirth.value !== ''){
        var $age = defineAge($dateBirth.value);

        if($age >= 4 && $age < 12){

            $price = 8.00 * $quotient;

        } else if($age => 12 && $age < 60 ){

            $price = 16.00 * $quotient;

        } else if($age >= 60){

            $price = 12.00 * $quotient;
        }

        if($checkbox.checked && $price > (10.00 * $quotient)){
            $price = (10.00 * $quotient);
        }

    }

    return $price;

}

function defineAge($dateFR){
    var $dateBirth = setDateObject($dateFR);

    var $today = new Date();
    $today.setHours(0,0,0,0);

    $age = $today.getFullYear() - $dateBirth.getFullYear();

    if(
        ($dateBirth.getMonth() === $today.getMonth() &&
            $dateBirth.getDate() < $today.getDate())
        || ($today.getMonth() < $dateBirth.getMonth())
        ){

        $age = $age - 1;

    }

    return $age;

}

function setDateObject($dateFR){
    var $dateObject = new Date();
    $dateObject.setFullYear($dateFR.substr(6,4));
    $dateObject.setMonth($dateFR.substr(3,2));
    $dateObject.setDate($dateFR.substr(0,2));
    $dateObject.setHours(0,0,0,0);
    return $dateObject

}

function calculateTotalPrice($foo,$ticketType){

    $totalPrice = 0.00;

    for(i=0; i < $foo; i++){
        $totalPrice = $totalPrice + definePrice(i,$ticketType);
    }

    document.getElementById("total_price").textContent = $totalPrice+"€*";


}

