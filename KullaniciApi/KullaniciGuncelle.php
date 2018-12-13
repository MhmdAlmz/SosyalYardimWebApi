<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["KullaniciID"]) &&
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

    $QueryKullaniciGuncelle = $db->prepare("UPDATE KullaniciBilgileriTablo SET
	KullaniciBilgileriTablo.[SehirTablo.SehirId] = :SehirID,
	KullaniciBilgileriTablo.KullaniciEPosta = :EPosta,
	KullaniciBilgileriTablo.KullaniciTelegramKullaniciAdi = :TelegramKullaniciAdi,
	KullaniciBilgileriTablo.KullaniciTCKimlikNumarasi = :TCKimlikNo,
	KullaniciBilgileriTablo.KullaniciMerkezdeMi = :Merkezde,
	KullaniciBilgileriTablo.KullaniciOnayliMi = :Onayli,
	KullaniciBilgileriTablo.KullaniciTelefonNumarasi = :Tel,
	KullaniciBilgileriTablo.KullaniciAdi = :KullaniciAdi,
	KullaniciBilgileriTablo.KullaniciSoyadi = :KullaniciSoyadi 
WHERE
KullaniciBilgileriTablo.KullaniciId=:KullaniciID");
    $QueryKullaniciGuncelle->bindParam(":KullaniciID",
        $_POST["KullaniciID"],PDO::PARAM_INT);
    $QueryKullaniciGuncelle->bindParam(":SehirID",
        $_POST["SehirID"],PDO::PARAM_INT);
    $QueryKullaniciGuncelle->bindParam(":EPosta",
        $_POST["EPosta"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":TelegramKullaniciAdi",
        $_POST["TelegramKullaniciAdi"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":TCKimlikNo",
        $_POST["TCKimlikNo"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":Merkezde",
        $_POST["Merkezde"],PDO::PARAM_INT);
    $QueryKullaniciGuncelle->bindParam(":Onayli",
        $_POST["Onayli"],PDO::PARAM_INT);
    $QueryKullaniciGuncelle->bindParam(":Tel",
        $_POST["Tel"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":KullaniciAdi",
        $_POST["KullaniciAdi"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":KullaniciSoyadi",
        $_POST["KullaniciSoyadi"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->execute();
    if($QueryKullaniciGuncelle)
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
