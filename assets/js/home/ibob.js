var img_route;
(function($, Dropzone) {
    "use strict";

    $('#modal-full-width').on('shown.bs.modal', function(e) {
        $('.imgbob-uploader-out').addClass('imgbob-modal-open');
    });
    $('#modal-full-width').on('hidden.bs.modal', function() {
        $('.imgbob-uploader-out').removeClass('imgbob-modal-open');
    });

    $(document).ready(function() {
        var pathname = window.location.pathname;
        
        const url = SITE_URL + "/upload";
        if(pathname.indexOf('/upload/modal')) {
            img_route = '/ib/';
        } else {
            img_route = '/modal/';
        }

        const block = $('#imgbob-drag-zone > .imgbob-drag-zone-place');
        const dropbox = $('.imgbob-uploader-box');
        const buttonreset = $('.imgbob-reset-button');

        function dragOverBlock(e) { block.addClass('onDrag'); }

        function dragLeaveBlock(e) { block.removeClass('onDrag'); }

        function onFileAdd(file) {
            $('#modal-full-width').modal('show');
            $(file.previewElement).removeClass('d-none');
            dropbox.addClass('d-none');
            buttonreset.removeClass('d-none');
            $('.uploaded-success').removeClass('d-none');
            if (dropzone.files.length > MAX_FILES) {
                this.removeFile(file);
            }
            if (dropzone.files.length >= MAX_FILES) {
                buttonreset.addClass('d-none');
            }
        }

        function onFileError(file, message = null) {
            const preview = $(file.previewElement);
            const anchor = preview.find('.alert-error');
            const progress = preview.find('.upload-progress');
            const erroricon = preview.find('.error-icon-box');
            const upboxerror = preview.find('.imgbob-card');

            anchor.html(message ? message : BIG_FILES_DETECTED);
            anchor.removeClass('d-none');
            progress.addClass('d-none');
            erroricon.removeClass('d-none');
            upboxerror.addClass('box-is-error');
        }

        function onUploadComplete(file) {
            if (file.status == "success") {
                const response = JSON.parse(file.xhr.response);
                if (response.type == 'success') {
                    const id = response.data.id;
                    const preview = $(file.previewElement);

                    const anchor = preview.find('.success-input');
                    const buttonLink = preview.find('.success-button');
                    const progress = preview.find('.upload-progress');
                    const sucessicon = preview.find('.success-icon-box');
                    const upbox = preview.find('.imgbob-card');

                    var category = $('.imgbob-drag-zone').attr('data-category');
                    var preset = $('.imgbob-drag-zone').attr('data-preset');

                    anchor.html('textarea', SITE_URL + img_route + id);
                    if(img_route == '/modal/'){
                        buttonLink.attr('href', SITE_URL + img_route + id  + '/' + category + '/' + preset);
                        window.location.href = SITE_URL + img_route + id  + '/' + category + '/' + preset;
                    } else {
                        buttonLink.attr('href', SITE_URL + img_route + id);
                    
                        anchor.html(SITE_URL + img_route + id);
                        anchor.removeClass('d-none');
                        buttonLink.removeClass('d-none');
                        progress.addClass('d-none');
                        sucessicon.removeClass('d-none');
                        upbox.addClass('box-is-success');
                    }
                } else
                    onFileError(file, response.errors);
            }
        }


        let previewNode = document.querySelector("#imgbob-drop-template");
        previewNode.id = "";
        let previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        const dropzone = new Dropzone(
            'div#imgbob-drag-zone', {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                paramName: 'uploads',
                maxFiles: MAX_FILES,
                maxFilesize: MAX_SIZE,
                previewTemplate: previewTemplate,
                previewsContainer: "#imgbob-preview-uploads",
                clickable: "div#imgbob-upload-clickable",
                acceptedFiles: "image/jpg, image/png, image/jpeg, image/gif, file/ico",
                timeout: 180000,
                init: function() {
                    this.on("removedfile", function(file) {
                        if (dropzone.files.length == 0) { dropbox.removeClass('d-none'); }
                        if (dropzone.files.length <= MAX_FILES) { buttonreset.removeClass('d-none'); };
                        if (dropzone.files.length == 0) { buttonreset.addClass('d-none'); }
                    });
                }
            },
        );

        dropzone.on('dragover', dragOverBlock);
        dropzone.on('dragleave', dragLeaveBlock);
        dropzone.on('addedfile', onFileAdd);
        dropzone.on('error', onFileError);
        dropzone.on('complete', onUploadComplete);

        $(".modal").on("hidden.bs.modal", function() {
            buttonreset.addClass('d-none');
            dropbox.removeClass('d-none');
            dropzone.removeAllFiles(true);
        });

    })
})(jQuery, Dropzone);