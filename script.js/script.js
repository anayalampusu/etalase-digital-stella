document.addEventListener("DOMContentLoaded", function () {
    // Konfirmasi sebelum menghapus data
    const deleteButtons = document.querySelectorAll(".btn-delete");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                e.preventDefault();
            }
        });
    });
});