const viewButtons =
document.querySelectorAll(".view-btn");

viewButtons.forEach(button => {

    button.addEventListener("click", () => {

        viewButtons.forEach(btn => {
            btn.classList.remove("active");
        });

        button.classList.add("active");

    });

});