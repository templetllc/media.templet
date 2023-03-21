var feedbackList = [];
var feedbackCount = 1;
var jsonFile, path, offsetTop, wrapElement;


class feedbackClass {

	constructor(file) {
		this.jsonFile = file;
		this.feedbackList = [];

		jsonFile = this.jsonFile;
	};

	loadFeedback(){
		alert(this.jsonFile);
	}

	init(parameters){

		//feedback-wrapper
		var _feedback_wrapper = document.createElement('div');
		_feedback_wrapper.setAttribute("id", "feedback-wrapper");
  		_feedback_wrapper.setAttribute("class", "feedbackPanel toggled");
  		//_feedback_wrapper.setAttribute("class", "feedbackPanel");
  		_feedback_wrapper.setAttribute("data-json", this.jsonFile);

  		//sidebar-wrapper
		var _sidebar_wrapper = document.createElement('div');
		_sidebar_wrapper.setAttribute("id", "sidebar-wrapper");

		//sidebar-nav
		var _sidebar_nav = document.createElement('div');
		_sidebar_nav.setAttribute("class", "sidebar-nav");

		//Scroll Container
		var _scroll_container = document.createElement('div');
		_scroll_container.setAttribute("class", "scroll-container");
		_scroll_container.setAttribute("id", "style-3");
		_scroll_container.setAttribute("data-spy", "scroll");
		_scroll_container.setAttribute("data-offset", "0");

		//Comment List
		var _comment_list = document.createElement('div');
		_comment_list.setAttribute("class", "comment-list-heading feedback-comment-list");
		_scroll_container.appendChild(_comment_list);

		//Overlay
		var _overlay = document.createElement('div');
		_overlay.setAttribute("class", "feedback-overlay");
		_overlay.setAttribute("style", "height: "+$(document).height()+"px; width: 100%;");

		_sidebar_wrapper.appendChild(_sidebar_nav);
		_sidebar_wrapper.appendChild(_scroll_container);
		_feedback_wrapper.appendChild(_sidebar_wrapper);
		
  		document.body.prepend(_feedback_wrapper);
  		document.body.prepend(_overlay);

  		var _header = '<div class="d-flex justify-content-between align-items-start mb-3">';
        _header += '	<div><h3>Feedback</h3></div>';
        _header += '	<button class="close feedback-close">';
        _header += '		<svg xmlns="http://www.w3.org/2000/svg" width="12" height="11.997" viewBox="0 0 12 11.997">';
		_header += '			<path id="Icon_ionic-ios-close" data-name="Icon ionic-ios-close" d="M18.707,17.287,22.993,13a1,1,0,0,0-1.42-1.42l-4.286,4.286L13,11.581A1,1,0,1,0,11.581,13l4.286,4.286-4.286,4.286A1,1,0,0,0,13,22.993l4.286-4.286,4.286,4.286a1,1,0,0,0,1.42-1.42Z" transform="translate(-11.285 -11.289)" fill="#58595b"/>';
		_header += '		</svg>';
		_header += '	</button>';
        _header += '	</div>';
        _header += '</div>';
        _header += '<hr>';
  		$('.sidebar-nav').html(_header);

	    var _toolbar = '<div class="d-flex justify-content-between mb-3">';
        _toolbar += '	<button class="btn-placepin" onClick="activeFeedback(this)">';
		_toolbar += '		<div class="d-flex justify-content-between align-items-center">';
		_toolbar += '			<div>';
		_toolbar += '				<svg xmlns="http://www.w3.org/2000/svg" width="16.661" height="16.661" viewBox="0 0 16.661 16.661">';
		_toolbar += '					<path id="Path_2453" data-name="Path 2453" d="M16.458,5.113,11.549.2a.694.694,0,0,0-.982,0L8.6,2.167a.694.694,0,0,0,0,.982l.491.491-3.6,3.6a4.166,4.166,0,0,0-4.74.814.694.694,0,0,0,0,.982L3.7,11.986l-.021.019L.2,15.477a.694.694,0,0,0,.982.982l3.471-3.471.019-.021,2.946,2.946a.694.694,0,0,0,.982,0,4.166,4.166,0,0,0,.814-4.741l3.6-3.6.491.491a.694.694,0,0,0,.982,0l1.963-1.963A.694.694,0,0,0,16.458,5.113ZM8.051,14.378,2.284,8.611a2.778,2.778,0,0,1,3.374.429L7.622,11A2.777,2.777,0,0,1,8.051,14.378ZM8.6,10.022,6.64,8.058l3.436-3.436L12.04,6.585ZM14,6.585l-.49-.49h0L10.567,3.149h0l-.49-.49.982-.982L14.985,5.6Z" transform="translate(0 -0.001)" fill=""/>';
		_toolbar += '				</svg>                                                                   ';
		_toolbar += '			</div>';
		_toolbar += '			<div class="ml-2"><h3 class="text-green active-pin">Place a pin</h3></div>';
        _toolbar += '		</div>';
		_toolbar += '	</button>';
		_toolbar += '	<div class="d-flex justify-content-between align-items-center">';
		_toolbar += '		<div class="mr-2"><h3 class="text-resolved">Resolved</h3></div>';
		_toolbar += '		<div class="custom-control custom-switch">';
		_toolbar += '			<input type="checkbox" class="custom-control-input resolved-status" id="resolved-status">';
		_toolbar += '			<label class="custom-control-label" for="resolved-status"></label>';
		_toolbar += '		</div>';
		_toolbar += '	</div>';
		_toolbar += '</div>';
		$('.sidebar-nav').append(_toolbar);


		var _textarea = '<div class="form-group">';
		_textarea += '	<textarea class="form-control" id="textarea-comment" rows="2" placeholder="Make a comment" disabled></textarea>';
		_textarea += '</div>';

		_textarea += '<div class="d-flex justify-content-between">';
		_textarea += '	<div><a class="btn btn-secondary btn-cancel_feedback"><span>Cancel</span></a></div>';
		_textarea += '	<div><a class="btn btn-primary btn-send_feedback"><span>Send</span></a></div>';
		_textarea += '</div>';
		_textarea += '<hr class="my-3">';
		$('.sidebar-nav').append(_textarea);

		this.loadFeedbackList(parameters);
	}

