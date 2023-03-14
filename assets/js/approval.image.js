(function() {
    'use strict';

    $(document).ready(function() {

        // Ajax setup headers 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Csrf token
            }
        });
        
        countCheck();

        // Check Image
        $("body").on("change", "[data-toggle=checkImage]", function(e) {
            e.preventDefault();

            if($(this).is(":checked")){
                var id = $(this).val();
                $(this).parents('.img-item').addClass("imgCheck");
                $(this).parents('.img-item').find('img#img_'+id).addClass("active");
            } else {
                var id = $(this).val();
                $(this).parents('.img-item').removeClass("imgCheck");
                $(this).parents('.img-item').find('img#img_'+id).removeClass("active");
            }

            countCheck();
        });

        // Check All Image
        $("body").on("click", "[data-toggle=selectAll]", function(e) {
            e.preventDefault();
            $('.gallery-approval input[type=checkbox]').prop("checked", true);
            $('.gallery-approval .img-item').addClass("imgCheck");
            $('.gallery-approval .img-item').find('img').addClass("active");
            countCheck();
        });

        // Uncheck All Image
        $("body").on("click", "[data-toggle=deselectAll]", function(e) {
            e.preventDefault();
            $('.gallery-approval input[type=checkbox]').prop("checked", false);
            $('.gallery-approval .img-item').removeClass("imgCheck");
            $('.gallery-approval .img-item').find('img').removeClass("active");
            countCheck();
        });

        // Approvals Images
        $("body").on("click", "[data-toggle=approvalImage]", function(e) {
            e.preventDefault();
            var _container = $('.gallery-approval');
            var _approvals = 0;
            var _forApprove = $(_container).find('#checkImage:checked').length;

            $(_container).find('#checkImage:checked').each(function(){
                var id = $(this).val();
                var query = '/approvals/approve/' + id;
                $.ajax({
                    url: query,
                    type: 'get',
                    async: false,
                    success: function(response) {
                        _approvals++;
                    }
                });
            });
            if(_approvals == _forApprove){
                swal("Success!", "Images successfully approved", {
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    },
                }).then(function() {
                    location.reload(); // reload when click ok
                });
            }
        });

        // Unapprovals Images
        $("body").on("click", "[data-toggle=unapprovalImage]", function(e) {
            e.preventDefault();
            var _container = $('.gallery-approval');
            var _unapprovals = 0;
            var _forUnapprove = $(_container).find('#checkImage:checked').length;

            $(_container).find('#checkImage:checked').each(function(){
                var id = $(this).val();
                var query = '/approvals/unapprove/' + id;
                $.ajax({
                    url: query,
                    type: 'get',
                    async: false,
                    success: function(response) {
                        _unapprovals++;
                    }
                });
            });
            if(_unapprovals == _unapprovals){
                swal("Success!", "Images successfully unapproved", {
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    },
                }).then(function() {
                    location.reload(); // reload when click ok
                });
            }
        });

        // Approval Image Details
        $("body").on("click", "[data-toggle=approvalDetail]", function(e) {
            e.preventDefault();
            
            var id = $(this).attr('id');
            var query = '/approvals/approve/' + id;
            $.ajax({
                url: query,
                type: 'get',
                async: false,
                success: function(response) {
                    console.log(response);
                },
                complete: function(response) {
                    swal("Success!", "Images successfully approved", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then(function() {
                        location.reload(); // reload when click ok
                    });
                }
            });
        });

        // Approval Image Details
        $("body").on("click", "[data-toggle=unapprovalDetail]", function(e) {
            e.preventDefault();
            
            var id = $(this).attr('id');
            var query = '/approvals/unapprove/' + id;
            $.ajax({
                url: query,
                type: 'get',
                async: false,
                success: function(response) {
                    console.log(response);
                },
                complete: function(response) {
                    swal("Success!", "Images successfully unapproved", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then(function() {
                        location.reload(); // reload when click ok
                    });
                }
            });
        });
    });

    var countCheck = function(){

        var _checked = $('.gallery-approval input[type=checkbox]:checked');

        $('#actionImages').find('#selected').html($(_checked).length);

    }

})(jQuery);