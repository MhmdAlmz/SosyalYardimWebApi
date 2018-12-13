<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
    isset($_POST["EsyaId"])
    )&&$_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QuerySil=$db->prepare("DELETE EsyaTablo 
WHERE
	EsyaTablo.EsyaId= :EsyaId");
    $QuerySil->bindParam(":EsyaId",$_POST["EsyaId"],PDO::PARAM_INT);
    $QuerySil->execute();
    if($QuerySil)
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
