(function() {
    'use strict';

    var init = function(){
        filterCategoryPag();
        filterPresetPag();
        filterDatePag();
        filterTagsPag();
        filterTypePag();
        filterTags();
        filterGroup();


        $('.js-select2').select2();
    };

    var filterCategoryPag = function(){

        $('[data-toggle=drop-category]').each(function(){    

            $(this).on('change', function(event){
                event.preventDefault();
                setFilters(this);
            });
        });
    }

    var filterPresetPag = function(){

        $('[data-toggle=drop-preset]').each(function(){    

            $(this).on('change', function(event){
                event.preventDefault();
                setFilters(this);
            });
        });
    }

    var filterDatePag = function(){

        $('[data-toggle=drop-date]').each(function(){    

            $(this).on('change', function(event){
                event.preventDefault();
                setFilters(this);
            });
        });
    }

    var filterTagsPag = function(){

        $('[data-toggle=drop-tags]').each(function(){    

            $(this).on('change', function(event){
                event.preventDefault();
                $('.item-tag').removeClass('active');
                setFilters(this);
            });
        });
    }

    var filterTags = function(){
        $('.item-tag').each(function(){
            $(this).on('click', function(event){
                event.preventDefault();

                if($(this).hasClass('active')){
                    $(this).removeClass('active');
                    $('[data-toggle=drop-tags]').val('');
                } else {
                    $('.item-tag').removeClass('active');
                    $(this).addClass('active');
                }
                
                setFilters(this);
            });
        });
    }

    var filterTypePag = function(){

        $('[data-toggle=drop-type]').each(function(){    

            $(this).on('change', function(event){
                event.preventDefault();
                setFilters(this);
            });
        });
    }

    var filterGroup = function(){

        $('[data-toggle=drop-group]').each(function(){    

            $(this).on('change', function(event){
                event.preventDefault();
                setFilters(this);
            });
        });
    }

    var setFilters = function(element){

        //Obtengo los valores de todos los dropdown
        var _category = $('[data-toggle=drop-category]').val();
        var _preset   = $('[data-toggle=drop-preset]').val();
        var _date     = $('[data-toggle=drop-date]').val();
        var _tag      = $('[data-toggle=drop-tags]').val();
        var _type     = $('[data-toggle=drop-type]').val();
        var _tag2     = $('.tags-container').find('.active').text();
        var _group    = $('[data-toggle=drop-group]').val();

        //Creo la url
        var _url = '';

        if(_category !== undefined){
            if(_category.length > 0 ){
                (_url.indexOf('?') < 0) ? _url+='?' : _url+='&';
                _url += 'c=' + _category;
            };
        }

         //Filtro por Preset
        if(_preset.length > 0 ){
            (_url.indexOf('?') < 0 ) ? _url+='?' : _url+='&';
            _url += 'p=' + _preset;
        };

        //Filtro por Date
        if(_date.length > 0 ){
            (_url.indexOf('?') < 0) ? _url+='?' : _url+='&';
            _url += 'd=' + _date;
        };

        //Filtro por Tag
        if(_tag.length > 0 ){
            (_url.indexOf('?') < 0) ? _url+='?' : _url+='&';
            _url += 't=' + _tag;
        };

        //Filtro por Tag 2
        _tag2 = _tag2.trim();
        if(_tag2.length > 0 ){
            (_url.indexOf('?') < 0) ? _url+='?' : _url+='&';
            _url += 't=' + _tag2;
        };

        //Filtro Type
        if(_type >= 0 ){
            (_url.indexOf('?') < 0) ? _url+='?' : _url+='&';
            _url += 's=' + _type;
        };

        //Filtro por Group
        if(_group >= 0 ){
            (_url.indexOf('?') < 0) ? _url+='?' : _url+='&';
            _url += 'g=' + _group;
        };

        $(location).attr('href', _url);

    };

    init();

})(jQuery);