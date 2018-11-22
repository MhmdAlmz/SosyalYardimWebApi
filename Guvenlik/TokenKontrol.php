<?php
if (!isset($_POST["AndroidToken"])&&$_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);
} else {
    $AndroidToken = $_POST["AndroidToken"];
    $QueryTokenKontrol = $db->prepare("Select 1 from KullaniciBilgileriTablo
where 	KullaniciBilgileriTablo.AndroidToken=:AndroidToken");
    $QueryTokenKontrol->bindParam(":AndroidToken", $AndroidToken, PDO::PARAM_STR);
    $QueryTokenKontrol->execute();
    if(!$QueryTokenKontrol->rowCount())
    {
        $Sonuc["Sonuc"] = "hata";
        $Sonuc["Aciklama"] = $HataKodlari[3];
        $Sonuc["HataKodu"] = 3;
        $Sonuc=json_encode($Sonuc);
        print_r($Sonuc);
        die();
    }
}
