(function($){
    $.fn.extend({
        //pass the options variable to the function
        hathooraGrid: function(options, args)
        {
            //Set the default values, use comma to separate the settings, example:
            var defaults = {
                //padding: 20,
            }

            if (typeof options == 'object')
                var options = $.extend(defaults, options);

            var onReadyCallTrigger = function(table_id)
            {
                if (options.onReady && typeof options.onReady == 'function')
                {
                    func = options.onReady;
                    func(table_id);
                }
            }

            if (options.isHackReady)
            {
                var obj = $(this);
                table_id = $(obj).attr('id');
                options.isHackReady = false;
                onReadyCallTrigger(table_id);
            }

            // public methods
            if (typeof options == 'string')
            {
                if (options == 'setProperty' && typeof args == 'object')
                {
                    var obj = $(this);
                    table_id = $(obj).attr('id');

                    if (hathooraGrid['tables'][table_id])
                    {
                        $.each(args, function(k,v)
                        {
                            value = v;
                            key = k;
                            if (typeof v == 'object')
                            {
                                for(var _k in v)
                                {
                                    key = _k;
                                    value = v[key];
                                    break;
                                }
                            }

                            if (typeof hathooraGrid['tables'][table_id][key] != undefined)
                            {
                                hathooraGrid['tables'][table_id][key] = value;
                            }
                        });
                    }

                    return;
                }
                else if (options == 'refresh')
                {
                    var obj = $(this);
                    table_id = $(obj).attr('id');

                    if (hathooraGrid['tables'][table_id])
                    {
                        ajaxData = {'hathooraGrid_id': table_id}

                        page = hathooraGrid['tables'][table_id]['page'];
                        if (page)
                            ajaxData.page = page;

                        sort = hathooraGrid['tables'][table_id]['sort'];
                        if (sort)
                            ajaxData.sort = sort;

                        order = hathooraGrid['tables'][table_id]['order']
                        if (order)
                            ajaxData.order = order;

                        $.ajax({
                            async: false,
                            url: hathooraGrid['tables'][table_id]['url'],
                            data:  ajaxData,
                            type: 'get',
                            dataType: 'html',
                            cache: false,
                            beforeSend: function()
                            {
                                $('#' + table_id + '_inner').html('<div class="please_wait"></div>');
                            },
                            success: function(data, textStatus, xhr)
                            {
                                $('#' + table_id + '_inner').html(data);
                                onReadyCallTrigger(table_id);
                            }
                        });
                    }
                }
            };

            return this.each(function()
            {
                var obj = $(this);
                table_id = $(obj).attr('id');

                if (hathooraGrid['tables'][table_id])
                {
                    columns = {};
                    if (hathooraGrid['tables'][table_id] && typeof hathooraGrid['tables'][table_id]['columns'] == 'object')
                        columns = hathooraGrid['tables'][table_id]['columns'];
                    sort_current = hathooraGrid['tables'][table_id]['sort'];
                    order_current = hathooraGrid['tables'][table_id]['order'].toLowerCase();
                    page_current = hathooraGrid['tables'][table_id]['page'];
                    url_grid = hathooraGrid['tables'][table_id]['url'];


                    // colum operations: sort, delete, reorder
                    if (hathooraGrid['tables'][table_id]['columns'] && $('#' + table_id + ' thead tr th').length)
                    {
                        $.each(columns, function(i,v)
                        {
                            field = v.field;
                            thElm = $('#' + table_id + ' thead tr th[htg-field="'+ v.field +'"]');

                            // delete column
                            if ($(thElm).find('.hathooraColumnDel').length)
                            {
                                /*
                                delIcon = $(thElm).find('.hathooraColumnDel')
                                    .bind('click', function()
                                    {
                                        table_id = $(this).parents('table.hathooraTable').attr('id');
                                        var thInder = $('#' + table_id + ' thead th').index($(this).parents('th'));

                                        $(this).closest('th').remove();
                                        $('#' + table_id + ' tbody tr td:nth-child(' + (thInder + 1) + ')').remove();

                                        return false;
                                    });
                                */
                            }

                            // sorting bind
                            if ($(thElm).find('.hathooraColumnSort').length)
                            {
                                // make column icons clickable..
                                $(thElm).find('.hathooraColumnSort').addClass('hathooraGridAjax');

                                // also bind sorting by clicking on name
                                $(thElm).find('.hathooraColumnName')
                                        .css('cursor', 'pointer')
                                        .click(function()
                                        {
                                            $(this).parent('th').find('.hathooraColumnSort').click();
                                            return false;
                                        });
                            }
                        });
                    }

                    // ajax fetch link
                    $('#' + table_id + '_inner .hathooraPaginator a, #' + table_id + ' .hathooraGridAjax').click(function()
                    {
                        table_id = $(this).parents('.hathooraGrid').attr('htg-table_id');
                        if (typeof hathooraGrid['tables'][table_id] == 'object' && typeof hathooraGrid['tables'][table_id]['columns'] == 'object')
                            columns = hathooraGrid['tables'][table_id]['columns'];
                        sort_current = hathooraGrid['tables'][table_id]['sort'];
                        order_current = hathooraGrid['tables'][table_id]['order'].toLowerCase();
                        page_current = hathooraGrid['tables'][table_id]['page'];
                        url_grid = hathooraGrid['tables'][table_id]['url'];

                        ajaxData = {'hathooraGrid_id': table_id}

                        page = ($(this).attr('htg-page') ? $(this).attr('htg-page') : page_current);
                        if (page)
                            ajaxData.page = page;

                        // is sortIcon?
                        if ($(this).hasClass('hathooraColumnSort'))
                            sort = ($(this).parents('th').attr('htg-field') ? $(this).parents('th').attr('htg-field') : sort_current);
                        else
                            sort = ($(this).attr('htg-field') ? $(this).attr('htg-field') : sort_current);
                        if (sort)
                            ajaxData.sort = sort;

                        order = ($(this).attr('htg-order') ? $(this).attr('htg-order') : order_current);
                        if (order)
                            ajaxData.order = order;

                        $.ajax({
                            async: false,
                            url: url_grid,
                            data:  ajaxData,
                            type: 'get',
                            dataType: 'html',
                            cache: false,
                            beforeSend: function()
                            {
                                $('#' + table_id + '_inner').html('<div class="please_wait">Pleae wait...</div>');
                            },
                            success: function(data, textStatus, xhr)
                            {
                                $('#' + table_id + '_inner').html(data);
                                onReadyCallTrigger(table_id);
                            }
                        });

                        return false;
                    });
                }
            });
        }
    });
})(jQuery);