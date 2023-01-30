# Configurasi file
1. run `composer install` untuk download vendor
2. Setelah selesai jalankan perintah `php aritsan migrate` untuk generate database

# Dokumentasi API dan contoh

### 1.  Register

#### HTTP Request
```json
POST http://127.0.0.1:8000/api/register
```
#### Parameters

| Parameters    |               | Description  |
| ------------- |:-------------:| -------------|
| name   | required	  	| Nama user |
| email   | required	  	| E-mail user |
| password   | required	  	| Password user yang nantinya digunakan untuk login |
| password_confirmation   | required	  	| Sebagai validasi atas isian password yang kita tulis sebelumnya |



#### Result

| Parameters    |  Description  |
| ------------- |:--------------|
|success| `true` Jika berhasil register. `false` Jika tidak berhasil register |
|user | Menampilkan data user yang berhasil register|


#### Example
```json
curl -X POST -H 'Content-Type: application/json' -d '{"name": "Somak","email": "somak@gmail.com","password": "Hello123#", "password_confirmation" : "Hello123#"}' https://127.0.0.1:8000/api/register
```
```json
{
    "success": true,
    "user": {
        "name": "Somak",
        "email": "somak@gmail.com",
        "updated_at": "2023-01-30T07:22:31.000000Z",
        "created_at": "2023-01-30T07:22:31.000000Z",
        "id": 2
    }
}
```



### 2.  Login

#### HTTP Request
```json
POST http://127.0.0.1:8000/api/login
```
#### Parameters

| Parameters    |               | Description  |
| ------------- |:-------------:| -------------|
| email   | required	  	| E-mail user yang telah didaftarkan melalui register |
| password   | required	  	| Password user yang telah didaftarkan melalui register |



#### Result

| Parameters    |  Description  |
| ------------- |:--------------|
|accessToken | Sebagai token untuk consume API |
|refreshToken | Sebagai token untuk merefresh `accesToken` |


#### Example
```json
curl -X POST -H 'Content-Type: application/json' -d '{"email": "somak@gmail.com","password": "Hello123#"}' https://127.0.0.1:8000/api/login
```
```json
{
    "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.xxxxxx",
    "refreshToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.xxxxxx"
}
```




### 3. Update Token

#### HTTP Request
```json
POST http://127.0.0.1:8000/api/update-token
```
#### Parameters

| Parameters    |               | Description  |
| ------------- |:-------------:| -------------|
| token   | required	  	| token yang akan dikiriman adalah `refreshToken` yang telah didapat dari login |




#### Result

| Parameters    |  Description  |
| ------------- |:--------------|
|accessToken | Sebagai token untuk consume API yang telah diperbarui |
|refreshToken | Sebagai token untuk merefresh `accesToken` |


#### Example
```json
curl -X POST -H 'Content-Type: application/json' -d '{"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.xxxxxx"}' https://127.0.0.1:8000/api/register
```
```json
{
    "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.xxxxxx",
    "refreshToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.xxxxxx"
}
```



### 3. Transfer

#### HTTP Request
```json
POST http://127.0.0.1:8000/api/trasfer
```
#### Parameters

| Parameters    |               | Description  |
| ------------- |:-------------:| -------------|
|Authorization (headers)   | required	  	|  Berisi `accessToken` yang telah didapatkan setelah login |
|nilai_transfer | required | Jumlah nilai transfer yang akan dikirim |
|bank_tujuan | required | Nama bank penerima yang akan dituju |
|rekening_tujuan | required | Nomor rekening penerima dari bank yang dituju |
|atasnama_tujuan | required | Nama pemilik nomor rekening yang dituju |
|bank_pengirim | required | Nama bank yang menjadi pengirim |
 



#### Result

| Parameters    |  Description  |
| ------------- |:--------------|
|id_transaksi | ID transaksi yang telah dilakukan |
|nilai_transfer | Jumlah uang yang dikirimkan |
|kode_unik | Kode unik untuk setiap transaksi admin |
|total_transfer | Jumlah uang yang dikirimkan ditambah dengan kode unik |
|bank_perantara | Nama bank yang menjembatani dari bank pengirim ke penerima |
|expired_transfer | Batas waktu transfer dari pengirim ke Platform APP |


#### Example
```json
curl -X POST -H 'Content-Type: application/json' -H 'Bearer (Token)' -d ' {"nilai_transfer": 300000,"bank_tujuan": "BCA", "rekening_tujuan" : "12398210", "atasnama_tujuan" : "Hendro", "bank_pengirim" : "BNI" }' https://127.0.0.1:8000/api/transfer
```
```json
{
    "id_transaksi": "TF30012300002",
    "nilai_transfer": 300000,
    "kode_unik": 171,
    "total_transfer": 300171,
    "bank_perantara": "BCA",
    "rekening_perantara": "029399030493",
    "expired_transfer": "2023-01-30 08:38:03"
}
```
