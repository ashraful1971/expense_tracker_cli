<?php

namespace Ashraful\FinanceCli;

use Ashraful\FinanceCli\AccountBook;
use Ashraful\FinanceCli\Enums\Action;

class Application
{

    private static $options;
    private static $categories;
    private static AccountBook $accountBook;

    /**
     * Initilize the application
     *
     * @return void
     */
    private static function init(): void
    {
        self::$options = [
            Action::ADD_INCOME->value => 'Add income',
            Action::ADD_EXPENSE->value => 'Add expense',
            Action::VIEW_INCOME->value => 'View income',
            Action::VIEW_EXPENSE->value => 'View expense',
            Action::VIEW_SAVINGS->value => 'View savings',
            Action::VIEW_CATEGORIES->value => 'View categories',
            Action::EXIT->value => 'Exit',
        ];

        self::$categories = [
            ['name' => 'Business', 'type' => 'Income'],
            ['name' => 'Loan', 'type' => 'Income'],
            ['name' => 'Sallary', 'type' => 'Income'],
            ['name' => 'Rent', 'type' => 'Expense'],
            ['name' => 'Utility Bill', 'type' => 'Expense'],
            ['name' => 'Internet Bill', 'type' => 'Expense'],
        ];

        self::$accountBook = AccountBook::init();
    }

    /**
     * Run the application
     *
     * @return void
     */
    public static function run(): void
    {
        self::init();

        $in_loop = true;

        while ($in_loop) {

            echo PHP_EOL;
            self::printAvailableOpions();

            echo PHP_EOL;
            $input = (int)readline('=> Enter your option: ');
            echo PHP_EOL;

            switch ($input) {
                case Action::ADD_INCOME->value:
                    self::addIncome();
                    break;
                case Action::ADD_EXPENSE->value:
                    self::addExpense();
                    break;
                case Action::VIEW_INCOME->value:
                    self::printIncomes(self::$accountBook->getIncomes());
                    break;
                case Action::VIEW_EXPENSE->value:
                    self::printExpenses(self::$accountBook->getExpenses());
                    break;
                case Action::VIEW_SAVINGS->value:
                    echo 'Total savings: ' . self::getTotalSavings() . PHP_EOL;
                    break;
                case Action::VIEW_CATEGORIES->value:
                    self::printAvailableCategories();
                    break;
                case Action::EXIT->value:
                    $in_loop = false;
                    break;
                default:
                    echo 'Wrong number! Try again!' . PHP_EOL;
                    break;
            }
        }
    }

    /**
     * Add new income
     *
     * @return void
     */
    public static function addIncome(): void
    {
        $amount = (float) readline('Enter the income amount: ');
        $category = self::inputValidCategory('Income');
        self::$accountBook->addIncome($amount, $category)->save();
        echo PHP_EOL . 'New income record added!' . PHP_EOL;
    }

    /**
     * Add new expense
     *
     * @return void
     */
    public static function addExpense(): void
    {
        $amount = (float) readline('Enter the expense amount: ');
        $category = self::inputValidCategory('Expense');
        self::$accountBook->addExpense($amount, $category)->save();
        echo PHP_EOL . 'New expense record added!' . PHP_EOL;
    }
    
    /**
     * Get the total savings amount
     *
     * @return float
     */
    public static function getTotalSavings(): float
    {
        return self::$accountBook->getSavings();
    }

    /**
     * Get valid category input from the user
     *
     * @param string $type
     * @return integer
     */
    public static function inputValidCategory(string $type): int
    {
        $category = null;
        echo PHP_EOL;
        self::printAvailableCategories($type);
        while (true) {
            $category = (int) readline('Enter the category option: ');
            if (array_key_exists($category, self::$categories) && self::$categories[$category]['type'] == $type) {
                break;
            }
            echo 'Enter a valid income category' . PHP_EOL;
        }

        return $category;
    }

    /**
     * Print all available actions
     *
     * @return void
     */
    private static function printAvailableOpions(): void
    {
        echo "--- Available Actions ---" . PHP_EOL;
        foreach (self::$options as $key => $value) {
            printf("[%d] %s \n", $key, $value);
        }
    }

    /**
     * Print all the availalbe categories
     *
     * @param string|null $type
     * @return void
     */
    private static function printAvailableCategories(string $type = null): void
    {
        echo "--- Available Categories ---" . PHP_EOL;
        foreach (self::$categories as $key => $value) {
            if ($type && $type != $value['type']) continue;
            printf("[%d] Name: %s, Type: %s \n", $key, $value['name'], $value['type']);
        }
        echo PHP_EOL;
    }

    /**
     * Print all the income records
     *
     * @param array $incomes
     * @return void
     */
    private static function printIncomes(array $incomes): void
    {
        echo "--- All Incomes ---" . PHP_EOL;
        foreach ($incomes as $key => $value) {
            printf("[%d] Amount: %s, Category: %s \n", $key, $value['amount'], self::$categories[$value['category']]['name']);
        }
        echo PHP_EOL;
    }

    /**
     * Print all the expense records
     *
     * @param array $expenses
     * @return void
     */
    private static function printExpenses(array $expenses): void
    {
        echo "--- All Expenses ---" . PHP_EOL;
        foreach ($expenses as $key => $value) {
            printf("[%d] Amount: %s, Category: %s \n", $key, $value['amount'], self::$categories[$value['category']]['name']);
        }
        echo PHP_EOL;
    }
}
