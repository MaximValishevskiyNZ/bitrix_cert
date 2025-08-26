<div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

              <div class="d-flex py-4 px-4">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>№</th>
                      <th>Товар</th>
                      <th>Категория</th>
                      <th>Город</th>
                      <th>Количество</th>
                    </tr>
                  </thead>
                  <tbody>
                    <? foreach($arResult["ITEMS"] as $arItem): ?>
                        <tr>
                            <td><?=$arItem["NAME"]?></td>
                            <td><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["PROPERTIES"]["PRODUCT"]["VALUE"]?></a></td>
                            <td><?=$arItem["PROPERTIES"]["PRODUCT_CATEGORY"]["VALUE"]?></td>
                            <td><?=$arItem["PROPERTIES"]["CITY"]["VALUE"]?></td>
                            <td><?=$arItem["PROPERTIES"]["QUANTITY"]["VALUE"]?></td>
                        </tr>
                    <? endforeach; ?>
                  </tbody>
                </table>
              </div>
         
              <div class="d-flex py-2 px-4 flex-column">
                  <?=$arResult["NAV_STRING"]?>
              </div>

            </div>
          </div>

        </div>
      </div>

<pre>
    <? print_r($arResult) ?>
</pre>