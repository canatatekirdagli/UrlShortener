const modalBtn1 = document.getElementById("istatistik");
const modal1 = document.querySelector(".modal1");
const modalKapat1 = document.getElementById("modal-kapat1");

modalBtn1.addEventListener("click", () => {
    modal1.style.display = "flex";
});

modalKapat1.addEventListener("click", () => {
    modal1.style.display = "none";
});


const modalBtn = document.getElementById("uyelink");
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

kısattmodal.addEventListener("click", () => {
    kısattmodal2.style.display="flex";
});




function kopyala(){
    var metin = document.getElementById("url");
    metin.select();
    document.execCommand("copy");
    alert("URL KOPYALANDI");
}