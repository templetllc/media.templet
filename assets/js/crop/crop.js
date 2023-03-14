var Crop = (function() {

	var cropContainer, viewport, image_id, presetText;

    var init = function(){

    	$('[data-toggle=crop]').each(function(){

    		$(this).parent().height('98vh');
    		var widthParent = $(this).parent().width();
    		var heightParent = $(this).parent().height();

    		var _element = $(this);
	    	var _img = $(this).attr('data-image');
	    	var _width = parseInt($(this).attr('data-width'));
	    	var _height = parseInt($(this).attr('data-height'));

	    	if(isNaN(_width)){
	    		_width = widthParent;	
	    	} 
	    	if(isNaN(_height)){
	    		_height = widthParent;	
	    	} 

	    	preset_id = $(".select-preset").val();
	    	preset = $('select[name="preset"] option:selected').attr('data-value');
	    	//preset = $(".select-preset").attr('data-value');

	    	if(preset === undefined){
	    		var width_preset, height_preset;

	    		if(_width > widthParent){
	    			width_preset = widthParent;
	    		} else {
	    			width_preset = _width;
	    		}

	    		if(_height > heightParent){
	    			height_preset = heightParent;
	    		} else {
	    			height_preset = _height;
	    		}

	    		preset = width_preset+"x"+height_preset;
	    	}

	    	viewport = preset.split('x');
	    	width_crop = parseInt(viewport[0]);
	    	height_crop = parseInt(viewport[1]);

	    	// console.log(width_crop, height_crop);
	    	// console.log(_width, _height);

	    	if(width_crop > widthParent){
	    		height_crop = (height_crop / width_crop) * widthParent;
	    		width_crop = widthParent;
	    		// height_crop = (height_crop / width_crop) * widthParent-100;
	    		// width_crop = widthParent-100;
	    	} 

	    	if(width_crop > _width)	_width = width_crop;
	    	if(height_crop > _height) _height = height_crop;

	    	//console.warn('width_crop', width_crop, 'height_crop', height_crop);

	    	cropContainer = _element.croppie({
	    		enableExif: false,
				viewport: { width: width_crop, height: height_crop },
				boundary: { width: _width, height: _height },
	            enableOrientation: true,
	            mouseWheelZoom: false,
			});

	    	//

			cropContainer.croppie('bind', {
				url: _img,
			});
		});
        
    };

    $('[data-toggle=save]').each(function(){

		$(this).on('click', function() {
			
			var pathname = window.location.pathname;
			
			var data_image = $(cropContainer).attr('data-image');

			image_id = $(this).attr('data-id');
			var category = $(this).parents('.image-info').find('#category').val();
			var tags = $(this).parents('.image-info').find('#tags').val();
			var description = $('select[name="preset"] option:selected').text();
			var preset = $('select[name="preset"] option:selected').attr('data-value');
			var preset_id = $('select[name="preset"] option:selected').val();
			if(typeof preset_id === 'undefined'){
				preset_id = "";
			}
			if(typeof data_image === 'undefined'){
				var image = $('.image-container').attr('src');
			} else {
				var image = $(cropContainer).attr('data-image');
			}
			var ext = image.substr(image.lastIndexOf(".")+1);
			var thumbnail = ($('#thumbnail').is(':checked')) ? 1 : 0;
			var gallery = ($('#gallery').is(':checked')) ? 1 : 0;
			
			if(ext == 'gif' && pathname.indexOf('modal') >= 0){
				preset = "";
				description = "";
				updateGifModal(image_id, category, tags, description, preset_id, ext, thumbnail, gallery);

           		setTimeout(function(){ 
					parent.postMessage(image, '*');
				}, 1000);
               	
			} else {
				if(preset_id.length == 0){
					//Si viene de un modal compruebo que seleccione el preset
					if(pathname.indexOf('modal') >=0){
						swal("Oops!", "You must select a Preset to continue", {
	                        icon: "error",
	                        buttons: {
	                            confirm: {
	                                className: 'btn btn-danger'
	                            }
	                        },
	                    });
						return false;
					} else {
						preset = "";
						description = "";
						updateImageInfo(image_id, category, tags, description, preset_id, ext, thumbnail, gallery);
					}
				} else {

					// if($('#duplicate').is(':checked')){
					// 	var newImage = duplicateImage(image_id, category, tags, description, preset_id, ext, thumbnail);
					// 	var path_image = image.substr(0, image.lastIndexOf("/")+1);
					// }

					var w = parseInt(viewport[0], 10),
						h = parseInt(viewport[1], 10),
						size = 'viewport';

					if (w || h) {
						size = { width: w, height: h };
					}

					cropContainer.croppie('result', {
						type: 'canvas',
						size: size,
						resultSize: {
							width: 50,
							height: 50
						}
					}).then(function (image) {
						
						var response_image = updateImage(image, image_id, category, tags, description, preset_id, ext, thumbnail, gallery);
						var datetime = new Date();  
						$(cropContainer).attr('data-image', response_image + '?' + datetime.getTime())

						if(pathname.indexOf('modal') < 0){
		               		popupResult({
								src: image
							});
		               	} else {
		               		setTimeout(function(){ 
								parent.postMessage(response_image, '*');
							}, 1000);
		               	}
					});
				};
			};
		});	
	});

	$('[data-toggle=preset]').each(function(){

		$(this).on('change', function(){
			var value = $(this).val();
			
			// if(value.length > 0){
			// 	$('#duplicate').prop('disabled', false);
			// 	$('#duplicate').prop('checked', true );
			// } else {
			// 	$('#duplicate').prop('disabled', true);
			// 	$('#duplicate').prop('checked', false );
			// }

			cropContainer.croppie('destroy');
			init();
		});
	});

    function popupResult(result) {

		new swal({
			title: '',
			icon: result.src
		}).then((value) => {
  			cropContainer.croppie('destroy');
			// init();
			// $('.select-preset').val('').change();
			location.reload();
		});

		setTimeout(function(){
			$('.sweet-alert').css('margin', function() {
				var top = -1 * ($(this).height() / 2),
					left = -1 * ($(this).width() / 2);

				return top + 'px 0 0 ' + left + 'px';
			});
		}, 1);

	}

	function updateImage(image, image_id, category, tags, description, preset, ext, thumbnail, gallery){

	    var form_data = new FormData();
	    var image_result;
	    console.log('description', description);
	    description = description.substr(0, description.lastIndexOf('('));

	    form_data.append('new_image', image);
	    form_data.append('image_id', image_id);
	    form_data.append('category', category);
	    form_data.append('tags', tags);
	    form_data.append('description', description);
	    form_data.append('preset', preset);
	    form_data.append('ext', ext);
	    form_data.append('thumbnail', thumbnail);
	    form_data.append('gallery', gallery);

	    $.ajax({
	    	headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        url: '/ib/update', // <-- point to server-side PHP script 
	        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: form_data,                         
	        type: 'post',
	        async: false,
	        success: function(response){
	        	image_result = response;
	        	
	            //alert(php_script_response); // <-- display response from the PHP script, if any
	        }
	   //      complete: function(response){

	   //      	setTimeout(function(){ 
				// 	//location.reload();
				// }, 2000);
	   //      }
	     });

	    return image_result;
	}

	function updateImageInfo(image_id, category, tags, description, preset, ext, thumbnail, gallery){

	    var form_data = new FormData();                
	    form_data.append('image_id', image_id);
	    form_data.append('category', category);
	    form_data.append('tags', tags);
	    form_data.append('description', description);
	    form_data.append('preset', preset);
	    form_data.append('ext', ext);
	    form_data.append('thumbnail', thumbnail);
	    form_data.append('gallery', gallery);

	    $.ajax({
	    	headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        url: '/ib/updateInfo', // <-- point to server-side PHP script 
	        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: form_data,                         
	        type: 'post',
	        success: function(php_script_response){
	        	
	        	//console.log(window.location);
	        	//jQuery('#div_session_write').load(window.location.origin + '/session/saved');
	        	$.ajax({
                	url: window.location.origin + '/session/saved',
                	type: "get",
	                async: false,
	                success: function(response) {
	                    location.reload();
	                }
	            });
	        	
	        }
	     });
	}

	function updateGifModal(image_id, category, tags, description, preset, ext, thumbnail, gallery){

	    var form_data = new FormData();                
	    form_data.append('image_id', image_id);
	    form_data.append('category', category);
	    form_data.append('tags', tags);
	    form_data.append('description', description);
	    form_data.append('preset', preset);
	    form_data.append('ext', ext);
	    form_data.append('thumbnail', thumbnail);
	    form_data.append('gallery', gallery);

	    $.ajax({
	    	headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        url: '/ib/updateInfo', // <-- point to server-side PHP script 
	        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: form_data,                         
	        type: 'post',
	        success: function(php_script_response){
	        	image_result = php_script_response;
	        }
	     });
	}
	

	function duplicateImage(image_id, category, tags, description, preset, ext, thumbnail, gallery){

		var form_data = new FormData();
	    var image_result;

	    description = description.substr(0, description.lastIndexOf('('));

	    form_data.append('image_id', image_id);
	    form_data.append('category', category);
	    form_data.append('tags', tags);
	    form_data.append('description', description);
	    form_data.append('preset', preset);
	    form_data.append('ext', ext);
	    form_data.append('thumbnail', thumbnail);
	    form_data.append('gallery', gallery);

	    $.ajax({
	    	headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        url: '/ib/duplicate',
	        dataType: 'text',  
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: form_data,                         
	        type: 'post',
	        success: function(response){
	        	image_result = response;
	        }
	     });

	    return image_result;
	}

	$('#duplicate').prop('disabled', true);
	init();

})(jQuery);

