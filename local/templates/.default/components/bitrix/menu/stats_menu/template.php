<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
  $currentDepth = 1;
  $menuId = 0 
?>
<ul class="sidebar-nav" id="sidebar-nav">

  <? foreach ($arResult as $arItem): ?>

    <? if ($currentDepth > $arItem["DEPTH_LEVEL"]): ?>
      </ul>
    </li>
    <? endif ?>

<? if (1 != $arItem["DEPTH_LEVEL"]): ?>

    <!-- Пункт меню второй уровень -->
    <li>
      <a class="<?=$arItem["SELECTED"] ? 'active' : ""?>" href="<?=$arItem["LINK"]?>">
        <i class="bi bi-circle"></i><span><?= $arItem["TEXT"] ?></span>
      </a>
    </li>

  <? elseif ($arItem["IS_PARENT"]): ?>

    <!-- Пункт меню папочка -->
    <li class="nav-item">
        <a class="nav-link <?=$arItem["SELECTED"] ? '' : "collapsed"?>" data-bs-target="#<?=$menuId?>" data-bs-toggle="collapse" href="<?=$arItem["LINK"]?>">
          <i class="bi <?= $arItem["PARAMS"]["menu_ico"] ?>"></i><span><?=$arItem["TEXT"]?></span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="<?=$menuId?>" class="nav-content collapse <?=$arItem["SELECTED"] ? 'show' : ""?>" data-bs-parent="#sidebar-nav">
    <? $menuId++ ?>

  <? else: ?>

    <!-- Пункт меню первый уровень -->
    <li class="nav-item">
      <a class="nav-link <?=$arItem["SELECTED"] ? '' : "collapsed"?>" href="<?=$arItem["LINK"]?>">
        <i class="bi <?= $arItem["PARAMS"]["menu_ico"] ?>"></i>
        <span><?= $arItem["TEXT"] ?></span>
      </a>
    </li>

  <? endif ?>
    
    <? $currentDepth = $arItem["DEPTH_LEVEL"] ?>
<? endforeach; ?>


<? if ($currentDepth != 1): ?>
    </ul>
    </li>
<? endif ?>

</ul>
 