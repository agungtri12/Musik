# -*- coding: utf-8 -*-
import time
import sys
import webbrowser
from colorama import Fore, Style, init
import requests

# Inisialisasi colorama untuk mendukung warna teks
init(autoreset=True)

def animasi_teks(teks, warna=Fore.WHITE, kecepatan=0.05):
    panjang_teks = len(teks)
    if panjang_teks > 50:
        kecepatan = 0.02  # Lebih cepat untuk teks panjang
    elif panjang_teks < 20:
        kecepatan = 0.1  # Lebih lambat untuk teks pendek

    for huruf in teks:
        sys.stdout.write(warna + huruf + Style.RESET_ALL)
        sys.stdout.flush()
        time.sleep(kecepatan)
    print()

def header_animasi():
    header = "\n=========================================="
    header += "\n*** SELAMAT DATANG DI YOUTUBE MUSIC PLAYER ***"
    header += "\n==========================================\n"
    animasi_teks(header, Fore.CYAN, 0.07)

def cari_di_yt_atau_google():
    animasi_teks("\n🎵 Apa yang ingin Anda cari? 🎵", Fore.GREEN)
    pilihan = input(Fore.YELLOW + "Apakah Anda ingin (1) mencari lagu di YouTube, (2) mencari gambar di Google, atau (3) mencari lagu lewat lirik? (ketik '1', '2', atau '3'): " + Style.RESET_ALL).strip()

    if pilihan == '1':
        return 'lagu'
    elif pilihan == '2':
        return 'gambar'
    elif pilihan == '3':
        return 'lirik'
    else:
        animasi_teks("\n❌ Input tidak valid. Harap ketik '1', '2', atau '3'.", Fore.RED)
        return None

def cari_lagu_dari_lirik():
    animasi_teks("\n🎵 Masukkan potongan lirik lagu yang Anda ingat: 🎵", Fore.GREEN)
    lirik = input(Fore.YELLOW + "Potongan Lirik: " + Style.RESET_ALL)

    if not lirik.strip():
        raise ValueError("Potongan lirik tidak boleh kosong. Harap masukkan potongan lirik yang valid.")

    animasi_teks(f"\n🔍 Mencari lagu dengan lirik '{lirik}'...", Fore.MAGENTA)

    try:
        # Contoh penggunaan Genius API untuk pencarian lagu berdasarkan lirik
        api_url = "https://api.genius.com/search"
        headers = {"Authorization": "Bearer YOUR_GENIUS_API_TOKEN"}
        params = {"q": lirik}
        response = requests.get(api_url, headers=headers, params=params)

        if response.status_code == 200:
            data = response.json()
            hits = data.get("response", {}).get("hits", [])

            if hits:
                animasi_teks("\n🎶 Lagu ditemukan berdasarkan lirik Anda: 🎶", Fore.BLUE)
                for hit in hits[:3]:  # Menampilkan hingga 3 hasil teratas
                    judul = hit["result"].get("title", "Unknown Title")
                    artis = hit["result"].get("primary_artist", {}).get("name", "Unknown Artist")
                    animasi_teks(f"- {judul} oleh {artis}", Fore.GREEN)
            else:
                animasi_teks("\n❌ Tidak ditemukan lagu dengan lirik tersebut.", Fore.RED)
        else:
            animasi_teks("\n❌ Terjadi kesalahan saat mengakses API. Cek kembali koneksi atau token API Anda.", Fore.RED)

    except Exception as e:
        animasi_teks(f"\n❌ Terjadi kesalahan: {e}", Fore.RED)

def main():
    try:
        header_animasi()
        time.sleep(1)

        pilihan_cari = None
        while pilihan_cari is None:
            pilihan_cari = cari_di_yt_atau_google()

        if pilihan_cari == 'lirik':
            cari_lagu_dari_lirik()
        else:
            animasi_teks("\n⚠️ Fitur lain belum dimasukkan dalam contoh ini.", Fore.YELLOW)

    except ValueError as e:
        animasi_teks(f"\n❌ Error: {e}", Fore.RED)
    except Exception as e:
        animasi_teks(f"\n❌ Terjadi kesalahan: {e}", Fore.RED)

if __name__ == "__main__":
    main()
