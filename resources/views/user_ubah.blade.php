<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update User</title>
</head>
<body>
    <h1>Form Ubah Data User</h1>
    <a href="{{ url('/user') }}">Kembali</a>
    <br><br>

    <form action="{{ url('/user/ubah_simpan/'.$data->user_id) }}" method="post">
    {{-- <form action="/user/ubah_simpan/{{$data->user_id}}" method="post"> --}}

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukan Username" value="{{$data->username}}">
        <br>
        <label>Nama</label>
        <input type="text" name="nama" placeholder="Masukan Nama" value="{{$data->nama}}">
        <br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukan Password" value="{{$data->password}}">
        <br>
        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukan Level ID" value="{{$data->level_id}}">
        <br><br>
        <button type="submit" class="btn btn-success" value="Ubah">Ubah User</button>
    </form>
</body>
</html>
