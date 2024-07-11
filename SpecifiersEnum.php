<?php

namespace FpDbTest;

enum SpecifiersEnum: string
{
    case DEFAULT_TYPE = '?'; // тип переданного значения
    case INTEGER = '?d'; // целое число
    case FLOAT = '?f'; // число с плавающей точкой
    case ARRAY = '?a'; // массив значений
    case IDENTIFIER = '?#'; // идентификатор или массив идентификаторов
}
