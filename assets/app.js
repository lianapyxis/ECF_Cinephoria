
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

    let table4 = new DataTable('#frontSeances', {
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
    })


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
            $(".reservation-selected-places-confirmation").text(selectedPlaces.toString())
            $(".reservation-price").text(priceTTC +"€/ SIÈGE")
            $(".reservation-total-cost").text(costTTC + "€")
            $(".reservation-price-confirmation").text(priceTTC +"€/ SIÈGE")
            $(".reservation-total-cost-confirmation").text(costTTC + "€")
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
        let reservationId = $("#reservationId").val()

        if(reservationId.length > 0) {
            $.ajax({
                type: "POST",
                url: "/seances/editBook",
                data: {
                    totalCost: totalCost,
                    places : places,
                    reservationId : reservationId,
                },
                success: function(responseData){
                    console.log(responseData)
                    $(".modal-reservation-confirmation").css("display", "flex")
                    $(".modal-reservation-details").css("display", "none")
                }
            })
        } else {
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
                    $(".modal-reservation-confirmation").css("display", "flex")
                    $(".modal-reservation-details").css("display", "none")
                }
            })
        }

    })

    $(".cancel-reservation").click(function(){
        $(".modal-cancellation-confirmation").css("display", "flex")
    })

    $(".cancel-cancellation").click(function(){
        $(".modal-cancellation-confirmation").css("display", "none")
    })


    let gradeNoted = $("input#grade").val()
    if(typeof gradeNoted != 'undefined') {
        if (gradeNoted.length > 0) {
            $(".note-stars-container svg").each(function(){
                if($(this).attr("data-grade") == gradeNoted) {
                    $(this).prev().css('color', '#FFA704')
                    $(this).prev().prev().css('color', '#FFA704')
                    $(this).prev().prev().prev().css('color', '#FFA704')
                    $(this).prev().prev().prev().prev().css('color', '#FFA704')
                    $(this).css('color', '#FFA704')
                    $(this).next().css('color', '#D9D9D9')
                    $(this).next().next().css('color', '#D9D9D9')
                    $(this).next().next().next().css('color', '#D9D9D9')
                    $(this).next().next().next().next().css('color', '#D9D9D9')
                }
            })
        }
    }


    $(".note-stars-container svg").click(function(){

        let grade = $(this).attr("data-grade")
        let filmId = $("input#filmId").val()
        let gradeId = $("input#gradeId").val()

        if(gradeId.length > 0){
            $.ajax({
                type: "POST",
                url: "/user/addNote",
                data: {
                    grade: grade,
                    filmId: filmId,
                    gradeId : gradeId,
                },
                success: function(responseData){
                    console.log(responseData)
                }
            })
        } else {
            $.ajax({
                type: "POST",
                url: "/user/addNote",
                data: {
                    grade: grade,
                    filmId: filmId,
                },
                success: function(responseData){
                    console.log(responseData)
                }
            })
        }

        $(this).prev().css('color', '#FFA704')
        $(this).prev().prev().css('color', '#FFA704')
        $(this).prev().prev().prev().css('color', '#FFA704')
        $(this).prev().prev().prev().prev().css('color', '#FFA704')
        $(this).css('color', '#FFA704')
        $(this).next().css('color', '#D9D9D9')
        $(this).next().next().css('color', '#D9D9D9')
        $(this).next().next().next().css('color', '#D9D9D9')
        $(this).next().next().next().next().css('color', '#D9D9D9')

    })

    let gradeFilm = $("input#averageNote").val()
    if (gradeFilm > 0) {
        let intValue = Math.floor(gradeFilm)
        let rest = gradeFilm - intValue
        $(".film-note").append(`
                    <svg
                viewBox = "0 0 24 24"
                fill = "currentColor"
                width= "30px"
                height="30px"
                data-grade = "1"
                aria-hidden = "false"><path
                fill = "currentColor"
                d = "m8.243 7.34l-6.38.925l-.113.023a1 1 0 0 0-.44 1.684l4.622 4.499l-1.09 6.355l-.013.11a1 1 0 0 0 1.464.944l5.706-3l5.693 3l.1.046a1 1 0 0 0 1.352-1.1l-1.091-6.355l4.624-4.5l.078-.085a1 1 0 0 0-.633-1.62l-6.38-.926l-2.852-5.78a1 1 0 0 0-1.794 0z"></path></svg>
                `)
        $(".film-note").append(`
                    <svg
                viewBox = "0 0 24 24"
                fill = "currentColor"
                width= "30px"
                height="30px"
                data-grade = "2"
                aria-hidden = "false"><path
                fill = "currentColor"
                d = "m8.243 7.34l-6.38.925l-.113.023a1 1 0 0 0-.44 1.684l4.622 4.499l-1.09 6.355l-.013.11a1 1 0 0 0 1.464.944l5.706-3l5.693 3l.1.046a1 1 0 0 0 1.352-1.1l-1.091-6.355l4.624-4.5l.078-.085a1 1 0 0 0-.633-1.62l-6.38-.926l-2.852-5.78a1 1 0 0 0-1.794 0z"></path></svg>
                `)
        $(".film-note").append(`
                    <svg
                viewBox = "0 0 24 24"
                fill = "currentColor"
                width= "30px"
                height="30px"
                data-grade = "3"
                aria-hidden = "false"><path
                fill = "currentColor"
                d = "m8.243 7.34l-6.38.925l-.113.023a1 1 0 0 0-.44 1.684l4.622 4.499l-1.09 6.355l-.013.11a1 1 0 0 0 1.464.944l5.706-3l5.693 3l.1.046a1 1 0 0 0 1.352-1.1l-1.091-6.355l4.624-4.5l.078-.085a1 1 0 0 0-.633-1.62l-6.38-.926l-2.852-5.78a1 1 0 0 0-1.794 0z"></path></svg>
                `)
        $(".film-note").append(`
                    <svg
                viewBox = "0 0 24 24"
                fill = "currentColor"
                width= "30px"
                height="30px"
                data-grade = "4"
                aria-hidden = "false"><path
                fill = "currentColor"
                d = "m8.243 7.34l-6.38.925l-.113.023a1 1 0 0 0-.44 1.684l4.622 4.499l-1.09 6.355l-.013.11a1 1 0 0 0 1.464.944l5.706-3l5.693 3l.1.046a1 1 0 0 0 1.352-1.1l-1.091-6.355l4.624-4.5l.078-.085a1 1 0 0 0-.633-1.62l-6.38-.926l-2.852-5.78a1 1 0 0 0-1.794 0z"></path></svg>
                `)
        $(".film-note").append(`
                    <svg
                viewBox = "0 0 24 24"
                fill = "currentColor"
                width= "30px"
                height="30px"
                data-grade = "5"
                aria-hidden = "false"><path
                fill = "currentColor"
                d = "m8.243 7.34l-6.38.925l-.113.023a1 1 0 0 0-.44 1.684l4.622 4.499l-1.09 6.355l-.013.11a1 1 0 0 0 1.464.944l5.706-3l5.693 3l.1.046a1 1 0 0 0 1.352-1.1l-1.091-6.355l4.624-4.5l.078-.085a1 1 0 0 0-.633-1.62l-6.38-.926l-2.852-5.78a1 1 0 0 0-1.794 0z"></path></svg>
                `)
        if(rest > 0){
            $(".film-note svg").each(function(){
                if($(this).attr("data-grade") == intValue) {
                    $(this).prev().css('color', '#FFA704')
                    $(this).prev().prev().css('color', '#FFA704')
                    $(this).prev().prev().prev().css('color', '#FFA704')
                    $(this).prev().prev().prev().prev().css('color', '#FFA704')
                    $(this).css('color', '#FFA704')
                    $(this).next().css('color', '#D9D9D9')
                    $(this).next().next().css('color', '#D9D9D9')
                    $(this).next().next().next().css('color', '#D9D9D9')
                    $(this).next().next().next().next().css('color', '#D9D9D9')

                    $(this).next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="currentColor" d="M12 1a1 1 0 0 1 .823.443l.067.116l2.852 5.781l6.38.925c.741.108 1.08.94.703 1.526l-.07.095l-.078.086l-4.624 4.499l1.09 6.355a1 1 0 0 1-1.249 1.135l-.101-.035l-.101-.046l-5.693-3l-5.706 3q-.158.082-.32.106l-.106.01a1.003 1.003 0 0 1-1.038-1.06l.013-.11l1.09-6.355l-4.623-4.5a1 1 0 0 1 .328-1.647l.113-.036l.114-.023l6.379-.925l2.853-5.78A.97.97 0 0 1 12 1m0 3.274V16.75a1 1 0 0 1 .239.029l.115.036l.112.05l4.363 2.299l-.836-4.873a1 1 0 0 1 .136-.696l.07-.099l.082-.09l3.546-3.453l-4.891-.708a1 1 0 0 1-.62-.344l-.073-.097l-.06-.106z"></path></svg>
                    `)
                    $(this).next().next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                    $(this).next().next().next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                    $(this).next().next().next().next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                }
            })

        } else {
                $(".film-note svg").each(function(){
                    if($(this).attr("data-grade") == intValue) {

                        $(this).prev().css('color', '#FFA704')
                        $(this).prev().prev().css('color', '#FFA704')
                        $(this).prev().prev().prev().css('color', '#FFA704')
                        $(this).prev().prev().prev().prev().css('color', '#FFA704')
                        $(this).css('color', '#FFA704')
                        $(this).next().css('color', '#FFA704')
                        $(this).next().next().css('color', '#FFA704')
                        $(this).next().next().next().css('color', '#FFA704')
                        $(this).next().next().next().next().css('color', '#FFA704')

                        $(this).next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                        $(this).next().next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                        $(this).next().next().next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                        $(this).next().next().next().next().replaceWith(`
                    <svg viewBox="0 0 24 24" fill="currentColor" data-grade="1" aria-hidden="true" width="30px" height="30px" style="color: rgb(255, 167, 4);"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75l-6.172 3.245l1.179-6.873l-5-4.867l6.9-1l3.086-6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    `)
                    }
                })
        }
    }

})
