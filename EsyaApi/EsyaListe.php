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

    $QueryEsyaListeGetir = $db->prepare("SELECT
EsyaTablo.EsyaId,
EsyaTablo.EsyaAdi 
FROM
EsyaTablo
");
    $QueryEsyaListeGetir->execute();
    $EsyaListe = array();
    if ($QueryEsyaListeGetir->rowCount()) {
        foreach ($QueryEsyaListeGetir as $RowEsyaBilgileri) {
            $EsyaListe[] = $RowEsyaBilgileri;
        }
    }
    $Sonuc["Sonuc"] = "basarili";
    $Sonuc["Aciklama"] = '';
    $Sonuc["HataKodu"] = -1;
    $Sonuc["EsyaListe"] = $EsyaListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
