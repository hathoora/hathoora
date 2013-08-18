// hide all tab contens
function hathooraHideAllTabs()
{
    $('#hathoora_debug .hathoora_section_tab_content').hide();
    $('#hathoora_debug .hathoora_section_tab').removeClass('hathoora_section_tab_selected');
}

$(document).ready(function () 
{
    hathooraHideAllTabs();
    
    // section opener
    $('#hathoora_debug div.hathoora_section_toggle').click(function()
    {
        section = $(this).parent().attr('section');
        if (!$('#hathoora_debug div.hathoora_' + section + ' div.hathoora_section_table_wrapper').is(":visible"))
        {
            // hide all first
            $('#hathoora_debug div.hathoora_section_table_wrapper').hide('fast').parents('div[section]').removeClass('hathoora_opened');
            // then show this one
            $('#hathoora_debug div.hathoora_' + section + ' div.hathoora_section_table_wrapper').show('fast').parents('div.hathoora_' + section).addClass('hathoora_opened');
            
            // need to show first tab content?
            if ($('#hathoora_debug div.hathoora_' + section + ' div.hathoora_section_tabs .hathoora_section_tab').length)
            {
                $($('#hathoora_debug div.hathoora_' + section + ' div.hathoora_section_tabs .hathoora_section_tab')[0]).trigger('click');
            }
        }
        else
        {
            $('#hathoora_debug div.hathoora_' + section + ' div.hathoora_section_table_wrapper').hide('fast').parents('div.hathoora_' + section).removeClass('hathoora_opened');
        }
    });
    
    // tabs
    $('#hathoora_debug div.hathoora_section_tabs .hathoora_section_tab').click(function()
    {
        section = $(this).parents('div[section]').attr('section');
        tab = $(this).attr('tab');
        hathooraHideAllTabs();
        $(this).addClass('hathoora_section_tab_selected');
        $('#hathoora_debug div.hathoora_' + section + ' .hathoora_section_tab_content[tab="' + tab +'"]').show();
    });
});
