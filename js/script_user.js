// akun.php
// ================================================================================
const newPasswordButton = document.getElementById("newPasswordButton");
const newPasswordInput = document.getElementById("newPasswordInput");
const newPasswordEyeIcon = document.getElementById("newPasswordEyeIcon");
const confirmPasswordButton = document.getElementById("confirmPasswordButton");
const confirmPasswordInput = document.getElementById("confirmPasswordInput");
const confirmPasswordEyeIcon = document.getElementById(
    "confirmPasswordEyeIcon"
);

newPasswordButton.addEventListener("click", () => {
    if (newPasswordInput.type === "password") {
        newPasswordInput.type = "text";
        newPasswordEyeIcon.classList.remove("fas fa-eye"); // belum ketemu permasalahannya
        newPasswordEyeIcon.classList.add("fas fa-eye-slash");
    } else {
        newPasswordInput.type = "password";
        newPasswordEyeIcon.classList.remove("fas fa-eye-slash");
        newPasswordEyeIcon.classList.add("fas fa-eye");
    }
});

confirmPasswordButton.addEventListener("click", () => {
    if (confirmPasswordInput.type === "password") {
        confirmPasswordInput.type = "text";
        confirmPasswordEyeIcon.classList.remove("fas fa-eye");
        confirmPasswordEyeIcon.classList.add("fas fa-eye-slash");
    } else {
        confirmPasswordInput.type = "password";
        confirmPasswordEyeIcon.classList.remove("fas fa-eye-slash");
        confirmPasswordEyeIcon.classList.add("fas fa-eye");
    }
});

// buat_pesanan_keranjang.php
// ================================================================================
function updateHarga() {
    const subtotalValue = document.querySelector("#subtotalValue").value;
    const subtotal = document.querySelector("#subtotal");
    const jasaPengiriman = document.querySelector("#jasaPengiriman");
    const metodePembayaran = document.querySelector("#metodePembayaran");
    const feePembayaran = parseInt(
        metodePembayaran.options[metodePembayaran.selectedIndex].getAttribute(
            "data-fee"
        )
    );
    const ongkir = parseInt(
        jasaPengiriman.options[jasaPengiriman.selectedIndex].getAttribute(
            "data-fee"
        )
    );

    const feePembayaranValue = isNaN(feePembayaran) ? 0 : feePembayaran;
    const ongkirValue = isNaN(ongkir) ? 0 : ongkir;

    let subtotalInt = parseInt(subtotalValue);
    let totalPembayaran = subtotalInt + feePembayaranValue + ongkirValue;

    document.getElementById("feePembayaran").textContent = `Rp ${formatHarga(
        feePembayaranValue
    )}`;
    document.getElementById("ongkir").textContent = `Rp ${formatHarga(
        ongkirValue
    )}`;
    document.getElementById("totalPembayaran").textContent = `Rp ${formatHarga(
        totalPembayaran
    )}`;
}

function formatHarga(harga) {
    return harga.toLocaleString("id-ID");
}

updateHarga();
document
    .querySelector("#jasaPengiriman")
    .addEventListener("change", updateHarga);
document
    .querySelector("#metodePembayaran")
    .addEventListener("change", updateHarga);
// ================================================================================