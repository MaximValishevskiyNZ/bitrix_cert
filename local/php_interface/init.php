<? require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php");
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", array("MyClass", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("MyClass", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("MyClass", "OnAfterIBlockElementUpdateHandler"));
class MyClass
{
	protected static $oldAuthorID;
	public static function OnBeforeIBlockElementAddHandler(&$arFields)
	{
		if ($arFields["IBLOCK_ID"] == REVIEWS_IBLOCK_ID) {
			global $APPLICATION;
			if (mb_strlen($arFields["PREVIEW_TEXT"]) < 5) {
				$APPLICATION->throwException("Текст анонса слишком короткий: " . mb_strlen($arFields["PREVIEW_TEXT"]));
				return false;
			}
			$arFields["PREVIEW_TEXT"] = str_replace("#del#", "", $arFields["PREVIEW_TEXT"]);
		}
	}


	public static function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{
		if ($arFields["IBLOCK_ID"] == REVIEWS_IBLOCK_ID) {
			global $APPLICATION;
			if (mb_strlen($arFields["PREVIEW_TEXT"]) < 5) {
				$APPLICATION->throwException("Текст анонса слишком короткий: " . mb_strlen($arFields["PREVIEW_TEXT"]));
				return false;
			}
			$arFields["PREVIEW_TEXT"] = str_replace("#del#", "", $arFields["PREVIEW_TEXT"]);

			$arSelect = ["ID", "PROPERTY_AUTHOR"];
			$arFilter = ["ID" => $arFields['ID']];
			$res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
			$ob = $res->GetNextElement();
			if ($ob) {
				self::$oldAuthorID = $ob->GetFields()['PROPERTY_AUTHOR_VALUE'];
			}
		}
	}


	public static function OnAfterIBlockElementUpdateHandler(&$arFields)
	{
		if ($arFields["IBLOCK_ID"] == REVIEWS_IBLOCK_ID) {
			if (current($arFields['PROPERTY_VALUES'][REVIEWS_IBLOCK_AUTHOR_PROPERTY_ID])["VALUE"] != self::$oldAuthorID)
				CEventLog::Add(array(
					"SEVERITY" => "INFO",
					"AUDIT_TYPE_ID" => "ex2_590",
					"MODULE_ID" => "main",
					"ITEM_ID" => $arFields['ID'],
					"DESCRIPTION" => "В рецензии [" . $arFields['ID'] . "] изменился автор с [" . self::$oldAuthorID . "] на [" . current($arFields['PROPERTY_VALUES'][REVIEWS_IBLOCK_AUTHOR_PROPERTY_ID])["VALUE"] . "]»",
				));
		}
	}
}
function Agent_ex_610()
{
        CEventLog::Add(array(
					"SEVERITY" => "INFO",
					"AUDIT_TYPE_ID" => "ex2_590",
					"MODULE_ID" => "main",
					"ITEM_ID" => '1213',
					"DESCRIPTION" => "giga",
				));
        return "Agent_ex_610();";
}