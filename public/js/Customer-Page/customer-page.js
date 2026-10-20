document.addEventListener("DOMContentLoaded", () => {
    const menuIcon = document.getElementById("menuIcon");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (!(menuIcon instanceof HTMLButtonElement) || !(dropdownMenu instanceof HTMLElement)) {
        return;
    }

    const closeMenu = () => {
        dropdownMenu.classList.remove("is-open");
        menuIcon.setAttribute("aria-expanded", "false");
        menuIcon.setAttribute("aria-label", "Open account menu");
    };

    menuIcon.addEventListener("click", () => {
        const isExpanded = menuIcon.getAttribute("aria-expanded") === "true";

        dropdownMenu.classList.toggle("is-open", !isExpanded);
        menuIcon.setAttribute("aria-expanded", String(!isExpanded));
        menuIcon.setAttribute("aria-label", isExpanded ? "Open account menu" : "Close account menu");
    });

    document.addEventListener("click", (event) => {
        if (event.target instanceof Node && !menuIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
            closeMenu();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && menuIcon.getAttribute("aria-expanded") === "true") {
            closeMenu();
            menuIcon.focus();
        }
    });
});