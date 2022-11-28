package handlers

import (
	"net/http"
)

func InitRoutes(mux *http.ServeMux) {
	mux.HandleFunc("/", Home)
	mux.HandleFunc("/health", Health)
}
