@extends('layouts.app')
<title>Kelola Slot Pengiriman</title>

@section('content')
    <div class="container-fluid">
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title mb-30">
                        <h2>Data Slot Pengiriman</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah -->
        <button type="button" class="btn btn-success mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#addSlotModal">
            <i class="fa fa-plus"></i> Tambah Slot
        </button>

        <!-- Modal Tambah Slot -->
        <div class="modal fade" id="addSlotModal" tabindex="-1" aria-labelledby="addSlotModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('store.slot') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Slot Pengiriman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Bulan dan Tahun<span class="text-red">*</span></label>
                                <input type="month" class="form-control" name="tanggal_pengiriman" required>
                                <div class="form-text">
                                    <small class="text-muted">Pilih bulan dan tahun untuk slot pengiriman</small>
                                </div>
                                @error('tanggal_pengiriman')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slot Pengiriman<span class="text-red">*</span></label>
                                <input type="number" class="form-control" name="slot_pengiriman" min="1" required>
                                <div class="form-text">
                                    <small class="text-muted">Masukkan jumlah slot pengiriman</small>
                                </div>
                                @error('slot_pengiriman')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Slot -->
        <div class="modal fade" id="editSlotModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('edit.slot') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Slot Pengiriman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="slotId" name="id">
                            <div class="mb-3">
                                <label class="form-label">Bulan dan Tahun<span class="text-red">*</span></label>
                                <input type="month" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" required>
                                <div id="edit-error-tanggal_pengiriman" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slot Pengiriman<span class="text-red">*</span></label>
                                <input type="number" class="form-control" id="slot_pengiriman" name="slot_pengiriman" min="1" required>
                                <div id="edit-error-slot_pengiriman" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-success" id="updateSlotBtn">
                                <i class="fa fa-save"></i> Ubah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete -->
        <div class="modal fade" id="deleteSlotModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('delete.slot') }}">
                    @csrf
                    <input type="hidden" name="id" id="delete_slot_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Slot Pengiriman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Yakin ingin menghapus slot pengiriman ini?</p>
                            <div id="delete-details"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash"></i> Ya, Hapus
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="slots-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan Pengiriman</th>
                                <th>Slot Pengiriman</th>
                                <th>Permintaan Kirim</th>
                                <th>Over/Sisa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk memastikan jQuery dan DataTables sudah dimuat -->
    <script>
        // Custom number_format function to mimic PHP's number_format
        function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
            // Convert number to string and handle null/undefined
            number = (number === null || number === undefined) ? 0 : number;
            number = parseFloat(number).toFixed(decimals);
            let parts = number.split('.');
            let integer = parts[0];
            let decimal = parts[1] || '';
            
            // Add thousand separators
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            
            // Return formatted number
            return decimals > 0 ? integer + dec_point + decimal : integer;
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery === 'undefined') {
                console.error('jQuery tidak ditemukan. Pastikan jQuery sudah dimuat di layout.');
                return;
            }

            if (typeof jQuery.fn.DataTable === 'undefined') {
                console.error('DataTables tidak ditemukan. Pastikan DataTables sudah dimuat di layout.');
                return;
            }

            jQuery(document).ready(function($) {
                var dataTable = $('#slots-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('manage.slot') }}",
                        type: 'GET',
                        error: function(xhr, error, code) {
                            console.error('DataTables Ajax Error:', error);
                        }
                    },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tanggal_pengiriman',
                            name: 'tanggal_pengiriman',
                            render: function(data) {
                                return data ? moment(data).format('MMMM YYYY') : 'N/A';
                            }
                        },
                        {
                            data: 'slot_pengiriman',
                            name: 'slot_pengiriman',
                            render: function(data) {
                                return data ? number_format(data, 0, ',', '.') : '0';
                            }
                        },
                        {
                            data: 'permintaan_kirim',
                            name: 'permintaan_kirim',
                            render: function(data) {
                                return data ? number_format(data, 0, ',', '.') : '0';
                            }
                        },
                        {
                            data: 'over_sisa',
                            name: 'over_sisa',
                            render: function(data) {
                                return data !== null ? number_format(data, 0, ',', '.') : 'N/A';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        processing: "Memuat...",
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        loadingRecords: "Memuat...",
                        zeroRecords: "Tidak ada data yang cocok",
                        emptyTable: "Tidak ada data tersedia",
                        paginate: {
                            first: "Pertama",
                            previous: "Sebelumnya",
                            next: "Selanjutnya",
                            last: "Terakhir"
                        }
                    }
                });

                $(document).on('click', '.editBtn', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const id = $btn.data('id');

                    console.log('Edit button clicked for ID:', id);

                    $('.text-danger').hide();

                    const buttonData = {
                        id: $btn.data('id'),
                        tanggal_pengiriman: $btn.data('tanggal_pengiriman'),
                        slot_pengiriman: $btn.data('slot_pengiriman')
                    };

                    console.log('Button data:', buttonData);

                    if (buttonData.id && buttonData.tanggal_pengiriman && buttonData.slot_pengiriman) {
                        console.log('Using button data - all fields present');
                        populateEditModal(buttonData);
                        $('#editSlotModal').modal('show');
                        return;
                    }

                    console.log('Button data incomplete, trying Ajax...');

                    showLoadingInModal();
                    $('#editSlotModal').modal('show');

                    $.ajax({
                        url: `/admin/slot/${id}/detail`,
                        type: 'GET',
                        timeout: 10000,
                        success: function(response) {
                            console.log('Ajax success:', response);
                            hideLoadingInModal();

                            if (response.success && response.data) {
                                populateEditModal(response.data);
                            } else {
                                $('#editSlotModal').modal('hide');
                                showAlert('error', 'Gagal memuat data slot: ' + (response.message || 'Data tidak ditemukan'));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax error:', { status: status, error: error, response: xhr.responseText });
                            hideLoadingInModal();
                            $('#editSlotModal').modal('hide');
                            showAlert('error', 'Terjadi kesalahan saat memuat data. Silakan refresh halaman dan coba lagi.');
                        }
                    });
                });

                function showLoadingInModal() {
                    $('#slotId').val('');
                    $('#tanggal_pengiriman').val('');
                    $('#slot_pengiriman').val('');

                    $('.modal-body').prepend(
                        '<div id="loading-message" class="text-center mb-3"><i class="fa fa-spinner fa-spin"></i> Memuat data...</div>'
                    );
                }

                function hideLoadingInModal() {
                    $('#loading-message').remove();
                }

                function populateEditModal(data) {
                    console.log('Populating modal with data:', data);

                    $('#slotId').val(data.id || '');
                    $('#tanggal_pengiriman').val(data.tanggal_pengiriman ? data.tanggal_pengiriman.substring(0, 7) : '');
                    $('#slot_pengiriman').val(data.slot_pengiriman || '');

                    console.log('Modal populated:', {
                        id: $('#slotId').val(),
                        tanggal_pengiriman: $('#tanggal_pengiriman').val(),
                        slot_pengiriman: $('#slot_pengiriman').val()
                    });
                }

                function showAlert(type, message) {
                    const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
                    const alertHtml = `
                        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    $('body').prepend(alertHtml);
                    $('html, body').animate({ scrollTop: 0 }, 500);
                }

                $('#updateSlotBtn').click(function(e) {
                    $(this).closest('form').submit();
                });

                $(document).on('click', '.deleteBtn', function() {
                    const $btn = $(this);
                    const id = $btn.data('id');

                    $('#delete_slot_id').val(id);

                    const deleteDetails = {
                        tanggal_pengiriman: $btn.data('tanggal_pengiriman') ? moment($btn.data('tanggal_pengiriman')).format('MMMM YYYY') : 'N/A',
                        slot_pengiriman: $btn.data('slot_pengiriman') ? number_format($btn.data('slot_pengiriman'), 0, ',', '.') : '0',
                        permintaan_kirim: $btn.data('permintaan_kirim') ? number_format($btn.data('permintaan_kirim'), 0, ',', '.') : '0',
                        over_sisa: $btn.data('over_sisa') !== null ? number_format($btn.data('over_sisa'), 0, ',', '.') : 'N/A'
                    };

                    $('#delete-details').html(`
                        <div class="alert alert-info">
                            <strong>Bulan Pengiriman:</strong> ${deleteDetails.tanggal_pengiriman}<br>
                            <strong>Slot Pengiriman:</strong> ${deleteDetails.slot_pengiriman}<br>
                            <strong>Permintaan Kirim:</strong> ${deleteDetails.permintaan_kirim}<br>
                            <strong>Over/Sisa:</strong> ${deleteDetails.over_sisa}
                        </div>
                    `);

                    $('#deleteSlotModal').modal('show');
                });
            });
        });
    </script>
@endsection