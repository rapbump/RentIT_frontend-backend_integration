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

    if (fileInput && bgpic && bgImg) {
        fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                if (typeof event.target?.result === "string") {
                    bgImg.src = event.target.result;
                    bgpic.classList.add("active");
                }
            };
            reader.readAsDataURL(file);
        });
    }

    if (close && bgpic) {
        close.addEventListener("click", (event) => {
            event.preventDefault();
            bgpic.classList.remove("active");
        });
    }

    if (dateSpan) {
        dateSpan.textContent = formattedDate;
    }

    if (monthPicker && rangeDisplay) {
        monthPicker.addEventListener("change", () => {
            const value = monthPicker.value;
            if (value) {
                const [year, month] = value.split("-");
                const firstDay = new Date(year, month - 1, 1);
                const lastDay = new Date(year, month, 0);
                const rangeOptions = { month: "short", day: "numeric", year: "numeric" };
                const startText = firstDay.toLocaleDateString("en-US", rangeOptions);
                const endText = lastDay.toLocaleDateString("en-US", rangeOptions);

                rangeDisplay.textContent = `${startText} - ${endText}`;
            }
        });
    }
});
