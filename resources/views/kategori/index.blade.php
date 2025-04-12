@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-info">Import Kategori</button>
                <a href="{{ url('/kategori/export_excel') }}" class="btn btn-warning"><i class="fa fa-file-
                    excel"></i> Export Suplier</a>
                    <a href="{{ url('/kategori/export_pdf') }}" class="btn btn-danger" target="blank"><i class="fa fa-file-
                        pdf"></i> Export Barang PDF</a>
                <a class="btn btn-primary" href="{{ url('kategori/create') }}">Tambah Data</a>
                <button onclick="modalAction('{{ url('kategori/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id">
                                <option value="">- Semua Kategori -</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih Kategori</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataMKategori;
        $(document).ready(function () {
            var dataMKategori = $('#table_kategori').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    "url": "{{ url('kategori/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.kategori_id = $('#kategori_id').val(); // Kirim filter ke server
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori_kode", // Menampilkan kolom kategori_kode
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori_nama", // Menampilkan kolom kategori_nama
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi", // Aksi
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload DataTables saat filter kategori berubah
            $('#kategori_id').on('change', function () {
                dataMKategori.ajax.reload();
            });
        });
    </script>
@endpush
