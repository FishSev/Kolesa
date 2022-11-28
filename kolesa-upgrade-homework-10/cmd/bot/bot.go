package bot

import (
	"fmt"
	"log"
	"strconv"
	"strings"
	"time"
	"upgrade/internal/models"

	"gopkg.in/telebot.v3"
)

type UpgradeBot struct {
	Bot   *telebot.Bot
	Users *models.UserModel
	Tasks *models.TaskModel
}

func (bot *UpgradeBot) StartHandler(ctx telebot.Context) error {
	newUser := models.User{
		Name:       ctx.Sender().Username,
		TelegramId: ctx.Chat().ID,
		FirstName:  ctx.Sender().FirstName,
		LastName:   ctx.Sender().LastName,
		ChatId:     ctx.Chat().ID,
	}

	existUser, err := bot.Users.FindOne(ctx.Chat().ID)

	if err != nil {
		log.Printf("Ошибка получения пользователя %v", err)
	}

	if existUser == nil {
		err := bot.Users.Create(newUser)

		if err != nil {
			log.Printf("Ошибка создания пользователя %v", err)
		}
	}

	return ctx.Send("Привет, " + ctx.Sender().FirstName + " =^_^=\nИспользуй команды /addTask, /showTasks, /deleteTask. Они помогут тебе в работе с планировщиком. Ввод команд без параметров подскажет тебе, как они работают.")
}

func InitBot(token string) *telebot.Bot {
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

func (bot *UpgradeBot) AddTaskHandler(ctx telebot.Context) error {

	allText := ctx.Text()

	if allText == "/addTask" {
		return ctx.Send("Введи заголовок, описание и дату задачи. Разделяйте их символами '>>'. \nНапример: \n/addTask годовой отчёт>> лучше сделать его хорошо>>07.07.2022")
	}

	clearText := strings.Replace(allText, "/addTask ", "", -1)

	vals := strings.Split(clearText, ">>")

	if len(vals) != 3 {
		return ctx.Send("Вы что-то ввели не правильно, обратите внимание на пример. \nПример: \n/addTask годовой отчёт>> лучше сделать его хорошо>>07.07.2022")
	}

	date, err := time.Parse("02.01.2006", strings.TrimSpace(vals[2]))

	if err != nil {
		return ctx.Send("Вы ввели неправильную дату. \nПример даты: 07.07.2022")
	}

	newTask := models.Task{
		Title:       strings.TrimSpace(vals[0]),
		Description: strings.TrimSpace(vals[1]),
		UserId:      ctx.Sender().ID,
		EndDate:     date,
	}

	if err := bot.Tasks.Create(newTask); err != nil {
		log.Printf("Ошибка создания задачи %v", err)
		return ctx.Send("Ошибка создания задачи")
	}
	return ctx.Send("Новая задача добавлена")
}

func (bot *UpgradeBot) DeleteTaskHandler(ctx telebot.Context) error {

	if ctx.Text() == "/deleteTask" {
		return ctx.Send("Введите id задачи для её удаления. \nПример: \n/deleteTask 1")
	}

	args := ctx.Args()
	deleteId, err := strconv.ParseInt(args[0], 0, 64)

	if err != nil {
		return ctx.Send("вы ввели неправильынй ID")
	}

	if len(args) > 1 {
		return ctx.Send("Вы ввели больше одного ИД")
	}

	if err := bot.Tasks.Delete(deleteId, ctx.Sender().ID); err != nil {
		log.Fatalf("Ошибка выполнения запроса пользователя %v", err)
	}

	return ctx.Send("Такой задачи больше нет (а может её и не было)")

}

func (bot *UpgradeBot) ShowTasksHandler(ctx telebot.Context) error {

	tasks, err := bot.Tasks.FindAll(ctx.Sender().ID)

	if err != nil {
		log.Fatalf("Ошибка обработки задачи %v", err)
	}

	var (
		Tasks     []string
		task_info string
	)

	for _, el := range tasks {
		task_info = fmt.Sprintf(`_ID_: %d _title_: %s _desc_: %s _deadline_: %s`, el.ID, el.Title, el.Description, el.EndDate.Format("01.02.2006"))
		Tasks = append(Tasks, task_info)
	}

	resString := strings.Join(Tasks, "\n")

	return ctx.Send(resString)
}
