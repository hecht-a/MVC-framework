const bar = document.querySelector(".bar");

bar.addEventListener("click", (e) => {
    const menu = document.querySelector(".menu-collapsed");
    const img = bar.querySelector("img")
    menu.classList.toggle("menu-expanded");
    if (menu.classList.contains("menu-expanded")) {
        img.src = "/assets/close.svg"
    } else {
        img.src = "/assets/burger.svg"
    }
});