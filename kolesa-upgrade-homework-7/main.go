package main

import (
	"bufio"
	"fmt"
	"os"

	"example.com/m/v2/calcs"
)

func main() {
	var input string

	fmt.Println("Вы хотите вычислить простое выражение из двух операндов (1) или сложное (2)?")
	fmt.Println("'Задание часть 1' - (1) простое выражение : 1 * 2.3")
	fmt.Println("'Задание часть 2' - (2) сложное выражение : 1+3*(1+2/1-(2.5-1))")
	fmt.Println()

	scanner := bufio.NewScanner(os.Stdin)
	scanner.Scan()
	input = scanner.Text()

	fmt.Println()
	if input == "1" {
		fmt.Println("Можно использовать следующие операторы: +,-,/,*,^,>,<,=")
		scanner.Scan()
		calcs.Calc_v1(scanner.Text())
	} else if input == "2" {
		fmt.Println("Вводи выражение:")
		scanner.Scan()
		calcs.Calc_v2(scanner.Text())
	} else {
		println("Вы ввели что-то иное нежели 1 или 2. Такое моя не понимай!")
	}
}
