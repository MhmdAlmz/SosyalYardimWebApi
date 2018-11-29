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

    $QuerySehirListeGetir = $db->prepare("SELECT
	SehirTablo.SehirId AS ID,
	SehirTablo.SehirAdi AS Ad 
FROM
	SehirTablo");
    $QuerySehirListeGetir->execute();
    $sehirListe = array();
    if ($QuerySehirListeGetir->rowCount()) {
        foreach ($QuerySehirListeGetir as $RowSehirBilgileri) {
            $sehirListe[] = $RowSehirBilgileri;
        }
    }
    $Sonuc["Sonuc"] = "basarili";
    $Sonuc["HataKodu"] = -1;
    $Sonuc["SehirListe"] = $sehirListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
