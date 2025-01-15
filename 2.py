from pytube import YouTube
from pydub import AudioSegment
import os

def youtube_to_mp3(youtube_url, output_folder="downloads"):
    try:
        # Membuat folder output jika belum ada
        if not os.path.exists(output_folder):
            os.makedirs(output_folder)
        
        # Unduh video dari YouTube
        print("Mengunduh video...")
        yt = YouTube(youtube_url)
        video_stream = yt.streams.filter(only_audio=True).first()
        downloaded_file = video_stream.download(output_path=output_folder)
        
        # Ubah format video menjadi MP3
        print("Mengonversi ke MP3...")
        mp3_file = os.path.splitext(downloaded_file)[0] + ".mp3"
        audio = AudioSegment.from_file(downloaded_file)
        audio.export(mp3_file, format="mp3")
        
        # Hapus file audio asli (opsional)
        os.remove(downloaded_file)
        
        print(f"Konversi selesai! File MP3 disimpan di: {mp3_file}")
    except Exception as e:
        print(f"Terjadi kesalahan: {e}")

if __name__ == "__main__":
    link = input("Masukkan URL YouTube: ")
    youtube_to_mp3(link)
