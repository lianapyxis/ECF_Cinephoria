
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import jquery from './vendor/jquery/jquery.index.js';

import DataTable from './vendor/datatables.net/datatables.net.index.js'

const $ = jquery;
window.$ = window.jQuery = $;
$(window).on('turbo:load', function(){
    $(".login-container .show-password").on('click', function(){
        if($(this).text() == "Afficher") {
            $(this).text('Masquer')
            $(this).prev().get(0).type = 'text';
        } else {
            $(this).text('Afficher')
            $(this).prev().get(0).type = 'password';
        }

    })

    $(".register-container .show-password").on('click', function(){
        if($(this).text() == "Afficher") {
            $(this).text('Masquer')
            $(this).prev().children().last().get(0).type = 'text';
        } else {
            $(this).text('Afficher')
            $(this).prev().children().last().get(0).type = 'password';
        }

    })
    $(".register-container button#user_submit").text('CRÉER UN COMPTE')

    $(".form-film form .film-input-genre").on('click', function (){
        let customWidth = $(this).outerWidth()
        console.log(customWidth)
        if ($(this).next().css('display') == "none"){
            $(this).next().css('display', "block")
            $(this).next().css('overflow', "overlay")
            $(this).next().css('position', "absolute")
            $(this).next().css('width', `${customWidth}px`)
        } else {
            $(this).next().css('display', "none")
            $(this).next().css('position', "static")
        }

    })

    if($(".form-film .img-film").length > 0){
        $(".form-file-type input#film_imgPath").prop('required',false)
    }

    $(".form-file-type").prepend("<div class=\"input-file-name\"></div>")

    $(".form-file-type .input-file-name").text($('.form-file-type input[type=file]').val());

    $('.form-file-type input[type="file"]').change(function() {
        $(".form-file-type .input-file-name").text($('.form-file-type input[type=file]').val().replace(/C:\\fakepath\\/i, ''));
    })
    $("select#film_genres").mousedown(function(e){
        e.preventDefault();

        var select = this;
        var scroll = select .scrollTop;

        e.target.selected = !e.target.selected;

        setTimeout(function(){select.scrollTop = scroll;}, 0);

        $(select ).focus();
    }).mousemove(function(e){e.preventDefault()});

    let table = new DataTable('#adminFilms', {
        language: {
            paginate: {
                first: '&#8606;',
                last: '&#8608;',
                previous: '&#8592;',
                next: '&#8594;'
            },
            search: "",
            searchPlaceholder: 'RECHERCHER PAR TITRE...',
        },
        "searching": true,
        responsive: true,
        pageLength: 10,
        "dom": 'frtip',
        "info": false,
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }]
    });

    let table2 = new DataTable('#staffFilms', {
        language: {
            paginate: {
                first: '&#8606;',
                last: '&#8608;',
                previous: '&#8592;',
                next: '&#8594;'
            },
            search: "",
            searchPlaceholder: 'RECHERCHER PAR TITRE...',
        },
        "searching": true,
        responsive: true,
        pageLength: 10,
        "dom": 'frtip',
        "info": false,
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }]
    });

    let table3 = new DataTable('#adminSeances', {
        language: {
            paginate: {
                first: '&#8606;',
                last: '&#8608;',
                previous: '&#8592;',
                next: '&#8594;'
            },
            search: "",
            searchPlaceholder: 'RECHERCHER PAR TITRE...',
        },
        "searching": true,
        responsive: true,
        pageLength: 10,
        "dom": 'frtip',
        "info": false,
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }]
    });


    function changeTag(tag){
        if(tag.length > 0) {
            $(".custom-tags").append('<span>'+tag+'</span>')
            if($(".roomSpecialPlaces > :last-child input").length > 0){
                let val = $(".roomSpecialPlaces > :last-child input").attr('id').match(/\d+/)
                let newval = parseInt(val, 10) + 1
                $(".roomSpecialPlaces").append('<div class="form-group"><label for="room_specialPlaces_'+newval+'_place" class="required"> </label><input type="text" id="room_specialPlaces_'+newval+'_place" name="room[specialPlaces]['+newval+'][place]" required="required" value="'+tag+'" class="form-control mb-3"></div>')
            } else{
                $(".roomSpecialPlaces").append('<div class="form-group"><label for="room_specialPlaces_0_place" class="required"> </label><input type="text" id="room_specialPlaces_0_place" name="room[specialPlaces][0][place]" required="required" value="'+tag+'" class="form-control mb-3"></div>')
            }

        }
    }

    $("input#input-tag-custom").on("keypress", function(e){
        if(e.which == 44) {
                let seats = $(this).val().split(',')
                changeTag(seats[seats.length -1])
                $(this).val("")
            }

    })

    $("input#input-tag-custom").on("keyup", function(e){
        if(e.which == 8) {
            if($(this).val().length == 0) {
                $(".roomSpecialPlaces .form-group input").each(function(){
                    if($(this).val() == $(".custom-tags > :last-child").text()){
                        $(this).parent().remove();
                    }
                })
                $(".custom-tags > :last-child").remove()
            }
        }
    })

    let cityTitle = $("select#city_select").find("option:selected").text()

    $('select#seance_id_room option').each(function() {
        if ($(this).attr('data-city') == cityTitle) {
            $(this).show();
        } else {
            $(this).hide();
            $(this).removeClass('room-option')
        }
    })

    $("select#city_select").on("change", function(e){
        let cityTitle = $(this).find("option:selected").text();
        $("select#seance_id_room option:selected").removeAttr("selected")
        $('select#seance_id_room option').each(function() {
            if ($(this).attr('data-city') == cityTitle) {
                $(this).show();
                $(this).addClass('room-option')
            } else {
                $(this).hide();
                $(this).removeClass('room-option')
            }
        })
        $("select#seance_id_room option.room-option").first().attr('selected', 'selected')
    })

    $("#form-user").submit(function(e) {
       if ($("#form-user input#user_password_first").val() !== $("#form-user input#user_password_second").val()) {
           $(".match-error").remove()
           $('.form-custom-column').append('<div class="match-error">Les champs du nouveau mot de passe doivent correspondre.</div>')
           e.preventDefault();
       } else{
           $(".match-error").remove()
       }
    })

    $(".show-comment-details").click(function(){
        let lastname = $(this).prev().prev().prev().val()
        let firstname = $(this).prev().prev().val()
        let comment = $(this).prev().val()
        let date = $(this).parent().prev().prev().text()
        let film = $(this).parent().prev().prev().prev().text()
        let status = $(this).parent().prev().text()
        let id = $(this).parent().prev().prev().prev().prev().prev().text()

        $(".modal-comment-details").css("display", "flex")
        $(".comment-info-lastname").text(lastname)
        $(".comment-info-firstname").text(firstname)
        $(".modal-comment-text").text(comment)
        $(".comment-info-date").text(date)
        $(".comment-info-film").text(film)

        if(status = "DRAFT") {
            $(".comment-info-status").text("EN ATTENTE DE VALIDATION")
        } else {
            $(".comment-info-status").text("PUBLIÉ")
        }

        let hrefValidate = "/comment/publish/" + id
        $(".comment-action-publish").attr("href", hrefValidate)

        let hrefDelete = "/comment/delete/" + id
        $(".comment-action-delete").attr("href", hrefDelete)
    })
    $(".modal-comment-details .button-close").click(function(){
        $(".modal-comment-details").css("display", "none")
    })

    $(".btn-add-comment").click(function(){
        $(this).css("display", "none")
    })

    $(".filter-city select#city_select").on("change", function(){
        let city = $(this).find("option:selected").val()
        $(".film-container-home").each(function(){
            if(city == "all") {
                $(this).css("display", "flex")
            } else if($(this).find("input").val().indexOf(city) < 0){
                $(this).css("display", "none")
            } else {
                $(this).css("display", "flex")
            }
        })
    })

    $(".seance-action-select").click(function(){
        let selectedPlaces = []

        $(".modal-places .checkbox-wrapper input").each(function(){
            if ($(this).prop('checked')==true){
                selectedPlaces.push($(this).val().toUpperCase())
            }
        })
        if(selectedPlaces.length <= 0){
            $(".modal-seance-places > div:first-child").append('<div class="alert-info">Veuillez sélectionner des sièges afin de procéder avec la réservation.</div>')
        } else {
            let priceTTC = $(".selected-seance-price").val()
            let costTTC = priceTTC * selectedPlaces.length

            $(".modal-seance-details").css("display", "none")
            $(".modal-reservation-details").css("display", "flex")

            $(".reservation-selected-places").text(selectedPlaces.toString())
            $(".reservation-price").text(priceTTC +"€/ SIÈGE")
            $(".reservation-total-cost").text(costTTC + "€")
        }

    })
    $(".reservation-action-return").click(function(){
        $(".modal-seance-details").css("display", "flex")
        $(".modal-reservation-details").css("display", "none")
    })

    $(".reservation-action-confirm").click(function(){
        let idSeance = $(".seance-id").val()
        let idUser = $(".user-id").val()
        let totalCost = $(".reservation-total-cost").text().replace(/[^0-9]/g, '')
        let places = $(".reservation-selected-places").text().split(",")

        $.ajax({
            type: "POST",
            url: "/seances/book",
            data: {
                idSeance: idSeance,
                idUser: idUser,
                totalCost: totalCost,
                places : places
            },
            success: function(responseData){
                console.log(responseData)
            }
        })
    })

})
