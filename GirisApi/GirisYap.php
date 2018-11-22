<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["KullaniciEPosta"]) ||
        isset($_POST["Sifre"])

    )&&$_SERVER['REQUEST_METHOD'] !== 'POST'
) {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    $kullaniciEPosta = $_POST["KullaniciEPosta"];
    $sifre = $_POST["Sifre"];
    $QueryGirisBilgileriGetir = $db->prepare("SELECT
	KullaniciBilgileriTablo.AndroidToken AS Token ,
	KullaniciBilgileriTablo.KullaniciAdi AS KullaniciAdi ,
	KullaniciBilgileriTablo.KullaniciSoyadi AS KullaniciSoyadi 
FROM
	KullaniciBilgileriTablo 
WHERE
	KullaniciBilgileriTablo.KullaniciEPosta= :KullaniciEPosta 
	AND KullaniciBilgileriTablo.KullaniciSifre= :Sifre");
    $QueryGirisBilgileriGetir->bindParam(":KullaniciEPosta", $kullaniciEPosta, PDO::PARAM_STR);
    $QueryGirisBilgileriGetir->bindParam(":Sifre", $sifre, PDO::PARAM_STR);
    $QueryGirisBilgileriGetir->execute();
    if ($QueryGirisBilgileriGetir->rowCount()) {
        $Sonuc["Sonuc"] = "basarili";
        $Sonuc["HataKodu"] = -1;
        foreach ($QueryGirisBilgileriGetir as $RowGirisBilgileri) {
            $Sonuc["KullaniciBilgileri"] = [
                'AndroidToken' => $RowGirisBilgileri["Token"],
                'KullaniciAdi' => $RowGirisBilgileri["KullaniciAdi"],
                'KullaniciSoyadi' => $RowGirisBilgileri["KullaniciSoyadi"]
            ];
            break;
        }

    } else {
        $Sonuc["Sonuc"] = "hata";
        $Sonuc["Aciklama"] = $HataKodlari[2];
        $Sonuc["HataKodu"] = 2;
    }
}

print_r(json_encode($Sonuc));


$db = null;







