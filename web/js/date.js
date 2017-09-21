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

        if(dayOfWeek($dateObject) === 0 || dayOfWeek($dateObject) === 2){
            // if date is sunday or tuesday
            console.info("dimanche ou mardi");
            $inputFull.disabled = true;
            $inputFull.checked = false;
            $inputHalf.disabled = true;
            $inputHalf.checked = false;
            $btnSuivant.disabled = true;
            $blockInfos.className = "card card-error center";
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

    return $dateObject

}
function isToday($dateToCompare, $now){
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


function dayOfWeek($date){

    var $month = $date.getMonth();
    var $day = $date.getDate();
    var $year = $date.getFullYear();

    var $c = Math.floor((14-$month)/12);
    var $a = $year - $c;
    var $m = ($month + (12 * $c)) - 2;
    return ($day + $a + Math.floor($a / 4) - Math.floor($a/100) + Math.floor($a/400) + Math.floor((31 * $m)/12)) %7;
    /*
    if($month >= 3){
        return  ( Math.floor((23 * $month)/9) + $day + 4 + $year + Math.floor($year/4) - Math.floor($year/100) + Math.floor($year/400) - 2 ) % 7;
    } else {
        return ( Math.floor((23 * $month) /9 ) + $day + 4 + $year + Math.floor($year/4) - Math.floor($year/100) + Math.floor($year/400)) %7;
    }*/

}


