<?
function PR($o, $show = false) //печать массивов
{
    global $USER;
    if ($USER->isAdmin() || $show) {
        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        ?>
        <div style='font-size: 12px;font-family: monospace;width: 100%;color: #181819;background: #EDEEF8;border: 1px solid #006AC5;'>
            <div style='padding: 5px 10px;font-size: 10px;font-family: monospace;background: #006AC5;font-weight:bold;color: #fff;'>
                File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]
            </div>
            <pre style='padding:10px;'><? print_r($o) ?></pre>
        </div>
        <?
    } else {
        return false;
    }
}

function URL($url = true)
{
    global $APPLICATION;
    if ($url === false) {
        $page_url = explode('/', $APPLICATION->GetCurPage());
        return $page_url;
    } elseif ($url === true) {
        $page_url = $APPLICATION->GetCurPage();
        return $page_url;
    } else {
        return false;
    }
}

function resizeImage($id, $width, $height, $type = 3)
{
    $arReturn = array();
    if (is_array($id)) {
        $arReturn["ALT"] = $id["ALT"];
        $arReturn["TITLE"] = $id["TITLE"];
    }
    $type = $type >= 1 && $type <= 3 ? $type : 3;
    $arTypeResize = array(
        1 => BX_RESIZE_IMAGE_EXACT,
        2 => BX_RESIZE_IMAGE_PROPORTIONAL,
        3 => BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
    );
    return array_merge(
        $arReturn,
        array_change_key_case(CFile::ResizeImageGet(
            $id,
            array("width" => $width, "height" => $height),
            $arTypeResize[$type],
            true,
            false,
            false,
            100
        ),
            CASE_UPPER
        )
    );
}

function hideH1(){
	global $APPLICATION;
	if($APPLICATION->GetProperty("hide_h1") != "Y"){
		return '<div class="page-title"><h1>'.$APPLICATION->GetTitle().'</h1></div>';
	}
}

function getClassLayout(){
	global $APPLICATION;
	$classLayout = $APPLICATION->GetProperty("LAYOUT-CLASS");
	return $classLayout;
}

function plural($number, $one, $two, $five) {
	if (($number - $number % 10) % 100 != 10) {
		if ($number % 10 == 1) {
			$result = $one;
		} elseif ($number % 10 >= 2 && $number % 10 <= 4) {
			$result = $two;
		} else {
			$result = $five;
		}
	} else {
		$result = $five;
	}
	return $result;
}

/*
 * При первом обращении получает все ID инфоюлоков (для текущего сайта)
 * для того что бы уменьшить кол-во обращений к БД
 */
function getIblockID($iblockCode)
{
    static $arIblock = [];
    $result = false;

    if(!empty($arIblock[$iblockCode])){
        $result = $arIblock[$iblockCode];
    }
    else if(empty($arIblockID) && \Bitrix\Main\Loader::includeModule('iblock')){
        $o = \CIBlock::GetList([]);
        while($r = $o->Fetch()){
            $arIblock[$r['CODE']] = (int)$r['ID'];
        }

        if(!empty($arIblock[$iblockCode])){
            $result = $arIblock[$iblockCode];
        }
    }

    return $result;
}
?>
