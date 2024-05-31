// on crée un nouveau fichier car si on le met dans app.js il fera tout ce qu'y est mit ici à chaque fois et pas uniquement dans les fichiers voulu
const addExosMuscu = document.querySelector('#exos-muscu')
addExosMuscu.addEventListener('click',()=>{
    // compter combien j'ai d form-group pour les indices ex: annonce_image_0_url
    const widgetCounter = document.querySelector("#widgets-counter")
    const index = +widgetCounter.value // le + permet de transformer en nombre, value rends tjrs un string 
    const exosMusculations = document.querySelector("#seance_exosMusculations")
    // recup le prototype dans la div

    const prototypeMuscu = exosMusculations.dataset.prototype.replace(/__name__/g, index) // drapeau g pour indiqur que l'on va le faire plusieurs fois 
    exosMusculations.insertAdjacentHTML('beforeend', prototypeMuscu)
    widgetCounter.value = index+1
    handleDeleteButtons() //  pour mettre à jour la table deletes
})

const addExosCardio = document.querySelector('#exos-cardio')
addExosCardio.addEventListener('click',()=>{
    // compter combien j'ai d form-group pour les indices ex: annonce_image_0_url
    const widgetCounter = document.querySelector("#widgets-counter")
    const index = +widgetCounter.value // le + permet de transformer en nombre, value rends tjrs un string 
    const exosCardios = document.querySelector("#seance_exosCardios")
    // recup le prototype dans la div

    const prototypeCardio = exosCardios.dataset.prototype.replace(/__name__/g, index) // drapeau g pour indiqur que l'on va le faire plusieurs fois 
    exosCardios.insertAdjacentHTML('beforeend', prototypeCardio)
    widgetCounter.value = index+1
    handleDeleteButtons() //  pour mettre à jour la table deletes
})

const updateCounter = () => {
    const count = document.querySelectorAll("#annonce_images div.form-group").length
    document.querySelector("#widgets-counter").value = count 
}

const handleDeleteButtons = () => {
    let deletes = document.querySelectorAll("button[data-action='delete']")
    deletes.forEach(button => {
        button.addEventListener('click', ()=>{
            const target = button.dataset.target
            const elementTarget = document.querySelector(target)
            if(elementTarget){
                elementTarget.remove() // supprimer l'éléménet
            }
        })
    })

}

updateCounter()
handleDeleteButtons()

// Permet de remonter jusqu'en haut de la page
const scrollTop = document.querySelector('.scrollTop')

scrollTop.addEventListener('click',()=>{
    window.scrollTo({
        top:0,
        left:0,
        behavior: "smooth"
    })
})