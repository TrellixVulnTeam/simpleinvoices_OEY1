<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=payments&amp;view=save">
    <div class="si_form">
        <table>
            {if $smarty.get.op === "pay_selected_invoice"}
                <tr>
                    <th>{$invoice.preference|htmlsafe}</th>
                    <td>{$invoice.index_id|htmlsafe}</td>
                    <th class="details_screen">{$LANG.total}</th>
                    <td>{$invoice.total|siLocal_number:2}</td>
                </tr>
                <tr>
                    <th>{$LANG.biller}</th>
                    <td>{$biller.name|htmlsafe}</td>
                    <th>{$LANG.paid}</th>
                    <td>{$invoice.paid|siLocal_number}</td>
                </tr>
                <tr>
                    <th>{$LANG.customer}</th>
                    <td>{$customer.name|htmlsafe}</td>
                    <th>{$LANG.owing}</th>
                    <td style="text-decoration: underline;">{$invoice.owing|siLocal_number}</td>
                </tr>
                <tr>
                    <th>{$LANG.amount}</th>
                    <td colspan="5">
                        <input type="text" name="ac_amount" size="25" class="validate[required,custom[number]]"
                               value="{$invoice.owing|siLocal_number}"/>
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_auto_amount"
                           title="{$LANG.process_payment_auto_amount}">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.date_formatted}</th>
                    <td colspan="5">
                        <input type="text" name="ac_date" id="date1"
                               class="validate[required,custom[date],length[0,10]] date-picker"
                               value="{if isset($today)}{$today|htmlsafe}{/if}"/>
                    </td>
                </tr>
            {elseif $smarty.get.op === "pay_invoice"}
                <tr>
                    <th>{$LANG.invoice}</th>
                    <td colspan="3">
                        <select name="invoice_id" class="validate[required]">
                            <option value=''></option>
                            {foreach from=$invoice_all item=invoice}
                                <option value="{if isset($invoice.id)}{$invoice.id|htmlsafe}{/if}">
                                    {$invoice.index_name|htmlsafe}
                                    (
                                    {$invoice.biller|htmlsafe},
                                    {$invoice.customer|htmlsafe},
                                    {$LANG.total} {$invoice.total|siLocal_number} :
                                    {$LANG.owing} {$invoice.owing|siLocal_number}
                                    )
                                </option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.amount}</th>
                    <td colspan="3"><input type="text" name="ac_amount" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.date_formatted}</th>
                    <td colspan="3">
                        <input type="text" class="date-picker" name="ac_date" id="date1" value="{if isset($today)}{$today|htmlsafe}{/if}"/>
                    </td>
                </tr>
            {/if}
            <tr>
                <th>{$LANG.payment_type_method}</th>
                <td>
                    {if !$paymentTypes}
                        <p><em>{$LANG.no_payment_types}</em></p>
                    {else}
                        <select name="ac_payment_type" id="pymt_type">
                            {foreach from=$paymentTypes item=paymentType}
                                <option value="{if isset($paymentType.pt_id)}{$paymentType.pt_id|htmlsafe}{/if}" {if $paymentType.pt_id==$defaults.payment_type}selected{/if}>{$paymentType.pt_description|htmlsafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
                <th>{$LANG.check_number}</th>
                <td>
                    <input type="text" name="ac_check_number" id="chk_num" size="10"/>
                    {literal}
                        <script>
                            $(function(){
                                $('#frmpost').submit(function(){
                                    let pymt_type = $('#pymt_type option:selected').text().toLowerCase();
                                    if ($('#pymt_type option:selected').text().toLowerCase() == 'check') {
                                        let cknum = $('#chk_num').val().toUpperCase();
                                        if (!(/^[1-9][0-9]* *$/).test(cknum) && cknum != 'N/A') {
                                            alert('Enter a valid Check Number, \"N/A\" or change the Payment Type.');
                                            $('#chk_num').focus();
                                            return (false);
                                        };
                                        $('#chk_num').val(cknum);
                                    }
                                });
                            });
                        </script>
                    {/literal}
                </td>
            </tr>
            <tr>
                <th>{$LANG.note}</th>
                <td colspan="3">
                    <!--
                    <textarea class="editor" name="ac_notes"></textarea>
                    -->
                    <input name="ac_notes" id="ac_notes" type="hidden">
                    <trix-editor input="ac_notes"></trix-editor>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="process_payment" value="{$LANG.save}">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=payments&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        {if $smarty.get.op == 'pay_selected_invoice'}
            <input type="hidden" name="invoice_id" value="{if isset($invoice.id)}{$invoice.id|htmlsafe}{/if}"/>
        {/if}
    </div>
</form>
