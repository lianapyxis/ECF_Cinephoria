
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
import './styles/app-responsive.css';

/*
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');*/

import jquery from './vendor/jquery/jquery.index.js';

import DataTable from './vendor/datatables.net/datatables.net.index.js'

import { Chart, registerables } from './vendor/chart.js/chart.js.index.js';

Chart.register(...registerables);

const $ = jquery;
window.$ = window.jQuery = $;
$(window).on('turbo:load', function(){

    let setCity = localStorage.getItem("cinephoria_city");

    if(setCity == null) {
        localStorage.setItem("cinephoria_city", "Toutes les villes");
        $(".selected-city").text("Toutes les villes")
    } else {
        if(setCity == "Toutes les villes") {
            $(".selected-city").text("Toutes les villes")
        } else {
            $(".selected-city").text(setCity)
        }
    }

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

    if(typeof $("#frontSeances") !== 'undefined') {
        var table4 = new DataTable('#frontSeances', {
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
            "searching": false,
            responsive: true,
            pageLength: 10,
            "dom": 'frtip',
            "info": false,
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        })
    } else {
        var table4 = undefined;
    }
    if(typeof $("#collectionFilms") !== 'undefined') {
        var table5 = new DataTable('#collectionFilms', {
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
            pageLength: 5,
            "dom": 'frtip',
            "info": false,
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        })
    } else {
        var table5 = undefined;
    }


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

        if(status !== "PUBLISHED") {
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

    $(".filter-city select#city_select option").each(function(){
        let selectedCity = localStorage.getItem("cinephoria_city");
        if ($(this).text() == selectedCity && selectedCity !== '' && typeof selectedCity !== 'undefined') {
            $(this).attr("selected", true)
        } else {
            $(this).attr("selected", false)
        }
    })

    $(".filter-city-seances select#city_select_seances option").each(function(){
        let selectedCity = localStorage.getItem("cinephoria_city");
        if ($(this).val() == selectedCity && selectedCity !== '' && typeof selectedCity !== 'undefined') {
            $(this).attr("selected", true)
        } else {
            $(this).attr("selected", false)
        }
        table4.$(".city-seance").each(function(){
            if(selectedCity == "Toutes les villes") {
                $(this).parent().parent().removeClass("hidden-filter-city")
            } else if($(this).val().indexOf(selectedCity) < 0){
                $(this).parent().parent().addClass("hidden-filter-city")
            } else {
                $(this).parent().parent().removeClass("hidden-filter-city")
            }
        })
    })

    $(".filter-city.filter-home select#city_select").on("change", function(){
        let city = $(this).find("option:selected").text()
        localStorage.setItem("cinephoria_city", city);
        $(".selected-city").text(city)
        let cityId = $(this).find("option:selected").val()
            $.ajax({
                type: "POST",
                url: "/films/filter",
                dataType: 'json',
                data: {
                    city: cityId,
                },
                success: function(responseData){
                    let dataResponse = JSON.parse(responseData)
                    let films = dataResponse.films
                    $('.films-container-home').children().remove()
                    for (let film of films) {
                        let filmTemplate = `<div class="film-container-home">
                    <a href="/films/show/${film.id}" class="film-hover-home">VOIR LES SÉANCES</a>
                    <a href="/films/show/${film.id}"><img src="/uploads/${film.imgPath}" alt="${film.title}" class="img-film-home"></a>
                    <a href="/films/show/${film.id}" class="film-title-home">${film.title} (${film.year})</a>
                    <p class="film-date-home">Ajouté : ${film.dateAdd}</p>
                    </div>`
                        $('.films-container-home').append(filmTemplate)
                    }
                    $("nav.pagination-container").remove()

                    if(dataResponse.totalPages > 1){
                        let paginationTemplate = `<nav class="pagination-container">
                        <ul class="pagination">`
                        for (let page = 1; page <= dataResponse.totalPages; page++) {
                            if(page == dataResponse.currentPage) {
                                paginationTemplate = paginationTemplate + `<li class="pagination-item active">
                                        <a href="/films/?page=${page}" class="pagination-link">${page}</a>
                                    </li>`
                            } else {
                                paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/?page=${page}" class="pagination-link">${page}</a>
                                    </li>`
                            }
                        }
                        paginationTemplate = paginationTemplate + `</ul></nav>`
                        $(".home-page-body").after(paginationTemplate)
                    }
                }
            })
    })

    $(".filter-city.filter-reserver select#city_select").on("change", function(){
        let city = $(this).find("option:selected").text()
        localStorage.setItem("cinephoria_city", city);
        $(".selected-city").text(city)
        let cityId = $(this).find("option:selected").val()
        let date = $(".filter-city #date-seance").val()
        if (date == null){
            let cityId = $(this).find("option:selected").val()
            $.ajax({
                type: "POST",
                url: "/films/filterDate",
                dataType: 'json',
                data: {
                    city: cityId,
                    date: ''
                },
                success: function(responseData){
                    let dataResponse = JSON.parse(responseData)
                    let films = dataResponse.films


                    $('.films-container-home').children().remove()
                    for (let film of films) {
                        let filmTemplate = `<div class="film-container-home">
                    <a href="/films/show/${film.id}" class="film-hover-home">VOIR LES SÉANCES</a>
                    <a href="/films/show/${film.id}"><img src="/uploads/${film.imgPath}" alt="${film.title}" class="img-film-home"></a>
                    <a href="/films/show/${film.id}" class="film-title-home">${film.title} (${film.year})</a>
                    <p class="film-date-home">Ajouté : ${film.dateAdd}</p>
                    </div>`
                        $('.films-container-home').append(filmTemplate)
                    }
                    $("nav.pagination-container").remove()

                    if(dataResponse.totalPages > 1){
                        let paginationTemplate = `<nav class="pagination-container">
                        <ul class="pagination">`
                        for (let page = 1; page <= dataResponse.totalPages; page++) {

                                if(page == dataResponse.currentPage) {
                                    paginationTemplate = paginationTemplate + `<li class="pagination-item active">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>`
                                } else {
                                    if(dataResponse.currentPage == 1){
                                        if(page == dataResponse.totalPages) {
                                            paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=2&city=${cityId}" class="pagination-link">&gt;</a>
                                    </li>`
                                        }
                                    } else if (dataResponse.currentPage == dataResponse.totalPages) {
                                        if(page == 1) {
                                            paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=1&city=${cityId}" class="pagination-link">&lt;</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>`
                                        }
                                    } else {
                                        paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>`
                                    }
                                }
                        }
                        paginationTemplate = paginationTemplate + `</ul></nav>`
                        $(".home-page-body").after(paginationTemplate)
                    }
                }
            })
        } else {
            let dateArr = date.split("-")
            if(dateArr[0][0] != 0) {
                let url = $(this).find("option:selected").val() + '&date=' + date
                $.ajax({
                    type: "POST",
                    url: "/films/filterDate",
                    dataType: 'json',
                    data: {
                        city: cityId,
                        date: date,
                    },
                    success: function(responseData){
                        let dataResponse = JSON.parse(responseData)
                        let films = dataResponse.films
                        $('.films-container-home').children().remove()
                        for (let film of films) {
                            let filmTemplate = `<div class="film-container-home">
                    <a href="/films/show/${film.id}" class="film-hover-home">VOIR LES SÉANCES</a>
                    <a href="/films/show/${film.id}"><img src="/uploads/${film.imgPath}" alt="${film.title}" class="img-film-home"></a>
                    <a href="/films/show/${film.id}" class="film-title-home">${film.title} (${film.year})</a>
                    <p class="film-date-home">Ajouté : ${film.dateAdd}</p>
                    </div>`
                            $('.films-container-home').append(filmTemplate)
                        }

                        $("nav.pagination-container").remove()

                        if(dataResponse.totalPages > 1){
                            let paginationTemplate = `<nav class="pagination-container">
                            <ul class="pagination">`
                            for (let page = 1; page <= dataResponse.totalPages; page++) {
                                if(page == dataResponse.currentPage) {
                                    paginationTemplate = paginationTemplate + `<li class="pagination-item active">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>`
                                } else {
                                    if(dataResponse.currentPage == 1){
                                        if(page == dataResponse.totalPages) {
                                            paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=2&city=${cityId}&date=${date}" class="pagination-link">&gt;</a>
                                    </li>`
                                        }
                                    } else if (dataResponse.currentPage == dataResponse.totalPages) {
                                        if(page == 1) {
                                            paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=1&city=${cityId}&date=${date}" class="pagination-link">&lt;</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>`
                                        }
                                    } else {
                                        paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>`
                                    }
                                }
                            }
                            paginationTemplate = paginationTemplate + `</ul></nav>`
                            $(".home-page-body").after(paginationTemplate)
                        }
                    }
                })
            }
        }
    })

    $(".filter-city.filter-reserver #date-seance").on("change", function(){
        let cityId = $(".filter-city.filter-reserver select#city_select").find("option:selected").val()
        let date = $(this).val()
        let dateArr = date.split("-")

        if(dateArr[0][0] != 0){
            let url = $(this).find("option:selected").val() + '&date=' + date
            $.ajax({
                type: "POST",
                url: "/films/filterDate",
                dataType: 'json',
                data: {
                    city: cityId,
                    date: date,
                },
                success: function(responseData){
                    let dataResponse = JSON.parse(responseData)
                    let films = dataResponse.films
                    $('.films-container-home').children().remove()
                    for (let film of films) {
                        let filmTemplate = `<div class="film-container-home">
                    <a href="/films/show/${film.id}" class="film-hover-home">VOIR LES SÉANCES</a>
                    <a href="/films/show/${film.id}"><img src="/uploads/${film.imgPath}" alt="${film.title}" class="img-film-home"></a>
                    <a href="/films/show/${film.id}" class="film-title-home">${film.title} (${film.year})</a>
                    <p class="film-date-home">Ajouté : ${film.dateAdd}</p>
                    </div>`
                        $('.films-container-home').append(filmTemplate)
                    }

                    $("nav.pagination-container").remove()

                    if(dataResponse.totalPages > 1){
                        let paginationTemplate = `<nav class="pagination-container">
                            <ul class="pagination">`
                        for (let page = 1; page <= dataResponse.totalPages; page++) {
                            if(page == dataResponse.currentPage) {
                                paginationTemplate = paginationTemplate + `<li class="pagination-item active">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>`
                            } else {
                                if(dataResponse.currentPage == 1){
                                    if(page == dataResponse.totalPages) {
                                        paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=2&city=${cityId}&date=${date}" class="pagination-link">&gt;</a>
                                    </li>`
                                    }
                                } else if (dataResponse.currentPage == dataResponse.totalPages) {
                                    if(page == 1) {
                                        paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=1&city=${cityId}&date=${date}" class="pagination-link">&lt;</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>`
                                    }
                                } else {
                                    paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}&date=${date}" class="pagination-link">${page}</a>
                                    </li>`
                                }
                            }
                        }
                        paginationTemplate = paginationTemplate + `</ul></nav>`
                        $(".home-page-body").after(paginationTemplate)
                    }
                }
            })
        }

        if( date == '') {
            let cityId = $(".filter-city.filter-reserver select#city_select").find("option:selected").val()
            $.ajax({
                type: "POST",
                url: "/films/filterDate",
                dataType: 'json',
                data: {
                    city: cityId,
                    date: ''
                },
                success: function(responseData){
                    let dataResponse = JSON.parse(responseData)
                    let films = dataResponse.films


                    $('.films-container-home').children().remove()
                    for (let film of films) {
                        let filmTemplate = `<div class="film-container-home">
                    <a href="/films/show/${film.id}" class="film-hover-home">VOIR LES SÉANCES</a>
                    <a href="/films/show/${film.id}"><img src="/uploads/${film.imgPath}" alt="${film.title}" class="img-film-home"></a>
                    <a href="/films/show/${film.id}" class="film-title-home">${film.title} (${film.year})</a>
                    <p class="film-date-home">Ajouté : ${film.dateAdd}</p>
                    </div>`
                        $('.films-container-home').append(filmTemplate)
                    }
                    $("nav.pagination-container").remove()

                    if(dataResponse.totalPages > 1){
                        let paginationTemplate = `<nav class="pagination-container">
                        <ul class="pagination">`
                        for (let page = 1; page <= dataResponse.totalPages; page++) {

                            if(page == dataResponse.currentPage) {
                                paginationTemplate = paginationTemplate + `<li class="pagination-item active">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>`
                            } else {
                                if(dataResponse.currentPage == 1){
                                    if(page == dataResponse.totalPages) {
                                        paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=2&city=${cityId}" class="pagination-link">&gt;</a>
                                    </li>`
                                    }
                                } else if (dataResponse.currentPage == dataResponse.totalPages) {
                                    if(page == 1) {
                                        paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=1&city=${cityId}" class="pagination-link">&lt;</a>
                                    </li>
                                    <li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>`
                                    }
                                } else {
                                    paginationTemplate = paginationTemplate + `<li class="pagination-item">
                                        <a href="/films/reservationlist?page=${page}&city=${cityId}" class="pagination-link">${page}</a>
                                    </li>`
                                }
                            }
                        }
                        paginationTemplate = paginationTemplate + `</ul></nav>`
                        $(".home-page-body").after(paginationTemplate)
                    }
                }
            })
        }
    })

    $(".film-page-container .filter-city-seances select#city_select_seances").on("change", function(){
        let city = $(this).find("option:selected").val()
        localStorage.setItem("cinephoria_city", city);
        table4.$(".city-seance").each(function(){
            if(city == "Toutes les villes") {
                $(this).parent().parent().removeClass("hidden-filter-city")
            } else if($(this).val().indexOf(city) < 0){
                    $(this).parent().parent().addClass("hidden-filter-city")
            } else {
                $(this).parent().parent().removeClass("hidden-filter-city")
            }
        })
    })

    $(".film-page-container .form-group-date-seance #date-seance").on("change", function(){
        let date = $(this).val()
        let dateArr = $(this).val().split('-')
        let newDate = dateArr[2] + '.' + dateArr[1] + '.' + dateArr[0]
        table4.$(".date-table-seance").each(function(){
            if ($(this).text() == newDate || date == ''){
                $(this).parent().removeClass("hidden-filter-date")
            } else {
                $(this).parent().addClass("hidden-filter-date")
            }
        })
    })

    $(".film-page-container .form-group-places-seance #places-seance").on("input", function(){
        let places = parseInt($(this).val(), 10);
        table4.$(".restingPlaces").each(function(){
            if (parseInt($(this).text(), 10) < places){
                $(this).parent().addClass("hidden-filter-places")
            } else {
                $(this).parent().removeClass("hidden-filter-places")
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


        if(typeof reservationId !== 'undefined') {
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

    table5.$(".averageNoteCollection").each(function(){
        let gradeFilm = $(this).val()
        if (gradeFilm > 0) {
            let intValue = Math.floor(gradeFilm)
            let rest = gradeFilm - intValue
            $(this).parent().append(`
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
            $(this).parent().append(`
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
            $(this).parent().append(`
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
            $(this).parent().append(`
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
            $(this).parent().append(`
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
                $(this).parent().find("svg").each(function(){
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
                $(this).parent().find("svg").each(function(){
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
    $(".collection-body-container .filter-city-seances #city_select_seances option").each(function(){
        let selectedCity = localStorage.getItem("cinephoria_city");

        if ($(this).text() == selectedCity && selectedCity !== '' && typeof selectedCity !== 'undefined') {
            $(this).attr("selected", true)
            cityTitle = $(this).text()
        } else {
            $(this).attr("selected", false)
        }

        if(cityTitle !== 'Toutes les villes'){
            table5.search(function (d) {
                return d.includes(cityTitle);
            }).draw()
        }
    })
    $(".collection-body-container .filter-city-seances select#city_select_seances").on("change", function(){

        let city = $(this).find("option:selected").val()
        localStorage.setItem("cinephoria_city", city);
        let date = $(".collection-body-container .form-group-date-seance #date-seance").val("")
        city = city.replace('è', 'e')

        $(".collection-body-container #genres_select option").each(function(){
            if ($(this).val() == 'all') {
                $(this).parent().val($(this).val())
                $(this).attr("selected", true)
            } else {
                $(this).attr("selected", false)
            }
        })

        if(city !== 'Toutes les villes'){
            table5.column(0)
                .data().search(function (d) {
                    return d.includes(city);
            }).draw()
        } else {
            table5.column(0)
                .data().search(function (d) {
                    return d.includes('');
            }).draw()
        }
    })

    $(".collection-body-container .form-group-date-seance #date-seance").on("change", function(){
        let date = $(this).val()
        let dateArr = $(this).val().split('-')
        let city = localStorage.getItem("cinephoria_city");
        let newDate = dateArr[2] + '.' + dateArr[1] + '.' + dateArr[0]

        $(".collection-body-container #genres_select option").each(function(){
            if ($(this).val() == 'all') {
                $(this).parent().val($(this).val())
                $(this).attr("selected", true)
            } else {
                $(this).attr("selected", false)
            }
        })

        if(date !== '') {
            if(city !== 'Toutes les villes'){
                table5.column(0)
                    .data().search(function (d) {
                    if(d.includes(city)){
                        return d.includes(newDate);
                    }
                }).draw()
            } else {
                table5.column(0)
                    .data().search(function (d) {
                    return d.includes(newDate);
                }).draw()
            }
        }

    })

    $(".collection-body-container #genres_select").on("change", function(){

        let city = localStorage.getItem("cinephoria_city");
        let date = $(".collection-body-container .form-group-date-seance #date-seance").val()
        let dateArr = date.split('-')
        let newDate = dateArr[2] + '.' + dateArr[1] + '.' + dateArr[0]
        let genre = $(this).find("option:selected").val()

        genre = genre.replace('é','e')

        if(city == 'Toutes les villes'){
            if(date !== '') {
                table5.column(0)
                    .data().search(function (d) {
                    if(genre == 'all') {
                        return d.includes(newDate);
                    } else {
                        if(d.includes(newDate)){
                            return d.includes(genre);
                        }
                    }
                }).draw()
            } else {
                table5.column(0)
                    .data().search(function (d) {
                    if(genre == 'all') {
                        return d.includes('');
                    } else {
                        return d.includes(genre);
                    }
                }).draw()
            }
        } else {
            if(date !== '') {
                table5.column(0)
                    .data().search(function (d) {
                    if(d.includes(newDate) && d.includes(city)){
                        if(genre == 'all') {
                            return d;
                        } else {
                            return d.includes(genre);
                        }
                    }
                }).draw()
            } else {
                table5.column(0)
                    .data().search(function (d) {
                    if(d.includes(city)){
                        if(genre == 'all') {
                            return d;
                        } else {
                            return d.includes(genre);
                        }
                    }
                }).draw()
            }
        }
    })

    table5.$(".consult-seances-btn").click(function(){
        let filmId = $(this).next().val()
        let date = $(".collection-body-container .form-group-date-seance #date-seance").val()
        let city = localStorage.getItem("cinephoria_city");

        $.ajax({
            type: "POST",
            url: "/films/getSeances",
            dataType: 'json',
            data: {
                filmId: filmId,
                date: date,
                city: city,
            },
            success: function(responseData){
                    let dataResponse = JSON.parse(responseData)
                let film = dataResponse.film
                let seances = film.seances

                if(date !== '') {
                    let dateArr = date.split('-')
                    let newDate = dateArr[2] + '.' + dateArr[1] + '.' + dateArr[0]
                    $(".modal-seance-details .modal-seance-info").remove()
                    $(".modal-seance-details .modal-seance-places").remove()
                    $(".modal-seance-details").append(
                        `
        <div class="modal-seance-info">
            <div class="modal-seance-info-column">
                <div><span class="title-detail-reservation">FILM :</span> <span class="seance-info-date">${film.title} (${film.year})</span></div>
                <div><span class="title-detail-reservation">DATE :</span> <span class="seance-info-hour">${newDate}</span></div>
            </div>
        </div>
        <div class="modal-seance-places">
            <div>SÉANCES DISPONIBLES :</div>
            <div class="modal-places">
                <table class="table" id="collectionFilmSeances">
                    <thead>
                    <tr>
                        <th>DATE</th>
                        <th>HEURE</th>
                        <th>QUALITÉ</th>
                        <th>PRIX</th>
                    </tr>
                    </thead>
                    <tbody>
                   </tbody>
                    </table>
                    </div>
                    </div>
                        `
                    )


                    for (let seance of seances){
                        $(".modal-seance-details table tbody").append(
                            `
                                <tr>
                                    <td>${seance.date}</td>
                                    <td>${seance.heureDebut}-${seance.heureFin}</td>
                                    <td>${seance.format}</td>
                                    <td>${seance.prix}€</td>
                                </tr>
                            `
                        )
                    }

                } else {
                    $(".modal-seance-details .modal-seance-info").remove()
                    $(".modal-seance-details .modal-seance-places").remove()
                    $(".modal-seance-details").append(
                        `
        <div class="modal-seance-info">
            <div class="modal-seance-info-column">
                <div><span class="title-detail-reservation">FILM :</span> <span class="seance-info-date">${film.title} (${film.year})</span></div>
                <div><span class="title-detail-reservation">DATE :</span> <span class="seance-info-hour">Toutes les dates</span></div>
            </div>
        </div>
        <div class="modal-seance-places">
            <div>SÉANCES DISPONIBLES :</div>
            <div class="modal-places">
                <table class="table" id="collectionFilmSeances">
                    <thead>
                    <tr>
                        <th>DATE</th>
                        <th>HEURE</th>
                        <th>QUALITÉ</th>
                        <th>PRIX</th>
                    </tr>
                    </thead>
                    <tbody>
                     </tbody>
                    </table>
                    </div>
                    </div>
                        `
                    )

                    for (let seance of seances){
                        $(".modal-seance-details table tbody").append(
                            `
                                <tr>
                                    <td>${seance.date}</td>
                                    <td>${seance.heureDebut}-${seance.heureFin}</td>
                                    <td>${seance.format}</td>
                                    <td>${seance.prix}€</td>
                                </tr>
                            `
                        )
                    }
                }

                $(".collection-body-container .modal-seance-details").css('display', 'flex')
            }
        })
    })

    $(".button-seances-close").click(function(){
        $(".collection-body-container .modal-seance-details").css('display', 'none')
    })

    $(".dt-search input").click(function(){
        let date = $(".collection-body-container .form-group-date-seance #date-seance").val("")

        $(".collection-body-container #genres_select option").each(function(){
            if ($(this).val() == 'all') {
                $(this).parent().val($(this).val())
                $(this).attr("selected", true)
            } else {
                $(this).attr("selected", false)
            }
        })
    })

    if(typeof $(".js-dashboard").attr("data-dashboard") !== 'undefined'){
        let dataDashboard = JSON.parse($(".js-dashboard").attr("data-dashboard"))

        var ctx = $("#dashboard-chart")
        const data = Object.entries(dataDashboard).map(([name, obj]) => ({ name, ...obj }))

        const chartAreaBorder = {
            id: 'chartAreaBorder',
            beforeDraw(chart, args, options) {
                const {ctx, chartArea: {left, top, width, height}} = chart;
                ctx.save();
                ctx.strokeStyle = options.borderColor;
                ctx.lineWidth = options.borderWidth;
                ctx.setLineDash(options.borderDash || []);
                ctx.lineDashOffset = options.borderDashOffset;
                ctx.strokeRect(left, top, width, height);
                ctx.restore();
            }
        };

        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(row => row.date),
                datasets: [
                    {
                        label: 'Nombre de réservations par jour',
                        data: data.map(row => row.nmrReservations),
                        borderColor: 'rgb(236, 65, 134)',
                    }
                ],
            },
            options: {
                plugins: {
/*                    chartAreaBorder: {
                        borderColor: 'rgb(236, 65, 134)',
                        borderWidth: 1,
                        borderDashOffset: 0,
                    },*/
                }
            },
            plugins: [chartAreaBorder]
        });
    }

})
