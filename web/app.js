/**
 * Created by Daldas on 13/02/2019.
 */
$(document).ready(function () {
    'use strict';
    $('#form_team').autocompleter({
        url_list: '/search-leagues',
        url_get: '/?term='
    });


    $("#ui-id-1").click(function(){


        var id = $("#fake_form_team").val();


        if(id.toString().length > 0){
            $(location).attr('href', '/teams/'+id.toString());
        }

    })

});
