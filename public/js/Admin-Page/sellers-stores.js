document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".category-btns button");
    const contents = document.querySelectorAll(".category-content");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            const target = btn.getAttribute("data-target");

            contents.forEach(content => {
                content.classList.remove("active");
            });

            document.querySelector(`.category-content.${target}`).classList.add("active");

            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
        });
    });
});