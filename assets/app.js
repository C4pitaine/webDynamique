/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/bootstrap.min.css';

import 'bootstrap/dist/js/bootstrap.bundle';

// Gestion Menu Burger
const menuBurger = document.querySelector('#burger')
const header = document.querySelector('header')
const menu = document.querySelector('#menu')
const menuA = document.querySelectorAll('#menu a')

menuBurger.addEventListener('click',()=>{
    header.classList.toggle('menuOpened')
    menu.classList.toggle('menuOpened')
})

menuA.forEach(()=>{
    menu.classList.remove('menuOpened')
})

