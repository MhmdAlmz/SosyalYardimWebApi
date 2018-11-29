<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["IlID"])&&
        isset($_POST["KullaniciID"])

    )&&$_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryEkle=$db->prepare("INSERT INTO SubeTablo 
( SubeTablo.[KullaniciBilgileriTablo.KullaniciId], SubeTablo.[SehirTablo.SehirId] )
VALUES
	(:KullaniciID,:IlID)");
    $QueryEkle->bindParam(":IlID",$_POST["IlID"],PDO::PARAM_INT);
    $QueryEkle->bindParam(":KullaniciID",$_POST["KullaniciID"],PDO::PARAM_INT);
    $QueryEkle->execute();
    if($QueryEkle)
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
