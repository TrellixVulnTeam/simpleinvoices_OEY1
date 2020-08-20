<?php

namespace Inc\Claz;

/**
 * Class NetIncomeInvoice
 * @package Inc\Claz
 */
class NetIncomeInvoice
{
    public int $customerId;
    public string $date;
    public int $id;
    public array $items;
    public int $indexId;
    public array $pymts;
    public float $totalAmount;
    public float $totalPayments;
    public float $totalPeriodPayments;

    /**
     * NetIncomeInvoice constructor.
     * @param $id
     * @param $indexId
     * @param $date
     * @param $customerId
     */
    public function __construct(int $id, int $indexId, string $date, int $customerId)
    {
        $this->id = $id;
        $this->indexId = $indexId;
        $this->date = $date;
        $this->customerId = $customerId;
        $this->totalAmount = 0;
        $this->totalPayments = 0;
        $this->totalPeriodPayments = 0;
        $this->items = [];
        $this->pymts = [];
    }

    public function addItem(float $amount, string $description, string $cflags): void
    {
        $this->items[] = new NetIncomeItem($amount, $description, $cflags);
        $this->totalAmount += $amount;
    }

    public function addPayment(float $amount, string $date, bool $in_period): void
    {
        $this->pymts[] = new NetIncomePayment($amount, $date);
        $this->totalPayments += $amount;
        if ($in_period) {
            $this->totalPeriodPayments += $amount;
        }
    }

    /**
     * Adjust the amount paid to exclude billed items that were flagged
     * as non-income. This is typically items that were pre-paid for by
     * the client so the cost is straight pass through. The only income
     * on these items will be in the shipping and handling changes assessed.
     * Example: Invoice was for $527.50. Of this, $500 was cost of computer,
     * and $27.50 was shipping and handling. The client paid $505 up front in
     * in the month prior to the beginning of the report selection period.
     * The TV was delivered and the final $22.50 was paid during the report
     * period. To the <b>total_payments<b> is $527.50, the <b>total_amount</b>
     * is $27.50 (income amount), the <b>totalPeriodPayments<b> is $22.50.
     * @example For the report we show:
     *     Invoice Total:          $27.50 (does not include non-income amount)  $this->totalAmount
     *     Total Paid:             $27.50 (include pre-period and post)         $this->totalPayments up to $this->totalAmount
     *     Total Paid This Period: $22.50 (net_income for this period)          $this->totalPeriodPayments max of $this->totalPayments
     */
    public function adjustPymtsForNonIncome(): void
    {
        if ($this->totalPayments > $this->totalAmount) {
            $this->totalPayments = $this->totalAmount;
        }
        if ($this->totalPeriodPayments > $this->totalPayments) {
            $this->totalPeriodPayments = $this->totalPayments;
        }
    }
}
