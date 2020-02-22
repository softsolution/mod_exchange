<?php
/* ****************************************************************************************** */
/* created by soft-solution.ru                                                                */
/* install.php mod_exchange for InstantCMS 1.10                                               */
/* ****************************************************************************************** */

    function info_module_mod_exchange(){

        //
        // Описание модуля
        //

        //Заголовок (на сайте)
        $_module['title']        = 'Курс валют';

        //Название (в админке)
        $_module['name']         = 'Курс валют';

        //описание
        $_module['description']  = 'Курс валют получает курс валют с сайта ЦБ РФ раз в сутки';
        
        //ссылка (идентификатор)
        $_module['link']         = 'mod_exchange';
        
        //позиция
        $_module['position']     = 'sidebar';

        //автор
        $_module['author']       = 'soft-solution.ru';

        //текущая версия
        $_module['version']      = '1.0';

        //
        // Настройки по-умолчанию
        //
        $_module['config'] = array();
        $_module['config']['show_flag']        = 1;
        $_module['config']['show_charcode']    = 1;
        $_module['config']['show_diff']        = 1;
        $_module['config']['server_diff']      = 0;
        $_module['config']['time_upd" title']  = '15:00';
        $_module['config']['time_upd_stop']    = '17:00';
        $_module['config']['USD']              = 1;
        $_module['config']['EUR']              = 1;

        return $_module;

    }

// ========================================================================== //

    function install_module_mod_exchange(){
        
        $inCore     = cmsCore::getInstance();
        $inDB       = cmsDatabase::getInstance();
        $inConf     = cmsConfig::getInstance();

        include($_SERVER['DOCUMENT_ROOT'].'/includes/dbimport.inc.php');

        dbRunSQL($_SERVER['DOCUMENT_ROOT'].'/modules/mod_exchange/query.sql', $inConf->db_prefix);

        return true;

    }

// ========================================================================== //

    function upgrade_module_mod_exchange(){

        return true;
        
    }

// ========================================================================== //

?>