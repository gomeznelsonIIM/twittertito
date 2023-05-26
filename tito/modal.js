// Variables pour récupérer les class
let modal = document.querySelector(".modal")
let popup_container = document.querySelector(".popup_container")

// Fait appraître le pop quand cliqué dessus
modal.addEventListener("click", function() {
    popup_container.classList.toggle("active")
} )