package pipeline

import (
	"encoding/csv"
	"log"
	"os"
	"sort"
	"strconv"
	"strings"
	"sync"

	"github.com/Kolesa-Education/kolesa-upgrade-homework-8/evaluate"
	"gonum.org/v1/gonum/stat/combin"
)

func readCsvFile(filePath string) [][]string {
	f, err := os.Open(filePath)
	if err != nil {
		log.Fatal("Unable to read input file "+filePath, err)
	}
	defer f.Close()
	csvReader := csv.NewReader(f)
	res, err := csvReader.ReadAll()
	if err != nil {
		log.Fatal("Unable to parse file as CSV for "+filePath, err)
	}
	return res
}

func sort2D(array [][]string) {
	sort.Slice(array, func(i, j int) bool {
		return array[i][5] < array[j][5]
	})
}

func writeCsvFile(filePath string, data [][]string) {
	csvFile, err := os.Create(filePath)
	if err != nil {
		log.Fatalf("failed creating file: %s", err)
	}
	csvwriter := csv.NewWriter(csvFile)
	for _, oneComb := range data {
		_ = csvwriter.Write(oneComb)
	}
	csvwriter.Flush()
	csvFile.Close()
}

func addHandName(combin_list [][]string) [][]string {
	var lineWithName []string
	arrWithHandsNames := [][]string{}
	for _, oneLine := range combin_list {
		hand_name := evaluate.AnalyzeHand(strings.Join(changeVal(oneLine), " "))
		if hand_name != "High card" && hand_name != "Invalid hand" {
			lineWithName = append(oneLine, []string{hand_name}...)
			arrWithHandsNames = append(arrWithHandsNames, lineWithName)
		}
	}
	return arrWithHandsNames
}

func removeDuplicates(cards []string) []string {
	allKeys := make(map[string]bool)
	list := []string{}
	for _, item := range cards {
		if _, value := allKeys[item]; !value {
			allKeys[item] = true
			list = append(list, item)
		}
	}
	return list
}

func suitToLetter(suit string) string {
	var letter string
	switch suit {
	case "\u2666":
		letter = "d"
	case "\u2663":
		letter = "c"
	case "\u2665":
		letter = "h"
	case "\u2660":
		letter = "s"
	}
	return letter
}

func changeVal(cards []string) []string {
	list := []string{}
	for _, item := range cards {
		suit := suitToLetter(item[0:3])
		if len(item) == 5 {
			list = append(list, "t"+suit)
		} else {
			_, err := strconv.ParseFloat(item[3:4], 64)
			if err == nil {
				list = append(list, item[3:4]+suit)
			} else {
				card_nom := ""
				switch item[3:4] {
				case "J":
					card_nom = "j"
				case "Q":
					card_nom = "q"
				case "K":
					card_nom = "k"
				case "A":
					card_nom = "a"
				}
				list = append(list, card_nom+suit)
			}
		}
	}
	return list
}

func getCombinations(cards []string, col int) [][]string {
	cs := combin.Combinations(len(cards), col)
	super_list := [][]string{}
	for _, c := range cs {
		list := []string{}
		for i := 0; i < col; i++ {
			list = append(list, cards[c[i]])
		}
		super_list = append(super_list, list)
	}
	return super_list
}

func Pipeline(num string, wgrp *sync.WaitGroup) {
	records := readCsvFile("dataset/dat" + num + ".csv")[0]
	records = removeDuplicates(records)
	combin_list := getCombinations(records, 5)
	combin_list = addHandName(combin_list)
	sort2D(combin_list)
	writeCsvFile("datares/dat"+num+"_res.csv", combin_list)
	wgrp.Done()
}
