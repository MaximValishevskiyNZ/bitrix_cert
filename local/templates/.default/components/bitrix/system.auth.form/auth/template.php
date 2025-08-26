 <?if($USER->IsAuthorized()): ?>
    <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2">Ivanov</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?=$arResult["USER_LOGIN"]?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?=$arResult["PROFILE_URL"]?>">
                <i class="bi bi-person"></i>
                <span>Мой профиль</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <div class="col-12 mb-3 mt-3 d-flex justify-content-center">
                <button 
                  class="btn btn-secondary btn-sm"
                  type="submit"
                  name="logout_butt"
                  value="Выйти"   
                >
                  Выйти
                </button>
              </div>
            </li>

          </ul>
        </li>
 <? endif ?>   

        <?
        $data = $arResult;  
$output = json_encode($data);  
echo "<script>console.log('{$output}' );</script>";  
