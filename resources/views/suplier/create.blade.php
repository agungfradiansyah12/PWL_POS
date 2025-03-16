@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('suplier') }}" class="form-horizontal">
            @csrf

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Nama Suplier</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="nama_suplier" name="nama_suplier" value="{{ old('nama_suplier') }}" required>
                    @error('nama_suplier')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Alamat Suplier</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="alamat_suplier" name="alamat_suplier" value="{{ old('alamat_suplier') }}">
                    @error('alamat_suplier')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Telepon Suplier</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="telepon_suplier" name="telepon_suplier" value="{{ old('telepon_suplier') }}">
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
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
