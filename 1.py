# -*- coding: utf-8 -*-
import pywhatkit
import time
import sys
from colorama import Fore, Style, init
import webbrowser

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

def play_new_song():
    animasi_teks("\n🎵 Apa yang ingin Anda dengarkan hari ini? 🎵\n", Fore.GREEN)
    lagu = input(Fore.YELLOW + "Masukkan Nama Lagu: " + Style.RESET_ALL)

    if not lagu.strip():
        raise ValueError("Nama lagu tidak boleh kosong. Harap masukkan nama lagu yang valid.")

    animasi_teks(f"\n🔍 Mencari '{lagu}' di YouTube...", Fore.MAGENTA)
    time.sleep(1)

    pywhatkit.playonyt(lagu)

    animasi_teks(f"\n🎶 Lagu '{lagu}' berhasil diputar di YouTube. Nikmati musik Anda! 🎶", Fore.BLUE)
    return lagu

def cari_gambar():
    animasi_teks("\n🔍 Apa yang ingin Anda cari gambarnya? 🔍", Fore.GREEN)
    gambar = input(Fore.YELLOW + "Masukkan Nama Gambar: " + Style.RESET_ALL)

    if not gambar.strip():
        raise ValueError("Nama gambar tidak boleh kosong. Harap masukkan nama gambar yang valid.")

    animasi_teks(f"\n🔍 Mencari gambar '{gambar}' di Google...", Fore.MAGENTA)
    time.sleep(1)

    # Membuka hasil pencarian gambar di Google
    webbrowser.open(f"https://www.google.com/search?hl=en&tbm=isch&q={gambar}")

    animasi_teks(f"\n🖼️ Gambar '{gambar}' ditemukan! Nikmati hasil pencarian Anda.", Fore.BLUE)

def cari_lagu_dari_lirik():
    animasi_teks("\n🎵 Masukkan potongan lirik lagu yang Anda ingat: 🎵", Fore.GREEN)
    lirik = input(Fore.YELLOW + "Potongan Lirik: " + Style.RESET_ALL)

    if not lirik.strip():
        raise ValueError("Potongan lirik tidak boleh kosong. Harap masukkan potongan lirik yang valid.")

    animasi_teks(f"\n🔍 Mencari lagu dengan lirik '{lirik}' di Google...", Fore.MAGENTA)
    time.sleep(1)

    # Membuka hasil pencarian lagu di Google
    webbrowser.open(f"https://www.google.com/search?hl=en&q={lirik} lyrics")

    animasi_teks(f"\n🎶 Lagu dengan lirik '{lirik}' berhasil ditemukan! Nikmati hasil pencarian Anda.", Fore.BLUE)

def pause_video(current_song):
    # Memberikan instruksi untuk pause video secara manual
    animasi_teks(f"\n⏸️ Lagu '{current_song}' sedang diputar di YouTube.", Fore.CYAN)
    animasi_teks("\nUntuk menjeda lagu, Anda dapat melakukannya secara manual di YouTube.", Fore.YELLOW)
    webbrowser.open(f'https://www.youtube.com/results?search_query={current_song}')
    animasi_teks(f"\n🔴 Silakan jeda lagu menggunakan kontrol di YouTube.", Fore.RED)

try:
    header_animasi()
    time.sleep(1)

    pilihan_cari = None
    while pilihan_cari is None:
        pilihan_cari = cari_di_yt_atau_google()

    if pilihan_cari == 'lagu':
        current_song = play_new_song()

        # Menampilkan status lagu yang sedang diputar
        animasi_teks(f"\n📢 Saat ini Anda sedang memutar lagu: '{current_song}'", Fore.YELLOW)

        while True:
            status = input(Fore.GREEN + "\nApakah Anda ingin (1) pause lagu, (2) ganti lagu, atau (3) keluar? (ketik '1', '2', atau '3'): " + Style.RESET_ALL).strip().lower()
            if status == '3':
                animasi_teks("\n👋 Terima kasih telah menggunakan YouTube Music Player. Sampai jumpa lagi!", Fore.YELLOW)
                break
            elif status == '1':
                pause_video(current_song)
            elif status == '2':
                animasi_teks("\n🔄 Mengganti lagu...", Fore.CYAN)
                current_song = play_new_song()
            else:
                animasi_teks("\n❌ Input tidak valid. Harap ketik '1', '2', atau '3'.", Fore.RED)
    elif pilihan_cari == 'gambar':
        cari_gambar()
    elif pilihan_cari == 'lirik':
        cari_lagu_dari_lirik()

except ValueError as e:
    animasi_teks(f"\n❌ Error: {e}", Fore.RED)
    time.sleep(5)  # Jeda sebelum program keluar
except Exception as e:
    animasi_teks(f"\n❌ Terjadi kesalahan: {e}", Fore.RED)
    time.sleep(5)  # Jeda sebelum program keluar