	loadFeedbackList(parameters){
		var width = $(window).width();
		var height = $(document).height();

		path = parameters.path;
		offsetTop = (parameters.offsetTop.length == 0) ? 0 : parameters.offsetTop;
		
		if(typeof path === 'undefined'){
			var _pathname = window.location.pathname;
			path = window.location.origin+_pathname.substr(0, _pathname.lastIndexOf('admin'))+'admin/assets';
			//console.log(path);
		}

		$.ajax({
	        type: 'GET',
	        url: path+"/feedback/include/getFeedbackList.php",
	        data: {'feedback':jsonFile, 'width':width, 'height':height},
	        contentType: 'application/json',
	        async: true,
	        success: function (response) {
	        	//console.log(response, response.length);
	        	if(response.length > 5){
	        		var response = jQuery.parseJSON(response);
	        		$('body').append(response[0].marks);
	        		$('.feedback-comment-list').html(response[0].comments);
	        		feedbackCount = response[0].count+1;

	        		$('.sidebar-feedback').hide();
	        		$('.sidebar-feedback[visible="visible"]').show();

	        		$('.feedback-pin').hide();
	        		
	        		$(document).find('[data-action="feedback"]').addClass('has-feedback');
	        		if(response[0].pending > 0){
	        			$(document).find('[data-action="feedback"]').append('<span class="badge badge-warning">'+response[0].pending+'</span>');
	        		} else {
	        			$(document).find('[data-action="feedback"]').addClass('no-pending');
	        		}
	        	}
			}
		});
	}
}

