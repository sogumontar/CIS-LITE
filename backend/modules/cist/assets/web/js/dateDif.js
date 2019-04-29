/**
 * Created by Gerald Sihotang on 17/05/2018.
 */
// $("#end").on("change", function () {
//     var olddate = new Date($("#start").val());
//     var newdate = new Date($(this).val());
//     var timeDiff = Math.abs(newdate.getTime() - olddate.getTime());
//     var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
//     $("#diff").val(diffDays);
// });
$("#cat").on("change", function(){
    var op = $(this).find('option:selected').val();
   //  switch($(this).val()){
   //     case "1": $("#diff").val("90");
   //         break;
   //     case "2": $("#diff").val("14");
   //         break;
   //     case "3": $("#diff").val("90");
   //         break;
   //     case "4": $("#diff").val("14");
   //         break;
   //     case "5": $("#diff").val("14");
   //         break;
   //     case "6": $("#diff").val("20");
   //         break;
   //     //default : $("#diff").val("0");
   // }
});

