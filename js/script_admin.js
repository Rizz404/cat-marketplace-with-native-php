// manajemen_user.php
// ================================================================================
const editRoleButtons = document.querySelectorAll("#editRoleButtons");
const editRoleModal = document.querySelector("#editRoleModal");
const editRoleForm = document.querySelector("#editRoleForm");

editRoleButtons.forEach((button) => {
    button.addEventListener("click", () => {
        let role = button.getAttribute("data-role");
        let idUser = button.getAttribute("data-id");

        // Set nilai role pada radio button
        let radioInputs = editRoleForm.querySelectorAll("input[type=radio]");

        radioInputs.forEach((input) => {
            if (input.value.toLowerCase() === role.toLowerCase()) {
                input.checked = true;
            } else {
                input.checked = false;
            }
        });

        // Simpan idUser ke hidden input pada form
        let idUserInput = editRoleForm.querySelector("#idUserInput");
        idUserInput.value = idUser;

        // Tampilkan modal
        let modal = new bootstrap.Modal(editRoleModal);
        modal.show();
    });
});
// ================================================================================

// transaksi.php
// ================================================================================
const detailButtons = document.querySelectorAll("#detailButtons");
const namaUser = document.getElementById("namaUser");
const rasKucing = document.getElementById("rasKucing");
const tanggalTransaksi = document.getElementById("tanggalTransaksi");
const waktuTransaksi = document.getElementById("waktuTransaksi");
const jasaPengiriman = document.getElementById("jasaPengiriman");
const metodePembayaran = document.getElementById("metodePembayaran");
const noWallet = document.getElementById("noWallet");
const alamat = document.getElementById("alamat");
const totalHarga = document.getElementById("totalHarga");
const proofModal = document.getElementById("transactionProofModal");
const proofImage = document.getElementById("proofImage");
const buktiTransaksiButtons = document.querySelectorAll("#bukti-transaksi");
const downloadButton = document.getElementById("downloadButton");
const downloadTransaksiButton = document.getElementById("downloadModalButton");

buktiTransaksiButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const buktiTransaksi = button.getAttribute("data-bukti-transaksi");
        proofImageElement.src = `../img/proof-transfer/${buktiTransaksi}`;
        downloadButton.href = `../img/proof-transfer/${buktiTransaksi}`;
        
        let modal = new bootstrap.Modal(proofModal);
        modal.show();
    });
});

detailButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const namaUserAttribute = button.getAttribute("data-nama-user");
        const rasKucingAttribute = button.getAttribute("data-ras-kucing");
        const tanggalTransaksiAttribute = button.getAttribute("data-tanggal-transaksi");
        const waktuTransaksiAttribute = button.getAttribute("data-waktu-transaksi");
        const jasaPengirimanAttribute = button.getAttribute("data-jasa-pengiriman");
        const metodePembayaranAttribute = button.getAttribute("data-metode-pembayaran");
        const noWalletAttribute = button.getAttribute("data-no-wallet");
        const alamatAttribute = button.getAttribute("data-alamat");
        const totalHargaAttribute = parseInt(button.getAttribute("data-total-harga"));

        const formattedTotalHarga = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(totalHargaAttribute);

        namaUser.textContent = namaUserAttribute;
        rasKucing.textContent = rasKucingAttribute;
        tanggalTransaksi.textContent = tanggalTransaksiAttribute;
        waktuTransaksi.textContent = waktuTransaksiAttribute;
        jasaPengiriman.textContent = jasaPengirimanAttribute;
        metodePembayaran.textContent = metodePembayaranAttribute;
        noWallet.textContent = noWalletAttribute;
        alamat.textContent = alamatAttribute;
        totalHarga.textContent = formattedTotalHarga;

        downloadTransaksiButton.addEventListener("click", () => {
            // Ambil elemen modal
            const transactionModal =
                document.getElementById("transactionModal");

            // Sembunyikan modal footer sebelum melakukan konversi
            const downloadFooter = transactionModal.querySelector(
                "#downloadModalButton"
            );
            downloadFooter.style.display = "none";

            // Konversi modal menjadi gambar dengan menggunakan html2canvas
            html2canvas(transactionModal).then(function (canvas) {
                // Buat link untuk mendownload gambar
                const link = document.createElement("a");
                link.href = canvas.toDataURL("image/jpeg"); // Mengubah gambar menjadi format JPEG
                link.download = "maxwellcat-proof.jpg"; // Nama file yang akan di-download

                // Klik link secara otomatis untuk memulai proses download
                link.click();

                downloadFooter.style.display = "block";
            });
        });
    });
});
// ================================================================================
