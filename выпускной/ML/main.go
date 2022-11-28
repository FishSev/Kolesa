package main

import (
	"log"
	"os"
	"os/exec"
)

func main() {

    argsWithoutProg := os.Args[1:]
	
    cmd := exec.Command("/usr/bin/python3", "find_word.py", argsWithoutProg[0])
    output, err := cmd.CombinedOutput()
    if err != nil {
        log.Fatal(err)
    }

	if string(output) != "error" {
		print("hello")
	}
}