import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/home.scss';
import './styles/blog.scss';
import './styles/user.scss';
import './styles/account.scss';
import './styles/seance.scss';
import './styles/forum.scss';
import './styles/legals.scss';
import './styles/entrainement.scss';
import './styles/muscle.scss';
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

menuA.forEach((menuA)=>{
    menu.classList.remove('menuOpened')
    menuA.addEventListener('click',()=>{
        menu.classList.remove('menuOpened')
        header.classList.remove('menuOpened')
    })
})

menu.addEventListener('click',()=>{
    menu.classList.remove('menuOpened')
    header.classList.remove('menuOpened')
})

// Taille de l'écran 


// Gestion Alerte de suppresion admin
const deleteButtons = document.querySelectorAll('.deleteButton')
const alertDelete = document.querySelectorAll('.alertDelete')
const annulerDelete = document.querySelectorAll('.annulerDelete')

deleteButtons.forEach((deleteButton,key)=>{
    deleteButton.addEventListener('click',()=>{
        alertDelete[key].classList.add('active')
    })
})

annulerDelete.forEach((annulerDelete,key)=>{
    annulerDelete.addEventListener('click',()=>{
        alertDelete[key].classList.remove('active')
    })
})

// Gestion Alerte de suppresion User
const deleteButtonsUser = document.querySelectorAll('.deleteButtonUser')
const alertDeleteUser = document.querySelectorAll('.alertDeleteUser')
const annulerDeleteUser = document.querySelectorAll('.annulerDeleteUser')

deleteButtonsUser.forEach((deleteButton,key)=>{
    deleteButton.addEventListener('click',()=>{
        alertDeleteUser[key].classList.add('active')
    })
})

annulerDeleteUser.forEach((annulerDelete,key)=>{
    annulerDelete.addEventListener('click',()=>{
        alertDeleteUser[key].classList.remove('active')
    })
})
