# AdLink Lite Anti-Cheat (VieFaucet Integration)

Script ini adalah versi sederhana mirip AdLinkFly untuk kebutuhan faucet/shortlink + smartlink task seperti Adsterra.

## Fitur

1. **Shortlink + 2 Smartlink Task (wajib)**
   - Admin membuat link via `create_link.php` dengan:
     - URL tujuan akhir
     - Task 1 smartlink URL
     - Task 2 smartlink URL
   - Pengunjung membuka shortlink `go.php?c=KODE&uid=USER_ID`.
   - Pengunjung wajib menyelesaikan **2 task smartlink** (berurutan) sebelum bisa claim reward.

2. **Proteksi anti-cheat**
   - Rate limit IP per jam.
   - Cooldown claim per fingerprint + link.
   - Math challenge + countdown wajib.
   - Session token wajib valid.
   - Fingerprint (IP + user-agent) harus sama dari awal sampai claim.
   - Honeypot field untuk tangkap bot form filler.
   - Validasi urutan task (task 2 tidak bisa duluan).
   - Minimum waktu penyelesaian task (anti bypass cepat).
   - Log abuse untuk ban/analisa manual.

3. **Integrasi VieFaucet**
   - Setelah validasi lolos, server memanggil callback VieFaucet (`POST`) dengan:
     - `api_key`
     - `uid`
     - `reference`
     - `amount`

## Struktur file

- `index.php`: daftar link aktif.
- `create_link.php`: form admin buat link.
- `go.php`: validasi awal + tampil challenge/task.
- `open_task.php`: membuka smartlink task (record opened_at).
- `complete_task.php`: menandai task selesai setelah waktu minimum.
- `claim.php`: validasi final + callback VieFaucet + redirect.
- `src/AntiCheatService.php`: logic anti-cheat.
- `src/ViefaucetClient.php`: HTTP client callback.
- `db.sql`: skema database.

## Cara install

1. Import database:
   ```bash
   mysql -u root -p adlink_lite < db.sql
   ```
2. Copy config:
   ```bash
   cp config.example.php config.php
   ```
3. Edit `config.php` (DB, `admin_secret`, callback VieFaucet, threshold security).
4. Jalankan server:
   ```bash
   php -S 0.0.0.0:8000
   ```

## Contoh integrasi di VieFaucet

Pola URL shortlink:

```text
https://domain-kamu.com/go.php?c=KODE_LINK&uid={user_id}
```

`{user_id}` diganti user identifier dari VieFaucet.

## Catatan keamanan

- Simpan `admin_secret` dan `api_key` di server (jangan expose frontend).
- Wajib pakai HTTPS.
- Untuk anti-VPN lebih akurat, pakai IP intelligence service.
- Untuk anti-cheat yang lebih kuat, tambahkan verifikasi callback server-side dari provider smartlink jika tersedia.
