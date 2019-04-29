$('#mdp-demo').multiDatesPicker({
    numberOfMonths: [1,2],
    altField: '#altField',
    dateFormat: "yy-mm-dd",
    defaultDate: 3,
    onSelect: function(){
        $('#numberSelected').val($('#mdp-demo').multiDatesPicker('getDates').length);
    },
    beforeShowDay: $.datepicker.noWeekends,
});

$('#mdp-demos').multiDatesPicker({
    altField: '#start',
    dateFormat: "yy-mm-dd",
    minDate: null,
    maxPicks: 1,
    beforeShowDay: $.datepicker.noWeekends,
});

$('#mdp-demoe').multiDatesPicker({
    altField: '#end',
    dateFormat: "yy-mm-dd",
    minDate: null,
    maxPicks: 1,
    beforeShowDay: $.datepicker.noWeekends,
});