$(function(){

	//Open & Close panel feedback
	$('[data-action="feedback"]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#feedback-wrapper').toggleClass('toggled');
        $('body').toggleClass('show-feedback');
        $('.feedback-pin[visible="visible"]').show();
    });

    //Open & Close panel feedback
	$('body').on('click', '.feedback-close', function(e) {
        e.preventDefault();
        $('#feedback-wrapper').toggleClass('toggled');
        $('body').toggleClass('show-feedback');

        if($('body').hasClass('active_feedback')){
        	$('body').toggleClass('active_feedback');
        }
        
        if($('.btn-placepin').hasClass('active')){
        	$('.btn-placepin').removeClass('active');
        }
        $('.btn-placepin').find('h3').html('Place a pin');

        var _statusTextarea = $("#textarea-comment").prop('disabled');

        if(!_statusTextarea){
        	var _feedbackPinTemp = $("#textarea-comment").attr("data-index");
        	$("#textarea-comment").prop('disabled', true);
        	$("#textarea-comment").attr("data-index", '');
			$("#textarea-comment").val('');
			
			if($("#textarea-comment").attr("data-status") == 0){
				$("#feedback-pin"+ _feedbackPinTemp).remove();
				feedbackCount--;
			};
        };
    });

	$('body').on('click', function(){

		if($(this).hasClass('active_feedback')) {
			var _parent = $(event.target).parents('.feedbackPanel');
			//if(($(event.target).hasClass("no-mark") || $(event.target).hasClass("feedbackPanel") ||  $(event.target).hasClass("feedbackMark") || $(event.target).parents('#feedbackPanel').length)){
			
			if($(_parent).hasClass('feedbackPanel')){

			} else {
				var _mark, _comment;
				var _count = $(".feedback-comment-list .sidebar-feedback").length;
				feedbackCount = _count + 1;
				_mark = '<div id="feedback-pin' + feedbackCount + '" class="feedback-pin" feedbackIndex="' + feedbackCount + '" ';
				_mark += 'leftPosition="' + (event.pageX*100/$(window).width()) + '" ';
				_mark += 'topPosition="'+ (event.pageY*100/$(document).height()) + '" ';
				_mark += 'style="left:' + event.pageX + 'px;top:' + event.pageY + 'px">';
				_mark += '<span>'+feedbackCount + '</span></div>';
				$("body").append(_mark);

				$("#textarea-comment").prop('disabled', false);
				$("#textarea-comment").attr("data-index", feedbackCount);
				$("#textarea-comment").attr("data-status", "0");
				$("#textarea-comment").focus();

				feedbackCount++;
				inactiveFeedback();
			}
		} else {
			if(!$('#feedback-wrapper').hasClass('toggled')){
				var _parent = $(event.target);
				
				if(_parent.parent('[data-action="feedback"]').length == 0 && 
					!$(_parent).parents('.feedbackPanel').hasClass('feedbackPanel') &&
					!$(_parent).parents('.feedback-pin').hasClass('feedback-pin') &&
					!$(_parent).hasClass('feedback-pin') &&
					!$(_parent).parents('.swal-overlay').hasClass('swal-overlay')
					){
						$('.feedback-close').click();
				}
			};
		};
	});

	$('body').on('click', '.feedback-pin', function(e) {
		e.preventDefault();
		if($('#feedback-wrapper').hasClass('toggled')){
			$('#feedback-wrapper').removeClass('toggled');
		}
		var index = $(this).attr('feedbackindex');

		var comment = $('.feedback-comment-list').find('[data-index="'+index+'"]');
		$('.feedback-comment-list').find('[data-index="'+index+'"]').find('.dropdown-toggle').dropdown();
		//$('#feedbackComment'+$(this).attr('feedbackIndex')).find('textarea').focus();
	});

	$('body').on('mouseover mouseenter', '.feedback-pin', function() {
		if($('#feedback-wrapper').hasClass('toggled')){
			$('#feedback-wrapper').removeClass('toggled');
		}
		var index = $(this).attr('feedbackindex');

		$(this).hover(
			function() {
				$('.feedback-comment-list').find('[data-index="'+index+'"]').addClass( "hover" );
			}, function() {
				$('.feedback-comment-list').find('[data-index="'+index+'"]').removeClass( "hover" );
			}
		);
	});

	$('body').on('click', '.feedback-pin', function() {
		var index = $(this).attr('feedbackindex');

		if($(this).hasClass("active")){
			$(".feedback-pin").removeClass('active');
			$(".sidebar-feedback").removeClass('active');
		} else {
			$(".feedback-pin").removeClass('active');
			$(".sidebar-feedback").removeClass('active');

			$(this).addClass("active");
			$(".sidebar-feedback[data-index='"+index+"']").addClass("active");
		}
	});

	$("body").on("click", ".sidebar-feedback", function(e) {
		e.preventDefault();
		$("html, body").animate({
			scrollTop: (($("#feedback-pin"+$(this).attr("data-index")).attr("topPosition")*$(document).height()/100)-offsetTop)
		}, 600);
	});

	$("body").on("mouseenter", ".sidebar-feedback", function() {
		var index = $(this).attr('data-index');

		$(this).hover(
			function() {
				$("#feedback-pin"+index).addClass("hover");
			}, function() {
				$("#feedback-pin"+index).removeClass("hover");
			}
		);
	});

	$("body").on("click", ".sidebar-feedback", function() {
		var index = $(this).attr('data-index');

		if($(this).hasClass("active")){
			$(".feedback-pin").removeClass('active');
			$(".sidebar-feedback").removeClass('active');
		} else {
			$(".feedback-pin").removeClass('active');
			$(".sidebar-feedback").removeClass('active');

			$(this).addClass("active");
			$("#feedback-pin"+index).addClass("active");
		}
	});

	$('body').on('click', '.btn-cancel_feedback', function(event) {

		var feedbackIndex = $("#textarea-comment").attr("data-index");
		$("#textarea-comment").prop('disabled', true);
		$("#textarea-comment").attr("data-index", '');
		$("#textarea-comment").val('');
		
		if($("#textarea-comment").attr("data-status") == 0){
			$("#feedback-pin"+ feedbackIndex).remove();

			var _count = $(".feedback-comment-list .sidebar-feedback").length;
			
			//feedbackCount--;
			feedbackCount = _count+1;
		};
	});

	$('body').on('click', '.btn-send_feedback', function(event) {

		var _textarea = $("#textarea-comment").val();
		if(_textarea.length > 0){
			var feedback = $("#textarea-comment");
			var repository = $('.feedbackPanel').attr('data-json');
			var index = $(feedback).attr("data-index");
			var feedbackString = [];
			feedbackString.length = 0;

			feedbackString.push({
				feedbackIndex:  index,
				comment:    	$(feedback).val(),
				status: 		$(feedback).attr("data-status"), 
				leftPosition: 	$("#feedback-pin"+index).attr("leftPosition"), 
				topPosition: 	$("#feedback-pin"+index).attr("topPosition")
			});

			$.ajax ({
				type: "POST",
				url: path+"/feedback/include/saveFeedback.php",
				data: {'feedbackString':JSON.stringify(feedbackString), 'jsonFile':repository},
				success: function() {
					//
					$('.resolved-status').prop('checked', false);
					$('.btn-placepin').find('h3').html('Place a pin');
					loadFeedbackList();
				},
				error: function() {
					alert("An error occurred while saving the record.");
				}
			});
		}
	});

	$('body').on('change', '.resolved-status', function(){

		var feedbackIndex = $("#textarea-comment").attr("data-index");
		$("#textarea-comment").prop('disabled', true);
		$("#textarea-comment").attr("data-index", '');
		$("#textarea-comment").val('');

		if($("#textarea-comment").attr("data-status") == 0){
			$("#feedback-pin"+ feedbackIndex).remove();
			var _count = $(".feedback-comment-list .sidebar-feedback").length;

			//feedbackCount--;
			feedbackCount = _count;
		};
		
		$('.sidebar-feedback').hide();
		$(".sidebar-feedback").attr("visible", "hidden");

		$('.feedback-pin').hide();
	    $('.feedback-pin').attr("visible", "hidden");
		
		if($(this).is(':checked')){
			$(".sidebar-feedback[data-status='2']").attr("visible","visible");
	        $('.sidebar-feedback[visible="visible"]').show();
	        
	        $('.feedback-pin_resolved').attr("visible","visible");
	        $('.feedback-pin[visible="visible"]').show();
		} else {
			$(".sidebar-feedback[data-status='1']").attr("visible","visible");
	        $('.sidebar-feedback[visible="visible"]').show();
	        
	        $('.feedback-pin').attr("visible","visible");
	        $('.feedback-pin_resolved').attr("visible","hidden");
	        $('.feedback-pin[visible="visible"]').show();
		}
	});

	$('body').on('click', '.btn-resolve', function(event) {
		var feedback = $(this).parents(".sidebar-feedback");
		var repository = $('.feedbackPanel').attr('data-json');
		var index = $(feedback).attr("data-index");
		
		var feedbackString = [];
		feedbackString.length = 0;

		feedbackString.push({
			feedbackIndex:  index,
			comment:    	$(feedback).find("textarea").val(),
			status: 		2, 
			leftPosition: 	$("#feedback-pin"+index).attr("leftPosition"), 
			topPosition: 	$("#feedback-pin"+index).attr("topPosition")
		});

    	$.ajax ({
			type: "POST",
			url: path+"/feedback/include/statusFeedback.php",
			data: {'feedbackString':JSON.stringify(feedbackString), 'jsonFile':repository},
		}).done(function() {
            loadFeedbackList();
		});	
	});

	$('body').on('click', '.btn-unresolved', function(event) {
		var feedback = $(this).parents(".sidebar-feedback");
		var repository = $('.feedbackPanel').attr('data-json');
		var index = $(feedback).attr("data-index");

		var feedbackString = [];
		feedbackString.length = 0;

		feedbackString.push({
			feedbackIndex:  index,
			comment:    	$(feedback).find("textarea").val(),
			status: 		1, 
			leftPosition: 	$("#feedback-pin"+index).attr("leftPosition"), 
			topPosition: 	$("#feedback-pin"+index).attr("topPosition")
		});

    	$.ajax ({
			type: "POST",
			url: path+"/feedback/include/statusFeedback.php",
			data: {'feedbackString':JSON.stringify(feedbackString), 'jsonFile':repository},
		}).done(function() {
			$('.resolved-status').prop('checked', false);
            loadFeedbackList();
		});	
	});


	$('body').on('click', '.btn-delete_feedback', function(event) {
		var feedback = $(this).parents(".sidebar-feedback");
		var repository = $('.feedbackPanel').attr('data-json');
		var index = $(feedback).attr("data-index");

		var feedbackString = [];
		feedbackString.length = 0;

		feedbackString.push({
			feedbackIndex:  index,
			comment:    	$(feedback).find("textarea").val(),
			status: 		$(feedback).attr("data-status"),
			leftPosition: 	$("#feedback-pin"+index).attr("leftPosition"), 
			topPosition: 	$("#feedback-pin"+index).attr("topPosition")
		});

		swal({
            title: "Are you sure?",
            text: "You will not be able to recover this feedback!",
            icon: "error",
            buttons: true,
            dangerMode: true,
            buttons: {
                cancel: "Oh wait, no!",
                catch: {
                    text: "Yes, i'm sure!",
                    value: "ok",
                },
            }
        })
        .then((willDelete) => {
            if (willDelete) {
            	$.ajax ({
					type: "POST",
					url: path+"/feedback/include/deleteFeedback.php",
					data: {'feedbackString':JSON.stringify(feedbackString), 'jsonFile':repository},
				}).done(function() {
					var _count = $(".feedback-comment-list .sidebar-feedback").length;
					feedbackCount = _count;
                    loadFeedbackList();
				});	
            };
        });
	});

	$('body').on('click', '.btn-edit_feedback', function(event) {
		var feedback = $(this).parents(".sidebar-feedback");
		var repository = $('.feedbackPanel').attr('data-json');
		
		$("#textarea-comment").prop('disabled', false);
		$("#textarea-comment").attr("data-index", $(feedback).attr("data-index"));
		$("#textarea-comment").attr("data-status", $(feedback).attr("data-status"));
		$("#textarea-comment").val($(feedback).find('.text-comment').html());
		$("#textarea-comment").focus(); 
	});
});


