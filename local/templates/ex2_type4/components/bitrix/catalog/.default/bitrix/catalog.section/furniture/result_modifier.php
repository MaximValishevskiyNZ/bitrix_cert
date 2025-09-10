<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as $key => $arItem)
{
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] = number_format((float)$arItem['PRICES']['PRICE']['PRINT_VALUE'], 0, '.', ' ');
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] .= ' '.$arItem['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

	$arResult['ITEMS'][$key] = $arItem;
}


// Мой код


$arSelect = ["ID", "IBLOCK_ID", "NAME", "PROPERTIES", "PROPERTY_AUTHOR", "PROPERTY_PRODUCT"];
$res = [];
$count = 0;
$approvedAuthors = [];
$firstReview = [];

//Запрос рецензий
$arSelect = ["ID", "IBLOCK_ID", "NAME", "PROPERTIES", "PROPERTY_AUTHOR", "PROPERTY_PRODUCT"];
$arrayProductsIds = array_column($arResult["ITEMS"], "ID");

// Проверяем, что есть ID товаров
if (!empty($arrayProductsIds)) {
    $arFilter = ["IBLOCK_CODE" => "reviews", "PROPERTY_PRODUCT" => $arrayProductsIds];
    $req = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
    $res = [];
    while($ob = $req->GetNext()) {
        $res[] = $ob;
    }

    // Фильтрация авторов
    $arrayAuthorIds = array_unique(array_column($res, "PROPERTY_AUTHOR_VALUE"));
    if (!empty($arrayAuthorIds)) {
        $arrayAuthorIdsStr = implode(' | ', $arrayAuthorIds);
        $usersFilter = [
            "ID" => $arrayAuthorIdsStr,
            "GROUPS_ID" => [GROUP_AUTHORS_ID],
            "UF_AUTHOR_STATUS" => [PUBLISHED_AUTHOR_STATUS_ID]
        ];
        $usersParams = ["FIELDS" => ["ID"]];
        $authorsFiltered = [];
        $req = CUser::GetList("ID", 'asc', $usersFilter, $usersParams);
        while($ob = $req->GetNext()) {
            $authorsFiltered[] = $ob["ID"];
        }

        // Фильтрация рецензий
        $res = array_filter($res, function($review) use ($authorsFiltered) {
            return in_array($review["PROPERTY_AUTHOR_VALUE"], $authorsFiltered);
        });

        foreach ($res as $review) {
            if ($arResult["ITEMS"][array_search($review["PROPERTY_PRODUCT_VALUE"], $arrayProductsIds)]["REVIEWS"][] = $review) {
                if (!$count) {
                    $firstReview = $review;
                }
                $count++;
            }
        }
    }
}


$prop = $APPLICATION->GetProperty('ex2_meta');
if(str_contains($prop, '#count#')) {
	$prop = str_replace("#count#", $count, $prop);
	$APPLICATION->SetPageProperty('ex2_meta', $prop);
}


if($firstReview) {
$this->SetViewTarget('review_block');?>
	<div id="filial-special" class="information-block">
	<div class="top"></div>
	<div class="information-block-inner">
		<h3>Дополнительно</h3>
		<div class="special-product">
			<div class="special-product-title">
				<?= $firstReview["NAME"] ?>
			</div>
		</div>
	</div>
	<div class="bottom"></div>
</div>
<?$this->EndViewTarget(); 
}




?>

<pre>
	<? //print_r($res);?>
</pre>
