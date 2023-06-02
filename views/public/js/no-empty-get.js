window.addEventListener("load", function() {
    const FORM_ELEMENT = document.querySelector("form[data-prevent-empty-get='true']");
    FORM_ELEMENT.addEventListener("formdata", function (event) {
        let oFormData = event.formData;
        for (let [sKey, sValue] of Array.from(oFormData.entries())) {
            if (sValue === "") {
                oFormData.delete(sKey);
            }
        }
    });
});

