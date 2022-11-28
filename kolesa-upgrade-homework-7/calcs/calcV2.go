package calcs

import (
	"go/token"
	"go/types"
)

func Calc_v2(text string) {
	file_set := token.NewFileSet()
	eval_res, err := types.Eval(file_set, nil, token.NoPos, text)
	if err != nil {
		println("ты делаешь то чего делать не надо! подумай над этим")
	}
	println("Ответ:", eval_res.Value.String())
}
