<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["SehirID"]) &&
        isset($_POST["Ad"]) &&
        isset($_POST["Soyad"]) &&
        isset($_POST["EPosta"]) &&
        isset($_POST["Tel"]) &&
        isset($_POST["Adres"]) 
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryBagisciEkle = $db->prepare("INSERT INTO  KullaniciBilgileriTablo
	(KullaniciBilgileriTablo.[SehirTablo.SehirId] ,
	KullaniciBilgileriTablo.KullaniciEPosta ,
	KullaniciBilgileriTablo.KullaniciAdres ,
	KullaniciBilgileriTablo.KullaniciTelefonNumarasi ,
	KullaniciBilgileriTablo.KullaniciAdi ,
	KullaniciBilgileriTablo.BagisciMi ,
	KullaniciBilgileriTablo.KullaniciSoyadi ) 
	VALUES (
	:SehirID,
    :EPosta,
    :KullaniciAdres,
    :Tel,
    :KullaniciAdi,
    1,
    :KullaniciSoyadi
	)");
    $QueryBagisciEkle->bindParam(":SehirID",
        $_POST["SehirID"],PDO::PARAM_INT);
    $QueryBagisciEkle->bindParam(":EPosta",
        $_POST["EPosta"],PDO::PARAM_STR);
    $QueryBagisciEkle->bindParam(":KullaniciAdres",
        $_POST["KullaniciAdres"],PDO::PARAM_STR);
    $QueryBagisciEkle->bindParam(":Tel",
        $_POST["Tel"],PDO::PARAM_STR);
    $QueryBagisciEkle->bindParam(":KullaniciAdi",
        $_POST["Ad"],PDO::PARAM_STR);
    $QueryBagisciEkle->bindParam(":KullaniciSoyadi",
        $_POST["Soyad"],PDO::PARAM_STR);
    $QueryBagisciEkle->execute();
    if($QueryBagisciEkle)
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
