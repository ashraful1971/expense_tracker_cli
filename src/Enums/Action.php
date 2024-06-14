<?php

namespace Ashraful\FinanceCli\Enums;

enum Action: int {
    case ADD_INCOME = 0;
    case ADD_EXPENSE = 1;
    case VIEW_INCOME = 2;
    case VIEW_EXPENSE = 3;
    case VIEW_SAVINGS = 4;
    case VIEW_CATEGORIES = 5;
    case EXIT = 6;
}