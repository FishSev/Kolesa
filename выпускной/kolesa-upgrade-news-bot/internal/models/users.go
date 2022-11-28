package models

import (
	"log"

	"gorm.io/gorm"
)

type User struct {
	ID         uint
	Name       string `json:"name"`
	TelegramId int64  `json:"telegram_id"`
	FirstName  string `json:"first_name"`
	LastName   string `json:"last_name"`
	ChatId     int64  `json:"chat_id"`
}

type UserModel struct {
	Db *gorm.DB
}

func (m *UserModel) Create(user User) error {
	result := m.Db.Create(&user)
	return result.Error
}

func (m *UserModel) FindOne(telegramId int64) (*User, error) {
	existUser := User{}
	result := m.Db.First(&existUser, User{TelegramId: telegramId})
	if result.Error != nil {
		return nil, result.Error
	}
	return &existUser, nil
}
func (m *UserModel) All() []User {
	var existUser []User
	result := m.Db.Find(&existUser)
	if result.Error != nil {
		log.Printf("Ошибка при получении пользователей %v", result.Error)
	}

	return existUser
}
