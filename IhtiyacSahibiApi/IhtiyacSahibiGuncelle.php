<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["IhtiyacSahibiID"]) &&
        isset($_POST["Adi"]) &&
        isset($_POST["Soyadi"]) &&
        isset($_POST["TelNo"]) &&
        isset($_POST["SehirID"]) &&
        isset($_POST["Adres"]) &&
        isset($_POST["Aciklama"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryIhtiyacSahibiGuncelle = $db->prepare("UPDATE IhtiyacSahibiTablo SET
	IhtiyacSahibiTablo.IhtiyacSahibiAdi=:Adi,
	IhtiyacSahibiTablo.IhtiyacSahibiSoyadi=:Soyadi,
    IhtiyacSahibiTablo.IhtiyacSahibiTelNo=:TelNo,
	IhtiyacSahibiTablo.[SehirTablo.SehirId]=:SehirID,
	IhtiyacSahibiTablo.IhtiyacSahibiAdres=:Adres,
	IhtiyacSahibiTablo.IhtiyacSahibiAciklama=:Aciklama
WHERE
IhtiyacSahibiTablo.IhtiyacSahibiId=:IhtiyacSahibiID");
    $QueryIhtiyacSahibiEkle->bindParam(":IhtiyacSahibiID", $_POST["IhtiyacSahibiID"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":Adi", $_POST["Adi"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":Soyadi", $_POST["Soyadi"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":TelNo", $_POST["TelNo"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":SehirID", $_POST["SehirID"],PDO::PARAM_INT);
    $QueryIhtiyacSahibiEkle->bindParam(":Adres", $_POST["Adres"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":Aciklama", $_POST["Aciklama"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiGuncelle->execute();
    if($QueryIhtiyacSahibiGuncelle)
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
