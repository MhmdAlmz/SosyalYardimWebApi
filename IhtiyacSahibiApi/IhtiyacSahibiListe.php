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

    $QueryIhtiyacSahibiListeGetir = $db->prepare("SELECT
	IhtiyacSahibiTablo.IhtiyacSahibiId AS IhtiyacSahibiID,
	IhtiyacSahibiTablo.IhtiyacSahibiAdi AS Adi,
	IhtiyacSahibiTablo.IhtiyacSahibiSoyadi AS Soyadi,
	IhtiyacSahibiTablo.IhtiyacSahibiAciklama AS Aciklama,
	IhtiyacSahibiTablo.IhtiyacSahibiAdres AS Adres,
	IhtiyacSahibiTablo.[SehirTablo.SehirId] AS SehirID,
	IhtiyacSahibiTablo.IhtiyacSahibiTelNo AS TelNo,
	SehirTablo.SehirAdi as SehirAd
FROM
	IhtiyacSahibiTablo
	INNER JOIN SehirTablo ON SehirTablo.SehirId= IhtiyacSahibiTablo.[SehirTablo.SehirId]");
    $QueryIhtiyacSahibiListeGetir->execute();
    $ihtiyacSahibiListe = array();
    if ($QueryIhtiyacSahibiListeGetir->rowCount()) {
        foreach ($QueryIhtiyacSahibiListeGetir as $RowIhtiyacSahibiBilgileri) {
            $ihtiyacSahibiListe[] = $RowIhtiyacSahibiBilgileri;
        }
    }
    $Sonuc["Sonuc"] = "basarili";
    $Sonuc["Aciklama"] = '';
    $Sonuc["HataKodu"] = -1;
    $Sonuc["IhtiyacSahibiListe"] = $ihtiyacSahibiListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
