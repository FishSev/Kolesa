package bot

import (
	"log"
	"time"

	"gopkg.in/telebot.v3"
)

func InitBot(token string) *telebot.Bot {
	if token == "" {
		log.Print("Ошибка при получении токена")
		return nil
	}
	pref := telebot.Settings{
		Token:  token,
		Poller: &telebot.LongPoller{Timeout: 10 * time.Second},
	}
	bot, err := telebot.NewBot(pref)
	if err != nil {
		log.Fatalf("Ошибка при инициализации бота %v", err)
	}

	return bot
}
