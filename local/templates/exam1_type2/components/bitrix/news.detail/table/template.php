<style>
        .table-detail .label {
          font-weight: 600;
          color: rgba(1, 41, 112, 0.6);
        }
        .table-detail .row {
          margin-bottom: 20px;
        }
        .table-detail .backurl {
          margin-top: 40px;
          margin-bottom: 20px;
        }
      </style>

<div class="row">
        <div class="col-lg-12">

            <div class="card table-detail">
              <div class="card-body">
                <h5 class="card-title">ID - <?=$arResult["ID"]?></h5>
                <div class="row">
                  <div class="col-2 label">Товар</div>
                  <div class="col-4 "><?=$arResult["PROPERTIES"]["PRODUCT"]["VALUE"]?></div>
                </div>
                <div class="row">
                  <div class="col-2 label">Категория</div>
                  <div class="col-4 "><?=$arResult["PROPERTIES"]["PRODUCT_CATEGORY"]["VALUE"]?></div>
                </div>
                <div class="row">
                  <div class="col-2 label">Город</div>
                  <div class="col-4 "><?=$arResult["PROPERTIES"]["CITY"]["VALUE"]?></div>
                </div>
                <div class="row">
                  <div class="col-2 label">Количество</div>
                  <div class="col-4 "><?=$arResult["PROPERTIES"]["QUANTITY"]["VALUE"]?></div>
                </div>
                <div class="backurl">
                  <a href="<?=$arResult["LIST_PAGE_URL"]?>">Назад к списку</a>
                </div>
              </div>
            </div>
        </div>
      </div>

      <pre>
    <? print_r($arResult) ?>
</pre>