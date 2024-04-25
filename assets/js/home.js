const scrollTop = document.querySelector('.scrollTop')

scrollTop.addEventListener('click',()=>{
    window.scrollTo({
        top:0,
        left:0,
        behavior: "smooth"
    })
})