
@extends('layouts.app')
<title>Kelola Data Rekap</title>

@section('content')
    <div class="container-fluid">
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title mb-30">
                        <h2>Data Rekap</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Filter Final / Non Final -->
        <div class="mb-3">
            <a href="{{ route('datarekaps.index') }}"
               class="btn btn-outline-primary me-2 {{ !request('filter') ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('datarekaps.index', ['filter' => 'final']) }}"
               class="btn btn-outline-success me-2 {{ request('filter') === 'final' ? 'active' : '' }}">
                Final
            </a>
            <a href="{{ route('datarekaps.index', ['filter' => 'non-final']) }}"
               class="btn btn-outline-warning {{ request('filter') === 'non-final' ? 'active' : '' }}">
                Non Final
            </a>
        </div>

        <!-- Tombol Tambah (hanya untuk admin) -->
        @if(Auth::user()->type == 1)
            <button type="button" class="btn btn-success mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#addDataRekapModal">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
        @endif

        <!-- Modal Tambah Data Rekap -->
        @if(Auth::user()->type == 1)
            <div class="modal fade" id="addDataRekapModal" tabindex="-1" aria-labelledby="addDataRekapModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('store.datarekap') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Data Rekap</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No Faktur</label>
                                        <input type="text" class="form-control" name="no_faktur">
                                        @error('no_faktur')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Faktur</label>
                                        <input type="date" class="form-control" name="tgl_faktur">
                                        @error('tgl_faktur')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No SJ/Mutasi</label>
                                        <input type="text" class="form-control" name="no_sj_mutasi">
                                        @error('no_sj_mutasi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal SJ/Mutasi</label>
                                        <input type="date" class="form-control" name="tgl_sj_mutasi">
                                        @error('tgl_sj_mutasi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Konsumen</label>
                                        <input type="text" class="form-control" name="nama_konsumen">
                                        @error('nama_konsumen')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan Kirim</label>
                                        <input type="text" class="form-control" name="kecamatan_kirim">
                                        @error('kecamatan_kirim')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kota Kirim</label>
                                        <input type="text" class="form-control" name="kota_kirim">
                                        @error('kota_kirim')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Leasing</label>
                                        <input type="text" class="form-control" name="leasing">
                                        @error('leasing')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Type</label>
                                        <input type="text" class="form-control" name="nama_type">
                                        @error('nama_type')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Warna</label>
                                        <input type="text" class="form-control" name="warna">
                                        @error('warna')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cabang</label>
                                        <input type="text" class="form-control" name="cabang">
                                        @error('cabang')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Supir</label>
                                        <input type="text" class="form-control" name="supir">
                                        @error('supir')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Kirim</label>
                                        <input type="date" class="form-control" name="tgl_kirim">
                                        @error('tgl_kirim')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Stock</label>
                                        <input type="text" class="form-control" name="stock">
                                        @error('stock')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Harga</label>
                                        <input type="text" class="form-control" name="harga">
                                        @error('harga')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kwitansi</label>
                                        <input type="text" class="form-control" name="kwitansi">
                                        @error('kwitansi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Konsumen Bayar</label>
                                        <input type="text" class="form-control" name="konsumen_bayar">
                                        @error('konsumen_bayar')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan Tambahan</label>
                                        <input type="text" class="form-control" name="keterangan_tambahan">
                                        @error('keterangan_tambahan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Serah Terima Unit</label>
                                        <input type="date" class="form-control" name="tgl_serah_terima_unit">
                                        @error('tgl_serah_terima_unit')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lead Time</label>
                                        <input type="text" class="form-control" name="pengiriman_leadtime">
                                        @error('pengiriman_leadtime')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Performance (Hari)</label>
                                        <input type="text" class="form-control" name="performance_pengiriman_hari">
                                        @error('performance_pengiriman_hari')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status Pengiriman</label>
                                        <input type="text" class="form-control" name="status_pengiriman">
                                        @error('status_pengiriman')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan Pending</label>
                                        <input type="text" class="form-control" name="keterangan_pending">
                                        @error('keterangan_pending')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan Lainnya</label>
                                        <input type="text" class="form-control" name="keterangan_lainnya">
                                        @error('keterangan_lainnya')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
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
        @endif

        <!-- Modal Edit Data Rekap -->
        @if(Auth::user()->type == 1)
            <div class="modal fade" id="editDataRekapModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('edit.datarekap') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Rekap</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="dataRekapId" name="id">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No Faktur</label>
                                        <input type="text" class="form-control" id="no_faktur" name="no_faktur">
                                        <div id="edit-error-no_faktur" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Faktur</label>
                                        <input type="date" class="form-control" id="tgl_faktur" name="tgl_faktur">
                                        <div id="edit-error-tgl_faktur" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No SJ/Mutasi</label>
                                        <input type="text" class="form-control" id="no_sj_mutasi" name="no_sj_mutasi">
                                        <div id="edit-error-no_sj_mutasi" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal SJ/Mutasi</label>
                                        <input type="date" class="form-control" id="tgl_sj_mutasi" name="tgl_sj_mutasi">
                                        <div id="edit-error-tgl_sj_mutasi" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Konsumen</label>
                                        <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen">
                                        <div id="edit-error-nama_konsumen" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan Kirim</label>
                                        <input type="text" class="form-control" id="kecamatan_kirim" name="kecamatan_kirim">
                                        <div id="edit-error-kecamatan_kirim" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kota Kirim</label>
                                        <input type="text" class="form-control" id="kota_kirim" name="kota_kirim">
                                        <div id="edit-error-kota_kirim" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Leasing</label>
                                        <input type="text" class="form-control" id="leasing" name="leasing">
                                        <div id="edit-error-leasing" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Type</label>
                                        <input type="text" class="form-control" id="nama_type" name="nama_type">
                                        <div id="edit-error-nama_type" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Warna</label>
                                        <input type="text" class="form-control" id="warna" name="warna">
                                        <div id="edit-error-warna" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cabang</label>
                                        <input type="text" class="form-control" id="cabang" name="cabang">
                                        <div id="edit-error-cabang" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Supir</label>
                                        <input type="text" class="form-control" id="supir" name="supir">
                                        <div id="edit-error-supir" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Kirim</label>
                                        <input type="date" class="form-control" id="tgl_kirim" name="tgl_kirim">
                                        <div id="edit-error-tgl_kirim" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Stock</label>
                                        <input type="text" class="form-control" id="stock" name="stock">
                                        <div id="edit-error-stock" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Harga</label>
                                        <input type="text" class="form-control" id="harga" name="harga">
                                        <div id="edit-error-harga" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kwitansi</label>
                                        <input type="text" class="form-control" id="kwitansi" name="kwitansi">
                                        <div id="edit-error-kwitansi" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Konsumen Bayar</label>
                                        <input type="text" class="form-control" id="konsumen_bayar" name="konsumen_bayar">
                                        <div id="edit-error-konsumen_bayar" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan Tambahan</label>
                                        <input type="text" class="form-control" id="keterangan_tambahan" name="keterangan_tambahan">
                                        <div id="edit-error-keterangan_tambahan" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Serah Terima Unit</label>
                                        <input type="date" class="form-control" id="tgl_serah_terima_unit" name="tgl_serah_terima_unit">
                                        <div id="edit-error-tgl_serah_terima_unit" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lead Time</label>
                                        <input type="text" class="form-control" id="pengiriman_leadtime" name="pengiriman_leadtime">
                                        <div id="edit-error-pengiriman_leadtime" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Performance (Hari)</label>
                                        <input type="text" class="form-control" id="performance_pengiriman_hari" name="performance_pengiriman_hari">
                                        <div id="edit-error-performance_pengiriman_hari" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status Pengiriman</label>
                                        <input type="text" class="form-control" id="status_pengiriman" name="status_pengiriman">
                                        <div id="edit-error-status_pengiriman" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan Pending</label>
                                        <input type="text" class="form-control" id="keterangan_pending" name="keterangan_pending">
                                        <div id="edit-error-keterangan_pending" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan Lainnya</label>
                                        <input type="text" class="form-control" id="keterangan_lainnya" name="keterangan_lainnya">
                                        <div id="edit-error-keterangan_lainnya" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-success" id="updateDataRekapBtn">
                                    <i class="fa fa-save"></i> Ubah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Modal Detail Data Rekap -->
        <div class="modal fade" id="detailDataRekapModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Data Rekap</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>No Faktur</strong></label>
                                <p id="detail_no_faktur" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Tanggal Faktur</strong></label>
                                <p id="detail_tgl_faktur" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>No SJ/Mutasi</strong></label>
                                <p id="detail_no_sj_mutasi" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Tanggal SJ/Mutasi</strong></label>
                                <p id="detail_tgl_sj_mutasi" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Nama Konsumen</strong></label>
                                <p id="detail_nama_konsumen" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Kecamatan Kirim</strong></label>
                                <p id="detail_kecamatan_kirim" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Kota Kirim</strong></label>
                                <p id="detail_kota_kirim" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Leasing</strong></label>
                                <p id="detail_leasing" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Nama Type</strong></label>
                                <p id="detail_nama_type" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Warna</strong></label>
                                <p id="detail_warna" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Cabang</strong></label>
                                <p id="detail_cabang" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Supir</strong></label>
                                <p id="detail_supir" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Tanggal Kirim</strong></label>
                                <p id="detail_tgl_kirim" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Stock</strong></label>
                                <p id="detail_stock" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Harga</strong></label>
                                <p id="detail_harga" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Kwitansi</strong></label>
                                <p id="detail_kwitansi" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Konsumen Bayar</strong></label>
                                <p id="detail_konsumen_bayar" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Keterangan Tambahan</strong></label>
                                <p id="detail_keterangan_tambahan" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Tanggal Serah Terima Unit</strong></label>
                                <p id="detail_tgl_serah_terima_unit" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Lead Time</strong></label>
                                <p id="detail_pengiriman_leadtime" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Performance (Hari)</strong></label>
                                <p id="detail_performance_pengiriman_hari" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Status Pengiriman</strong></label>
                                <p id="detail_status_pengiriman" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Keterangan Pending</strong></label>
                                <p id="detail_keterangan_pending" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Keterangan Lainnya</strong></label>
                                <p id="detail_keterangan_lainnya" class="form-control-plaintext"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        @if(Auth::user()->type == 1)
            <div class="modal fade" id="deleteDataRekapModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('delete.datarekap') }}">
                        @csrf
                        <input type="hidden" name="id" id="delete_data_rekap_id">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Hapus Data Rekap</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus data rekap ini?</p>
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
        @endif

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="datarekaps-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Faktur</th>
                                <th>Tanggal Faktur</th>
                                <th>No SJ/Mutasi</th>
                                <th>Tanggal SJ/Mutasi</th>
                                <th>Nama Konsumen</th>
                                <th>Kecamatan Kirim</th>
                                <th>Tanggal Kirim</th>
                                <th>Supir</th>
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
            number = (number === null || number === undefined) ? 0 : number;
            number = parseFloat(number).toFixed(decimals);
            let parts = number.split('.');
            let integer = parts[0];
            let decimal = parts[1] || '';
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
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
                var filter = "{{ request('filter') }}";
                var dataTable = $('#datarekaps-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('manage.datarekap') }}",
                        type: 'GET',
                        data: function(d) {
                            d.filter = filter;
                        },
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
                            data: 'no_faktur',
                            name: 'no_faktur',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'tgl_faktur',
                            name: 'tgl_faktur',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'no_sj_mutasi',
                            name: 'no_sj_mutasi',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'tgl_sj_mutasi',
                            name: 'tgl_sj_mutasi',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'nama_konsumen',
                            name: 'nama_konsumen',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'kecamatan_kirim',
                            name: 'kecamatan_kirim',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'tgl_kirim',
                            name: 'tgl_kirim',
                            render: function(data) {
                                return data || '-';
                            }
                        },
                        {
                            data: 'supir',
                            name: 'supir',
                            render: function(data) {
                                return data || '-';
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

                // Open detail modal
                $(document).on('click', '.detailBtn', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const id = $btn.data('id');

                    console.log('Detail button clicked for ID:', id);

                    showLoadingInModal('detailDataRekapModal');
                    $('#detailDataRekapModal').modal('show');

                    $.ajax({
                        url: `/datarekaps/${id}/detail`,
                        type: 'GET',
                        timeout: 10000,
                        success: function(response) {
                            console.log('Ajax success:', response);
                            hideLoadingInModal('detailDataRekapModal');

                            if (response.success && response.data) {
                                populateDetailModal(response.data);
                            } else {
                                $('#detailDataRekapModal').modal('hide');
                                showAlert('error', 'Gagal memuat data rekap: ' + (response.message || 'Data tidak ditemukan'));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax error:', { status: status, error: error, response: xhr.responseText });
                            hideLoadingInModal('detailDataRekapModal');
                            $('#detailDataRekapModal').modal('hide');
                            showAlert('error', 'Terjadi kesalahan saat memuat data. Silakan refresh halaman dan coba lagi.');
                        }
                    });
                });

                // Open edit modal
                $(document).on('click', '.editBtn', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const id = $btn.data('id');

                    console.log('Edit button clicked for ID:', id);

                    $('.text-danger').hide();

                    const buttonData = {
                        id: $btn.data('id'),
                        no_faktur: $btn.data('no_faktur'),
                        tgl_faktur: $btn.data('tgl_faktur'),
                        no_sj_mutasi: $btn.data('no_sj_mutasi'),
                        tgl_sj_mutasi: $btn.data('tgl_sj_mutasi'),
                        nama_konsumen: $btn.data('nama_konsumen'),
                        kecamatan_kirim: $btn.data('kecamatan_kirim'),
                        kota_kirim: $btn.data('kota_kirim'),
                        leasing: $btn.data('leasing'),
                        nama_type: $btn.data('nama_type'),
                        warna: $btn.data('warna'),
                        cabang: $btn.data('cabang'),
                        supir: $btn.data('supir'),
                        tgl_kirim: $btn.data('tgl_kirim'),
                        stock: $btn.data('stock'),
                        harga: $btn.data('harga'),
                        kwitansi: $btn.data('kwitansi'),
                        konsumen_bayar: $btn.data('konsumen_bayar'),
                        keterangan_tambahan: $btn.data('keterangan_tambahan'),
                        tgl_serah_terima_unit: $btn.data('tgl_serah_terima_unit'),
                        pengiriman_leadtime: $btn.data('pengiriman_leadtime'),
                        performance_pengiriman_hari: $btn.data('performance_pengiriman_hari'),
                        status_pengiriman: $btn.data('status_pengiriman'),
                        keterangan_pending: $btn.data('keterangan_pending'),
                        keterangan_lainnya: $btn.data('keterangan_lainnya')
                    };

                    console.log('Button data:', buttonData);

                    if (buttonData.id) {
                        console.log('Using button data - all fields present');
                        populateEditModal(buttonData);
                        $('#editDataRekapModal').modal('show');
                        return;
                    }

                    console.log('Button data incomplete, trying Ajax...');

                    showLoadingInModal('editDataRekapModal');
                    $('#editDataRekapModal').modal('show');

                    $.ajax({
                        url: `/datarekaps/${id}/detail`,
                        type: 'GET',
                        timeout: 10000,
                        success: function(response) {
                            console.log('Ajax success:', response);
                            hideLoadingInModal('editDataRekapModal');

                            if (response.success && response.data) {
                                populateEditModal(response.data);
                            } else {
                                $('#editDataRekapModal').modal('hide');
                                showAlert('error', 'Gagal memuat data rekap: ' + (response.message || 'Data tidak ditemukan'));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax error:', { status: status, error: error, response: xhr.responseText });
                            hideLoadingInModal('editDataRekapModal');
                            $('#editDataRekapModal').modal('hide');
                            showAlert('error', 'Terjadi kesalahan saat memuat data. Silakan refresh halaman dan coba lagi.');
                        }
                    });
                });

                // Function untuk menampilkan loading di modal
                function showLoadingInModal(modalId) {
                    $(`#${modalId} .modal-body`).prepend(
                        '<div id="loading-message" class="text-center mb-3"><i class="fa fa-spinner fa-spin"></i> Memuat data...</div>'
                    );
                    if (modalId === 'editDataRekapModal') {
                        $('#dataRekapId').val('');
                        $('#no_faktur').val('');
                        $('#tgl_faktur').val('');
                        $('#no_sj_mutasi').val('');
                        $('#tgl_sj_mutasi').val('');
                        $('#nama_konsumen').val('');
                        $('#kecamatan_kirim').val('');
                        $('#kota_kirim').val('');
                        $('#leasing').val('');
                        $('#nama_type').val('');
                        $('#warna').val('');
                        $('#cabang').val('');
                        $('#supir').val('');
                        $('#tgl_kirim').val('');
                        $('#stock').val('');
                        $('#harga').val('');
                        $('#kwitansi').val('');
                        $('#konsumen_bayar').val('');
                        $('#keterangan_tambahan').val('');
                        $('#tgl_serah_terima_unit').val('');
                        $('#pengiriman_leadtime').val('');
                        $('#performance_pengiriman_hari').val('');
                        $('#status_pengiriman').val('');
                        $('#keterangan_pending').val('');
                        $('#keterangan_lainnya').val('');
                    }
                }

                // Function untuk menyembunyikan loading di modal
                function hideLoadingInModal(modalId) {
                    $(`#${modalId} #loading-message`).remove();
                }

                // Function untuk populate edit modal
                function populateEditModal(data) {
                    console.log('Populating edit modal with data:', data);

                    $('#dataRekapId').val(data.id || '');
                    $('#no_faktur').val(data.no_faktur || '');
                    $('#tgl_faktur').val(data.tgl_faktur || '');
                    $('#no_sj_mutasi').val(data.no_sj_mutasi || '');
                    $('#tgl_sj_mutasi').val(data.tgl_sj_mutasi || '');
                    $('#nama_konsumen').val(data.nama_konsumen || '');
                    $('#kecamatan_kirim').val(data.kecamatan_kirim || '');
                    $('#kota_kirim').val(data.kota_kirim || '');
                    $('#leasing').val(data.leasing || '');
                    $('#nama_type').val(data.nama_type || '');
                    $('#warna').val(data.warna || '');
                    $('#cabang').val(data.cabang || '');
                    $('#supir').val(data.supir || '');
                    $('#tgl_kirim').val(data.tgl_kirim || '');
                    $('#stock').val(data.stock || '');
                    $('#harga').val(data.harga || '');
                    $('#kwitansi').val(data.kwitansi || '');
                    $('#konsumen_bayar').val(data.konsumen_bayar || '');
                    $('#keterangan_tambahan').val(data.keterangan_tambahan || '');
                    $('#tgl_serah_terima_unit').val(data.tgl_serah_terima_unit || '');
                    $('#pengiriman_leadtime').val(data.pengiriman_leadtime || '');
                    $('#performance_pengiriman_hari').val(data.performance_pengiriman_hari || '');
                    $('#status_pengiriman').val(data.status_pengiriman || '');
                    $('#keterangan_pending').val(data.keterangan_pending || '');
                    $('#keterangan_lainnya').val(data.keterangan_lainnya || '');

                    console.log('Edit modal populated:', {
                        id: $('#dataRekapId').val(),
                        no_faktur: $('#no_faktur').val(),
                        tgl_faktur: $('#tgl_faktur').val()
                    });
                }

                // Function untuk populate detail modal
                function populateDetailModal(data) {
                    console.log('Populating detail modal with data:', data);

                    $('#detail_no_faktur').text(data.no_faktur || '-');
                    $('#detail_tgl_faktur').text(data.tgl_faktur || '-');
                    $('#detail_no_sj_mutasi').text(data.no_sj_mutasi || '-');
                    $('#detail_tgl_sj_mutasi').text(data.tgl_sj_mutasi || '-');
                    $('#detail_nama_konsumen').text(data.nama_konsumen || '-');
                    $('#detail_kecamatan_kirim').text(data.kecamatan_kirim || '-');
                    $('#detail_kota_kirim').text(data.kota_kirim || '-');
                    $('#detail_leasing').text(data.leasing || '-');
                    $('#detail_nama_type').text(data.nama_type || '-');
                    $('#detail_warna').text(data.warna || '-');
                    $('#detail_cabang').text(data.cabang || '-');
                    $('#detail_supir').text(data.supir || '-');
                    $('#detail_tgl_kirim').text(data.tgl_kirim || '-');
                    $('#detail_stock').text(data.stock || '-');
                    $('#detail_harga').text(data.harga || '-');
                    $('#detail_kwitansi').text(data.kwitansi || '-');
                    $('#detail_konsumen_bayar').text(data.konsumen_bayar || '-');
                    $('#detail_keterangan_tambahan').text(data.keterangan_tambahan || '-');
                    $('#detail_tgl_serah_terima_unit').text(data.tgl_serah_terima_unit || '-');
                    $('#detail_pengiriman_leadtime').text(data.pengiriman_leadtime || '-');
                    $('#detail_performance_pengiriman_hari').text(data.performance_pengiriman_hari || '-');
                    $('#detail_status_pengiriman').text(data.status_pengiriman || '-');
                    $('#detail_keterangan_pending').text(data.keterangan_pending || '-');
                    $('#detail_keterangan_lainnya').text(data.keterangan_lainnya || '-');

                    console.log('Detail modal populated:', {
                        id: data.id,
                        no_faktur: $('#detail_no_faktur').text(),
                        tgl_faktur: $('#detail_tgl_faktur').text()
                    });
                }

                // Function untuk menampilkan alert
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

                // Submit edit
                $('#updateDataRekapBtn').click(function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const originalText = $btn.html();

                    const formData = {
                        id: $('#dataRekapId').val(),
                        no_faktur: $('#no_faktur').val(),
                        tgl_faktur: $('#tgl_faktur').val(),
                        no_sj_mutasi: $('#no_sj_mutasi').val(),
                        tgl_sj_mutasi: $('#tgl_sj_mutasi').val(),
                        nama_konsumen: $('#nama_konsumen').val(),
                        kecamatan_kirim: $('#kecamatan_kirim').val(),
                        kota_kirim: $('#kota_kirim').val(),
                        leasing: $('#leasing').val(),
                        nama_type: $('#nama_type').val(),
                        warna: $('#warna').val(),
                        cabang: $('#cabang').val(),
                        supir: $('#supir').val(),
                        tgl_kirim: $('#tgl_kirim').val(),
                        stock: $('#stock').val(),
                        harga: $('#harga').val(),
                        kwitansi: $('#kwitansi').val(),
                        konsumen_bayar: $('#konsumen_bayar').val(),
                        keterangan_tambahan: $('#keterangan_tambahan').val(),
                        tgl_serah_terima_unit: $('#tgl_serah_terima_unit').val(),
                        pengiriman_leadtime: $('#pengiriman_leadtime').val(),
                        performance_pengiriman_hari: $('#performance_pengiriman_hari').val(),
                        status_pengiriman: $('#status_pengiriman').val(),
                        keterangan_pending: $('#keterangan_pending').val(),
                        keterangan_lainnya: $('#keterangan_lainnya').val()
                    };

                    console.log('Form data to submit:', formData);

                    if (!formData.id) {
                        showAlert('error', 'ID data tidak valid!');
                        return;
                    }

                    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengubah...');

                    $('.text-danger').hide();

                    $.ajax({
                        url: "{{ route('edit.datarekap') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ...formData
                        },
                        success: function(response) {
                            console.log('Update success:', response);
                            $('#editDataRekapModal').modal('hide');
                            dataTable.ajax.reload(null, false);
                            showAlert('success', response.message || 'Data rekap berhasil diupdate!');
                        },
                        error: function(xhr) {
                            console.error('Update error:', xhr);
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                Object.keys(errors).forEach(field => {
                                    $(`#edit-error-${field}`).text(errors[field][0]).show();
                                });
                            } else {
                                showAlert('error', 'Terjadi kesalahan saat mengupdate data. Silakan coba lagi.');
                            }
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html(originalText);
                        }
                    });
                });

                // Delete modal
                $(document).on('click', '.deleteBtn', function() {
                    const $btn = $(this);
                    const id = $btn.data('id');

                    $('#delete_data_rekap_id').val(id);

                    const deleteDetails = {
                        no_faktur: $btn.data('no_faktur') || 'N/A',
                        tgl_faktur: $btn.data('tgl_faktur') || 'N/A',
                        nama_konsumen: $btn.data('nama_konsumen') || 'N/A',
                        kecamatan_kirim: $btn.data('kecamatan_kirim') || 'N/A',
                        tgl_kirim: $btn.data('tgl_kirim') || 'N/A',
                        supir: $btn.data('supir') || 'N/A'
                    };

                    $('#delete-details').html(`
                        <div class="alert alert-info">
                            <strong>No Faktur:</strong> ${deleteDetails.no_faktur}<br>
                            <strong>Tanggal Faktur:</strong> ${deleteDetails.tgl_faktur}<br>
                            <strong>Nama Konsumen:</strong> ${deleteDetails.nama_konsumen}<br>
                            <strong>Kecamatan Kirim:</strong> ${deleteDetails.kecamatan_kirim}<br>
                            <strong>Tanggal Kirim:</strong> ${deleteDetails.tgl_kirim}<br>
                            <strong>Supir:</strong> ${deleteDetails.supir}
                        </div>
                    `);

                    $('#deleteDataRekapModal').modal('show');
                });
            });
        });
    </script>
@endsection