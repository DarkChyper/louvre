jQuery(function($){
    $.datepicker.regional['fr'] = {
        closeText: 'Fermer',
        prevText: '&#x3c;Préc',
        nextText: 'Suiv&#x3e;',
        currentText: 'Courant',
        monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
            'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
        monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
            'Jul','Aoû','Sep','Oct','Nov','Déc'],
        dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
        dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
        dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['fr']);
});

var forbidden = ["Mar","Dim"];

$( ".datepicker" ).datepicker(  {
    beforeShowDay: function(date){
        var string = jQuery.datepicker.formatDate('D',date);
        return [ forbidden.indexOf(string) === -1 ];
    },
    minDate: 0,
    maxDate: "+2Y",
    calendarWeeks: true,
    editable: true

} , $.datepicker.regional[ "fr" ]);

$( ".dateBirthPicker" ).datepicker( {minDate: "-120Y", maxDate: 0, calendarWeeks: true, editable: true, changeMonth: true, yearRange: "-100:+0", changeYear: true } , $.datepicker.regional[ "fr" ]);


