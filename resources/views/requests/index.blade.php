@extends('layouts.app')
<title>Kelola Request</title>

@section('content')
    <div class="container-fluid">
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title mb-30">
                        <h2>Data Request</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah -->
        <button type="button" class="btn btn-success mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#addRequestModal">
            <i class="fa fa-plus"></i> Import dari Excel
        </button>

        <!-- Modal Tambah Request -->
        <div class="modal fade" id="addRequestModal" tabindex="-1" aria-labelledby="addRequestModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('store.request') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Request dari Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="excel_file" class="form-label">Upload File Excel<span
                                        class="text-red">*</span></label>
                                <input type="file" class="form-control" name="excel_file" accept=".xlsx,.xls" required>
                                <div class="form-text">
                                    <small class="text-muted">
                                        Format yang didukung: .xlsx, .xls (maksimal 2MB)<br>
                                        <strong>Format Excel yang diperlukan:</strong><br>
                                        - A2: CABANG : [Nama Cabang]<br>
                                        - A3: DIBUAT TGL : [Tanggal]<br>
                                        - A7+: Data unit (sistem akan menghitung otomatis)
                                    </small>
                                </div>
                                @error('excel_file')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Request -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('edit.request') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Request</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="requestId" name="id">
                            <div class="mb-3">
                                <label class="form-label">Cabang<span class="text-red">*</span></label>
                                <select class="form-select" id="branch_id" name="branch_id" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <div id="edit-error-branch_id" class="text-danger small mt-1" style="display: none;"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal<span class="text-red">*</span></label>
                                <input type="date" class="form-control" id="date" name="date" required>
                                <div id="edit-error-date" class="text-danger small mt-1" style="display: none;"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Unit<span class="text-red">*</span></label>
                                <input type="number" class="form-control" id="unit" name="unit" min="1"
                                    required>
                                <div id="edit-error-unit" class="text-danger small mt-1" style="display: none;"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status<span class="text-red">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                    <option value="Diproses">Diproses</option>
                                </select>
                                <div id="edit-error-status" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-success" id="updateBtn">
                                <i class="fa fa-save"></i> Ubah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete -->
        <div class="modal fade" id="deleteRequestModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('delete.request') }}">
                    @csrf
                    <input type="hidden" name="id" id="delete_request_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Request</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Yakin ingin menghapus request ini?</p>
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
                    <table class="display" id="requests-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cabang</th>
                                <th>Tanggal</th>
                                <th>Unit</th>
                                <th>Status</th>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah jQuery sudah dimuat
            if (typeof jQuery === 'undefined') {
                console.error('jQuery tidak ditemukan. Pastikan jQuery sudah dimuat di layout.');
                return;
            }

            // Cek apakah DataTables sudah dimuat
            if (typeof jQuery.fn.DataTable === 'undefined') {
                console.error('DataTables tidak ditemukan. Pastikan DataTables sudah dimuat di layout.');
                return;
            }

            // Gunakan jQuery setelah dipastikan tersedia
            jQuery(document).ready(function($) {
                // Inisialisasi DataTables
                var dataTable = $('#requests-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('manage.request') }}",
                        type: 'GET',
                        error: function(xhr, error, code) {
                            console.error('DataTables Ajax Error:', error);
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'branch_id',
                            name: 'branch.city',
                            render: function(data, type, row) {
                                // Pastikan hanya menampilkan teks cabang, bukan HTML
                                return data || '-';
                            }
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'unit',
                            name: 'unit'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: null,
                            className: "text-center",
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                // Ambil data mentah untuk tombol (tanpa HTML formatting)
                                const cleanId = row.id || '';
                                const cleanBranchId = row.branch_id || '';
                                const cleanDate = row.date || '';
                                const cleanUnit = row.unit || '';
                                // Untuk status, ambil dari data asli jika ada
                                const cleanStatus = (row.action && row.action.status) ? row
                                    .action.status :
                                    (row.status ? row.status.replace(/<[^>]*>/g, '') : '');

                                return `
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary me-1 rounded editBtn" 
                                        data-id="${cleanId}" 
                                        data-branch_id="${cleanBranchId}" 
                                        data-date="${cleanDate}" 
                                        data-unit="${cleanUnit}" 
                                        data-status="${cleanStatus}"
                                        title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger me-1 rounded deleteBtn" 
                                        data-id="${cleanId}"
                                        title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `;
                            }
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

                // Open edit modal - dengan fallback jika Ajax gagal
                $(document).on('click', '.editBtn', function() {
                    const $btn = $(this);
                    const id = $btn.data('id');

                    // Clear previous errors
                    $('.text-danger').hide();

                    // Ambil data dari atribut tombol
                    const fallbackData = {
                        id: id,
                        branch_id: $btn.data('branch_id'),
                        date: $btn.data('date'),
                        unit: $btn.data('unit'),
                        status: $btn.data('status')
                    };

                    console.log('Button data:', fallbackData);

                    // Bersihkan status dari HTML tags jika ada
                    if (fallbackData.status) {
                        fallbackData.status = fallbackData.status.replace(/<[^>]*>/g, '').trim();
                    }

                    // Jika data fallback lengkap, gunakan langsung
                    if (fallbackData.branch_id && fallbackData.date && fallbackData.unit &&
                        fallbackData.status) {
                        console.log('Using fallback data:', fallbackData);
                        populateEditModal(fallbackData);
                        $('#editModal').modal('show');
                        return;
                    }

                    // Jika tidak, coba Ajax
                    $.ajax({
                        url: `/admin/request/${id}/detail`,
                        type: 'GET',
                        timeout: 10000, // 10 detik timeout
                        success: function(response) {
                            console.log('Ajax response:', response);
                            if (response.success) {
                                populateEditModal(response.data);
                                $('#editModal').modal('show');
                            } else {
                                alert('Gagal memuat data request: ' + (response
                                    .message || 'Unknown error'));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax error:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });

                            // Jika Ajax gagal, coba gunakan data dari row tabel
                            const rowData = dataTable.row($btn.parents('tr')).data();
                            if (rowData) {
                                console.log('Using row data as fallback:', rowData);
                                const cleanRowData = {
                                    id: rowData.id,
                                    branch_id: rowData.branch_id,
                                    date: rowData.date,
                                    unit: rowData.unit,
                                    status: rowData.status ? rowData.status.replace(
                                        /<[^>]*>/g, '').trim() : ''
                                };
                                populateEditModal(cleanRowData);
                                $('#editModal').modal('show');
                            } else {
                                alert(
                                    'Terjadi kesalahan saat memuat data. Silakan refresh halaman dan coba lagi.'
                                    );
                            }
                        }
                    });
                });

                // Function untuk populate edit modal
                function populateEditModal(data) {
                    $('#requestId').val(data.id);
                    $('#branch_id').val(data.branch_id);
                    $('#date').val(data.date);
                    $('#unit').val(data.unit);
                    $('#status').val(data.status);
                }

                // Submit edit
                $('#updateBtn').click(function() {
                    const $btn = $(this);
                    const originalText = $btn.html();

                    // Disable button dan ubah text
                    $btn.prop('disabled', true).html(
                        '<i class="fa fa-spinner fa-spin"></i> Mengubah...');

                    // Clear previous errors
                    $('.text-danger').hide();

                    $.ajax({
                        url: "{{ route('edit.request') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: $('#requestId').val(),
                            branch_id: $('#branch_id').val(),
                            date: $('#date').val(),
                            unit: $('#unit').val(),
                            status: $('#status').val()
                        },
                        success: function(response) {
                            $('#editModal').modal('hide');
                            dataTable.ajax.reload();

                            // Show success message
                            const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                            $('body').prepend(alertHtml);

                            // Scroll ke atas
                            $('html, body').animate({
                                scrollTop: 0
                            }, 500);
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                Object.keys(errors).forEach(field => {
                                    $(`#edit-error-${field}`).text(errors[field]
                                        [0]).show();
                                });
                            } else {
                                console.error('Update error:', xhr);
                                alert(
                                    'Terjadi kesalahan saat mengupdate data. Silakan coba lagi.'
                                    );
                            }
                        },
                        complete: function() {
                            // Enable button dan kembalikan text
                            $btn.prop('disabled', false).html(originalText);
                        }
                    });
                });

                // Delete modal
                $(document).on('click', '.deleteBtn', function() {
                    const id = $(this).data('id');
                    const row = dataTable.row($(this).parents('tr')).data();

                    $('#delete_request_id').val(id);

                    if (row) {
                        // Bersihkan HTML dari status
                        const cleanStatus = row.status ? row.status.replace(/<[^>]*>/g, '').trim() :
                            'N/A';

                        $('#delete-details').html(`
                    <div class="alert alert-info">
                        <strong>Cabang:</strong> ${row.branch_id || 'N/A'}<br>
                        <strong>Tanggal:</strong> ${row.date || 'N/A'}<br>
                        <strong>Unit:</strong> ${row.unit || 'N/A'}<br>
                        <strong>Status:</strong> ${cleanStatus}
                    </div>
                `);
                    }

                    $('#deleteRequestModal').modal('show');
                });
            });
        });
    </script>
@endsection
