(function() {
    'use strict';
    $(document).ready(function() {
        // Copy image URL
        $("#copy").on("click", function() {
            var copyText = document.getElementById("sharelink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
        });
        // Show share buttons on desktop
        $("#share").on("click", function() { $(".share-buttons").toggle(); });
        // Show share buttons on mobile
        $("#share-mobile").on("click", function() { $(".share-buttons").toggle(); });

        // Insert image iframe
        $('[data-toggle=insert]').each(function(){
            $(this).on('click', function(e) {
                e.preventDefault();
                var image = $("#sharelink").val();
                parent.postMessage(image, '*');
            });
        });

        $(".tagin-input").on('keypress', function(e){
            var tecla = (document.all) ? e.keyCode : e.which;
            
            //Tecla de retroceso para borrar, siempre la permite
            if (tecla == 8) {
               return true;
            }

            if(tecla == 32) {
                $(this).blur();
                $(this).focus();
            }
            
            // Patrón de entrada, en este caso solo acepta numeros y letras
            var patron = /[A-Za-z0-9]/;
            var tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        });

    });
})(jQuery);