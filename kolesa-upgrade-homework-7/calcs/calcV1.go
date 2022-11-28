package calcs

import (
	"fmt"
	"math"
	"strconv"
	"strings"
)

func Calc_v1(text string) {
	var operator string
	var number1, number2 float64
	var str_number1, str_number2 string

	res := strings.Split(text, " ")

	if len(res) != 3 {
		println("неправильное выражение")
		return
	}

	str_number1 = res[0]
	operator = res[1]
	str_number2 = res[2]

	number1, err := strconv.ParseFloat(str_number1, 64)

	if err != nil {
		fmt.Printf("'%s' - это не число", str_number1)
		return
	}

	number2, err = strconv.ParseFloat(str_number2, 64)

	if err != nil {
		fmt.Printf("'%s' - это не число", str_number2)
		return
	}

	output := 0.0
	error := ""
	switch operator {
	case "+":
		output = number1 + number2
	case "-":
		output = number1 - number2
	case "*":
		output = number1 * number2
	case ">":
		if number1 > number2 {
			output = 1
		} else {
			output = 0
		}
	case "<":
		if number1 < number2 {
			output = 1
		} else {
			output = 0
		}
	case "=":
		if number1 == number2 {
			output = 1
		} else {
			output = 0
		}
	case "/":
		if number2 == 0.0 {
			error = "делить на 0 нельзя"
		} else {
			output = number1 / number2
		}
	case "^":
		if (number1 == 0.0) && (number2 == 0.0) {
			error = "неопределённость"
		} else {
			output = math.Pow(number1, number2)
		}
	default:
		error = "неправильная операция"
	}
	if error != "" {
		fmt.Println(error)
	} else {
		var print_txt, var_type string
		if math.Floor(number1) == number1 && math.Floor(number2) == number2 {
			var_type = "%.0f"
		} else {
			var_type = "%f"
		}

		if operator == ">" || operator == "<" || operator == "=" {
			print_txt = var_type + " %s " + var_type + " is %t"
			fmt.Printf(print_txt, number1, operator, number2, output == 1)
		} else {
			print_txt = var_type + " %s " + var_type + " = " + var_type
			fmt.Printf(print_txt, number1, operator, number2, output)
		}
	}
}
