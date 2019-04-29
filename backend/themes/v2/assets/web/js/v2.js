/*
Activate iCheck plugin
 */

$(function () {
	$('input').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
		// increaseArea: '20%' // optional
	});
});

/*
THEME CHANGE Functions
TODO: Api call to change user config
 */

function getRootUrl(){
    var url = window.location.href;
    var arr = url.split("/");
    return arr[0] + "//" + arr[2];
}
function saveThemeUserConfig(theme){
    sendUserConfigRequest("theme", theme);
}

function sendUserConfigRequest(key, value){
    var url = getRootUrl() + '/user/save-user-config';
    $.post(url, {k : key, v : value, userconfig: 0}, function (response){
        if (response.FAILED) {
            console.log('Saving config failed with message:'+ response.message);
        }
    })
    .fail(function(){
        console.log('ajax request failed');
    });
}

$('#theme-option-blue').on('click', function(event){
	event.preventDefault();
	event.stopPropagation();
	$('body')
	.removeClass('skin-blue-light skin-red skin-green skin-purple')
	.addClass('skin-blue');

    saveThemeUserConfig('skin-blue');
});

$('#theme-option-blue-light').on('click', function(event){
	event.preventDefault();
	event.stopPropagation();
	$('body')
	.removeClass('skin-blue skin-red skin-green skin-purple')
	.addClass('skin-blue-light');

    saveThemeUserConfig('skin-blue-light');
});

$('#theme-option-red').on('click', function(event){
	event.preventDefault();
	event.stopPropagation();
	$('body')
	.removeClass('skin-blue skin-blue-light skin-green skin-purple')
	.addClass('skin-red');

    saveThemeUserConfig('skin-red');
});

$('#theme-option-green').on('click', function(event){
	event.preventDefault();
	event.stopPropagation();
	$('body')
	.removeClass('skin-blue skin-red skin-blue-light skin-purple')
	.addClass('skin-green');

    saveThemeUserConfig('skin-green');
});

$('#theme-option-purple').on('click', function(event){
	event.preventDefault();
	event.stopPropagation();
	$('body')
	.removeClass('skin-blue skin-red skin-blue-light skin-green')
	.addClass('skin-purple');

    saveThemeUserConfig('skin-purple');
});

/** Sidebar toggle handler */
$('.sidebar-toggle').on('click', function(event){
    if($('body').hasClass('sidebar-collapse')){
        sendUserConfigRequest("sidebarToggle", 0);
    }else{
        sendUserConfigRequest("sidebarToggle", 1);
    }
    
});

/*
	enable pjax menu and link
 */
$.pjax.defaults.timeout = 10000;
$('a[data-pjax]').pjax({timeout:10000});

// $(document).pjax('a[nopush-pjax]', '#app-component-container', {push: false});

$('a[nopush-pjax]').each(function (){
    var target = $( this ).attr('nopush-pjax');
     // console.log('target 1' + target);
    $( this ).pjax($( this ), target, {push:false});
});

/*
    ------ pjax end -----
 */

NProgress.configure({
	parent: '#main-content'
});

$('#main-content').on('pjax:start', function() { NProgress.start();});

$('#main-content').on('pjax:success',   function() { NProgress.done();  });

/*tooltip*/
$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    container:'body',
  });
})

/*tooltip popover*/
$(function () {
  $('[data-toggle="popover"]').popover({
    container: 'body',
  })
})

/*override default yii alert with bootbox alert*/

yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    return message === undefined || yii.confirm(message, $e);
};
// --- Delete action (bootbox) ---
yii.confirm = function (message, ok, cancel) {
    //close opened dropdown
    $('[data-toggle="dropdown"]').parent().removeClass('open');
    //show confirmation modal
    bootbox.confirm(
        {
            message: message,
            animate: true,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "OK",
                    className: "btn-danger"
                },
                cancel: {
                    label: "Cancel"
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}

/** notification functions */

$(".notif-tools-markread").on('click', function(event){
    event.preventDefault();
    event.stopPropagation();
    
    var notifElement  = $(this).parent().parent(); 
    $.ajax({
        url: $(this).attr("goto"),

        success: function (data, text) {
            if(data.status == 'success'){
                notifElement.removeClass('info-unread');
                // console.log(data);
            }else{
                console.log("error calling server");
            }
        }
    });
});

$(".notif-tools-delete").on('click', function(event){
    event.preventDefault();
    event.stopPropagation();
    var notifElement  = $(this).parent().parent();
    $.ajax({
        url: $(this).attr("goto"),
        success: function(data, text){
            if(data.status == 'success'){
                notifElement.remove();
                // console.log(data);
            }else{
                console.log("error calling server");
            }
        }
    })
});

$("#notif-button").on('click', function (event) {
    $.ajax({
        url: $(this).attr("goto"),
        success: function(data, text){
            if(data.status == 'success'){
                $('#notif-banner').remove();
                // console.log(data);
            }else{
                console.log("error calling server");
            }
        }
    })
});
