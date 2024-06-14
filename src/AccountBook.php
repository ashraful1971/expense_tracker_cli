<?php

namespace Ashraful\FinanceCli;

class AccountBook {
    private $data = [];
    private static $instance = null;
    private static $file = 'db.json'; // filename with path to store the data

    /**
     * Fetch the data
     */
    public function __construct()
    {
        $this->fetchData();
    }

    /**
     * Create and return the class instance
     *
     * @return self
     */
    public static function init(): self
    {
        if(!self::$instance){
            self::$instance = new AccountBook();
        }

        return self::$instance;
    }

    /**
     * Get the default values
     *
     * @return array
     */
    public static function getDefaultData(): array
    {
        return [
            'incomes' => [],
            'expenses' => []
        ];
    }

    /**
     * Fetch and initialize the data
     *
     * @return void
     */
    public function fetchData(): void
    {
        if(!file_exists(self::$file)){
            file_put_contents(self::$file, json_encode(self::getDefaultData()), JSON_PRETTY_PRINT);
        }

        $this->data = json_decode(file_get_contents(self::$file), true);
    }

    /**
     * Get all the income records
     *
     * @return array
     */
    public function getIncomes(): array
    {
        return $this->data['incomes'];
    }

    /**
     * Create new income record
     *
     * @param float $amount
     * @param integer $category
     * @return self
     */
    public function addIncome(float $amount, int $category): self
    {
        $this->data['incomes'][] = ['amount' => $amount, 'category' => $category];
        return $this;
    }

    /**
     * Get all the expense records
     *
     * @return array
     */
    public function getExpenses(): array
    {
        return $this->data['expenses'];
    }
    
    /**
     * Create new expense record
     *
     * @param float $amount
     * @param integer $category
     * @return self
     */
    public function addExpense(float $amount, int $category): self
    {
        $this->data['expenses'][] = ['amount' => $amount, 'category' => $category];
        return $this;
    }

    /**
     * Get the total income amount
     *
     * @return float
     */
    public function getTotalIncome(): float
    {
        $total = 0;

        if(count($this->data['incomes'])){
            foreach($this->data['incomes'] as $income){
                $total += (float) $income['amount'];
            }
        }

        return $total;
    }
    
    /**
     * Get the total expense amount
     *
     * @return float
     */
    public function getTotalExpense(): float
    {
        $total = 0;

        if(count($this->data['expenses'])){
            foreach($this->data['expenses'] as $income){
                $total += (float) $income['amount'];
            }
        }

        return $total;
    }
    
    /**
     * Get the total savings amount
     *
     * @return float
     */
    public function getSavings(): float
    {
        return (float) $this->getTotalIncome() - $this->getTotalExpense();
    }

    /**
     * Save the data to the storage
     *
     * @return boolean
     */
    public function save(): bool
    {
        file_put_contents(self::$file, json_encode($this->data), JSON_PRETTY_PRINT);

        return true;
    }
}