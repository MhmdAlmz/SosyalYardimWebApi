<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryEsyaListeGetir = $db->prepare("SELECT
	[EsyaTablo.EsyaId] AS EsyaId,
	Adet AS Miktar,
	EsyaTablo.EsyaAdi AS EsyaAdi,
	DepoTablo.DepoEsyaId AS EsyaDepoId 
FROM
	DepoTablo
	INNER JOIN EsyaTablo ON DepoTablo.[EsyaTablo.EsyaId] = EsyaTablo.EsyaId 
WHERE
	DepoTablo.[SehirTablo.SehirId] = (
	SELECT
		KullaniciBilgileriTablo.[SehirTablo.SehirId] 
	FROM
		KullaniciBilgileriTablo 
WHERE
	KullaniciBilgileriTablo.AndroidToken= :AndroidToken)
");
    $QueryEsyaListeGetir->bindParam(":AndroidToken", $_POST["AndroidToken"],PDO::PARAM_STR);
    $QueryEsyaListeGetir->execute();
    $EsyaListe = array();
    if ($QueryEsyaListeGetir->rowCount()) {
        foreach ($QueryEsyaListeGetir as $RowEsyaBilgileri) {
            $EsyaListe[] = $RowEsyaBilgileri;
        }
    }
    $Sonuc["Sonuc"] = "basarili";
    $Sonuc["Aciklama"] = '';
    $Sonuc["HataKodu"] = -1;
    $Sonuc["EsyaDepoListe"] = $EsyaListe;
    $Sonuc=json_encode($Sonuc);
    print_r($Sonuc);

}
