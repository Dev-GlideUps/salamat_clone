document.addEventListener("DOMContentLoaded", function() {
    var canvas = document.querySelector("#signature_pad");
    var canvas1 = document.querySelector("#signature_pad1");
    var signaturePad = new SignaturePad(canvas, {
        minWidth: 2,
        maxWidth: 4,
    });
    var signaturePad1 = new SignaturePad(canvas1, {
        minWidth: 2,
        maxWidth: 4,
    });

    function resizeCanvas() {
        var ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        canvas1.width = canvas1.offsetWidth * ratio;
        canvas1.height = canvas1.offsetHeight * ratio;
        canvas1.getContext("2d").scale(ratio, ratio);
        let storedData = signaturePad.toData();
        let storedData1 = signaturePad1.toData();
        signaturePad.clear(); // otherwise isEmpty() might return incorrect value
        signaturePad.fromData(storedData);
        signaturePad1.clear(); // otherwise isEmpty() might return incorrect value
        signaturePad1.fromData(storedData1);
    }

    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    var clearButton = document.querySelector("#clear_button");
    clearButton.addEventListener("click", function() {
        signaturePad.clear();
    });

    var clearButton1 = document.querySelector("#clear_button1");
    clearButton.addEventListener("click", function() {
        signaturePad1.clear();
    });

    var finishButton = document.querySelector("#save-consent");
    finishButton.addEventListener("click", function() {
        const svgDataUrl = signaturePad.toDataURL("image/svg+xml");
        const svgDataUrl1 = signaturePad1.toDataURL("image/svg+xml");
        let patient_id = document.getElementById('patientconsent-patient_id').value;


        if (svgDataUrl !== '' && svgDataUrl.length > 500 && patient_id !== '') {
            document.getElementById('patientconsent-signature').value = svgDataUrl;
            document.getElementById('patientconsent-doctor_signature').value = svgDataUrl1;
            document.getElementById('w0').submit();
        } else {
            if (patient_id == '') {
                alert('Please Select A Patient');
            } else {
                alert('Please Sign The Consent!');
            }

        }

    });

});

// function saveConsent () {
//     alert('reached here');
//
// }