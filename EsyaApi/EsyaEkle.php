<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["EsyaAdi"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryEsyaEkle = $db->prepare("INSERT INTO EsyaTablo (
	EsyaTablo.EsyaAdi
)
VALUES(
    :EsyaAdi
)
");
    $QueryEsyaEkle->bindParam(":EsyaAdi", $_POST["EsyaAdi"],PDO::PARAM_STR);
    $QueryEsyaEkle->execute();
    if($QueryEsyaEkle)
    {
        $Sonuc["Sonuc"]="basarili";
        $Sonuc["Aciklama"]="basarili";
        $Sonuc["HataKodu"]=-1;
    }else{
        $Sonuc["Sonuc"]="hata";
        $Sonuc["Aciklama"]=$HataKodlari[4];
        $Sonuc["HataKodu"]=4;
    }

}

print_r(json_encode($Sonuc));
