<? require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php");
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", array("MyClass", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("MyClass", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("MyClass", "OnAfterIBlockElementUpdateHandler"));
AddEventHandler("search", "OnAfterIndexAdd", ["SearchTitleExtender", "OnAfterIndexAdd"]);
// AddEventHandler("main", "OnPanelCreate", "OnAdminContextMenuShow");

CModule::IncludeModule('iblock');
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

	$lastLog = CEventLog::GetList(
		['ID' => 'DESC'],
		['AUDIT_TYPE_ID' => 'ex2_590'],
		false
	);
	$lastDate = $lastLog->Fetch()["TIMESTAMP_X"];

	$reviewsReq =  CIBlockElement::GetList(
		[],
		['=IBLOCK_CODE' => 'reviews', ">TIMESTAMP_X" => date("d.m.Y H:i:s", strtotime($lastDate))],
		false,
		[],
		['ID']
	);

	$reviewsRes = [];
	while ($next = $reviewsReq->GetNext()) {
		$reviewsRes[] = $next;
	}

	CEventLog::Add(array(
		"SEVERITY" => "INFO",
		"AUDIT_TYPE_ID" => "ex2_590",
		"MODULE_ID" => "iblock",
		"DESCRIPTION" => GetMessage("REVIEWS_CHANGE", ['#DATE#' => $lastDate, "#NUMBER#" => count($reviewsRes)]),
	));
	return "Agent_ex_610();";
}


class SearchTitleExtender
{
	public static function OnAfterIndexAdd($searchContentId, &$arFields)
	{
		if ($arFields['PARAM2'] != REVIEWS_IBLOCK_ID) {
			return;
		}

		$arSelect = ["ID", "IBLOCK_ID", "PROPERTY_AUTHOR", "PROPERTY_AUTHOR_VALUE", "NAME"];
		$arFilter = ['ID' => $arFields['ITEM_ID']];
		$resReview = CIBlockElement::GetList([], $arFilter, false, [], $arSelect)->GetNext();

		$usersFilter = ["ID" => $resReview['PROPERTY_AUTHOR_VALUE']];
		$usersParams = ["SELECT" => ["UF_USER_CLASS"]];
		$resAuthor = CUser::GetList("ID", 'asc', $usersFilter, $usersParams)->GetNext();
		$UFName = CUserFieldEnum::GetList(
			[],
			['ID' => $resAuthor['UF_USER_CLASS'], 'USER_FIELD_ID' => 17]
		)->GetNext()['VALUE'];

		$cleanName = preg_replace(
			'/(\s*\.\s*Класс:\s*[^\.\n]*)$/u',
			'',
			$resReview['NAME']
		);
		$cleanName = trim($cleanName);

		$newName = $cleanName
			? $cleanName . '. Класс: ' . $UFName
			: 'Класс: ' . $UFName;

		$el = new CIBlockElement;
		$updateRes = $el->Update($resReview['ID'], ['NAME' => $newName], false, false);
	}
}


AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenu');

function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
{
	global $USER;
	$userID = $USER->GetID();
	$arGroups = CUser::GetUserGroup($userID);
	if (in_array(CONTENT_MANAGER_GROUP_ID, $arGroups)) {

		$aGlobalMenu = [
			'global_menu_content' => $aGlobalMenu['global_menu_content'],
			'global_menu_quick' => [
				"menu_id" => "quick",
				"page_icon" => "settings_title_icon",
				"index_icon" => "settings_page_icon",
				"text" => "Быстрый доступ",
				"title" => "Быстрый доступ",
				"sort" => "200",
				"items_id" => "global_menu_quick",
				"help_section" => "quick",
				"items" => []
			]
		];



		$aModuleMenu = array_filter(
			$aModuleMenu,
			function ($item) {
				return isset($item['parent_menu']) && $item['parent_menu'] === 'global_menu_content';
			}
		);

		$aModuleMenu[] = [
			"parent_menu" => "global_menu_quick",
			"sort" => "100",
			"text" => "Ссылка 1",
			"title" => "Ссылка 1",
			"url" => "https://test1"
		];
		$aModuleMenu[] = [
			"parent_menu" => "global_menu_quick",
			"sort" => "200",
			"text" => "Ссылка 2",
			"title" => "Ссылка 2",
			"url" => "https://test2"
		];
	}
	writeToLog($aModuleMenu);
}

function writeToLog($data, $filename = 'debug')
{
	// Папка для логов
	$logDir = $_SERVER['DOCUMENT_ROOT'] . '/local/log/';

	// Убедимся, что папка существует
	if (!is_dir($logDir)) {
		mkdir($logDir, 0755, true);
	}

	// Имя файла: debug.log, или ваше имя + .log
	$filePath = $logDir . $filename . '.log';

	// Форматируем дату
	$date = date('Y-m-d H:i:s');

	// Преобразуем данные в читаемый вид
	$output = "==========================\n";
	$output .= "Дата: {$date}\n";
	$output .= "--------------------------\n";

	if (is_array($data) || is_object($data)) {
		// Красивый вывод массива/объекта
		$output .= print_r($data, true);
	} else {
		$output .= $data;
	}

	$output .= "==========================\n\n";

	// Записываем в файл (режим 'a' — добавление)
	file_put_contents($filePath, $output, FILE_APPEND | LOCK_EX);
}
