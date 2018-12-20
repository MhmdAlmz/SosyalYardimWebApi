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
	KullaniciBilgileriTablo.KullaniciId AS BagisciId,
	SehirTablo.SehirId AS SehirId,
	SehirTablo.SehirAdi AS Sehir,
	KullaniciBilgileriTablo.KullaniciEPosta AS EPosta,
	KullaniciBilgileriTablo.KullaniciAdres AS Adres,
	KullaniciBilgileriTablo.KullaniciTelefonNumarasi AS Tel,
	KullaniciBilgileriTablo.KullaniciAdi AS Ad,
	KullaniciBilgileriTablo.KullaniciSoyadi AS Soyad 
FROM
	KullaniciBilgileriTablo
	INNER JOIN SehirTablo ON SehirTablo.SehirId = KullaniciBilgileriTablo.[SehirTablo.SehirId]
WHERE KullaniciBilgileriTablo.BagisciMi=1");
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
    $Sonuc["BagisciListe"] = $kullaniciListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
