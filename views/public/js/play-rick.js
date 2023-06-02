window.addEventListener("load", function () {
    const MAIN_ELEMENT = document.querySelector("main");
    const RICK = {
        ELEMENT: this.document.querySelector("#rick-astley"),
        SIZE_MIN: 50,
        SIZE_CUR: Math.min(this.window.innerWidth, this.window.innerHeight),
        TIME: 15 * 1000
    };

    MAIN_ELEMENT.style.backgroundImage = `url("${this.window.location.protocol + "//" + this.window.location.host}/views/public/troll/rick_astley.gif")`;
    MAIN_ELEMENT.style.backgroundPosition = "center";
    MAIN_ELEMENT.style.backgroundSize = RICK.size + "px";

    // RICK.ELEMENT.play();

    MAIN_ELEMENT.animate([
        {backgroundSize: `${RICK.SIZE_CUR}px`},
        {backgroundSize: `${RICK.SIZE_MIN}px`}
    ], {
        duration: RICK.TIME,
        fill: "forwards",
        easing: "cubic-bezier(0.25,0,0.5,1)"
    });

});
