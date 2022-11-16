package main

import (
	"database/sql"

	_ "github.com/go-sql-driver/mysql"
)

func main() {
	db, err := sql.Open("mysql", "root:906090@tcp(127.0.0.1:3306)/food_delivery_service")

	if err != nil {
		panic(err.Error())
	}

	results, err := db.Query("SELECT fullname FROM clients where id!=1")

	if err != nil {
		panic(err.Error())
	}

	for results.Next() {
		name := ""
		results.Scan(&name)
		print(name + "\n")
	}
	defer db.Close()

}
