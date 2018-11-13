<?php

use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SiLocal;

header("Content-type: text/xml");
global $auth_session, $LANG, $pdoDb;

// @formatter:off
$dir    = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC";
$sort   = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "id";
$rp     = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$having = (isset($_GET['having'])    ) ? $_GET['having']     : "";
$page   = (isset($_POST['page'])     ) ? $_POST['page']      : "1";
$query  = (isset($_POST['query'])    ) ? $_POST['query']     : null;
$qtype  = (isset($_POST['qtype'])    ) ? $_POST['qtype']     : null;
// @formatter:on

// If user role is customer or biller, then restrict invoices to those they
// have access to. Make customer access read only. Billers change work only
// on those invoices generated for them.
$read_only = ($auth_session->role_name == 'customer');

if (!empty($having)) {
    try {
        $pdoDb->setHavings(Invoice::buildHavings($having));
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/xml.php - error: " . $pde->getMessage());
    }
}

// @formatter:off
$invoices = Invoice::select_all(''     , $sort, $dir, $rp, $page, $qtype, $query);
$count    = Invoice::select_all('count', $sort, $dir, $rp, $page, $qtype, $query);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($invoices as $row) {
    $xml .= "<row id='" . $row['id'] . "'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='{$LANG['quick_view_tooltip']} {$row['preference']} {$row['index_id']}' " .
               "href='index.php?module=invoices&amp;view=quick_view&amp;id={$row['id']}'>" .
                "<img src='images/common/view.png' class='action' />" .
            "</a>";
    if (!$read_only) {
        $xml .=
            "<a class='index_table' title='{$LANG['edit_view_tooltip']} {$row['preference']} {$row['index_id']}' " .
               "href='index.php?module=invoices&amp;view=details&amp;id={$row['id']}&amp;action=view'>" .
                "<img src='images/common/edit.png' class='action' />" .
            "</a>";
    }
    $xml .= "<!--2 PRINT VIEW -->" .
             "<a class='index_table' title='" .
                $LANG['print_preview_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] .
                "' href='index.php?module=export&amp;view=invoice&amp;id=" . $row['id'] . "&amp;format=print'>".
                "<img src='images/common/printer.png' class='action' />" .
             "</a>" .
             "<!--3 EXPORT TO PDF DIALOG -->" .
             "<a title='{$LANG['export_tooltip']} {$row['preference']} {$row['index_id']}' " .
                "class='invoice_export_dialog' href='#' rel='{$row['id']}' " .
                "data_spreadsheet='{$config->export->spreadsheet}' " .
                "data_wordprocessor='{$config->export->wordprocessor}'>" .
                 "<img src='images/common/page_white_acrobat.png' class='action' />" .
             "</a>";
    if (!$read_only) {
        // Alternatively: The Owing column can have the link on the amount instead of the payment icon code here
        if ($row['status'] && $row['owing'] > 0) {
            // Real Invoice Has Owing - Process payment
            $xml .= "<!--6 Payment -->" .
                        "<a title='{$LANG['process_payment_for']} {$row['preference']} {$row['index_id']}' " .
                           "class='index_table' href='index.php?module=payments&amp;view=process&amp;id={$row['id']}&amp;op=pay_selected_invoice'>" .
                            "<img src='images/common/money_dollar.png' class='action' />" .
                        "</a>";
        } elseif ($row['status']) {
            // Real Invoice Payment Details if not Owing (get different color payment icon)
            $xml .= "<!--6 Payment -->" .
                        "<a title='{$LANG['process_payment_for']} {$row['preference']} {$row['index_id']}' " .
                           "class='index_table' href='index.php?module=payments&amp;view=details&amp;ac_inv_id={$row['id']}&amp;action=view'>" .
                            "<img src='images/common/money_dollar.png' class='action' />" .
                        "</a>";
        } else {
            // Draft Invoice Just Image to occupy space till blank or greyed out icon becomes available
            $xml .= "<!--6 Payment --><img src='images/common/money_dollar.png' class='action' />";
        }
        $xml .= "<!--7 Email -->" .
                    "<a title='{$LANG['email']} {$row['preference']} {$row['index_id']}' " .
                       "class='index_table' href='index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$row['id']}'>" .
                        "<img src='images/common/mail-message-new.png' class='action' />" .
                    "</a>";
    }
    $xml .= "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['index_name']                     . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['biller']                         . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['customer']                       . "]]></cell>";
    $xml .= "<cell><![CDATA[" . SiLocal::date($row['date'])            . "]]></cell>";
    $xml .= "<cell><![CDATA[" . SiLocal::number($row['invoice_total']) . "]]></cell>";
    if ($row['status']) {
        $xml .= "<cell><![CDATA[" . SiLocal::number($row['owing']) . "]]></cell>";
        $xml .= "<cell><![CDATA[" . $row['aging']                  . "]]></cell>";
    } else {
        $xml .= "<cell><![CDATA[&nbsp;]]></cell>";
        $xml .= "<cell><![CDATA[&nbsp;]]></cell>";
    }
    $xml .= "<cell><![CDATA[" . $row['preference'] . "]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
