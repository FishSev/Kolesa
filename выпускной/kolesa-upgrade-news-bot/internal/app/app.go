package app

import (
	"fmt"
	"kolesa-upgrade-team/delivery-bot/cmd/bot"
	"kolesa-upgrade-team/delivery-bot/config"
	"kolesa-upgrade-team/delivery-bot/http/handlers"
	"kolesa-upgrade-team/delivery-bot/internal/models"
	"log"
	"net/http"
	"os"
	"time"

	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

func Run(config *config.Config) {
	if config.DB.Password == "" {
		password, err := os.ReadFile("config/DbPassword.txt")
		if err != nil {
			log.Fatal(err)
		}
		config.DB.Password = string(password)
	}

	router := http.NewServeMux()
	db, err := gorm.Open(mysql.Open(getDsn(config)), &gorm.Config{})
	if err != nil {
		fmt.Println("Ошибка при подключении к БД")
		return
	}

	if config.Bot.Token == "" {
		token, err := os.ReadFile("config/token.txt")
		if err != nil {
			log.Fatal(err)
		}
		config.Bot.Token = string(token)
	}
	handler := &bot.Handler{
		Bot:  bot.InitBot(config.Bot.Token),
		User: &models.UserModel{Db: db},
	}
	handlers.InitRoutes(router)
	bot.Route(router, handler)

	server := &http.Server{
		Addr:         ":" + config.Http.Port,
		Handler:      router,
		ReadTimeout:  10 * time.Second,
		WriteTimeout: 10 * time.Second,
		IdleTimeout:  10 * time.Second,
	}

	go func() {
		err := server.ListenAndServe()
		if err != nil {
			log.Fatalf("Соединение не установлено %v", err)
		}
	}()
	log.Println("Запуск начался. http://localhost:" +  config.Http.Port)
	handler.Bot.Handle("/start", handler.StartHandler)
	handler.Bot.Handle("/hello", handler.HelloHandler)
	handler.Bot.Handle("/secret", handler.SecretHandler)
	handler.Bot.Start()
}
func getDsn(config *config.Config) string {
	Dsn := config.DB.User + ":" + config.DB.Password + "@tcp(" + config.DB.Host + ":" + config.DB.Port + ")/" + config.DB.Name

	return Dsn
}
