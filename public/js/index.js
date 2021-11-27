const bar = document.querySelector(".bar");
const buttons = document.querySelectorAll(".button:not(.button:disabled)");
console.log(buttons)

bar.addEventListener("click", () => {
    const menu = document.querySelector(".menu-collapsed");
    const img = bar.querySelector("img")
    menu.classList.toggle("menu-expanded");
    if (menu.classList.contains("menu-expanded")) {
        img.src = "/assets/close.svg"
    } else {
        img.src = "/assets/burger.svg"
    }
});

buttons.forEach((button) => {
    button.addEventListener("click", () => {
        const consultId = button.getAttribute("data-consultid");
        if (button.classList.contains("delete")) {
            window.location.href = `/profile/consultation/delete/${consultId}`;
            console.log(button);
        }
        if (button.classList.contains("edit")) {
            window.location.href = `/profile/consultation/edit/${consultId}`;
            console.log(button);
        }
    });
});

const itemsContainer = document.querySelectorAll(".items__container");
const arrows = document.querySelectorAll(".fas");

function toggleArrow(itemNumber) {
    const arrow = arrows[itemNumber];
    arrow.classList.toggle("fa-sort-up");
    arrow.classList.toggle("fa-sort-down");
}

itemsContainer.forEach((item, i) => {
    item.querySelector(".title__container").addEventListener("click", () => {
        toggleArrow(i);
        item.classList.toggle("hidden");
    });
});