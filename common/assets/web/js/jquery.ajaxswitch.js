/**
 * Plugin for ajax switch on/off
 *
 * combined with Yii2 component class
 * 
 * @author Marojahan Sigiro
 */
(function ( $ ) {
	$.fn.ajaxswitch = function (){
		this.bind( "click.ajaxswitch", function () {
			var btn = $(this);

            if(btn.hasClass('switch-container-error')){
                return;
            }

            btn.children(':first').removeClass('switch-toggle-on');
            btn.children(':first').addClass('switch-toggle-progress');
            var url = window.location.href;
            var btn_url = btn.attr('href');
            if(typeof btn_url !== typeof undefined && btn_url !== false){
                url = btn_url;
            }

            if(btn.hasClass('switch-container-on')){
                //fire off request
                console.log('fire switch-off request');
                $.post(url, {switch_data : btn.attr('switch-data'), switchcolumnreq: 0}, function (response){
                    if(response.SUCCESS){
                        btn.removeClass('switch-container-on');
                        btn.children(':first').removeClass('switch-toggle-progress');
                    }else if (response.FAILED) {
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-on');
                        eModal.alert('Failed', response.message);
                        console.log('Turning swith failed with message:'+ response.message);
                    }else{
                        eModal.alert('<div class=alert alert-danger>XHR Request Failed, Request is not handled properly on backend system !!</div>','Error');
                        btn.removeClass('switch-container-on');
                        btn.addClass('switch-container-error');
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-on');
                        btn.children(':first').addClass('switch-toggle-error');
                    }
                })
                .fail(function(){
                    btn.removeClass('switch-container-on');
                    btn.addClass('switch-container-error');
                    btn.children(':first').removeClass('switch-toggle-progress');
                    btn.children(':first').addClass('switch-toggle-on');
                    btn.children(':first').addClass('switch-toggle-error');
                    eModal.alert('<div class=alert alert-danger>XHR Request Failed</div>','Error');
                    console.log('ajax request failed');
                });

            }else {
                //fire on request
                console.log('fire switch-on request');
                $.post(url, {switch_data : btn.attr('switch-data'), switchcolumnreq: 1}, function (response){
                    if(response.SUCCESS){
                        btn.addClass('switch-container-on');
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-on');
                    }else if (response.FAILED) {
                        btn.children(':first').removeClass('switch-toggle-progress');
                        eModal.alert('Failed', response.message);
                        console.log('Turning swith failed with message:'+ response.message);
                    }else{
                        eModal.alert('<div class=alert alert-danger>XHR Request Failed, Request is not handled properly on backend system !!</div>','Error');
                        btn.addClass('switch-container-error');
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-error');
                    }
                })
                .fail(function(){
                    btn.addClass('switch-container-error');
                    btn.children(':first').removeClass('switch-toggle-progress');
                    btn.children(':first').addClass('switch-toggle-error');
                    eModal.alert('<div class=alert alert-danger>XHR Request Failed</div>','Error');
                    console.log('ajax request failed');
                });
            }
		});

		return this;
	};
})( jQuery );