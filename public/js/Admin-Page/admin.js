document.addEventListener("DOMContentLoaded", () => {
    const date = new Date();
    const options = { month: "long", day: "numeric", year: "numeric" };
    const formattedDate = date.toLocaleDateString("en-US", options);
    const dateSpan = document.getElementById("dateToday");

    if (dateSpan) {
        dateSpan.textContent = formattedDate;
    }
});