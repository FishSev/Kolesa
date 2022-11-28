package config

type Http struct {
	Port string
}

type Bot struct {
	Token string
}

type DB struct {
	User     string
	Password string
	Host     string
	Port     string
	Name     string
}

type Config struct {
	Http Http
	Bot  Bot
	DB   DB
}
