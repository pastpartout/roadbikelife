<?php

class modBottomBarHelper {
    public static function setModuleParams($module) {
        $newModule = clone $module;
        $newModuleParams = json_decode($newModule->params);
        $newModuleParams->style = 'html5';
        $newModule->params = json_encode($newModuleParams);
        return $newModule;
    }
}