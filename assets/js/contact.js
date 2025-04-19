const mailCard = document.getElementById('mail-card');
const mailText = document.getElementById('mail-text');
mailCard.addEventListener('mouseenter', function () {
    mailText.innerHTML = '<div class="fs-5 text-active mt-1">darylfarber@icloud.com</div>';
});
mailCard.addEventListener('mouseleave', function () {
    mailText.innerHTML = '<i class="fa-solid fa-envelope fs-1 text-active"></i>';
});