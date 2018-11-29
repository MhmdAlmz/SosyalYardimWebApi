<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
    isset($_POST["SubeID"])&&
    isset($_POST["IlID"])&&
    isset($_POST["KullaniciID"])

    )&&$_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QuerySil=$db->prepare("UPDATE SubeTablo 
SET SubeTablo.[KullaniciBilgileriTablo.KullaniciId] = :KullaniciID,
SubeTablo.[SehirTablo.SehirId] = :IlID 
WHERE
	SubeTablo.SubeId= :SubeID");
    $QuerySil->bindParam(":SubeID",$_POST["SubeID"],PDO::PARAM_INT);
    $QuerySil->bindParam(":IlID",$_POST["IlID"],PDO::PARAM_INT);
    $QuerySil->bindParam(":KullaniciID",$_POST["KullaniciID"],PDO::PARAM_INT);
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
