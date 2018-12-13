<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
    isset($_POST["IhtiyacSahibiID"])
    )&&$_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QuerySil=$db->prepare("DELETE IhtiyacSahibiTablo 
WHERE
	IhtiyacSahibiTablo.IhtiyacSahibiId= IhtiyacSahibiID");
    $QuerySil->bindParam(":IhtiyacSahibiID",$_POST["IhtiyacSahibiID"],PDO::PARAM_INT);
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
