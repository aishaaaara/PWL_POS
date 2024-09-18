<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Data</title>
</head>
<body>  
    <h1>Form Tambah Data User</h1>
    <form method="post" action={{url('/user/tambah_simpan')}}>
        {{ csrf_field()}}
        
    <label>Username</label>
    <input type="text" name="username" placeholder="Masukkan Username ">
    <br>
    <label>Nama</label>
    <input type="text" name="nama" placeholder="Masukkan Namaa">
    <br>
    <label>Password</label>
    <input type="text" name="password" placeholder="Masukkan password ">
    <br>
    <label>Level id</label>
    <input type="text" name="level id" placeholder="Masukkan Level id ">
    <br><br>
    <input type="submit" class="btn btn-success" value="Simpan">
    </form>
</body>
</html>