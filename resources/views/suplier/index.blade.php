@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/suplier/import') }}')" class="btn btn-info">
                Import Suplier
            </button>
            <a href="{{ url('/suplier/export_excel') }}" class="btn btn-warning "><i class="fa fa-file-
                excel"></i> Export Suplier</a>
                <a href="{{ url('/suplier/export_pdf') }}" class="btn btn-danger" target="blank"><i class="fa fa-file-
                    pdf"></i> Export Suplier PDF</a>
            <a class="btn btn-primary" href="{{ url('suplier/create') }}">Tambah</a>
            <!-- Tombol untuk membuka modal tambah supplier dengan Ajax -->
            <button onclick="modalAction('{{ url('suplier/create_ajax') }}')" class="btn btn-success">
                Tambah Ajax
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Menampilkan pesan jika ada success atau error -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form untuk Filter Supplier -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="suplier_id" name="suplier_id" required>
                            <option value="">- Semua -</option>
                            @foreach ($suplier as $item)
                                <option value="{{ $item->suplier_id }}">{{ $item->nama_suplier }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Suplier Pengguna</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel untuk Menampilkan Data Supplier -->
        <table class="table table-bordered table-striped table-hover table-sm" id="table_suplier">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Suplier</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal untuk menambah data supplier dengan Ajax -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false" aria-hidden="true">
</div>

<!-- Modal untuk Import Data Supplier -->
<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true">
    <form action="{{ url('/suplier/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Suplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Download Template</label>
                        <a href="{{ asset('template_suplier.xlsx') }}" class="btn btn-info btn-sm" download>
                            <i class="fa fa-file-excel"></i> Download
                        </a>
                        <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Pilih File</label>
                        <input type="file" name="file_suplier" id="file_suplier" class="form-control" required>
                        <small id="error-file_suplier" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    // Fungsi untuk memuat modal dengan Ajax
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataSuplier;

    $(document).ready(function () {
        // Inisialisasi DataTable
        dataSuplier = $('#table_suplier').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('suplier/list') }}",
                type: "POST",
                data: function (d) {
                    d.suplier_id = $('#suplier_id').val(); // Filter berdasarkan supplier_id
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama_suplier" },
                { data: "alamat_suplier" },
                { data: "telepon_suplier" },
                { data: "aksi", orderable: false, searchable: false }
            ]
        });

        // Menangani perubahan pada filter supplier
        $('#suplier_id').on('change', function () {
            dataSuplier.ajax.reload();
        });

        // Validasi dan pengolahan form import data
        $("#form-import").validate({
            rules: {
                file_suplier: {
                    required: true,
                    extension: "xlsx" // Hanya menerima file dengan ekstensi .xlsx
                },
            },
            submitHandler: function (form) {
                var formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status) {
                            $('#modal-import').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataSuplier.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
