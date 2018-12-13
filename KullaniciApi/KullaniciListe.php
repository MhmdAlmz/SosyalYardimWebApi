<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryKullaniciListeGetir = $db->prepare("SELECT
	KullaniciBilgileriTablo.KullaniciId AS KullaniciID,
	SehirTablo.SehirId AS SehirID,
	SehirTablo.SehirAdi AS Sehir,
	KullaniciBilgileriTablo.KullaniciEPosta AS EPosta,
	KullaniciBilgileriTablo.KullaniciTelegramKullaniciAdi AS TelegramKullaniciAdi,
	KullaniciBilgileriTablo.KullaniciTCKimlikNumarasi AS TCKimlikNo,
	KullaniciBilgileriTablo.KullaniciMerkezdeMi AS Merkezde,
	KullaniciBilgileriTablo.KullaniciOnayliMi AS Onayli,
	KullaniciBilgileriTablo.KullaniciTelefonNumarasi AS Tel,
	KullaniciBilgileriTablo.KullaniciAdi AS KullaniciAdi,
	KullaniciBilgileriTablo.KullaniciSoyadi AS KullaniciSoyadi 
FROM
	KullaniciBilgileriTablo
	INNER JOIN SehirTablo ON SehirTablo.SehirId = KullaniciBilgileriTablo.[SehirTablo.SehirId]");
    $QueryKullaniciListeGetir->execute();
    $kullaniciListe = array();
    if ($QueryKullaniciListeGetir->rowCount()) {
        foreach ($QueryKullaniciListeGetir as $RowKullaniciBilgileri) {
            $kullaniciListe[] = $RowKullaniciBilgileri;
        }
    }
    $Sonuc["Sonuc"] = "basarili";
    $Sonuc["Aciklama"] = '';
    $Sonuc["HataKodu"] = -1;
    $Sonuc["KullaniciListe"] = $kullaniciListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
