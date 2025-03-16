@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        @empty($suplier)
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('suplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @else
            <form method="POST" action="{{ url('/suplier/'.$suplier->suplier_id) }}" class="form-horizontal">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Suplier</label>
                    <div class="col-10">
                        <input type="text" class="form-control" name="nama_suplier"
                            value="{{ old('nama_suplier', $suplier->nama_suplier) }}" required>
                        @error('nama_suplier')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Alamat</label>
                    <div class="col-10">
                        <input type="text" class="form-control" name="alamat_suplier"
                            value="{{ old('alamat_suplier', $suplier->alamat_suplier) }}" required>
                        @error('alamat_suplier')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Telepon</label>
                    <div class="col-10">
                        <input type="text" class="form-control" name="telepon_suplier"
                            value="{{ old('telepon_suplier', $suplier->telepon_suplier) }}" required>
                        @error('telepon_suplier')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-2 control-label col-form-label"></label>
                    <div class="col-10">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('suplier') }}">Kembali</a>
                    </div>
                </div>
            </form>
        @endempty
    </div>
</div>
@endsection
