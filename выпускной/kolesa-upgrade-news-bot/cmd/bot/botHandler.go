package bot

import (
	"encoding/json"
	"io"
	"kolesa-upgrade-team/delivery-bot/internal/models"
	"log"
	"net/http"

	"gopkg.in/telebot.v3"
)

type Handler struct {
	Bot  *telebot.Bot
	User *models.UserModel
}

type Message struct {
	Title   string
	Message string `json:"message"`
}

func Route(mux *http.ServeMux, h *Handler) {
	mux.HandleFunc("/messages/sendToAll", h.Sender)

}

func (h *Handler) Sender(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")
	response := map[string]string{
		"status": "ok",
	}
	jsonResp, _ := json.Marshal(response)
	responseError := map[string]string{
		"status": "error",
		"error":  "Bad Request. Message must have body",
	}
	jsonRespErr, _ := json.Marshal(responseError)

	if r.Method != http.MethodPost {
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
		return
	}

	body, err := io.ReadAll(r.Body)

	if err != nil {
		http.Error(w, http.StatusText(http.StatusBadRequest), http.StatusBadRequest)
		return
	}

	res := models.Message{}
	err = json.Unmarshal(body, &res)

	if err != nil {
		w.Write(jsonRespErr)
		return
	}

	if res.Body == "" {
		w.Write(jsonRespErr)
		return
	}
	allUser := h.User.All()
	for _, user := range allUser {
		u := &telebot.User{
			ID: user.TelegramId,
		}
		_, err := h.Bot.Send(u, res.Body)
		if err != nil {
			w.Write(jsonRespErr)
		}
	}
	w.Write(jsonResp)
}

func (h *Handler) StartHandler(ctx telebot.Context) error {
	newUser := models.User{
		Name:       ctx.Sender().Username,
		TelegramId: ctx.Chat().ID,
		FirstName:  ctx.Sender().FirstName,
		LastName:   ctx.Sender().LastName,
		ChatId:     ctx.Chat().ID,
	}
	existUser, err := h.User.FindOne(ctx.Chat().ID)
	if err != nil {
		log.Printf("Ошибка получения пользователя %v.\n Создаю нового =O_o=", err)
	}

	if existUser == nil {
		err := h.User.Create(newUser)
		if err != nil {
			log.Printf("Ошибка создания пользователя %v", err)
		}
	}
	return ctx.Send("Привет, я дружелюбный бот. Мои команды /hello, /secret")
}

func (h *Handler) HelloHandler(ctx telebot.Context) error {
	return ctx.Send("Привет, " + ctx.Sender().FirstName + " =^_^=")
}

func (h *Handler) SecretHandler(ctx telebot.Context) error {
	return ctx.Send("Заходи на http://kolesa.site и отдохни душой")
}