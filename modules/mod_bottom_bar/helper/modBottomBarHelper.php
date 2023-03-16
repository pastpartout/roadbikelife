<?php

class modBottomBarHelper {
    public static function setModuleParams($module) {
        $newModule = clone $module;
        $newModuleParams = json_decode($newModule->params);
        $newModuleParams->bootstrap_size = '';
        $newModuleParams->moduleclass_sfx = 'module-wrapper-dark-rounded';
        $newModule->params = json_encode($newModuleParams);
        return $newModule;
    }
}