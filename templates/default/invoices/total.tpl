<!--suppress HtmlFormInputWithoutLabel -->
<div class="si_delay-display">
    {include file="$path/invoiceTypeButtons.tpl" }
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
        <div class="si_invoice_form">
            {include file="$path/invoiceBillerCustFields.tpl" }
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-10 bold">{$LANG.descriptionUc}:</div>
                <div class="cols__1-span-10 bold">
                <textarea name="description" id="description" style="overflow:scroll;"
                          rows="3" cols="100%">{$defaultInvoice.note|outHtml}</textarea>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$LANG.grossTotal}:</div>
                <div class="cols__3-span-3">
                    <input type="text" class="si_right validate[required]" name="unit_price" id="unit_price0" size="10"
                           value="{if isset($defaultInvoiceItems[0].unit_price)}{$defaultInvoiceItems[0].unit_price|utilNumber}{/if}"/>
                </div>
                {if $defaults.tax_per_line_item > 0}
                    <div class="cols__6-span-1 bold si_right">{$LANG.tax}:&nbsp;</div>
                    {section name=tax loop=$defaults.tax_per_line_item}
                        {$taxNumber = $smarty.section.tax.index}
                        {$colStart = $taxNumber + 7}
                        <div class="cols__{$colStart}-span-1 si_margin__right-1">
                            <select id="tax_id[{$smarty.section.line.index|htmlSafe}][{$taxNumber|htmlSafe}]"
                                    name="tax_id[{$smarty.section.line.index|htmlSafe}][{$taxNumber|htmlSafe}]">
                                <option value=""></option>
                                {foreach $taxes as $tax}
                                    <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}"
                                            {if isset($defaultInvoiceItems[$line].tax[$taxNumber]) &&
                                            $tax.tax_id == $defaultInvoiceItems[$line].tax[$taxNumber]}selected{/if}>{$tax.tax_description|htmlSafe}</option>
                                {/foreach}
                            </select>
                        </div>
                    {/section}
                {/if}
            </div>
            {$customFields.1}
            {$customFields.2}
            {$customFields.3}
            {$customFields.4}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$LANG.invPref}:&nbsp;</div>
                <div class="cols__3-span-3">
                    {if !isset($preferences) }
                        <em>{$LANG.noPreferences}</em>
                    {else}
                        <select name="preference_id">
                            {foreach $preferences as $preference}
                                <option {if $preference.pref_id == $defaults.preference}selected{/if}
                                        value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
                <div class="cols__6-span-2 bold">{$LANG.salesRepresentative}:&nbsp;</div>
                <div class="cols__8-span-3">
                    <input id="sales_representative}" name="sales_representative" size="30"
                           value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}"/>
                </div>
            </div>

            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=invoices&amp;view=manage" class="negative">
                    <img src="images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>

            <div class="si_help_div">
                <a class="cluetip" href="#" title="{$LANG.wantMoreFields}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvoiceCustomFields">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                    {$LANG.wantMoreFields}
                </a>
            </div>
        </div>
        <input type="hidden" name="max_items" value="{if isset($smarty.section.line.index)}{$smarty.section.line.index|htmlSafe}{/if}"/>
        <input type="hidden" name="type" value="1"/>
        <input type="hidden" name="op" value="create"/>
    </form>
    <script>
        // This causes the tabs to appear after being rendered
        {literal}
        $(document).ready(function () {
            $("div.si_delay-display").removeClass("si_delay-display");
        });
        {/literal}
    </script>
</div>
