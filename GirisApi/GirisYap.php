<?php

require_once("../HataKodlari.php");
$Sonuc = array();

function gen_uuid() {
    $uuid = array(
        'time_low'  => 0,
        'time_mid'  => 0,
        'time_hi'  => 0,
        'clock_seq_hi' => 0,
        'clock_seq_low' => 0,
        'node'   => array()
    );

    $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
    $uuid['time_mid'] = mt_rand(0, 0xffff);
    $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
    $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
    $uuid['clock_seq_low'] = mt_rand(0, 255);

    for ($i = 0; $i < 6; $i++) {
        $uuid['node'][$i] = mt_rand(0, 255);
    }

    $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
        $uuid['time_low'],
        $uuid['time_mid'],
        $uuid['time_hi'],
        $uuid['clock_seq_hi'],
        $uuid['clock_seq_low'],
        $uuid['node'][0],
        $uuid['node'][1],
        $uuid['node'][2],
        $uuid['node'][3],
        $uuid['node'][4],
        $uuid['node'][5]
    );

    return $uuid;
}



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
	KullaniciBilgileriTablo.KullaniciAdi AS KullaniciAdi ,
	KullaniciBilgileriTablo.KullaniciSoyadi AS KullaniciSoyadi ,
	KullaniciBilgileriTablo.KullaniciId AS KullaniciID,
	COALESCE
	((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Kullanıcı Listesi' 
			),
		0 
	) AS yetkiKullaniciListesi,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Kullanıcı Ekle' 
			),
		0 
	) AS yetkiKullaniciEkle,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Kullanıcı Sil' 
			),
		0 
	) AS yetkiKullaniciSil,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Kullanıcı Düzenle' 
			),
		0 
	) AS yetkiKullaniciDuzenle,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Kullanıcı Detay' 
			),
		0 
	) AS yetkiKullaniciDetay,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Şube Listesi' 
			),
		0 
	) AS yetkiSubeListesi,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Şube Ekle' 
			),
		0 
	) AS yetkiSubeEkle,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Şube Sil' 
			),
		0 
	) AS yetkiSubeSil,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Şube Düzenle' 
			),
		0 
	) AS yetkiSubeDuzenle,
	COALESCE ((
		SELECT
			1 
		FROM
			YetkiTablo
			INNER JOIN RotaTablo ON RotaTablo.RotaId = YetkiTablo.[RotaTablo.RotaId] 
		WHERE
			YetkiTablo.[KullaniciBilgileriTablo.KullaniciId] = KullaniciBilgileriTablo.KullaniciId
			AND RotaTablo.LinkAdi= 'Şube Detay' 
			),
	0 
	) AS yetkiSubeDetay
	
FROM
	KullaniciBilgileriTablo 
WHERE
	KullaniciBilgileriTablo.KullaniciEPosta= :KullaniciEPosta 
    AND KullaniciBilgileriTablo.BagisciMi=0
	AND KullaniciBilgileriTablo.KullaniciSifre= :Sifre");
    $QueryGirisBilgileriGetir->bindParam(":KullaniciEPosta", $kullaniciEPosta, PDO::PARAM_STR);
    $QueryGirisBilgileriGetir->bindParam(":Sifre", $sifre, PDO::PARAM_STR);
    $QueryGirisBilgileriGetir->execute();
    if ($QueryGirisBilgileriGetir->rowCount()) {
        $Sonuc["Sonuc"] = "basarili";
        $Sonuc["HataKodu"] = -1;
        foreach ($QueryGirisBilgileriGetir as $RowGirisBilgileri) {
            $token=gen_uuid();
            $Sonuc["KullaniciBilgileri"] = [
                'AndroidToken' => $token,
                'KullaniciAdi' => $RowGirisBilgileri["KullaniciAdi"],
                'KullaniciSoyadi' => $RowGirisBilgileri["KullaniciSoyadi"],
                'YetkiKullaniciListesi' => $RowGirisBilgileri["yetkiKullaniciListesi"],
                'YetkiKullaniciEkle' => $RowGirisBilgileri["yetkiKullaniciEkle"],
                'YetkiKullaniciSil' => $RowGirisBilgileri["yetkiKullaniciSil"],
                'YetkiKullaniciDuzenle' => $RowGirisBilgileri["yetkiKullaniciDuzenle"],
                'YetkiKullaniciDetay' => $RowGirisBilgileri["yetkiKullaniciDetay"],
                'YetkiSubeListesi' => $RowGirisBilgileri["yetkiSubeListesi"],
                'YetkiSubeEkle' => $RowGirisBilgileri["yetkiSubeEkle"],
                'YetkiSubeSil' => $RowGirisBilgileri["yetkiSubeSil"],
                'YetkiSubeDuzenle' => $RowGirisBilgileri["yetkiSubeDuzenle"],
                'YetkiSubeDetay' => $RowGirisBilgileri["yetkiSubeDetay"]
            ];
            $QueryAndroidTokenGuncelle=$db->prepare("UPDATE KullaniciBilgileriTablo
            SET KullaniciBilgileriTablo.AndroidToken =:Token
            WHERE KullaniciBilgileriTablo.KullaniciId =:KullaniciID");
            $QueryAndroidTokenGuncelle->bindParam(":Token",$token,PDO::PARAM_STR);
            $QueryAndroidTokenGuncelle->bindParam(":KullaniciID",$RowGirisBilgileri["KullaniciID"],PDO::PARAM_INT);
            $QueryAndroidTokenGuncelle->execute();
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







