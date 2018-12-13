<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["SehirID"]) &&
        isset($_POST["EPosta"]) &&
        isset($_POST["TelegramKullaniciAdi"]) &&
        isset($_POST["TCKimlikNo"]) &&
        isset($_POST["Merkezde"]) &&
        isset($_POST["Onayli"]) &&
        isset($_POST["Tel"]) &&
        isset($_POST["KullaniciAdi"]) &&
        isset($_POST["KullaniciSoyadi"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryKullaniciEkle = $db->prepare("INSERT INTO  KullaniciBilgileriTablo
	(KullaniciBilgileriTablo.[SehirTablo.SehirId] ,
	KullaniciBilgileriTablo.KullaniciEPosta ,
	KullaniciBilgileriTablo.KullaniciTelegramKullaniciAdi ,
	KullaniciBilgileriTablo.KullaniciTCKimlikNumarasi ,
	KullaniciBilgileriTablo.KullaniciMerkezdeMi ,
	KullaniciBilgileriTablo.KullaniciOnayliMi ,
	KullaniciBilgileriTablo.KullaniciTelefonNumarasi ,
	KullaniciBilgileriTablo.KullaniciAdi ,
	KullaniciBilgileriTablo.KullaniciSoyadi ) 
	VALUES (
	:SehirID,
    :EPosta,
    :TelegramKullaniciAdi,
    :TCKimlikNo,
    :Merkezde,
    :Onayli,
    :Tel,
    :KullaniciAdi,
    :KullaniciSoyadi
	)");
    $QueryKullaniciEkle->bindParam(":SehirID",
        $_POST["SehirID"],PDO::PARAM_INT);
    $QueryKullaniciEkle->bindParam(":EPosta",
        $_POST["EPosta"],PDO::PARAM_STR);
    $QueryKullaniciEkle->bindParam(":TelegramKullaniciAdi",
        $_POST["TelegramKullaniciAdi"],PDO::PARAM_STR);
    $QueryKullaniciEkle->bindParam(":TCKimlikNo",
        $_POST["TCKimlikNo"],PDO::PARAM_STR);
    $QueryKullaniciEkle->bindParam(":Merkezde",
        $_POST["Merkezde"],PDO::PARAM_INT);
    $QueryKullaniciEkle->bindParam(":Onayli",
        $_POST["Onayli"],PDO::PARAM_INT);
    $QueryKullaniciEkle->bindParam(":Tel",
        $_POST["Tel"],PDO::PARAM_STR);
    $QueryKullaniciEkle->bindParam(":KullaniciAdi",
        $_POST["KullaniciAdi"],PDO::PARAM_STR);
    $QueryKullaniciEkle->bindParam(":KullaniciSoyadi",
        $_POST["KullaniciSoyadi"],PDO::PARAM_STR);
    $QueryKullaniciEkle->execute();
    if($QueryKullaniciEkle)
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
