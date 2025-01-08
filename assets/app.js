
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

    function changeTag(tag){
        if(tag.length > 0) {
            $(".custom-tags").append('<span>'+tag+'</span>')
            let currentValue = $("input#room_specialPlaces").val()
            if(currentValue.length>0){
                $("input#room_specialPlaces").val(currentValue + ',' + tag)
            } else {
                $("input#room_specialPlaces").val(tag)
            }
            $("input#input-tag-custom").val('')
        }
    }

    $("input#input-tag-custom").on("keypress", function(e){
        if(e.which == 44) {
            let seats = $(this).val().split(',')

            changeTag(seats[seats.length -1])
        }
    })

    $("input#input-tag-custom").on("keyup", function(e){
        if(e.which == 8) {
            let currentValue = $("input#input-tag-custom").val()
            if(currentValue.length == 0) {
                let value = $("input#room_specialPlaces").val().split(',')
                    value = value.slice(0, -1)
                if(value.length >= 1) {
                    $("input#room_specialPlaces").val(value)
                } else {
                    $("input#room_specialPlaces").val('')
                }

                $(".custom-tags > :last-child").remove()
            }

        }
    })
/*    $("input#room_specialPlaces").on('change', function)*/
})
