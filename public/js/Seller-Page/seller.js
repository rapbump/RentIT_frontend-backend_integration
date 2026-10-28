document.addEventListener("DOMContentLoaded", () => {
    const date = new Date();
    const backgroundInput = document.getElementById("store-profile-background");
    const avatarInput = document.getElementById("store-profile-avatar");
    const bgImg = document.getElementById("bg");
    const avatarImg = document.getElementById("avatar");
    const options = { month: "long", day: "numeric", year: "numeric" };
    const formattedDate = date.toLocaleDateString("en-US", options);
    const dateSpan = document.getElementById("dateToday");
    const monthPicker = document.getElementById("monthPicker");
    const rangeDisplay = document.getElementById("rangeDisplay");

    if (backgroundInput && bgImg) {
        backgroundInput.addEventListener("change", () => {
            const file = backgroundInput.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                if (typeof event.target?.result === "string") {
                    bgImg.src = event.target.result;
                }
            };
            reader.readAsDataURL(file);
        });
    }

    if (avatarInput && avatarImg) {
        avatarInput.addEventListener("change", () => {
            const file = avatarInput.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                if (typeof event.target?.result === "string") {
                    avatarImg.src = event.target.result;
                }
            };
            reader.readAsDataURL(file);
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
