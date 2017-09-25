function calculateOnLoad($foo){
    for(i = 0; i < $foo; i++ ){
        calculateTicketPrice(i);
        console.info(i);
    }
    calculateTotalPrice($foo);
}


function calculateTicketPrice($foo){

    var $spanPrice = document.getElementById("price_"+$foo);

    $spanPrice.textContent = definePrice($foo);

}

function definePrice($foo){
    var $checkbox = document.getElementById("order_tickets_tickets_"+$foo+"_discount");
    var $dateBirth = document.getElementById("order_tickets_tickets_"+$foo+"_birth");

    var $price = 0.00;

    if($dateBirth.value !== 'undefined' && $dateBirth.value !== ''){
        var $age = defineAge($dateBirth.value);

        if($age >= 4 && $age <= 12){

            $price = 8.00;

        } else if($age > 12 && $age < 60 ){

            $price = 16.00;

        } else if($age >= 60){

            $price = 12.00;
        }

        if($checkbox.checked && $price > 10.00){
            $price = 10.00;
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

function calculateTotalPrice($foo){

    $totalPrice = 0.00;

    for(i=0; i < $foo; i++){
        $totalPrice = $totalPrice + definePrice(i);
    }

    document.getElementById("total_price").textContent = $totalPrice+"€*";


}

