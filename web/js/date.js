function isFullDayEnable() {
    var $inputDate = document.getElementById("date").value;
    console.info("valeur du champ : " + $inputDate);

    if($inputDate !== 'undefined' && $inputDate !== ""){
        var $dateObject = setDateObject($inputDate);
        var $now = new Date();
        var $inputFull = document.getElementById("full");
        console.info("Date Object timestamp : " + $dateObject.getDate() + "/" + $dateObject.getMonth() + "/" +$dateObject.getFullYear() + " " + $dateObject.getHours() + ":" + $dateObject.getMinutes() + ":" + $dateObject.getSeconds());
        console.info("Now : " + $now.getDate() + " " + $now.getMonth() + " " + $now.getFullYear());

        if( isToday($dateObject, $now) && $now.getHours() >= 13) {

            console.info("Hours : " + $now.getHours());
            var $inputHalf = document.getElementById("half");

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
        && $dateToCompare.getMonth() === $now.getMonth()
        && $dateToCompare.getFullYear() === $now.getFullYear()
    ){
        console.info("is Today");
        return true;
    }
    console.info("is NOT Today");
    return false;
}
