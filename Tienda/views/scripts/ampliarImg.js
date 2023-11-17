document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".ampliarImg").forEach((elemento) => {
        elemento.addEventListener("click", () => ampliar(elemento));
    });
});

function ampliar(elemento) {
    const estilo = window.getComputedStyle(elemento);
    const capa = document.createElement("div");
    capa.appendChild(document.createElement("img"));
    capa.className = "capa";
    document.body.appendChild(capa);
    if (elemento != document.querySelector(".capa img")) {
        setTimeout(() => {
            capa.classList.add("activo");
        }, 0.1);
        capa.style.setProperty("display", "flex");
        capa.firstElementChild.setAttribute("src", elemento.getAttribute("src"));
        capa.firstElementChild.style.setProperty("width", 1240 + "px");
        capa.firstElementChild.style.setProperty("height", 720 + "px");
        document.body.style.setProperty("overflow", "hidden");
        capa.addEventListener("click", function (event) {
            if (event.target == this) {
                this.classList.remove("activo");
                setTimeout(() => {
                    document.body.removeChild(this);
                    document.body.style.removeProperty("overflow");
                }, 1000);
            }
        })
    }
}