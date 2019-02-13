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

        var id = $("#form_team").val();

        $('.ui-helper-hidden-accessible').html('<a href="/teams/French Ligue 1">Show List of teams</a>');


        if(id.toString().length > 0){
            $(location).attr('href', '/teams/'+id.toString());
        }

    })

});
