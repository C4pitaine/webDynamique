const addExosMuscu = document.querySelector('#exos-muscu')
addExosMuscu.addEventListener('click',()=>{
    const widgetCounter = document.querySelector("#widgets-counter")
    const index = +widgetCounter.value
    const exosMusculations = document.querySelector("#seance_exosMusculations")

    const prototypeMuscu = exosMusculations.dataset.prototype.replace(/__name__/g, index)
    exosMusculations.insertAdjacentHTML('beforeend', prototypeMuscu)
    widgetCounter.value = index+1
    handleDeleteButtons()
})

const addExosCardio = document.querySelector('#exos-cardio')
addExosCardio.addEventListener('click',()=>{
    const widgetCounter = document.querySelector("#widgets-counter")
    const index = +widgetCounter.value
    const exosCardios = document.querySelector("#seance_exosCardios")

    const prototypeCardio = exosCardios.dataset.prototype.replace(/__name__/g, index)
    exosCardios.insertAdjacentHTML('beforeend', prototypeCardio)
    widgetCounter.value = index+1
    handleDeleteButtons()
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
                elementTarget.remove()
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