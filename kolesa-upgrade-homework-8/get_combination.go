package main

import (
	"fmt"
	"strconv"
	"sync"
	"time"

	"github.com/Kolesa-Education/kolesa-upgrade-homework-8/pipeline"
)

func main() {
	start := time.Now()

	var wg sync.WaitGroup
	for i := 0; i <= 100; i++ {
		wg.Add(1)
		go pipeline.Pipeline(strconv.Itoa(i), &wg)
	}
	wg.Wait()

	elapsed := time.Since(start)
	fmt.Print("Затрачено на выполнение ", elapsed)
}
