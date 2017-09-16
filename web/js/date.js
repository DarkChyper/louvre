function isFullDayEnable() {
    var $inputDate = document.getElementById("order_visitDate").value;
    console.info("valeur du champ : " + $inputDate);

    if($inputDate !== 'undefined' && $inputDate !== ""){
        var $dateObject = setDateObject($inputDate);
        var $now = new Date();
        var $btnSuivant = document.getElementById("order_suivant");
        var $inputFull = document.getElementById("order_ticketType_0");
        var $inputHalf = document.getElementById("order_ticketType_1");
        var $blockInfos = document.getElementById("blockInfos");

        console.info("getDay : " + $dateObject.getDay());
        if($dateObject.getDay() === 2 || $dateObject.getDay() === 4){
            // if date is sunday or tuesday
            console.info("dimanche ou mardi");
            $inputFull.disabled = true;
            $inputFull.checked = false;
            $inputHalf.disabled = true;
            $inputHalf.checked = false;
            $btnSuivant.disabled = true;
            $blockInfos.className = "card bgRed center";
            return; // no need more test

        } else {
            $inputFull.disabled = false;
            $inputHalf.disabled = false;
            $btnSuivant.disabled = false;
            $blockInfos.className = "invisible";
        }

        if( isToday($dateObject, $now) && $now.getHours() >= 14) {
            // no order for full day after 2pm the same day
            $inputFull.disabled = true;
            $inputFull.checked = false;
            $inputHalf.checked = true;

        } else {
            $inputFull.disabled = false;
        }
    }

}

function setDateObject($dateFR){
    var $dateObject = new Date();
    $dateObject.setFullYear($dateFR.substr(6,4));
    $dateObject.setMonth($dateFR.substr(3,2));
    $dateObject.setDate($dateFR.substr(0,2));

    console.info( $dateObject.getDate() + "/" + $dateObject.getMonth() + "/" +$dateObject.getFullYear());
    return $dateObject

}
function isToday($dateToCompare, $now){
    console.info( $dateToCompare.getDate() + "/" + $dateToCompare.getMonth() + "/" + $dateToCompare.getFullYear());
    console.info( $now.getDate() + " " + $now.getMonth() + " " + $now.getFullYear());
    if($dateToCompare.getDate() === $now.getDate()
        && $dateToCompare.getMonth() === ($now.getMonth()+1)
        && $dateToCompare.getFullYear() === $now.getFullYear()
    ){
        console.info("is Today");
        return true;
    }
    console.info("is NOT Today");
    return false;
}



