<?php
/* ****************************************************************************************** */
/* created by soft-solution.ru                                                                */
/* module.php mod_exchange for InstantCMS 1.10                                                */
/* ****************************************************************************************** */

function mod_exchange($module_id){

    $inCore = cmsCore::getInstance();
    $cfg = $inCore->loadModuleConfig($module_id);
    
    if (!isset($cfg['show_flag'])) { $cfg['show_flag'] = 1; }
    if (!isset($cfg['show_charcode'])) { $cfg['show_charcode'] = 1; }
    if (!isset($cfg['show_diff'])) { $cfg['show_diff'] = 1; }
    if (!isset($cfg['server_diff'])) { $cfg['server_diff'] = 0; }//разница сервера с Москвой
    if (!isset($cfg['time_upd'])) { $cfg['time_upd'] = "15:00"; }
    if (!isset($cfg['time_upd_stop'])) { $cfg['time_upd_stop'] = "17:00"; }
    
    if (!isset($cfg['USD'])) { $cfg['USD'] = 1;}
    if (!isset($cfg['EUR'])) { $cfg['EUR'] = 1;}
    
    $currency = getExchange();

    $last_update = $currency['AZN']['last_update'];
    $today=date("Y-m-d");

    $dayofweek = date("w");
    $correct_time = date("H") + $cfg['server_diff'];
    
    //обновляем во все дни кроме субботы и воскресенья
    if($today!=$last_update && $dayofweek!=0 && $dayofweek!=6){
       
       if ($correct_time>=$cfg['time_upd'] && $correct_time<=$cfg['time_upd_stop']){
           $currency = updateExchange(date("d/m/Y"));
       }
    }
    
    $val = array();
    $val['AZN'] = $cfg['AZN'];
    $val['AUD'] = $cfg['AUD'];
    $val['GBP'] = $cfg['GBP'];
    $val['AMD'] = $cfg['AMD'];
    $val['BYR'] = $cfg['BYR'];
    $val['BGN'] = $cfg['BGN'];
    $val['BRL'] = $cfg['BRL'];
    $val['HUF'] = $cfg['HUF'];
    $val['DKK'] = $cfg['DKK'];
    $val['USD'] = $cfg['USD'];
    $val['EUR'] = $cfg['EUR'];
    $val['INR'] = $cfg['INR'];
    $val['KZT'] = $cfg['KZT'];
    $val['CAD'] = $cfg['CAD'];
    $val['KGS'] = $cfg['KGS'];
    $val['CNY'] = $cfg['CNY'];
    $val['LVL'] = $cfg['LVL'];
    $val['LTL'] = $cfg['LTL'];
    $val['MDL'] = $cfg['MDL'];
    $val['NOK'] = $cfg['NOK'];
    $val['PLN'] = $cfg['PLN'];
    $val['RON'] = $cfg['RON'];
    $val['XDR'] = $cfg['XDR'];
    $val['SGD'] = $cfg['SGD'];
    $val['TJS'] = $cfg['TJS'];
    $val['TRY'] = $cfg['TRY'];
    $val['TMT'] = $cfg['TMT'];
    $val['UZS'] = $cfg['UZS'];
    $val['UAH'] = $cfg['UAH'];
    $val['CZK'] = $cfg['CZK'];
    $val['SEK'] = $cfg['SEK'];
    $val['CHF'] = $cfg['CHF'];
    $val['ZAR'] = $cfg['ZAR'];
    $val['KRW'] = $cfg['KRW'];
    $val['JPY'] = $cfg['JPY'];

    $is_currency = $currency ? 1 : 0;

    $smarty = $inCore->initSmarty('modules', 'mod_exchange.tpl');
    $smarty->assign('currency', $currency);
    $smarty->assign('is_currency', $is_currency);
    $smarty->assign('val', $val);
    $smarty->assign('cfg', $cfg);
    $smarty->display('mod_exchange.tpl');

    return true;

}

function getExchange() {
    
    $inCore = cmsCore::getInstance();
    $inDB   = cmsDatabase::getInstance();
    
    $sql = "SELECT * FROM cms_exchange";
    $result = $inDB->query($sql) ;
    
    if ($inDB->num_rows($result)) {
        $items = array();
        while ($item = $inDB->fetch_assoc($result)) {
            $item['diff'] = number_format(($item['value'] - $item['value_old']), 4, '.', '');
            $items[$item['charcode']] = $item;
        }
        return  $items;
    } else {
        return  false;
    }
    
}

function updateExchange($day) {
    
    if(!$day) {$day = date("d/m/Y");}
    $xml = file_get_contents("http://www.cbr.ru/scripts/XML_daily.asp?date_req=$day");
    
    $yesterday = date("d/m/Y",strtotime("-1 day"));

    $xml_old = file_get_contents("http://www.cbr.ru/scripts/XML_daily.asp?date_req=$yesterday");
    
    if ($xml){
        
        cmsCore::includefile("modules/mod_exchange/xml.php");
        $xml_parser = new Simple_XMLParser();
        $xml_parser->parse($xml);
        $xml_array = $xml_parser->data['VALCURS'][0]['child'];
        
        $xml_parser->parse($xml_old);
        $xml_array_old = $xml_parser->data['VALCURS'][0]['child'];

        $currency = array();
        foreach ($xml_array['VALUTE'] as $key=>$item) {
            $valuta['charcode']  = $item['child']['CHARCODE'][0]['data'];
            $valuta['nominal']   = $item['child']['NOMINAL'][0]['data'];
            $valuta['value']     = str_replace(',', '.',$item['child']['VALUE'][0]['data']);

            $valuta['value_old'] = str_replace(',', '.',$xml_array_old['VALUTE'][$key]['child']['VALUE'][0]['data']);
            $valuta['diff']      = number_format(($valuta['value'] - $valuta['value_old']), 4, '.', '');

            updateData($valuta);
            $currency[]   = $valuta;
        }

        return $currency;

    } else {
        return false;
    }
}

function updateData($valuta) {
    
    $inCore = cmsCore::getInstance();
    $inDB   = cmsDatabase::getInstance();
    $inDB->query("UPDATE cms_exchange SET nominal = '{$valuta['nominal']}', value = '{$valuta['value']}', value_old = '{$valuta['value_old']}', last_update = NOW() WHERE charcode = '{$valuta['charcode']}'");
    
}

?>