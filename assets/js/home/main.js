(function() {
    'use strict';

    $(document).ready(function() {

        // Ajax setup headers 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Csrf token
            }
        });
        
        // Delete image from gallery
        $("body").on("click", "#deleteImage", function(e) {
            e.preventDefault();
            var pathname = window.location.pathname;
            swal({
                icon: "info",
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                buttons: {
                    confirm: {
                        text: 'Yes, delete it!',
                        className: 'btn btn-primary'
                    },
                    cancel: {
                        visible: true,
                        className: 'btn btn-secondary'
                    }
                }
            }).then((willDelete) => {
                if (willDelete) {
                    var id = $(this).data("id"); // Image id
                    var url = BASE_URL + "/user/gallery/delete/" + id

                    $.ajax({
                        url: url,
                        type: 'delete',
                        dataType: "JSON",
                        data: {
                            "id": id
                        },
                        success: function(response) {
                            if ($.isEmptyObject(response.error)) {
                                $('.image' + id).remove(); // Remove image from gallery
                                if (response.avdata === 0) {
                                    $('.empty-gallery').removeClass('d-none'); // Show empty message if there is no data
                                }
                                swal("Success!", response.success, {
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-success'
                                        }
                                    },
                                }).then(function() {
                                    //location.reload(); // Reald when click ok
                                    //history.back();
                                    if(pathname.indexOf('user') >= 0 || pathname.indexOf('home') ){
                                        location.reload(); // Reald when click ok
                                    } else {
                                        location.href = "/home";
                                    }
                                });
                            } else {
                                console.log(response);
                                swal("Opps !", response.error, {
                                    icon: "error",
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-danger'
                                        }
                                    },
                                })
                            }
                        },
                    });
                } else {
                    swal.close();
                }
            });
        });

        $("body").on("click", "#duplicateImage", function(e) {
            e.preventDefault();

            var image_id = $(this).data('id');

            $.ajax({
                url: '/duplicate/'+image_id,
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,                       
                type: 'get',
                success: function(response){
                   location.reload();
                }
             });
            
        });

        // Copy image URL
        $("body").on("click", ".ico-copy", function(e) {
            e.preventDefault();
            var image_id = $(this).attr("data-id");
            var copyText = $(this).parents('.overlay').find("#"+image_id);
            copyText.select();
            //copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            var _parent = $(this).parent('div');
            var _msg;
            _msg = '<div class="alert alert-success text-center alert-copy" role="alert">';
            _msg += '<strong>Copied!!<strong>';
            _msg += '</div>';

            $(_parent).append(_msg).fadeIn(500);
            setTimeout(function(){
                $(".alert-copy").fadeOut(500);
            }, 1000);
        });

        //Dowload Image
        $("body").on("click", ".btn-download", function(e) {
            e.preventDefault();
            var url = $(this).data("url");
            var a = document.createElement('a');
            
            a.href = url;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });

        $("body").on("click", "button.close", function() {

            $(this).parent('.alert').hide();

        });

        $('body').on('click', '.alert-link', function() {
            var category = $(this).data('category');

            if(category.length > 1){
                var query = '/category/' + category;

                //Busco el ID de la Categoría
                $.ajax({
                    url: query,
                    type: 'get',
                    async: false,
                    success: function(response) {
                        var url = window.location.origin + '/home?c='+response;
                        window.location.href = url;
                    }
                });
            } else {
                var url = window.location.origin + '/home';
                window.location.href = url;
            }
        });

        window.scrollTo(0, 0);

        
    });
})(jQuery);