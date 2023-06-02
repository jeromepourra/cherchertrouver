window.addEventListener("load", function() {
    let RATE_ELEMENTS = document.querySelectorAll(".on-rate");
    RATE_ELEMENTS.forEach(function(element, index) {
        element.addEventListener("mouseover", function() {
            for(let i=index; i>=0; i--) {
                RATE_ELEMENTS[i].classList.add("on-rate-active");
            }
        });
        element.addEventListener("mouseleave", function() {
            for(let i=index; i>=0; i--) {
                RATE_ELEMENTS[i].classList.remove("on-rate-active");
            }
        });
    });
});

