
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
        if ($(this).next().css('display') == "none"){
            $(this).next().css('display', "block")
            $(this).next().css('overflow', "overlay")
            $(this).next().css('position', "absolute")
            $(this).next().css('width', "33.7%")
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


})