var activeFeedback = function(element){
	var count = 0;

	count = $("#textarea-comment").attr("data-index");
	if(count == 0 || typeof count === 'undefined'){
	 	$(element).toggleClass('active');
		$('body').toggleClass('active_feedback');

		if($(element).hasClass('active')){
			$(element).find('h3').html('Cancel pin');
		} else {
			$(element).find('h3').html('Place a pin');
		}
	} else {
		$("#textarea-comment").focus();
	}

	$(".feedback-pin").removeClass('active');
	$(".sidebar-feedback").removeClass('active');

}

var inactiveFeedback = function(element){
	$('body').toggleClass('active_feedback');
	$('.btn-placepin').toggleClass('active');
	$('.btn-placepin').find('h3').html('Place a pin');
}


var loadFeedbackList = function(){
	var width = $(window).width();
	var height = $(document).height();
	
	$.ajax({
        type: 'GET',
        url: path+"/feedback/include/getFeedbackList.php",
        data: {'feedback':jsonFile, 'width':width, 'height':height},
        contentType: 'application/json',
        async: true,
     }).done(function(response) {

     	$('.feedback-comment-list').html('');
     	$('body').find('.feedback-pin').remove();

    	if(response.length > 1){
    		var response = jQuery.parseJSON(response);

    		$('body').find('.feedback-pin').remove();
    		$('body').append(response[0].marks);

    		$('.feedback-comment-list').html('');
    		$('.feedback-comment-list').html(response[0].comments);
    		feedbackCount = response[0].count+1;

    		$("#textarea-comment").prop('disabled', true);
			$("#textarea-comment").attr("data-index", '');
			$("#textarea-comment").val('');

			$('.sidebar-feedback').hide();
        	$('.sidebar-feedback[visible="visible"]').show();

        	$('.feedback-pin').hide();
        	$('.feedback-pin[visible="visible"]').show();

        	var button_feedback = $(document).find('[data-action="feedback"]');
        	var pending = response[0].pending;
        	button_feedback.addClass('has-feedback');

        	if(parseInt(pending) > 0){
        		button_feedback.removeClass('no-pending');
        		if(button_feedback.find('.badge').length){
        			button_feedback.find('.badge').text(pending);
        		} else {
	        		button_feedback.append('<span class="badge badge-warning">'+pending+'</span>');
	        	}
	        } else {
	        	button_feedback.addClass('no-pending');
	        	if(button_feedback.find('.badge').length){
	        		button_feedback.find('.badge').remove();
	        	}
	        }
    	} else {
    		$(document).find('[data-action="feedback"]').removeClass('has-feedback');
    		$(document).find('[data-action="feedback"]').removeClass('no-pending');
    		if($(document).find('[data-action="feedback"]').find('.badge').length){
    			$(document).find('[data-action="feedback"]').find('.badge').remove();
    		}
    	}
    });
}

var countFeedback = function(){

}