@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('suplier/create') }}">Tambah</a>
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
                            <select class="form-control" id="filter_suplier">
                                <option value="">- Semua Suplier -</option>
                                @foreach ($suplier as $item)
                                    <option value="{{ $item->suplier_id }}">{{ $item->nama_suplier }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih Suplier</small>
                        </div>
                    </div>
                </div>
            </div>
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
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            var dataSuplier = $('#table_suplier').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    "url": "{{ url('suplier/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.filter_suplier = $('#filter_suplier').val(); // Kirim filter ke server
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
                        data: "nama_suplier",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "alamat_suplier",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "telepon_suplier",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload DataTables saat filter suplier berubah
            $('#filter_suplier').on('change', function () {
                dataSuplier.ajax.reload();
            });
        });
    </script>
@endpush
