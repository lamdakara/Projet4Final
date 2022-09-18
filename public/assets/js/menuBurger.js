const mainMenu = document.querySelector('#navMobile');
const closeMenu = document.querySelector('#crossIcon');
const openMenu = document.querySelector('#burgerMenu');
const menu_items = document.querySelectorAll('#navMobile a');

// Ouverte/fermeture par événement dû au clic
openMenu.addEventListener('click', show);
closeMenu.addEventListener('click', close);

// Ferme le menu quand on clique sur un lien
menu_items.forEach(item => {
    item.addEventListener('click', function () {
        close();
    })
})

// Ouvre le menu avec l'icone burger
function show() {
    mainMenu.style.display = 'flex';
}

// Ferme le menu avec l'icone croix
function close() {
    mainMenu.style.display = 'none';
}