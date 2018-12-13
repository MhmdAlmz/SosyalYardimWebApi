<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
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

    $QueryIhtiyacSahibiEkle = $db->prepare("INSERT INTO IhtiyacSahibiTablo (
	IhtiyacSahibiTablo.IhtiyacSahibiAdi,
	IhtiyacSahibiTablo.IhtiyacSahibiSoyadi,
    IhtiyacSahibiTablo.IhtiyacSahibiTelNo,
	IhtiyacSahibiTablo.[SehirTablo.SehirId],
	IhtiyacSahibiTablo.IhtiyacSahibiAdres,
	IhtiyacSahibiTablo.IhtiyacSahibiAciklama
)
VALUES(
    :Adi,
    :Soyadi,
    :TelNo,
    :SehirID,
    :Adres,
    :Aciklama
)
");
    $QueryIhtiyacSahibiEkle->bindParam(":Adi", $_POST["Adi"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":Soyadi", $_POST["Soyadi"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":TelNo", $_POST["TelNo"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":SehirID", $_POST["SehirID"],PDO::PARAM_INT);
    $QueryIhtiyacSahibiEkle->bindParam(":Adres", $_POST["Adres"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->bindParam(":Aciklama", $_POST["Aciklama"],PDO::PARAM_STR);
    $QueryIhtiyacSahibiEkle->execute();
    if($QueryIhtiyacSahibiEkle)
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
