<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["EsyaId"])&&
        isset($_POST["Miktar"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryEsyaEkle = $db->prepare("INSERT INTO DepoTablo (
	[SehirTablo.SehirId],
	[EsyaTablo.EsyaId],
	Adet
)
VALUES(
   (	Select KullaniciBilgileriTablo.[SehirTablo.SehirId] from KullaniciBilgileriTablo
	where KullaniciBilgileriTablo.AndroidToken=:AndroidToken),
       :EsyaId,
       :Miktar
)
");
    $QueryEsyaEkle->bindParam(":AndroidToken", $_POST["AndroidToken"],PDO::PARAM_STR);
    $QueryEsyaEkle->bindParam(":EsyaId", $_POST["EsyaId"],PDO::PARAM_INT);
    $QueryEsyaEkle->bindParam(":Miktar", $_POST["Miktar"],PDO::PARAM_INT);
    $QueryEsyaEkle->execute();
    if($QueryEsyaEkle)
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
