<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["EsyaDepoId"]) &&
        isset($_POST["Miktar"]) &&
        isset($_POST["EsyaId"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryEsyaGuncelle = $db->prepare("UPDATE DepoTablo SET
    [EsyaTablo.EsyaId]=:EsyaId,
    Adet=:Miktar
WHERE
DepoEsyaId=:EsyaDepoId");
    $QueryEsyaGuncelle->bindParam(":EsyaId", $_POST["EsyaId"],PDO::PARAM_INT);
    $QueryEsyaGuncelle->bindParam(":Miktar", $_POST["Miktar"],PDO::PARAM_INT);
    $QueryEsyaGuncelle->bindParam(":EsyaDepoId", $_POST["EsyaDepoId"],PDO::PARAM_INT);
    $QueryEsyaGuncelle->execute();
    if($QueryEsyaGuncelle)
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
