package main

import (
	"flag"
	"kolesa-upgrade-team/delivery-bot/config"
	"kolesa-upgrade-team/delivery-bot/internal/app"
	"log"

	"github.com/BurntSushi/toml"
)

func main() {
	port := flag.String("port", "8888", "HTTP port")
	configPath := flag.String("config", "config/local.toml", "Path to config file")
	flag.Parse()
	configs := &config.Config{}
	_, err := toml.DecodeFile(*configPath, configs)
	if err != nil {
		log.Fatalf("Ошибка декодирования файла конфигов %v", err)
	}
	configs.Http.Port = *port
	app.Run(configs)
}
