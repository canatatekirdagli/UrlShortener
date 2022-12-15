
const modalBtn1 = document.getElementById("istatistik");
const modal1 = document.querySelector(".modal1");
const modalKapat1 = document.getElementById("modal-kapat1");

modalBtn1.addEventListener("click", () => {
    modal1.style.display = "flex";
});

modalKapat1.addEventListener("click", () => {
    modal1.style.display = "none";
});


const modalBtn = document.getElementById("guncelle");
const modal = document.querySelector(".modal");
const modalKapat = document.getElementById("modal-kapat");

modalBtn.addEventListener("click", () => {
    modal.style.display = "flex";
});

modalKapat.addEventListener("click", () => {
    modal.style.display = "none";
});



const kısattmodal=document.getElementById("kısalt");
const kısattmodal2=document.getElementById("sonuc");
const kısaltmodal1 = document.getElementById("kopyalabuton");

kısattmodal.addEventListener("click", () => {
    kısaltmodal1.style.display = "flex";
    kısattmodal2.style.display="flex";
});

function myFunction() {
    // Get the text field
    var copyText = document.getElementById("sonuc");

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);

    // Alert the copied text
    alert("Kopyalanan metin: " + copyText.value);
}