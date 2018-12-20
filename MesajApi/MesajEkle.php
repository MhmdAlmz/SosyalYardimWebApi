<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["AlanID"]) &&
        isset($_POST["Baslik"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");


    $QueryMesajEkle = $db->prepare("
    INSERT INTO ChatMesajTablo ( 
ChatMesajTablo.GondericiID, 
ChatMesajlarTablo.AliciID, 
ChatMesajTablo.Baslik,
ChatMesajlarTablo.Tarih 
)
VALUES
	(
		(SELECT
	KullaniciBilgileriTablo.KullaniciId 
FROM
	KullaniciBilgileriTablo 
WHERE
	KullaniciBilgileriTablo.AndroidToken=:AndroidToken),
		:AliciID,
		:Baslik,
       CURRENT_TIMESTAMP
	) ");
    $QueryMesajEkle->bindParam(":AndroidToken", $_POST["AndroidToken"], PDO::PARAM_INT);
    $QueryMesajEkle->bindParam(":AliciID", $_POST["AlanID"], PDO::PARAM_INT);
    $QueryMesajEkle->bindParam(":Baslik", $_POST["Baslik"], PDO::PARAM_INT);
   $QueryMesajEkle->execute();
    if ($QueryMesajEkle) {
        $Sonuc["Sonuc"] = "basarili";
        $Sonuc["Aciklama"] = "basarili";
        $Sonuc["HataKodu"] = -1;
    } else {
        $Sonuc["Sonuc"] = "hata";
        $Sonuc["Aciklama"] = $HataKodlari[4];
        $Sonuc["HataKodu"] = 4;
    }

}

print_r(json_encode($Sonuc));
