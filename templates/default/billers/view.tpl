{*
 * Script: view.tpl
 *   Biller details template
 *
 * Last edited:
 *   20210615 by Rich Rowley to convert to grid layout.
 *   20180921 by Rich Rowley to add signature field.
 *
 * License:
 *   GPL v3 or above
*}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.billerName}:</div>
        <div class="cols__5-span-5">{$biller.name}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.street}:</div>
        <div class="cols__5-span-5">{$biller.street_address}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.street2}:</div>
        <div class="cols__5-span-5">{$biller.street_address2}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.city}:</div>
        <div class="cols__5-span-5">{$biller.city}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.zip}:</div>
        <div class="cols__5-span-5">{$biller.zip_code}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.state}:</div>
        <div class="cols__5-span-5">{$biller.state}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.country}:</div>
        <div class="cols__5-span-5">{$biller.country}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.mobilePhone}:</div>
        <div class="cols__5-span-5">{$biller.mobile_phone}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.phoneUc}:</div>
        <div class="cols__5-span-5">{$biller.phone}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.fax}:</div>
        <div class="cols__5-span-5">{$biller.fax}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.email}:</div>
        <div class="cols__5-span-5">{$biller.email}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.signature}:</div>
        <div class="cols__4-span-6">{$biller.signature|outHtml}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.paypalBusinessName}:</div>
        <div class="cols__5-span-5">{$biller.paypal_business_name}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.paypalNotifyUrl}:</div>
        <div class="cols__5-span-5">{$biller.paypal_notify_url}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.paypalReturnUrl}:</div>
        <div class="cols__5-span-5">{$biller.paypal_return_url}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.ewayCustomerId}:</div>
        <div class="cols__5-span-5">{$biller.eway_customer_id}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.paymentsGatewayApiId}:</div>
        <div class="cols__5-span-5">{$biller.paymentsgateway_api_id}</div>
    </div>
    {if !empty($customFieldLabel.biller_cf1)}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-3 bold align__text-right margin__right-1">{$customFieldLabel.biller_cf1|htmlSafe}:</div>
            <div class="cols__5-span-5">{$biller.custom_field1}</div>
        </div>
    {/if}
    {if !empty($customFieldLabel.biller_cf2)}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-3 bold align__text-right margin__right-1">{$customFieldLabel.biller_cf2|htmlSafe}:</div>
            <div class="cols__5-span-5">{$biller.custom_field2}</div>
        </div>
    {/if}
    {if !empty($customFieldLabel.biller_cf3)}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-3 bold align__text-right margin__right-1">{$customFieldLabel.biller_cf3|htmlSafe}:</div>
            <div class="cols__5-span-5">{$biller.custom_field3}</div>
        </div>
    {/if}
    {if !empty($customFieldLabel.biller_cf4)}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-3 bold align__text-right margin__right-1">{$customFieldLabel.biller_cf4|htmlSafe}:</div>
            <div class="cols__5-span-5">{$biller.custom_field4}</div>
        </div>
    {/if}
    {if $biller.logo != ''}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-3 bold align__text-right margin__right-1 margin__top-2">{$LANG.logoFile}:</div>
            <div class="cols__5-span-2 margin__top-2">{$biller.logo}</div>
            <div class="cols__7-span-4">
                <img src="templates/invoices/logos/{$biller.logo}" alt="{$biller.logo}">
            </div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.invoiceFooter}:</div>
        <div class="cols__4-span-6">{$biller.footer|outHtml}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.notes}:</div>
        <div class="cols__4-span-6">{$biller.notes|outHtml}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.enabled}:</div>
        <div class="cols__5-span-5">{$biller.enabled_text}</div>
    </div>
</div>
<br/>
<div class="align__text-center">
    <a href="index.php?module=billers&amp;view=edit&amp;id={$biller.id}" class="button positive"> <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a> <a href="index.php?module=billers&amp;view=manage" class="button negative"> <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
