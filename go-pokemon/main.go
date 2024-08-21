package main

import (
	"log"

	"github.com/ahmadhasannudin/go-pokemon/handler"
	"github.com/gofiber/fiber/v2"
)

func main() {
	app := fiber.New()

	app.Post("/pokemon/:id", handler.CapturePokemon)
	app.Get("/mypokemon", handler.MyPokemonList)
	app.Get("/pokemon", handler.AvailablePokemonList)
	app.Get("/pokemon/:id", handler.GetPokemonDetail)
	app.Put("/mypokemon/:id/:nickname", handler.UpdateNickname)
	app.Delete("/mypokemon/:id", handler.DeletePokemon)

	log.Fatal(app.Listen(":8080"))
}
