document.addEventListener("DOMContentLoaded", () => {
    const date = new Date();
    const bgpic = document.querySelector(".bgpic");
    const close = document.querySelector(".close a");
    const fileInput = document.getElementById("fileInput");
    const bgImg = document.getElementById("bg");
    const options = { month: "long", day: "numeric", year: "numeric" };
    const formattedDate = date.toLocaleDateString("en-US", options);
    const dateSpan = document.getElementById("dateToday");
    const monthPicker = document.getElementById("monthPicker");
    const rangeDisplay = document.getElementById("rangeDisplay");

    fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                bgImg.src = e.target.result;
                bgpic.classList.add("active");
            };
            reader.readAsDataURL(file);
        }
    });

    close.addEventListener("click", (e) => {
        e.preventDefault();
        bgpic.classList.remove("active");
    });

    if (dateSpan) {
        dateSpan.textContent = formattedDate;
    }
    monthPicker.addEventListener("change", () => {
        const value = monthPicker.value;
        if (value) {
            const [year, month] = value.split("-");
            const firstDay = new Date(year, month - 1, 1);
            const lastDay = new Date(year, month, 0);

            const options = { month: "short", day: "numeric", year: "numeric" };
            const startText = firstDay.toLocaleDateString("en-US", options);
            const endText = lastDay.toLocaleDateString("en-US", options);

            rangeDisplay.textContent = `${startText} - ${endText}`;
        }
    });
});
