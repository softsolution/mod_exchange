{* Модуль за курс валют *}

{* таблица стилей, желательно вынести в главную таблицу *}
{literal}
<style>
.mod_exchange{}
.mod_exchange span{padding: 0 5px 0 0;}
.exchange_head {
    padding: 0 6px 3px 0;
    margin-bottom: 1px;
    display: block;
}
.exchange_head span {
    font-weight: bold;
}
</style>
{/literal}

<div class="mod_exchange">

    {if $is_currency}
    <div class="exchange_head"> курсы валют на <span>сегодня</span> </div>
    <table>
        <tbody>
        {foreach key=tid item=valuta from=$currency}
           {if $val[$valuta.charcode]==1}
           <tr>
                {if $cfg.show_flag}
                <td class="flag-td">
                    <span><img src="/modules/mod_exchange/images/flag/{$valuta.charcode}.png"></span>
                </td>
               {/if}
               
                {if $cfg.show_simbols}
                <td class="simbols-td">
                    <span>
                        {if $valuta.charcode=='AUD'}&#163;
                        {elseif $valuta.charcode=='USD'} $
                        {elseif $valuta.charcode=='EUR'} &#8364;{*€*}
                        {elseif $valuta.charcode=='INR'} &#8360;
                        {elseif $valuta.charcode=='CNY'} &#65509;
                        {elseif $valuta.charcode=='PLN'} z&#x142;
                        {elseif $valuta.charcode=='TRY'} &#8356;
                        {elseif $valuta.charcode=='UAH'} &#8372;
                        {elseif $valuta.charcode=='KRW'} &#8361;
                        {elseif $valuta.charcode=='JPY'} &#165;
                        {else}
                        {/if}
                    </span>
                </td>
               {/if}

               {if $cfg.show_charcode}
                <td class="charcode-td">
                    <span>{$valuta.charcode}</span>
                </td>
               {/if}
               
               {if $cfg.show_name}
                <td class="name-td">
                    <span>{if $valuta.nominal>1}{$valuta.nominal} {/if}{$valuta.name|escape:'html'}</span>
                </td>
                {/if}
                
                <td class="value-td">
                    <span>{$valuta.value}</span>
                </td>
                
                {if $cfg.show_diff}
                <td class="diff-td">
                    <span>
                    {if $valuta.diff>0}+{*<img src="/modules/mod_exchange/images/upd.gif">*}{elseif $valuta.diff<0}{*<img src="/modules/mod_exchange/images/dwn.gif">*}{else}{/if}{$valuta.diff}
                    </span>
                </td>
                {/if}   
            </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>
    {else}
        Нет данных
    {/if}
</div>