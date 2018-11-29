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

    $QuerySubeListeGetir = $db->prepare("SELECT
    SubeTablo.SubeId as SubeID,
	SehirTablo.SehirAdi AS SubeIl,
	SehirTablo.SehirId AS IlID,
	CONCAT ( KullaniciBilgileriTablo.KullaniciAdi, ' ', KullaniciBilgileriTablo.KullaniciSoyadi ) AS SubeSorumlusu,
	KullaniciBilgileriTablo.KullaniciId AS GorevliID
FROM
	SubeTablo
	INNER JOIN KullaniciBilgileriTablo ON KullaniciBilgileriTablo.KullaniciId= SubeTablo.[KullaniciBilgileriTablo.KullaniciId]
	INNER JOIN SehirTablo ON SehirTablo.SehirId= SubeTablo.[SehirTablo.SehirId]");
    $QuerySubeListeGetir->execute();
    $subeListe = array();
    if ($QuerySubeListeGetir->rowCount()) {
        foreach ($QuerySubeListeGetir as $RowSubeBilgileri) {
            $subeListe[] = $RowSubeBilgileri;
        }
    }
    $Sonuc["Sonuc"] = "basarili";
    $Sonuc["Aciklama"] = '';
    $Sonuc["HataKodu"] = -1;
    $Sonuc["SubeListe"] = $subeListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
