<script>
window.addEventListener('load', function () {

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID') + ',-';
    }

    function filterTable() {
        var searchValue = $('#search-wbp').val().toLowerCase().trim();
        var statusValue = $('#filter-status').val();

        var totalTertagih = 0;
        var totalTerbayar = 0;

        var groups = [];
        $('.wbp-row').each(function () {
            var grp = $(this).data('group');
            if (grp && groups.indexOf(grp) === -1) groups.push(grp);
        });

        groups.forEach(function (grp) {
            var rows = $('.wbp-row[data-group="' + grp + '"]');
            var namaNama = rows.first().data('nama') || '';
            var matchSearch = namaNama.indexOf(searchValue) !== -1;

            if (!matchSearch) {
                rows.each(function () {
                    $(this).hide();
                    $(this).find('.rs-cell').hide();
                });
                return;
            }

            var visibleRows = [];

            rows.each(function () {
                var row = $(this);
                var statusRow = row.data('status') || '';
                var matchStatus = (statusValue === 'Semua' || statusRow === statusValue);

                row.find('.rs-cell').hide().attr('rowspan', 1);

                if (matchStatus) {
                    row.show();
                    visibleRows.push(row);

                    var rowTotal = parseInt(row.data('total')) || 0;
                    if (statusRow === 'Tertagih') totalTertagih += rowTotal;
                    else totalTerbayar += rowTotal;
                } else {
                    row.hide();
                }
            });

            if (visibleRows.length > 0) {
                visibleRows[0].find('.rs-cell')
                    .attr('rowspan', visibleRows.length)
                    .show();
            }
        });

        $('#footer-tertagih').text(formatRupiah(totalTertagih));
        $('#footer-terbayar').text(formatRupiah(totalTerbayar));
    }

    $('#search-wbp').on('input', filterTable);
    $('#filter-status').on('change', filterTable);
    filterTable();

});

// Eksekusi Pembayaran - di luar window.addEventListener karena dipanggil dari onclick HTML
function proses_bayar(kode_tahanan, nama_wbp) {
    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: 'Tandai semua tagihan ' + nama_wbp + ' sebagai Terbayar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Bayar!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('my_control/bayar_tagihan_rumbang') ?>",
                data: { kode_tahanan: kode_tahanan },
                dataType: "JSON",
                success: function(response) {
                    if(response.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.msg,
                            confirmButtonColor: '#17a2b8'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', response.msg, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                }
            });
        }
    });
}
</script>